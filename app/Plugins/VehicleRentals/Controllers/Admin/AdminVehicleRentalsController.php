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
 
namespace Plugins\VehicleRentals\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use JWTAuth;
use Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Plugins\VehicleRentals\Transformers\VehicleRentalTransformer;
use Plugins\VehicleRentals\Model\VehicleRental;
use Plugins\VehicleRentals\Model\VehicleRentalStatus;
use Plugins\VehicleRentals\Services\VehicleRentalService;
use Plugins\Vehicles\Services\UnavailableVehicleService;
use App\Services\TransactionService;
use App\User;
use Carbon;
use DB;

/**
 * VehicleRentals resource representation.
 * @Resource("Admin/AdminVehicleRentals")
 */
class AdminVehicleRentalsController extends Controller
{
    /**
     * @var
     */
    protected $vehicleRentalService;

    /**
     * @var TransactionService
     */
    protected $transactionService;
	
	/**
     * @var unavailableVehicleService
     */
    protected $unavailableVehicleService;

    /**
     * AdminVehicleRentalsController constructor.
     */
    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
        // Check the logged user role.
        $this->middleware('role');
        $this->setVehicleRentalService();
        $this->setTransactionService();
        $this->setUnavailableVehicleService();
    }

    public function setVehicleRentalService()
    {
        $this->vehicleRentalService = new VehicleRentalService();
    }

    public function setTransactionService()
    {
        $this->transactionService = new TransactionService();
    }
	
	public function setUnavailableVehicleService()
    {
        $this->unavailableVehicleService = new UnavailableVehicleService();
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
        $enabledIncludes = array('user', 'item_user_status');
        // check if plugin enabled and include
        (isPluginEnabled('VehicleCoupons')) ? $enabledIncludes[] = 'vehicle_coupon' : '';
        $vehicle_rentals = VehicleRental::with($enabledIncludes)
            ->select(DB::raw('item_users.*'))
            ->leftJoin(DB::raw('(select id,username from users) as user'), 'user.id', '=', 'item_users.user_id')
            ->leftJoin(DB::raw('(select id,name from vehicles) as item_userable'), 'item_userable.id', '=', 'item_users.item_userable_id')
            ->leftJoin(DB::raw('(select id,name from item_user_statuses) as item_user_status'), 'item_user_status.id', '=', 'item_users.item_user_status_id')
            ->filterByBooking($request)
            ->filterByVehicleRental(false)
            ->filterByRequest($request)->paginate(config('constants.ConstPageLimit'));
        $enabledIncludes = array_merge($enabledIncludes, array('item_userable'));
        return $this->response->paginator($vehicle_rentals, (new VehicleRentalTransformer)->setDefaultIncludes($enabledIncludes));
    }

    /**
     * Show the vehicle_rental.
     * Show the vehicle_rental with a `id`.
     * @Get("/vehicle_rentals/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"id": 1, "user_id": 1, "item_user_status_id": 1, "coupon_id": 1, "quantity": 1, "total_amount": 100.00, "user": {}, "coupon": {}}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function show($id)
    {
        $enabledIncludes = array('user', 'item_user_status', 'pickup_counter_location', 'drop_counter_location', 'vehicle_rental_additional_chargable');
        // check if plugin enabled and include
        (isPluginEnabled('VehicleCoupons')) ? $enabledIncludes[] = 'vehicle_coupon' : '';
        $vehicle_rental = VehicleRental::with($enabledIncludes)->find($id);
        if (!$vehicle_rental) {
            return $this->response->errorNotFound("Invalid Request");
        }
        $enabledIncludes = array_merge($enabledIncludes, array('item_userable'));
        return $this->response->item($vehicle_rental, (new VehicleRentalTransformer)->setDefaultIncludes($enabledIncludes));
    }

    /**
     * Cancel the specified vehicle_rental.
     * Cancel the vehicle_rental with a `id`.
     * @Put("/vehicle_rentals/{id}/cancelled-by-admin")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"success": "VehicleRental has been cancelled"}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function CancelledByAdmin($id)
    {
        $enabledIncludes = $this->vehicleRentalService->getEnableGateway();
        $vehicle_rental = VehicleRental::with($enabledIncludes)->where('id', '=', $id)->first();
        if (!$vehicle_rental || is_null($vehicle_rental->item_userable)) {
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
                        $error_msg = $voidPayment['error_message'];
                    }

                    $vehicle_rental->paypal_transaction_log->update($transaction_log);
                }
                if ($vehicle_rental->paypal_transaction_log->payment_type == 'completed') {
                    $paypal = new \Plugins\Paypal\Services\PayPalService();
                    $refundPayment = $paypal->refundPayment($vehicle_rental->paypal_transaction_log);
                    if (is_object($refundPayment)) {
                        $transaction_log['status'] = 'refunded';
                        $transaction_log['payment_type'] = $refundPayment->getState();
                        $transaction_log['refund_id'] = $refundPayment->getId();
                        if ($transaction_log['payment_type'] == 'completed') {
                            $is_payment_transaction = true;
                        }
                    } else if (is_array($refundPayment) && $refundPayment['error']) {
                        $error_msg = $refundPayment['error_message'];
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
                        $error_msg = $voidPayment['error_message'];
                    }
                } elseif ($vehicle_rental->sudopay_transaction_logs->status == 'Captured') {
                    $refundPayment = $sudopay->refundPayment($vehicle_rental->sudopay_transaction_logs);
                    if (!empty($refundPayment) && $refundPayment['status'] == 'Refunded') {
                        $transaction_log['status'] = $refundPayment['status'];
                        $is_payment_transaction = true;
                        $vehicle_rental->sudopay_transaction_logs->update($transaction_log);
                    } else if (is_array($refundPayment) && $refundPayment['error']) {
                        $error_msg = $refundPayment['error_message'];
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
                $vehicle_rental_data['item_user_status_id'] = config('constants.ConstItemUserStatus.CancelledByAdmin');
                $vehicle_rental_data['status_updated_at'] = Carbon::now()->toDateTimeString();
                $vehicle_rental->update($vehicle_rental_data);
				$this->unavailableVehicleService->clearUnavaialablelist($vehicle_rental->id);
                $this->vehicleRentalService->updateItemUserCount();
                //Save transactions
                $this->transactionService->log(config('constants.ConstUserTypes.Admin'), $vehicle_rental->user_id, config('constants.ConstTransactionTypes.RefundForRentingCanceledByAdmin'), $vehicle_rental->total_amount, $vehicle_rental->id, 'VehicleRentals', $gateway_id);
                $this->vehicleRentalService->changeStatusMail($vehicle_rental, $vehicle_rental->item_userable, $vehicle_rental->item_user_status_id, config('constants.ConstItemUserStatus.CancelledByAdmin'));
                return response()->json(['Success' => 'VehicleRental has been cancelled'], 200);
            } else {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleRental could not be updated. Please, try again.', array($error_msg));
            }
        } catch (\Exception $e) {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleRental could not be updated. Please, try again.', array($e->getMessage()));
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
        $vehicle_rental = VehicleRental::with($enabledIncludes)->filterByStatus($id, config('constants.ConstItemUserStatus.Confirmed'))->first();
        if (!$vehicle_rental || is_null($vehicle_rental->item_userable)) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->vehicleRentalService->saveCheckInDetail($vehicle_rental);
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
        $enabledIncludes = array('item_user_status', 'late_payment_detail');
        $vehicle_rental = VehicleRental::with($enabledIncludes)->filterByStatus($id, config('constants.ConstItemUserStatus.Attended'))->first();
        if (!$vehicle_rental || is_null($vehicle_rental->item_userable)) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->vehicleRentalService->saveCheckoutDetail($vehicle_rental, $request->claim_request_amount);
    }
}

