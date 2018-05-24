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
 
namespace Plugins\VehicleRentals\Controllers;

use App\Services\IpService;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Plugins\VehicleRentals\Model\VehicleRental;
use Plugins\VehicleRentals\Model\VehicleRentalAdditionalCharge;
use Plugins\Vehicles\Model\Vehicle;
use Plugins\Vehicles\Model\UnavailableVehicle;
use Plugins\Vehicles\Model\VehicleSpecialPrice;
use Plugins\VehicleRentals\Model\VehicleRentalLatePaymentDetail;
use Plugins\VehicleRentals\Services\VehicleRentalService;
use Plugins\VehicleRentals\Services\VehicleRentalBookerDetailService;
use Plugins\Vehicles\Model\VehicleType;
use Plugins\Vehicles\Services\VehicleService;
use Plugins\Vehicles\Services\UnavailableVehicleService;
use App\Services\UserService;
use JWTAuth;
use Validator;
use Plugins\VehicleRentals\Transformers\VehicleRentalTransformer;
use Carbon;
use App\User;
use App\Services\TransactionService;
use Log;

/**
 * VehicleRentals resource representation.
 * @Resource("VehicleRentals")
 */
class VehicleRentalsController extends Controller
{
    /**
     * @var VehicleRentalService
     */
    protected $vehicleRentalService;
    /**
     * @var $ipservice
     */
    protected $ipservice;
    /**
     * @var $userservice
     */
    protected $userservice;

    /**
     * @var TransactionService
     */
    protected $transactionService;

    /**
     * @var TaxService
     */
    protected $vehicleTaxService;

    /**
     * @var SurchargeService
     */
    protected $vehicleSurchargeService;

    /**
     * @var vehicleService
     */
    protected $vehicleService;

    /**
     * @var unavailableVehicleService
     */
    protected $unavailableVehicleService;

    /**
     * @var vehicleRentalBookerDetailService
     */
    protected $vehicleRentalBookerDetailService;

