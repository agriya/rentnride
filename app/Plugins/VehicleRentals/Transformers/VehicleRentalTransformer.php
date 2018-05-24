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
 
namespace Plugins\VehicleRentals\Transformers;

use League\Fractal;
use Plugins\VehicleRentals\Model\VehicleRental;
use App\Transformers\UserSimpleTransformer;
use Plugins\Vehicles\Services\VehicleService;
use Carbon;

/**
 * Class VehicleRentalTransformer
 * @package App\Transformers
 */
class VehicleRentalTransformer extends Fractal\TransformerAbstract
{
    /**
     * List of resources possible to include
     * @var array
     */
    protected $availableIncludes = [
        'User', 'ItemUserStatus', 'VehicleCoupon', 'ItemUserable', 'ItemUserDispute', 'VehicleFeedback', 'DropCounterLocation', 'PickupCounterLocation', 'VehicleRentalAdditionalChargable', 'LatePaymentDetail', 'BookerDetail', 'UnavailableVehicle'
    ];

    /**
     * @param VehicleRental $vehicle_rental
     * @return array
     */
    public function transform(VehicleRental $vehicle_rental)
    {
        $output = array_only($vehicle_rental->toArray(), ['id', 'user_id', 'created_at', 'quantity', 'total_amount', 'item_user_status_id', 'coupon_id', 'admin_commission_amount', 'item_userable_id', 'item_userable_type', 'is_payment_cleared', 'coupon_discount_amount', 'special_discount_amount', 'type_discount_amount', 'item_booking_start_date', 'item_booking_end_date', 'is_dispute', 'booking_amount', 'pickup_counter_location_id', 'drop_counter_location_id', 'deposit_amount', 'surcharge_amount', 'extra_accessory_amount', 'tax_amount', 'insurance_amount', 'fuel_option_amount', 'drop_location_differ_charges', 'additional_fee', 'claim_request_amount', 'late_fee']);
		$output['item_user_status_id'] = (integer)$output['item_user_status_id'];
        $vehicle_service = new VehicleService();
        $output['date_diff'] = $vehicle_service->getDateDiff($output['item_booking_start_date'], $output['item_booking_end_date']);
        $current_date = Carbon::now()->toDateTimeString();
        $check_in = 0;
        $check_out = 0;
        if ($output['item_booking_start_date'] <= $current_date && $output['item_booking_end_date'] > $current_date && $output['item_user_status_id'] == config('constants.ConstItemUserStatus.Confirmed')) {
            $check_in = 1;
        }
        if ($output['item_user_status_id'] == config('constants.ConstItemUserStatus.Attended')) {
            $check_out = 1;
            $late_fee_details = $vehicle_service->processCheckoutLateFee($vehicle_rental, 0);
            if (!empty($late_fee_details['late_checkout_total_fee'])) {
                $output['late_checkout_total_fee'] = $late_fee_details['late_checkout_total_fee'];
            }
            if (!empty($late_fee_details['grace_time'])) {
                $output['grace_time'] = $late_fee_details['grace_time'];
            }
            if (!empty($late_fee_details['revised_late_hours_from_gracetime'])) {
                $output['revised_late_hours_from_gracetime'] = $late_fee_details['revised_late_hours_from_gracetime'];
            }
            if (!empty($late_fee_details['late_checkout_hours'])) {
                $output['late_checkout_hours'] = $late_fee_details['late_checkout_hours'];
                $output['late_checkout_hours_fee'] = $late_fee_details['late_checkout_hours_fee'];
            }
            if (!empty($late_fee_details['late_checkout_days'])) {
                $output['late_checkout_days'] = $late_fee_details['late_checkout_days'];
                $output['late_checkout_days_fee'] = $late_fee_details['late_checkout_days_fee'];
            }
            if (!empty($late_fee_details['vehicle_type_late_checkout_fee'])) {
                $output['vehicle_type_late_checkout_fee'] = $late_fee_details['vehicle_type_late_checkout_fee'];
            }
        }
        if (isset($output['is_payment_cleared'])) {
            $output['is_payment_cleared'] = (int)$output['is_payment_cleared'];
        }
        if (isset($output['is_dispute'])) {
            $output['is_dispute'] = (int)$output['is_dispute'];
        }
        $output['checkin'] = $check_in;
        $output['checkout'] = $check_out;
        if ($vehicle_rental->drop_location_differ_charges > 0 && !is_null($vehicle_rental->item_userable) &&!is_null($vehicle_rental->item_userable->vehicle_type)) {
            if ($vehicle_rental->item_userable->vehicle_type->drop_location_differ_additional_fee > 0) {
                $output['drop_location_differ_additional_fee'] = $vehicle_rental->item_userable->vehicle_type->drop_location_differ_additional_fee;
            }
            $output['total_distance'] = ceil($vehicle_rental->drop_location_differ_charges / $vehicle_rental->item_userable->vehicle_type->drop_location_differ_unit_price);
            $output['distance_unit'] = config('vehicle.unit');
        }
        return $output;
    }


