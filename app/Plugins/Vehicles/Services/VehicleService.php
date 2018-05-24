<?php
/**
 * Plugin
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    RENT&RIDE
 * @subpackage Core
 * @author     Agriya <info@agriya.com>
 * @copyright  2018 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
 
namespace Plugins\Vehicles\Services;


use App\User;
use Plugins\Vehicles\Model\UnavailableVehicle;
use Plugins\Vehicles\Model\Vehicle;
use Plugins\Vehicles\Model\CounterLocation;
use Plugins\Vehicles\Services\VehicleSpecialPriceService;
use Plugins\Vehicles\Services\VehicleTypePriceService;
use Plugins\Vehicles\Model\VehicleType;
use Plugins\Vehicles\Model\VehicleMake;
use Plugins\Vehicles\Model\VehicleModel;
use Plugins\Vehicles\Model\VehicleCompany;
use App\Services\TransactionService;
use Carbon;
use DB;

class VehicleService
{
    /**
     * @var vehicleSpecialPriceService
     */
    protected $vehicleSpecialPriceService;

    /**
     * @var vehicleTypePriceService
     */
    protected $vehicleTypePriceService;

    /**
     * VehicleService constructor.
     */
    public function __construct()
    {
        $this->setTransactionService();
        $this->setVehicleSpecialPriceService();
        $this->setVehicleTypePriceService();
    }

    public function setTransactionService()
    {
        $this->transactionService = new TransactionService();
    }

    public function setVehicleSpecialPriceService()
    {
        $this->vehicleSpecialPriceService = new VehicleSpecialPriceService();
    }

    public function setVehicleTypePriceService()
    {
        $this->vehicleTypePriceService = new VehicleTypePriceService();
    }

    /**
     * get last registered record for admin dashboard
     * @param $request
     * @return User created_at
     */
    public function getLastAddVehicle()
    {
        $item_details = Vehicle::select('created_at')->where('is_active', 1)->orderBy('created_at', 'desc')->first();
        return ($item_details) ? $item_details->created_at->diffForHumans() : '-';
    }

    /**
     * @param        $request
     * @param string $type
     * @return mixed
     */
    public function getVehicleCount($request, $type = 'filter')
    {
        if ($type == 'filter') {
            $check_date = $this->getDateFilter($request);
            $check_date = Carbon::parse($check_date)->format('Y-m-d');
            $vehicle_count = Vehicle::where('created_at', '>=', $check_date)
                ->count();
        } else {
            $vehicle_count = Vehicle::count();
        }
        return $vehicle_count;
    }

    /**
     * get the date filter
     * @return $check_date
     */
    public function getDateFilter($request)
    {
        $check_date = Carbon::now()->subDays(7);
        if ($request->has('filter')) {
            if ($request->filter == 'lastDays') {
                $check_date = Carbon::now()->subDays(7);
            } else if ($request->filter == 'lastWeeks') {
                $check_date = Carbon::now()->subWeeks(4);
            } else if ($request->filter == 'lastMonths') {
                $check_date = Carbon::now()->subMonths(3);
            } else if ($request->filter == 'lastYears') {
                $check_date = Carbon::now()->subYears(3);
            }
        }
        return $check_date;
    }

    /**
     * @param $location1
     * @param $location2
     * @param $vehicle_type_id
     * @return float
     */
    public function processDifferLocationDropAmount($location1, $location2, $vehicle_type_id)
    {
        $unit = config('vehicle.unit');
        $lat = array();
        $lon = array();
        $diff_location_drop_details = array();
        $counter_locations = CounterLocation::whereIn('id', array($location1, $location2))->get();
        if (!empty($counter_locations)) {
            foreach ($counter_locations as $counter_location) {
                $lat[] = $counter_location->latitude;
                $lon[] = $counter_location->longitude;
            }
            $theta = $lon[0] - $lon[1];
            $dist = sin(deg2rad($lat[0])) * sin(deg2rad($lat[1])) + cos(deg2rad($lat[0])) * cos(deg2rad($lat[1])) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);
            if ($unit == "KM") {
                $miles = $miles * 1.609344;
            }
            $diff_location_drop_details['total_distance'] = ceil($miles);
            $vehicle_type = VehicleType::where('id', $vehicle_type_id)->first();
            $diff_location_drop_amount = ceil($miles) * $vehicle_type->drop_location_differ_unit_price;
            if (!empty($vehicle_type->drop_location_differ_additional_fee)) {
                $diff_location_drop_details['drop_location_differ_additional_fee'] = $vehicle_type->drop_location_differ_additional_fee;
            }
            $diff_location_drop_details['diff_location_drop_amount'] = $diff_location_drop_amount;
            $diff_location_drop_details['distance_unit'] = $unit;
            return $diff_location_drop_details;
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Given Counter locations not available. Please, try again.');
        }
    }

    /**
     * @param $vehicles
     * @param $start_date
     * @param $end_date
     * @return mixed
     */
    public function getDiscountRate($vehicles, $start_date, $end_date)
    {
        $total_trip_amount_details = array();
        foreach ($vehicles as $vehicle) {
            $special_price_percent = 0;
            // calculate total trip amount
            $total_trip_amount_details = $this->calculateBookingAmount($start_date, $end_date, $vehicle);
            $vehicle->booking_amount = $total_trip_amount_details['booking_amount'];
            // set vehicle type prices
            if (!is_null($vehicle->vehicle_type) && !is_null($vehicle->vehicle_type->vehicle_special_price)) {
                foreach ($vehicle->vehicle_type->vehicle_special_price as $special_price) {
                    if ($special_price->discount_percentage > $special_price_percent && strtotime($start_date) >= strtotime($special_price->start_date) && strtotime($end_date) <= strtotime($special_price->end_date)) {
                        $special_price_percent = $special_price->discount_percentage;
                    }
                }
            }
            // set vehicle type prices
            if (!is_null($vehicle->vehicle_type) && !is_null($vehicle->vehicle_type->vehicle_type_price)) {
                foreach ($vehicle->vehicle_type->vehicle_type_price as $type_price) {
                    if ($type_price->discount_percentage > $special_price_percent && $total_trip_amount_details['total_days'] >= $type_price->minimum_no_of_day && $total_trip_amount_details['total_days'] <= $type_price->maximum_no_of_day) {
                        $special_price_percent = $type_price->discount_percentage;
                    }
                }
            }

            if ($special_price_percent > 0) {
                $vehicle->max_discount_percent = $special_price_percent;
            }
        }
        unset($total_trip_amount_details['booking_amount']);
        $vehicles->booking_details = $total_trip_amount_details;
        return $vehicles;
    }

    /**
     * @param $vehicle_id
     * @param bool $item_user_id
     * @param bool $start_date
     * @param bool $end_date
     * @param int $is_dummy
     */
    public function addVehicleSearchRecord($vehicle_id, $item_user_id = false, $start_date = false, $end_date = false, $is_dummy = 0)
    {
        $vehicle = false;
        $search_insert_data['vehicle_id'] = $vehicle_id;
        if ($item_user_id)
            $search_insert_data['item_user_id'] = $item_user_id;
        $search_insert_data['is_dummy'] = $is_dummy;
        if ($start_date) {
            $search_insert_data['start_date'] = $start_date;
        }
        if ($end_date) {
            $search_insert_data['end_date'] = $end_date;
            if ($item_user_id && config('vehicle_rental.late_checkout_grace_time') > 0) {
                $grace_time_added_end_date = Carbon::createFromTimeStamp(strtotime($end_date))->addHours(config('vehicle_rental.late_checkout_grace_time'));
                $search_insert_data['end_date'] = $grace_time_added_end_date->toDateTimeString();
            }
        }
        if ($is_dummy && !$item_user_id) {
            $vehicle = UnavailableVehicle::where('vehicle_id', $vehicle_id)->where('is_dummy', $is_dummy)->first();
        } else {
            $vehicle = UnavailableVehicle::where('vehicle_id', $vehicle_id)->where('is_dummy', $is_dummy)->where('item_user_id', $item_user_id)->first();
        }
        if (!$vehicle) {
            UnavailableVehicle::create($search_insert_data);
        }
    }

    /**
     * @param $start_date
     * @param $end_date
     * @param $vehicle
     * @return array
     */
    public function calculateBookingAmount($start_date, $end_date, $vehicle)
    {
        $booking_calculated_details = array();
        $date_diff = $this->getDateDiff($start_date, $end_date);
        $booking_calculated_details['total_days'] = $date_diff['total_days'];
        $booking_calculated_details['total_hours'] = $date_diff['total_hours'];
        $day_amount = 0;
        $hour_amount = 0;
        $booking_calculated_details['is_day_price'] = 0;
        if ($booking_calculated_details['total_days'] > 0) {
            $day_amount = $booking_calculated_details['total_days'] * $vehicle->per_day_amount;
            $booking_calculated_details['is_day_price'] = 1;
        }
        if ($booking_calculated_details['total_hours'] > 0) {
            if (!empty($vehicle->per_hour_amount)) {
                $hour_amount = $booking_calculated_details['total_hours'] * $vehicle->per_hour_amount;
            } else {
                $hour_amount = $vehicle->per_day_amount;
            }
        }
        $booking_calculated_details['booking_amount'] = $day_amount + $hour_amount;
        $booking_calculated_details['special_price_discount_amount'] = $this->vehicleSpecialPriceService->processSpecialPriceAmount($vehicle->vehicle_type_id, $booking_calculated_details['booking_amount'], $start_date, $end_date);
        $booking_calculated_details['type_price_discount_amount'] = $this->vehicleTypePriceService->processTypePriceAmount($vehicle->vehicle_type_id, $booking_calculated_details['booking_amount'], $date_diff);
        return $booking_calculated_details;
    }

    public function updateTotalAmount($vehicle_rental)
    {
        $differ_drop_additional_fee = 0;
        if ($vehicle_rental->drop_location_differ_charges > 0) {
            $differ_drop_additional_fee = $vehicle_rental->item_userable->vehicle_type->drop_location_differ_additional_fee;
        }
        $vehicle_rental_data['total_amount'] = ($vehicle_rental->booking_amount + $vehicle_rental->tax_amount + $vehicle_rental->surcharge_amount + $vehicle_rental->drop_location_differ_charges + $vehicle_rental->additional_fee + $vehicle_rental->insurance_amount + $vehicle_rental->extra_accessory_amount + $vehicle_rental->fuel_option_amount + $vehicle_rental->deposit_amount + $vehicle_rental->cancellation_deduct_amount + $vehicle_rental->paid_manual_amount + $differ_drop_additional_fee) - ($vehicle_rental->special_discount_amount + $vehicle_rental->type_discount_amount + $vehicle_rental->coupon_discount_amount);
        $vehicle_rental_data['admin_commission_amount'] = $vehicle_rental_data['total_amount'] * (config('vehicle_rental.admin_commission_amount') / 100);
        if ($vehicle_rental->paid_deposit_amount > 0) {
            $vehicle_rental_data['booker_amount'] = $vehicle_rental->deposit_amount - $vehicle_rental->paid_deposit_amount;
        }
        $vehicle_rental_data['host_service_amount'] = ($vehicle_rental_data['total_amount'] + $vehicle_rental->paid_deposit_amount) - ($vehicle_rental_data['admin_commission_amount'] + $vehicle_rental->deposit_amount);
        $vehicle_rental->update($vehicle_rental_data);
        return $vehicle_rental;
    }

    public function getDateDiff($start_date, $end_date)
    {
        $date_diff = array();
        $hour_diff = round((strtotime($end_date) - strtotime($start_date)) / 3600, 1);
        $hour_diff = ceil($hour_diff);
        $date_diff['total_days'] = floor($hour_diff / 24);
        $date_diff['total_hours'] = ceil($hour_diff % 24);
        return $date_diff;
    }

    /**
     * @param $vehicle
     */
    public function afterSave($vehicle)
    {
        // unavailable vehicle dummy record put changes
        $this->addVehicleSearchRecord($vehicle->id, false, false, false, 1);
        // vehicle count update to realted table
        $this->updateVehicleCount($vehicle->vehicle_make_id, $vehicle->vehicle_model_id, $vehicle->vehicle_type_id, $vehicle->vehicle_company_id);
    }

    /**
     * Update Vehicle count in related table
     * @param $make_id
     * @param $model_id
     * @param $type_id
     * @param $company_id
     */
    public function updateVehicleCount($make_id, $model_id, $type_id, $company_id)
    {
        if ($make_id) {
            $make_count = Vehicle::where('is_active', 1)->where('vehicle_make_id', $make_id)->count();
            VehicleMake::where('id', '=', $make_id)->update(['vehicle_count' => $make_count]);
        }
        if ($model_id) {
            $vehicle_model_count = Vehicle::where('is_active', 1)->where('vehicle_model_id', $model_id)->count();
            VehicleModel::where('id', '=', $model_id)->update(['vehicle_count' => $vehicle_model_count]);
        }
        if ($type_id) {
            $type_count = Vehicle::where('is_active', 1)->where('vehicle_type_id', $type_id)->count();
            VehicleType::where('id', '=', $type_id)->update(['vehicle_count' => $type_count]);
        }
        if ($company_id) {
            $company_count = Vehicle::where('is_active', 1)->where('vehicle_company_id', $company_id)->count();
            VehicleCompany::where('id', '=', $company_id)->update(['vehicle_count' => $company_count]);
        }
    }

    /**
     * @param $vehicleId
     * @param $transaction_fee
     * @return bool
     */
    public function processVehicleLisitngFee($vehicleId, $gateway_id, $transaction_fee)
    {
        $vehicle = Vehicle::with('user')->where('id', $vehicleId)->first();
        if (empty($vehicle)) {
            return $this->response->errorNotFound("Invalid Request");
        }
        if (!$vehicle['is_paid']) {
            $this->transactionService->log($vehicle->user->id, config('constants.ConstUserIds.Admin'), config('constants.ConstTransactionTypes.VehicleListingFee'), config('vehicle.listing_fee'), $vehicle->id, 'Vehicles', $gateway_id, $transaction_fee, "Vehicle listing Fee");
            $vehicle->is_paid = true;
            $vehicle->save();
            return true;
        } else {
            return true;
        }
    }

    public function updateFeedbackDetails($vehicle, $average_rating)
    {
     
        $vehicle->feedback_count = \Plugins\VehicleFeedbacks\Model\VehicleFeedback::where('feedbackable_id', $vehicle->id)->count();
        // update average rating
        $vehicle->feedback_rating = $average_rating;
        $vehicle->save();
    }

    public function processCheckoutLateFee($vehicle_rental, $claim_request_amount)
    {
        $late_fee_details = array();
        $late_fee_details['late_checkout_days_fee'] = $late_fee_details['late_checkout_hours_fee'] = $late_fee_details['late_checkout_hours'] = $late_fee_details['revised_late_hours_from_gracetime'] = $late_fee_details['late_checkout_days'] = 0;
        $late_fee_details['grace_time'] = config('vehicle_rental.late_checkout_grace_time');
        $checkout_time = Carbon::now()->toDateTimeString();
        $late_fee_details['current_date'] = $checkout_time;
        $checkout_late_date_diff = $this->getDateDiff($vehicle_rental->item_booking_end_date, $checkout_time);
        $late_fee_details['total_late_hours'] = ($checkout_late_date_diff['total_days'] * 24) + $checkout_late_date_diff['total_hours'];
        if ($checkout_late_date_diff['total_days']) {
            $late_fee_details['late_checkout_days'] = $checkout_late_date_diff['total_days'];
            $late_fee_details['late_checkout_days_fee'] = $late_fee_details['late_checkout_days'] * $vehicle_rental->item_userable->per_day_amount;
        }
        if ($checkout_late_date_diff['total_hours']) {
            $late_fee_details['late_checkout_hours'] = $checkout_late_date_diff['total_hours'];
            if ($vehicle_rental->item_userable->per_hour_amount) {
                if ($late_fee_details['grace_time'] < $late_fee_details['late_checkout_hours']) {
                    $late_fee_details['revised_late_hours_from_gracetime'] = $late_fee_details['late_checkout_hours'] - $late_fee_details['grace_time'];
                }
                $late_fee_details['late_checkout_hours_fee'] = $late_fee_details['revised_late_hours_from_gracetime'] * $vehicle_rental->item_userable->per_hour_amount;
            } else {
                $late_fee_details['late_checkout_hours_fee'] = $vehicle_rental->item_userable->per_day_amount;
            }
        }
        $late_fee_details['vehicle_type_late_checkout_fee'] = $vehicle_rental->item_userable->vehicle_type->late_checkout_addtional_fee;
        $late_fee_details['late_checkout_total_fee'] = $late_fee_details['late_checkout_days_fee'] + $late_fee_details['late_checkout_hours_fee'] + $late_fee_details['vehicle_type_late_checkout_fee'];
        if ($late_fee_details['grace_time'] > $late_fee_details['late_checkout_hours']) {
            $late_fee_details['late_checkout_total_fee'] = 0;
        }
        // calculate manual payment
        $late_fee_details['paid_deposit_amount'] = 0;
        $late_fee_details['paid_manual_amount'] = 0;
        if ($claim_request_amount > 0 || $late_fee_details['late_checkout_total_fee'] > 0) {
            $booker_manual_payable = $claim_request_amount + $late_fee_details['late_checkout_total_fee'];
            if ($booker_manual_payable >= $vehicle_rental->deposit_amount) {
                $late_fee_details['paid_deposit_amount'] = $vehicle_rental->deposit_amount;
                $late_fee_details['paid_manual_amount'] = $booker_manual_payable - $vehicle_rental->deposit_amount;
            } else if ($vehicle_rental->deposit_amount > $booker_manual_payable) {
                $late_fee_details['paid_deposit_amount'] = $booker_manual_payable;
                $late_fee_details['paid_manual_amount'] = 0;
            }
        }
        return $late_fee_details;
    }

}