    /**
     * VehicleRentalsController constructor.
     * @param VehicleRentalMailService $service
     */
    public function __construct(UserService $userservice)
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
        $this->setVehicleRentalService();
        $this->setVehicleService();
        $this->setUnavailableVehicleService();
        $this->setIpService();
        $this->setTransactionService();
        $this->setVehicleRentalBookerDetailService();
        $this->UserService = $userservice;
    }

    public function setVehicleRentalService()
    {
        $this->vehicleRentalService = new VehicleRentalService();
    }

    public function setVehicleService()
    {
        $this->vehicleService = new VehicleService();
    }

    public function setUnavailableVehicleService()
    {
        $this->unavailableVehicleService = new UnavailableVehicleService();
    }

    public function setIpService()
    {
        $this->IpService = new IpService();
    }

    public function setTransactionService()
    {
        $this->transactionService = new TransactionService();
    }

    public function setVehicleRentalBookerDetailService()
    {
        $this->vehicleRentalBookerDetailService = new VehicleRentalBookerDetailService();
    }

    /**
     * Show all vehicle_rentals.
     * Get a JSON representation of all the vehicle_rentals.
     * @Get("/vehicle_rentals?filter={filter}&sort={sort}&sortby={sortby}&q={q}&page={page}")
     * @Parameters({
     *      @Parameter("filter", type="integer", required=false, description="Filter vehicle_rentals list by status.", default=null),
     *      @Parameter("sort", type="string", required=false, description="Sort the vehicle_rentals list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort vehicle_rentals by Ascending / Descending Order.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1),
     *      @Parameter("q", type="string", required=false, description="Search vehicle_rentals.", default=null)
     * })
     */
    public function index(Request $request)
    {
        $user = $this->auth->user();
        $enabledIncludes = array('user', 'item_user_status');
        // check if plugin enabled and include
        (isPluginEnabled('VehicleCoupons')) ? $enabledIncludes[] = 'vehicle_coupon' : '';
        $status_arr = array();
        if ($request->has('item_user_status_id') && $request->item_user_status_id == config('constants.ConstItemUserStatus.WaitingForReview')) {
            $status_arr = [config('constants.ConstItemUserStatus.WaitingForReview'), config('constants.ConstItemUserStatus.HostReviewed')];
        }
        if ($request->has('item_user_status_id') && $request->item_user_status_id == config('constants.ConstItemUserStatus.Completed')) {
            $status_arr = [config('constants.ConstItemUserStatus.BookerReviewed'), config('constants.ConstItemUserStatus.Completed'), config('constants.ConstItemUserStatus.WaitingForPaymentCleared')];
        }
        $bookings_count = config('constants.ConstPageLimit');
        if($request->has('limit') && $request->limit == 'all') {
            $bookings_count = VehicleRental::with($enabledIncludes)->filterByVehicleRental()->filterByRequest($request, $status_arr)->count();
        }
        $vehicle_rentals = VehicleRental::with($enabledIncludes)->filterByVehicleRental()->filterByRequest($request, $status_arr)->paginate($bookings_count);
        $enabledIncludes = array_merge($enabledIncludes, array('item_userable'));
        return $this->response->paginator($vehicle_rentals, (new VehicleRentalTransformer)->setDefaultIncludes($enabledIncludes));
    }

    /**
     * Show all items orders.
     * Get a JSON representation of all the items orders.
     * @Get("/item_orders?filter={filter}&sort={sort}&sortby={sortby}&q={q}")
     * @Parameters({
     *      @Parameter("filter", type="integer", required=false, description="Filter the items list by status.", default=null),
     *      @Parameter("sort", type="string", required=false, description="Sort the items list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort items by Ascending / Descending Order.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1)
     * })
     */
    public function itemOrders(Request $request)
    {
        $user = $this->auth->user();
        $vehicles = Vehicle::with('user')->where('user_id', $user->id)->pluck('id')->all();
        $status_arr = array();
        if ($request->has('item_user_status_id') && $request->item_user_status_id == config('constants.ConstItemUserStatus.WaitingForReview')) {
            $status_arr = [config('constants.ConstItemUserStatus.WaitingForReview'), config('constants.ConstItemUserStatus.BookerReviewed')];
        }
        $order_count = config('constants.ConstPageLimit');
        if($request->has('limit') && $request->limit == 'all') {
            $order_count = VehicleRental::whereIn('item_userable_id', $vehicles)->whereNotIn('item_user_status_id', [config('constants.ConstItemUserStatus.PaymentPending')])->where('item_userable_type', 'MorphVehicle')->filterByRequest($request, $status_arr)->count();
        }
        $item_orders = VehicleRental::whereIn('item_userable_id', $vehicles)->whereNotIn('item_user_status_id', [config('constants.ConstItemUserStatus.PaymentPending')])->where('item_userable_type', 'MorphVehicle')->filterByRequest($request, $status_arr)->paginate($order_count);
        return $this->response->paginator($item_orders, (new \Plugins\VehicleRentals\Transformers\VehicleRentalTransformer)->setDefaultIncludes(['user', 'item_userable']));
    }

    /**
     * Show the vehicle_rental.
     * Show the vehicle_rental with a `id`.
     * @Get("/vehicle_rentals/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"id": 1, "user_id": 1, "item_user_status_id": 1, "coupon_id": 1, "quantity": 1, "total_amount": 100.00, "user": {}, "coupon": {}, "item_user_status": {}}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function show($id, Request $request)
    {
        $enabledIncludes = array('user', 'item_user_status', 'pickup_counter_location', 'drop_counter_location', 'vehicle_rental_additional_chargable');
        // check if plugin enabled and include
        (isPluginEnabled('VehicleCoupons')) ? $enabledIncludes[] = 'vehicle_coupon' : '';
        $vehicle_rental = VehicleRental::with($enabledIncludes)->find($id);
        if (!$vehicle_rental) {
            return $this->response->errorNotFound("Invalid Request");
        }
        if($request->has('type') && $request->type == 'rental') {
            $unavailable_vehicles = $this->unavailableVehicleService->checkBookingAvailability($vehicle_rental);
            if ($unavailable_vehicles) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle not available for the given dates. Please try again with different dates');
            }
        }
        $enabledIncludes = array_merge($enabledIncludes, array('item_userable', 'booker_detail'));
        return $this->response->item($vehicle_rental, (new VehicleRentalTransformer)->setDefaultIncludes($enabledIncludes));
    }

    /**
     * Edit the vehicle_rental.
     * Edit the vehicle_rental with a `id`.
     * @Get("/vehicle_rentals/{id}/edit")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"id": 1, "user_id": 1, "item_user_status_id": 1, "coupon_id": 1, "quantity": 1, "total_amount": 100.00, "user": {}, "coupon": {}}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function edit($id)
    {
        $enabledIncludes = array('user', 'item_user_status');
        // check if plugin enabled and include
        (isPluginEnabled('VehicleCoupons')) ? $enabledIncludes[] = 'vehicle_coupon' : '';
        $vehicle_rental = VehicleRental::with($enabledIncludes)->find($id);
        if (!$vehicle_rental) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($vehicle_rental, (new VehicleRentalTransformer)->setDefaultIncludes($enabledIncludes));
    }

    /**
     * Store a vehicle_rental.
     * Store a new vehicle_rental with a a `vehicle_id`, `item_booking_start_date`, `item_booking_end_date`, `quantity` and `coupon_code`.
     * @Post("/vehicle_rentals")
     * @Transaction({
     *      @Request({"item_booking_start_date": "01-01-2016 00:00:00", "item_booking_end_date": "31-01-2016 00:00:00", "quantity": 1, "coupon_code": "XXXXXX"}),
     *      @Response(200, body={"success": "Record has been added."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {}, "status_code": 422})
     * })
     */
    public function store(Request $request)
    {
        $vehicle_rental_data = $request->only('vehicle_id', 'item_booking_start_date', 'item_booking_end_date', 'pickup_counter_location_id', 'drop_counter_location_id');
        $vehicle_rental_data['item_booking_start_date'] = $request->item_booking_start_date = date("Y-m-d H:i:s", strtotime($vehicle_rental_data['item_booking_start_date']));
        $vehicle_rental_data['item_booking_end_date'] = $request->item_booking_end_date = date("Y-m-d H:i:s", strtotime($vehicle_rental_data['item_booking_end_date']));
        $user = $this->auth->user();
        if (isPluginEnabled('Vehicles')) {
            $vehicle = Vehicle::with(['vehicle_type'])->find($request->vehicle_id);
            if (!$vehicle) {
                return $this->response->errorNotFound("Invalid Request");
            }
            if ($user) {
                $vehicle_rental_data['user_id'] = $user->id;
                if ($vehicle->user_id == $user->id) {
                    return $this->response->errorNotFound("Invalid Request");
                }
            }
            $cur_date = Carbon::now()->toDateTimeString();
            if ($cur_date > $vehicle_rental_data['item_booking_start_date'] || $vehicle_rental_data['item_booking_start_date'] > $vehicle_rental_data['item_booking_end_date']) {
                return $this->response->errorNotFound("Invalid Request");
            }
            $unavailable_vehicle = UnavailableVehicle::where('vehicle_id', $request->vehicle_id)
                ->where(function ($query) use ($request) {
                    $query->whereBetween('start_date', [$request->item_booking_start_date, $request->item_booking_end_date])
                        ->orWhereBetween('end_date', [$request->item_booking_start_date, $request->item_booking_end_date])
                        ->orwhere(function ($query) use ($request) {
                            $query->where('start_date', '>', $request->item_booking_start_date)
                                ->where('end_date', '<', $request->item_booking_start_date);
                        })->orwhere(function ($query) use ($request) {
                            $query->where('start_date', '<', $request->item_booking_end_date)
                                ->where('end_date', '>', $request->item_booking_end_date);
                        });
                })
                ->where('is_dummy', '!=', 1)->first();
            if ($unavailable_vehicle) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle not available for the given dates. Please try again with different dates');
            }
        } else {
            return $this->response->errorNotFound("Invalid Request");
        }
        $vehicle_rental_data['item_user_status_id'] = config('constants.ConstItemUserStatus.PaymentPending');
        $validator = Validator::make($vehicle_rental_data, VehicleRental::GetValidationRule(), VehicleRental::GetValidationMessage());
        if ($validator->passes()) {
            try {
                if (!is_null($vehicle->vehicle_type)) {
                    if ($vehicle->vehicle_type->deposit_amount > 0) {
                        $vehicle_rental_data['deposit_amount'] = $vehicle->vehicle_type->deposit_amount;
                    }
                }
                // quantity default set as 1
                $vehicle_rental_data['quantity'] = 1;
                $vehicle_rental = VehicleRental::create($vehicle_rental_data);
                $booking_calculated_details = $this->vehicleService->calculateBookingAmount($vehicle_rental_data['item_booking_start_date'], $vehicle_rental_data['item_booking_end_date'], $vehicle);
                $vehicle_rental_data['booking_amount'] = $booking_calculated_details['booking_amount'];
                if ($booking_calculated_details['special_price_discount_amount'] > $booking_calculated_details['type_price_discount_amount']) {
                    $vehicle_rental_data['special_discount_amount'] = $booking_calculated_details['special_price_discount_amount'];
                } else {
                    $vehicle_rental_data['type_discount_amount'] = $booking_calculated_details['type_price_discount_amount'];
                }
                if (isPluginEnabled('VehicleTaxes')) {
                    $vehicleTaxService = new \Plugins\VehicleTaxes\Services\VehicleTaxService();
                    $vehicle_rental_data['tax_amount'] = $vehicleTaxService->processTaxAmount($vehicle->vehicle_type_id, $vehicle_rental->id, $booking_calculated_details);
                }
                if (isPluginEnabled('VehicleSurcharges')) {
                    $vehicleSurchargeService = new \Plugins\VehicleSurcharges\Services\VehicleSurchargeService();
                    $vehicle_rental_data['surcharge_amount'] = $vehicleSurchargeService->processSurchargeAmount($vehicle->vehicle_type_id, $vehicle_rental->id, $booking_calculated_details);
                }
                if ($vehicle_rental_data['pickup_counter_location_id'] != $vehicle_rental_data['drop_counter_location_id']) {
                    $drop_location_differ_details = $this->vehicleService->processDifferLocationDropAmount($vehicle_rental_data['pickup_counter_location_id'], $vehicle_rental_data['drop_counter_location_id'], $vehicle->vehicle_type_id);
                    $vehicle_rental_data['drop_location_differ_charges'] = $drop_location_differ_details['diff_location_drop_amount'];
                }
                $vehicle_rental->update($vehicle_rental_data);
                if ($vehicle_rental) {
                    $curVehicle = Vehicle::with(['vehicle_rentals'])->where('id', '=', $request->vehicle_id)->first();
                    $curVehicle->vehicle_rentals()->save($vehicle_rental);
                    $vehicle_rental = VehicleRental::find($vehicle_rental->id);
                    $vehicle_rental = $this->vehicleService->updateTotalAmount($vehicle_rental);
                    $this->vehicleRentalService->updateItemUserCount();
                }
                return $this->response->item($vehicle_rental, new VehicleRentalTransformer);
            } catch (\Exception $e) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle Rental could not be added. Please, try again.',
                    array($e->getMessage()));
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle Rental could not be added. Please, try again.', $validator->errors());
        }
    }

    /**
     * @param Request $request
     * @return \Dingo\Api\Http\Response|void
     */
    public function update(Request $request, $id)
    {
        $user = $this->auth->user();
        $vehicle_rental_data = array();
        try {
            $vehicle_rental = VehicleRental::with('vehicle_rental_additional_chargable')->where('id', $request->id)->first();
            if ($vehicle_rental->user_id != $user->id) {
                return $this->response->errorNotFound("Invalid Request");
            }
            // add booking details
            $booker_details = $this->vehicleRentalBookerDetailService->addRentalBookerDetail($request);
            if (isset($booker_details['Error'])) {
                if (isset($booker_details['type']) && $booker_details['type'] == 'catch') {
                    throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle Rental could not be updated. Please, try again.', $booker_details['Error']);
                } else if (isset($booker_details['type']) && $booker_details['type'] == 'validate') {
                    throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle Rental could not be updated. Please, try again.', $booker_details['Error']->errors());
                } else {
                    return $this->response->errorNotFound("Invalid Request");
                }
            }
            // delete the records from item_user_additional_charges, if the user modify their choice of picking insurance, extra accessory, fuel option
            if (!empty($vehicle_rental->vehicle_rental_additional_chargable)) {
                foreach ($vehicle_rental->vehicle_rental_additional_chargable as $vehicle_rental_additional_charge) {
                    if ($vehicle_rental_additional_charge->item_user_additional_chargable_type == 'MorphInsurance' || $vehicle_rental_additional_charge->item_user_additional_chargable_type == 'MorphExtraAccessory' || $vehicle_rental_additional_charge->item_user_additional_chargable_type == 'MorphFuelOption') {
                        $vehicle_rental_additional_charge->delete();
                    }
                }
            }
            $date_diff = $this->vehicleService->getDateDiff($vehicle_rental->item_booking_start_date, $vehicle_rental->item_booking_end_date);
            if (isPluginEnabled('VehicleInsurances') && $request->has('vehicle_type_insurances') && !empty($request->vehicle_type_insurances)) {
                $vehicleInsuranceService = new \Plugins\VehicleInsurances\Services\VehicleInsuranceService();
                $vehicle_rental_data['insurance_amount'] = $vehicleInsuranceService->processInsuranceAmount($request->id, $vehicle_rental->booking_amount, $request->vehicle_type_insurances, $date_diff['total_days']);
            }else{
                $vehicle_rental_data['insurance_amount'] = 0.00;
            }
            if (isPluginEnabled('VehicleExtraAccessories') && $request->has('vehicle_type_extra_accessories') && !empty($request->vehicle_type_extra_accessories)) {
                $vehicleExtraAccessoryService = new \Plugins\VehicleExtraAccessories\Services\VehicleExtraAccessoryService();
                $vehicle_rental_data['extra_accessory_amount'] = $vehicleExtraAccessoryService->processExtraAccessoryAmount($request->id, $vehicle_rental->booking_amount, $request->vehicle_type_extra_accessories, $date_diff['total_days']);
            }else{
                $vehicle_rental_data['extra_accessory_amount'] = 0.00;
            }
            if (isPluginEnabled('VehicleFuelOptions') && $request->has('vehicle_type_fuel_options') && !empty($request->vehicle_type_fuel_options)) {
                $vehicleFuelOptionService = new \Plugins\VehicleFuelOptions\Services\VehicleFuelOptionService();
                $vehicle_rental_data['fuel_option_amount'] = $vehicleFuelOptionService->processFuelOptionAmount($request->id, $vehicle_rental->booking_amount, $request->vehicle_type_fuel_options, $date_diff['total_days']);
            }else{
                $vehicle_rental_data['fuel_option_amount'] = 0.00;
            }
            if (!empty($vehicle_rental_data)) {
                $vehicle_rental->update($vehicle_rental_data);
            }
            $vehicle_rental = $this->vehicleService->updateTotalAmount($vehicle_rental);
            return $this->response->item($vehicle_rental, new VehicleRentalTransformer);
        } catch (\Exception $e) {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle Rental could not be added. Please, try again.',
                array($e->getMessage()));
        }
    }

    /**
     * Reject the specified vehicle_rental.
     * Reject the vehicle_rental with a `id`.
     * @Put("/vehicle_rentals/{id}/reject")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"success": "Record has been rejected."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {}, "status_code": 422})
     * })
     */
    public function reject($id)
    {
        $enabledIncludes = $this->vehicleRentalService->getEnableGateway();
        $vehicle_rental = VehicleRental::with($enabledIncludes)->where(['item_user_status_id' => config('constants.ConstItemUserStatus.WaitingForAcceptance'), 'id' => $id])->first();
        if (!$vehicle_rental || is_null($vehicle_rental->item_userable)) {
            return $this->response->errorNotFound("Invalid Request");
        }
        $user = $this->auth->user();
        if ($vehicle_rental->item_userable->user_id != $user->id) {
            return $this->response->errorNotFound("Invalid Request");
        }
        try {
            $error_msg = '';
            $is_payment_transaction = false;
            $transaction_log = array();
            if (isPluginEnabled('Paypal') && !is_null($vehicle_rental->paypal_transaction_log)) {
                $gateway_id = config('constants.ConstPaymentGateways.PayPal');
                if ($vehicle_rental->paypal_transaction_log->payment_type == 'authorized') {
                    $paypal = new \Plugins\Paypal\Services\PayPalService();
                    $voidPayment = $paypal->voidPayment($vehicle_rental->paypal_transaction_log->authorization_id);
                    if (is_object($voidPayment)) {
                        $transaction_log['payment_type'] = $voidPayment->getState();
                        $transaction_log['void_id'] = $voidPayment->getId();
                        if ($transaction_log['payment_type'] == 'voided') {
                            $is_payment_transaction = true;
                        }
                    } else if (is_array($voidPayment) && $voidPayment['error']) {
                        if ($voidPayment['error']['message']) {
                            $error_msg = $voidPayment['error']['message'];
                        } else {
                            $error_msg = $voidPayment['error_message'];
                        }
                    }
                    $vehicle_rental->paypal_transaction_log->update($transaction_log);
                }
            }
            if (isPluginEnabled('Sudopays') && !is_null($vehicle_rental->sudopay_transaction_logs)) {
                $gateway_id = config('constants.ConstPaymentGateways.SudoPay');
                $sudopayService = new \Plugins\Sudopays\Services\SudopayService();
                if ($vehicle_rental->sudopay_transaction_logs->status == 'Authorized') {
                    $voidPayment = $sudopayService->voidPayment($vehicle_rental->sudopay_transaction_logs);
                    if (!empty($voidPayment) && $voidPayment['status'] == 'Canceled') {
                        $transaction_log['status'] = $voidPayment['status'];
                        $is_payment_transaction = true;
                        $vehicle_rental->sudopay_transaction_logs->update($transaction_log);
                    } else if (is_array($voidPayment) && $voidPayment['error']) {
                        if ($voidPayment['error']['message']) {
                            $error_msg = $voidPayment['error']['message'];
                        } else {
                            $error_msg = $voidPayment['error_message'];
                        }
                    }
                } elseif ($vehicle_rental->sudopay_transaction_logs->status == 'Captured') {
                    $refundPayment = $sudopayService->refundPayment($vehicle_rental->sudopay_transaction_logs);
                    if (!empty($refundPayment) && $refundPayment['status'] == 'Refunded') {
                        $transaction_log['status'] = $refundPayment['status'];
                        $is_payment_transaction = true;
                        $vehicle_rental->sudopay_transaction_logs->update($transaction_log);
                    } else if (is_array($refundPayment) && $refundPayment['error']) {
                        if ($refundPayment['error']['message']) {
                            $error_msg = $refundPayment['error']['message'];
                        } else {
                            $error_msg = $refundPayment['error_message'];
                        }

                    }
                }
            }
            if (!is_null($vehicle_rental->wallet_transaction_log)) {
                $gateway_id = config('constants.ConstPaymentGateways.Wallet');
                if ($vehicle_rental->wallet_transaction_log->payment_type == 'Captured') {
                    $walletService = new \App\Services\WalletService();
                    $is_payment_transaction = $walletService->voidPayment($vehicle_rental);
                }
            }
            if ($is_payment_transaction) {
                $vehicle_rental_data['item_user_status_id'] = config('constants.ConstItemUserStatus.Rejected');
                $vehicle_rental_data['status_updated_at'] = Carbon::now()->toDateTimeString();
                $vehicle_rental->update($vehicle_rental_data);
				$this->unavailableVehicleService->clearUnavaialablelist($vehicle_rental->id);
                $this->vehicleRentalService->updateItemUserCount();
                //Save transactions
                $this->transactionService->log(config('constants.ConstUserTypes.Admin'), $vehicle_rental->user_id, config('constants.ConstTransactionTypes.RefundForRejectedRenting'), $vehicle_rental->total_amount, $vehicle_rental->id, 'VehicleRentals', $gateway_id);
                $this->vehicleRentalService->changeStatusMail($vehicle_rental, $vehicle_rental->item_userable, config('constants.ConstItemUserStatus.WaitingForAcceptance'), config('constants.ConstItemUserStatus.Rejected'));
                return response()->json(['Success' => 'Vehicle Rental has been rejected'], 200);
            } else {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle Rental could not be updated. Please, try again.');
            }
         } catch (\Exception $e) {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle Rental could not be updated. Please, try again.', array($e->getMessage()));
        } 
    }

    /**
     * Cancel the specified vehicle_rental.
     * Cancel the vehicle_rental with a `id`.
     * @Put("/vehicle_rentals/{id}/cancel")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"success": "Record has been cancelled."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {}, "status_code": 422})
     * })
     */
    public function cancel($id)
    {
        $enabledIncludes = $this->vehicleRentalService->getEnableGateway();
        $user = $this->auth->user();
        $vehicle_rental = VehicleRental::with($enabledIncludes)->where(['item_user_status_id' => config('constants.ConstItemUserStatus.WaitingForAcceptance'), 'user_id' => $user->id, 'id' => $id])->first();
        if (!$vehicle_rental || !$vehicle_rental->item_userable) {
            return $this->response->errorNotFound("Invalid Request");
        }
        try {
            $error_msg = '';
            $is_payment_transaction = false;
            $transaction_log = array();
            if (isPluginEnabled('Paypal') && !is_null($vehicle_rental->paypal_transaction_log)) {
                $gateway_id = config('constants.ConstPaymentGateways.PayPal');
                if ($vehicle_rental->paypal_transaction_log->payment_type == 'authorized') {
                    $paypal = new \Plugins\Paypal\Services\PayPalService();
                    $voidPayment = $paypal->voidPayment($vehicle_rental->paypal_transaction_log->authorization_id);
                    if (is_object($voidPayment)) {
                        $transaction_log['payment_type'] = $voidPayment->getState();
                        $transaction_log['void_id'] = $voidPayment->getId();
                        if ($transaction_log['payment_type'] == 'voided') {
                            $is_payment_transaction = true;
                        }
                    } else if (is_array($voidPayment) && $voidPayment['error']) {
                        if ($voidPayment['error']['message']) {
                            $error_msg = $voidPayment['error']['message'];
                        } else {
                            $error_msg = $voidPayment['error_message'];
                        }
                    }
                    $vehicle_rental->paypal_transaction_log->update($transaction_log);
                }
            }
            if (isPluginEnabled('Sudopays') && !is_null($vehicle_rental->sudopay_transaction_logs)) {
                $gateway_id = config('constants.ConstPaymentGateways.SudoPay');
                $sudopay = new \Plugins\Sudopays\Services\SudopayService();
                if ($vehicle_rental->sudopay_transaction_logs->status == 'Authorized') {
                    $voidPayment = $sudopay->voidPayment($vehicle_rental->sudopay_transaction_logs);
                    if (!empty($voidPayment) && ($voidPayment['status'] == 'Voided' || $voidPayment['status'] == 'Canceled')) {
                        $transaction_log['status'] = $voidPayment['status'];
                        $is_payment_transaction = true;
                        $vehicle_rental->sudopay_transaction_logs->update($transaction_log);
                    } else if (is_array($voidPayment) && $voidPayment['error']) {
                        if ($voidPayment['error']['message']) {
                            $error_msg = $voidPayment['error']['message'];
                        } else if ($voidPayment['error_message']) {
                            $error_msg = $voidPayment['error_message'];
                        }
                    }
                } elseif ($vehicle_rental->sudopay_transaction_logs->status == 'Captured') {
                    $refundPayment = $sudopay->refundPayment($vehicle_rental->sudopay_transaction_logs);
                    if (!empty($refundPayment) && $refundPayment['status'] == 'Refunded') {
                        $transaction_log['status'] = $refundPayment['status'];
                        $is_payment_transaction = true;
                        $vehicle_rental->sudopay_transaction_logs->update($transaction_log);
                    } else if (is_array($refundPayment) && $refundPayment['error']) {
                        if ($refundPayment['error']['message']) {
                            $error_msg = $refundPayment['error']['message'];
                        } else {
                            $error_msg = $refundPayment['error_message'];
                        }
                    }
                }
            }
            if (!is_null($vehicle_rental->wallet_transaction_log)) {
                $gateway_id = config('constants.ConstPaymentGateways.Wallet');
                if ($vehicle_rental->wallet_transaction_log->payment_type == 'Captured') {
                    $walletService = new \App\Services\WalletService();
                    $is_payment_transaction = $walletService->voidPayment($vehicle_rental);
                }
            }
            if ($is_payment_transaction) {
                $vehicle_rental_data['item_user_status_id'] = config('constants.ConstItemUserStatus.Cancelled');
                $vehicle_rental_data['status_updated_at'] = Carbon::now()->toDateTimeString();
                $vehicle_rental->update($vehicle_rental_data);
                $this->unavailableVehicleService->clearUnavaialablelist($vehicle_rental->id);
                $this->vehicleRentalService->updateItemUserCount();
                $this->transactionService->log(config('constants.ConstUserTypes.Admin'), $vehicle_rental->user_id, config('constants.ConstTransactionTypes.RefundForCanceledRenting'), $vehicle_rental->total_amount, $vehicle_rental->id, 'VehicleRentals', $gateway_id);
                $this->vehicleRentalService->changeStatusMail($vehicle_rental, $vehicle_rental->item_userable, config('constants.ConstItemUserStatus.WaitingForAcceptance'), config('constants.ConstItemUserStatus.Cancelled'));
                return response()->json(['Success' => 'Vehicle Rental has been cancelled'], 200);
            } else {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle Rental could not be updated. Please, try again.', $error_msg);
            }
        } catch (\Exception $e) {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle Rental could not be updated. Please, try again.', array($e->getMessage()));
        }
    }


    /**
     * Confirm the specified vehicle_rental.
     * Confirm the vehicle_rental with a `id`.
     * @Put("/vehicle_rentals/{id}/confirm")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"success": "Record has been Confirmed."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {}, "status_code": 422})
     * })
     */
    public function confirmed($id)
    {
        $enabledIncludes = $this->vehicleRentalService->getEnableGateway();
        $vehicle_rental = VehicleRental::with($enabledIncludes)->where(['item_user_status_id' => config('constants.ConstItemUserStatus.WaitingForAcceptance'), 'id' => $id])->first();
        if (!$vehicle_rental || is_null($vehicle_rental->item_userable)) {
            return $this->response->errorNotFound("Invalid Request");
        }
        $user = $this->auth->user();
        if ($vehicle_rental->item_userable->user_id != $user->id) {
            return $this->response->errorNotFound("Invalid Request");
        }
        try {
            $error_msg = '';
            $is_payment_transaction = false;
            $vehicle_rental_data = array();
            if (isPluginEnabled('Paypal') && !is_null($vehicle_rental->paypal_transaction_log)) {
                if ($vehicle_rental->paypal_transaction_log->payment_type == 'authorized') {
                    $paypal = new \Plugins\Paypal\Services\PayPalService();
                    $authorization = $paypal->authorizePayment($vehicle_rental->paypal_transaction_log->authorization_id);
                    if (is_object($authorization)) {
                        $capturePayment = $paypal->capturePayment($authorization, $vehicle_rental->paypal_transaction_log);
                        if (is_object($capturePayment)) {
                            $transaction_log['payment_type'] = $capturePayment->getState();
                            $transaction_log['status'] = 'captured';
                            $transaction_log['capture_id'] = $capturePayment->getId();
                            $is_payment_transaction = true;
                            $vehicle_rental->paypal_transaction_log->update($transaction_log);
                        } elseif (is_array($capturePayment) && $capturePayment['error']) {
                            if ($capturePayment['error']['message']) {
                                $error_msg = $capturePayment['error']['message'];
                            } else {
                                $error_msg = $capturePayment['error_message'];
                            }
                        }
                    } else if (is_array($authorization) && $authorization['error']) {
                        if ($authorization['error']['message']) {
                            $error_msg = $authorization['error']['message'];
                        } else {
                            $error_msg = $authorization['error_message'];
                        }
                    }
                }
            }
            if (isPluginEnabled('Sudopays') && !is_null($vehicle_rental->sudopay_transaction_logs)) {
                if ($vehicle_rental->sudopay_transaction_logs->status == 'Authorized') {
                    $sudopay = new \Plugins\Sudopays\Services\SudopayService();
                    $capturePayment = $sudopay->capturePayment($vehicle_rental->sudopay_transaction_logs);
                    if ($capturePayment['status'] == 'Captured') {
                        $transaction_log['status'] = $capturePayment['status'];
                        $vehicle_rental->sudopay_transaction_logs->update($transaction_log);
                        $is_payment_transaction = true;
                        $vehicle_rental->sudopay_transaction_logs->update($transaction_log);
                    } elseif (is_array($capturePayment) && $capturePayment['error']) {
                        if ($capturePayment['error']['message']) {
                            $error_msg = $capturePayment['error']['message'];
                        } else {
                            $error_msg = $capturePayment['error_message'];
                        }
                    }
                }
            }
            if (!is_null($vehicle_rental->wallet_transaction_log)) {
                if ($vehicle_rental->wallet_transaction_log->payment_type == 'Captured') {
                    $is_payment_transaction = true;
                }
            }
            if ($is_payment_transaction) {
                $vehicle_rental_data['item_user_status_id'] = config('constants.ConstItemUserStatus.Confirmed');
                $vehicle_rental_data['status_updated_at'] = Carbon::now()->toDateTimeString();
                $vehicle_rental->update($vehicle_rental_data);
                $this->vehicleRentalService->updateItemUserCount();
                $this->vehicleRentalService->sendConfirmationMail($vehicle_rental, $vehicle_rental->item_userable);
                $this->vehicleRentalService->changeStatusMail($vehicle_rental, $vehicle_rental->item_userable, config('constants.ConstItemUserStatus.WaitingForAcceptance'), config('constants.ConstItemUserStatus.Confirmed'));
                return response()->json(['Success' => 'Vehicle Rental has been Confirmed'], 200);
            } else {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle Rental could not be updated. Please, try again.', $error_msg);
            }
        } catch (\Exception $e) {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle Rental could not be updated. Please, try again.', array($e->getMessage()));
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function payNow(Request $request, $vehicle_rental_id)
    {
        $user = $this->auth->user();
        $request_amount = $request->only('amount');
        if ($request->has('gateway_id') && ((isPluginEnabled('Paypal') && $request->gateway_id == config('constants.ConstPaymentGateways.PayPal')) || (isPluginEnabled('Sudopays') && $request->gateway_id == config('constants.ConstPaymentGateways.SudoPay')) || ($request->gateway_id == config('constants.ConstPaymentGateways.Wallet')))) {
            $data['amount'] = $request->amount;
            if ($request->gateway_id == config('constants.ConstPaymentGateways.PayPal')) {
                $paypalLogService = new \Plugins\Paypal\Services\PaypalTransactionLogService();
                $paypalService = new \Plugins\Paypal\Services\PayPalService();
                $paypal_vehicle_rental = VehicleRental::with('paypal_transaction_log')->where('id', $request->vehicle_rental_id)->first();
                if (!$paypal_vehicle_rental || is_null($paypal_vehicle_rental->item_userable)) {
                    return $this->response->errorNotFound("Invalid Request");
                }
                // Already user have try and not paid case
                if (!is_null($paypal_vehicle_rental->paypal_transaction_log)) {
                    $paypalLogService->updateLogById($data, $paypal_vehicle_rental->paypal_transaction_log->id);
                    $paypal_transaction = $paypal_vehicle_rental->paypal_transaction_log;
                } else {
                    $paypal_transaction = $paypalLogService->log($data);
                    $paypal_vehicle_rental = VehicleRental::with('paypal_transaction_log')->where('id', $request->vehicle_rental_id)->first();
                    $paypal_vehicle_rental->paypal_transaction_log()->save($paypal_transaction);
                }
                if (config('vehicle_rental.is_auto_approve')) {
                    $response = $paypalService->createPayment($paypal_transaction->id, config('constants.ConstPaypalGatewaysProcess.sale'));
                } else {
                    $response = $paypalService->createPayment($paypal_transaction->id, config('constants.ConstPaypalGatewaysProcess.authorize'));
                }
                return response()->json(['url' => $response], 200);
            } elseif ($request->gateway_id == config('constants.ConstPaymentGateways.SudoPay')) {
                $sudopay_data = array('address', 'city', 'country', 'email', 'gateway_id', 'payment_id', 'phone', 'state', 'zip_code');
                if ($request->has('credit_card_code')) {
                    $sudopay_data = array_merge($sudopay_data, array('credit_card_code', 'credit_card_expire', 'credit_card_name_on_card', 'credit_card_number'));
                }
                if ($request->has('payment_note')) {
                    $sudopay_data = array_merge($sudopay_data, array('payment_note'));
                }
                $sudopay_data = $request->only($sudopay_data);
                $log_data['amount'] = $request_amount['amount'];
                $log_data['gateway_id'] = $request['payment_id'];
                $this->logtService = new \Plugins\Sudopays\Services\SudopayTransactionLogService();
                $this->paymentService = new \Plugins\Sudopays\Services\SudopayService();
                $cur_vehicle_rental = VehicleRental::with(['sudopay_transaction_logs'])->where('id', '=', $request->vehicle_rental_id)->first();
                if (is_null(!$cur_vehicle_rental || $cur_vehicle_rental->item_userable)) {
                    return $this->response->errorNotFound("Invalid Request");
                }
                // Already user have try and not paid case
                if (!is_null($cur_vehicle_rental->sudopay_transaction_logs)) {
                    $this->logtService->updateLogById($log_data, $cur_vehicle_rental->sudopay_transaction_logs->id);
                    $cur_transaction = $cur_vehicle_rental->sudopay_transaction_logs;
                } else {
                    $cur_transaction = $this->logtService->log($log_data);
                    $cur_vehicle_rental = VehicleRental::with('sudopay_transaction_logs')->where('id', $request->vehicle_rental_id)->first();
                    $cur_vehicle_rental->sudopay_transaction_logs()->save($cur_transaction);
                }
                $response = $this->paymentService->createPayment($cur_transaction->id, $sudopay_data);
                if (!empty($response['gateway_callback_url'])) {
                    return response()->json(['url' => $response['gateway_callback_url']], 200);
                }
                if (!empty($response['pending'])) {
                    return response()->json(['Success' => 'Once payment is received, it will be processed'], 200);
                } elseif (!empty($response['success'])) {
                    return response()->json(['Success' => 'Vehicle booked successfully'], 200);
                } elseif (!empty($response['error'])) {
                    return response()->json(['Success' => 'Your payment could not be completed'], 200);
                }
            } elseif ($request->gateway_id == config('constants.ConstPaymentGateways.Wallet')) {
                $walletLogService = new \App\Services\WalletTransactionLogService();
                $walletService = new \App\Services\WalletService();
                $wallet_vehicle_rental = VehicleRental::with('wallet_transaction_log')->where('id', $request->vehicle_rental_id)->first();
                if (!$wallet_vehicle_rental || is_null($wallet_vehicle_rental->item_userable)) {
                    return $this->response->errorNotFound("Invalid Request");
                }
                $data['amount'] = $request->amount;
                // Already user have try and not paid case
                if (is_null($wallet_vehicle_rental->wallet_transaction_log)) {
                    $wallet_log = $walletLogService->log($data);
                    $wallet_vehicle_rental = VehicleRental::with('wallet_transaction_log')->where('id', $request->vehicle_rental_id)->first();
                    $wallet_vehicle_rental->wallet_transaction_log()->save($wallet_log);
                } else {
                    $walletLogService->updateLogById($data, $wallet_vehicle_rental->wallet_transaction_log->id);
                    $wallet_log = $wallet_vehicle_rental->wallet_transaction_log;
                }
                $response = $walletService->createPayment($wallet_log->id);
                if ($response) {
                    $exe_response = $walletService->executePayment($wallet_vehicle_rental, 'MorphVehicleRental', $wallet_log->id);
                    return response()->json(['data' => 'wallet'], 200);
                }
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Payment gateway could not be set. Please, try again.');
        }
    }

    /**
     * Checkin the specified vehicle_rental.
     * Checkin the vehicle_rental with a `id`.
     * @get("/vehicle_rentals/{id}/checkin")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"success": "Record has been checkin."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {}, "status_code": 422})
     * })
     */
    public function checkin($id)
    {
        $enabledIncludes = array('item_user_status');
        $user = $this->auth->user();
        $vehicle_rental = VehicleRental::with($enabledIncludes)->filterByStatus($id, config('constants.ConstItemUserStatus.Confirmed'))->first();
        if (!$vehicle_rental || is_null($vehicle_rental->item_userable)) {
            return $this->response->errorNotFound("Invalid Request");
        }
        if ($user->role_id == config('constants.ConstUserTypes.Admin') || ($user->id == $vehicle_rental->item_userable->user_id && config('vehicle_rental.is_host_checkin_and_checkout') == 1)) {
            return $this->vehicleRentalService->saveCheckInDetail($vehicle_rental);
        } else {
            return $this->response->errorNotFound("Invalid Request");
        }
    }

    /**
     * Checkout the specified vehicle_rental.
     * Checkout the vehicle_rental with a `id`.
     * @get("/vehicle_rentals/{id}/checkout")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"success": "Record has been checkout."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {}, "status_code": 422})
     * })
     */
    public function checkout(Request $request, $id)
    {
        $enabledIncludes = array('item_user_status', 'late_payment_detail', 'unavailable_vehicle');
        $user = $this->auth->user();
        $vehicle_rental = VehicleRental::with($enabledIncludes)->filterByStatus($id, config('constants.ConstItemUserStatus.Attended'))->first();
        if (!$vehicle_rental || is_null($vehicle_rental->item_userable)) {
            return $this->response->errorNotFound("Invalid Request");
        }
        if ($user->role_id == config('constants.ConstUserTypes.Admin') || ($user->id == $vehicle_rental->item_userable->user_id && config('vehicle_rental.is_host_checkin_and_checkout') == 1)) {
            $claim_request_amount = 0;
            if ($request->has('claim_request_amount')) {
                $claim_request_amount = $request->claim_request_amount;
            }
            return $this->vehicleRentalService->saveCheckoutDetail($vehicle_rental, $claim_request_amount);
        }
        return $this->response->errorNotFound("Invalid Request");
    }
}