    /**
     * @param VehicleRental $vehicle_rental
     * @return Fractal\Resource\Item
     */
    public function includeUser(VehicleRental $vehicle_rental)
    {
        if ($vehicle_rental->user) {
            return $this->item($vehicle_rental->user, new UserSimpleTransformer());
        } else {
            return null;
        }
    }

    /**
     * @param VehicleRental $vehicle_rental
     * @return Fractal\Resource\Item
     */
    public function includeItemUserStatus(VehicleRental $vehicle_rental)
    {
        if ($vehicle_rental->item_user_status) {
            return $this->item($vehicle_rental->item_user_status, new VehicleRentalStatusTransformer());
        } else {
            return null;
        }
    }

    /**
     * @param VehicleRental $vehicle_rental
     * @return Fractal\Resource\Item
     */
    public function includeVehicleCoupon(VehicleRental $vehicle_rental)
    {
        if ($vehicle_rental->vehicle_coupon) {
            return $this->item($vehicle_rental->vehicle_coupon, new \Plugins\VehicleCoupons\Transformers\VehicleCouponTransformer());
        } else {
            return null;
        }
    }

    /**
     * @param VehicleRental $vehicle_rental
     * @return Fractal\Resource\Item
     */
    public function includeItemUserable(VehicleRental $vehicle_rental)
    {
        if ($vehicle_rental->item_userable) {
            if ($vehicle_rental->item_userable_type == 'MorphVehicle') {
                return $this->item($vehicle_rental->item_userable, (new \Plugins\Vehicles\Transformers\VehicleTransformer)->setDefaultIncludes(['user', 'vehicle_company', 'vehicle_type', 'vehicle_make', 'vehicle_model', 'attachments', 'counter_location']));
            }
        } else {
            return null;
        }
    }

    /**
     * @param VehicleRental $vehicle_rental
     * @return Fractal\Resource\Item
     */
    public function includeItemUserDispute(VehicleRental $vehicle_rental)
    {
        if ($vehicle_rental->item_user_dispute) {
            if ($vehicle_rental->item_user_dispute->item_user_disputable_type == 'MorphVehicleRental') {
                return $this->item($vehicle_rental->item_user_dispute, (new \Plugins\VehicleDisputes\Transformers\VehicleDisputeTransformer)->setDefaultIncludes(['user', 'dispute_type', 'dispute_closed_type', 'dispute_status']));
            }
        } else {
            return null;
        }
    }

    /**
     * @param VehicleRental $vehicle_rental
     * @return Fractal\Resource\Item|null
     */
    public function includeVehicleFeedback(VehicleRental $vehicle_rental)
    {
        if ($vehicle_rental->vehicle_feedback) {
            return $this->item($vehicle_rental->vehicle_feedback, (new \Plugins\VehicleFeedbacks\Transformers\VehicleFeedbackTransformer)->setDefaultIncludes(['user']));
        } else {
            return null;
        }
    }

    /**
     * @param VehicleRental $vehicle_rental
     * @return Fractal\Resource\Item
     */
    public function includeLatePaymentDetail(VehicleRental $vehicle_rental)
    {
        if ($vehicle_rental->late_payment_detail) {
            return $this->item($vehicle_rental->late_payment_detail, new VehicleRentalLatePaymentDetailTransformer());
        } else {
            return null;
        }
    }

    public function includePickupCounterLocation(VehicleRental $vehicle_rental)
    {
        if ($vehicle_rental->pickup_counter_location) {
            return $this->item($vehicle_rental->pickup_counter_location, new  \Plugins\Vehicles\Transformers\CounterLocationTransformer);
        } else {
            return null;
        }
    }

    public function includeDropCounterLocation(VehicleRental $vehicle_rental)
    {
        if ($vehicle_rental->drop_counter_location) {
            return $this->item($vehicle_rental->drop_counter_location, new  \Plugins\Vehicles\Transformers\CounterLocationTransformer);
        } else {
            return null;
        }
    }

    public function includeVehicleRentalAdditionalChargable(VehicleRental $vehicle_rental)
    {
        if ($vehicle_rental->vehicle_rental_additional_chargable) {
            return $this->collection($vehicle_rental->vehicle_rental_additional_chargable, new VehicleRentalAdditionalChargeTransformer());
        } else {
            return null;
        }
    }

    /**
     * @param VehicleRental $vehicle_rental
     * @return Fractal\Resource\Item
     */
    public function includeBookerDetail(VehicleRental $vehicle_rental)
    {
        if ($vehicle_rental->booker_detail) {
            return $this->item($vehicle_rental->booker_detail, new VehicleRentalBookerDetailTransformer());
        } else {
            return null;
        }
    }

    /**
     * @param VehicleRental $vehicle_rental
     * @return Fractal\Resource\Item
     */
    public function includeUnavailableVehicle(VehicleRental $vehicle_rental)
    {
        if ($vehicle_rental->unavailable_vehicle) {
            return $this->item($vehicle_rental->unavailable_vehicle, new \Plugins\Vehicles\Transformers\UnavailableVehicleTransformer());
        } else {
            return null;
        }
    }

}
