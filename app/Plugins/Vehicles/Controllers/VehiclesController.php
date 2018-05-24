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
 
namespace Plugins\Vehicles\Controllers;


use Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Plugins\Vehicles\Model\Vehicle;
use Plugins\Vehicles\Model\VehicleMake;
use Plugins\Vehicles\Model\VehicleModel;
use JWTAuth;
use Validator;
use Plugins\Vehicles\Transformers\VehicleTransformer;
use Plugins\Vehicles\Services\VehicleService;
use Plugins\Vehicles\Services\VehicleCompanyService;
use Plugins\Vehicles\Services\VehicleMakeService;
use Plugins\Vehicles\Services\VehicleModelService;
use Plugins\Vehicles\Services\VehicleTypeService;
use Plugins\Vehicles\Services\CounterLocationService;
use Plugins\Vehicles\Services\FuelTypeService;
use EasySlug\EasySlug\EasySlugFacade as EasySlug;
use File;
use Image;
use App\Attachment;
use App\User;

/**
 * Class VehiclesController
 * @package Plugins\Vehicles\Controllers
 */
class VehiclesController extends Controller
{
    /**
     * @var
     */
    protected $vehicleService;

    /**
     * @var $vehicleCompanyService
     */
    protected $vehicleCompanyService;
    /**
     * @var $vehicleMakeService
     */
    protected $vehicleMakeService;
    /**
     * @var $vehicleModelService
     */
    protected $vehicleModelService;
    /**
     * @var $vehicleTypeService
     */
    protected $vehicleTypeService;
    /**
     * @var $counterLocationService
     */
    protected $counterLocationService;

    /**
     * @var $fuelTypeService
     */
    protected $fuelTypeService;

    /**
     * VehiclesController constructor.
     */
    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth', ['except' => ['search', 'getVehicleRelatedFilters', 'show']]);
        $this->setVehicleService();
        $this->setVehicleCompanyService();
        $this->setVehicleMakeService();
        $this->setVehicleModelService();
        $this->setVehicleTypeService();
        $this->setCounterLocationService();
        $this->setFuelTypeService();
    }

    /**
     * setVehicleService
     */
    public function setVehicleService()
    {
        $this->vehicleService = new VehicleService();
    }

    public function setVehicleCompanyService()
    {
        $this->vehicleCompanyService = new VehicleCompanyService();
    }

    public function setVehicleMakeService()
    {
        $this->vehicleMakeService = new VehicleMakeService();
    }

    public function setVehicleModelService()
    {
        $this->vehicleModelService = new VehicleModelService();
    }

    public function setVehicleTypeService()
    {
        $this->vehicleTypeService = new VehicleTypeService();
    }

    public function setCounterLocationService()
    {
        $this->counterLocationService = new CounterLocationService();
    }

    public function setFuelTypeService()
    {
        $this->fuelTypeService = new FuelTypeService();
    }

    /**
     * Show all vehicles.
     * Get a JSON representation of all the vehicles.
     *
     * @Get("/vehicles?filter={filter}&sort={sort}&sortby={sortby}&q={q}")
     * @Parameters({
     *      @Parameter("filter", type="integer", required=false, description="Filter the vehicles list by status.", default=null),
     *      @Parameter("sort", type="string", required=false, description="Sort the vehicles list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort vehicles by Ascending / Descending Order.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1)
     * })
     */
    public function search(Request $request)
    {
        if($request->has('start_date') && $request->has('end_date')) {
            $current_date = strtotime(Carbon::now()->toDateTimeString());
            $request->start_date = date("Y-m-d H:i:s", strtotime($request->start_date));
            $request->end_date = date("Y-m-d H:i:s", strtotime($request->end_date));
            $start_date = strtotime($request->start_date);
            $end_date = strtotime($request->end_date);
            if ($start_date <= $current_date) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('Start Date should be greater than current date');
            }
            if ($end_date < $start_date) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('End Date should be greater than start date');
            }
        }
        $enabled_includes = array('vehicle_type', 'fuel_type', 'counter_location', 'vehicle_company', 'vehicle_make', 'vehicle_model', 'attachments', 'user');
        $vehicles = Vehicle::with($enabled_includes)->filterByRequest($request)->filterActiveVehicle($request)->paginate(config('constants.ConstPageLimit'));
        if($request->has('start_date') && $request->has('end_date')) {
            $vehicles = $this->vehicleService->getDiscountRate($vehicles, $request->start_date, $request->end_date);
            $request_data = $request->only('start_date', 'end_date', 'pickup_location_id', 'drop_location_id', 'price_min', 'price_max', 'sort', 'sortby', 'mileage_min', 'mileage_max', 'seat_min', 'seat_max', 'vehicle_type', 'is_manual_transmission', 'fuel_type', 'ac', 'non_ac', 'auto_transmission', 'airbag', 'non_airbag');
            $res = $this->response->paginator($vehicles, (new VehicleTransformer)->setDefaultIncludes($enabled_includes));
            $res->addMeta('request', $request_data);
            $res->addMeta('booking_details', $vehicles->booking_details);
        } else {
            $request_data = $request->only('price_min', 'price_max', 'sort', 'sortby', 'mileage_min', 'mileage_max', 'seat_min', 'seat_max', 'vehicle_type', 'is_manual_transmission', 'fuel_type', 'ac', 'non_ac', 'auto_transmission', 'airbag', 'non_airbag');
            $res = $this->response->paginator($vehicles, (new VehicleTransformer)->setDefaultIncludes($enabled_includes));
            $res->addMeta('request', $request_data);
        }
        return $res;
    }

    /**
     * Show own all vehicles.
     * Get a JSON representation of own all the vehicles.
     *
     * @Get("/vehicles/me?filter={filter}&sort={sort}&sortby={sortby}&q={q}")
     * @Parameters({
     *      @Parameter("filter", type="integer", required=false, description="Filter the vehicles list by status.", default=null),
     *      @Parameter("sort", type="string", required=false, description="Sort the vehicles list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort vehicles by Ascending / Descending Order.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1)
     * })
     */
    public function index(Request $request)
    {
        $user = $this->auth->user();
        $enabled_includes = array('vehicle_type', 'fuel_type', 'counter_location', 'vehicle_company', 'vehicle_make', 'vehicle_model', 'attachments');
        $vehicles = Vehicle::with($enabled_includes)->filterByRequest($request)->filterByMyVehicle($request, $user->id)->paginate(config('constants.ConstPageLimit'));
        return $this->response->paginator($vehicles, (new VehicleTransformer)->setDefaultIncludes($enabled_includes));
    }

    /**
     * Store a new vehicle.
     * Store a new vehicle with a 'amount', 'user_id', 'name', 'booking_type_id', 'description'.
     * @Post("/vehicles")
     * @Transaction({
     *      @Request({"amount": 1000, "user_id": 1, "name": "house for rent", "booking_type_id": 1, "description": "This house is for rent"}),
     *      @Response(200, body={"success": "Record has been added."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {"amount": {}}, "status_code": 422})
     * })
     */
    public function store(Request $request)
    {
        $vehicle_data = $request->only('vehicle_make_id', 'vehicle_model_id', 'vehicle_type_id', 'pickup_counter_locations', 'drop_counter_locations', 'driven_kilometer', 'vehicle_no', 'no_of_seats', 'no_of_doors', 'no_of_gears', 'is_manual_transmission', 'no_small_bags', 'no_large_bags', 'is_ac', 'minimum_age_of_driver', 'mileage', 'is_km', 'is_airbag', 'no_of_airbags', 'is_abs', 'per_hour_amount', 'per_day_amount', 'fuel_type_id', 'feedback_count');
        $user = $this->auth->user();
        $user = User::with('vehicle_company')->where('id', $user->id)->first();
        if (!$user || !$user->vehicle_company) {
            return $this->response->errorNotFound("Invalid Request");
        }
        $vehicle_data['is_active'] = (config('vehicle.auto_approve')) ? true : false;
        $vehicle_data['is_paid'] = (config('vehicle.listing_fee') == 0) ? true : false;
        $vehicle_data['user_id'] = $user->id;
        $vehicle_data['vehicle_company_id'] = $user->vehicle_company->id;
        $vehicle_make = VehicleMake::where('id', $request->vehicle_make_id)->first();
        $vehicle_model = VehicleModel::where('id', $request->vehicle_model_id)->first();
        $vehicle_data['name'] = $vehicle_make->name . ':' . $vehicle_model->name;
        $vehicle_data['slug'] = EasySlug::generateUniqueSlug($vehicle_data['name'], 'vehicles');
        $validator = Validator::make($vehicle_data, Vehicle::GetValidationRule(), Vehicle::GetValidationMessage());
        if ($validator->passes() && ($request->hasFile('file') && $request->file('file')->isValid())) {
            try {
                $vehicle = Vehicle::create($vehicle_data);
                if (!empty($vehicle->id)) {
                    $vehicle_data['name'] = $vehicle_make->name . ':' . $vehicle_model->name.' #'.$vehicle->id;
                    $vehicle_data['slug'] = EasySlug::generateUniqueSlug($vehicle_data['name'], 'vehicles');
                    $vehicle->update($vehicle_data);
                    // afterSave count updatae & dummy record put
                    $this->vehicleService->afterSave($vehicle);
                    if (!empty($request->pickup_counter_locations) || !empty($request->drop_counter_locations)) {
                        $common_loctions = array_intersect($request->pickup_counter_locations, $request->drop_counter_locations);
                        $diff_in_pickup = array_diff($request->pickup_counter_locations, $request->drop_counter_locations);
                        $diff_in_drop = array_diff($request->drop_counter_locations, $request->pickup_counter_locations);
                        if ($common_loctions) {
                            foreach ($common_loctions as $key => $value) {
                                $vehicle->counter_location()->attach([$value => ['is_pickup' => 1, 'is_drop' => 1]]);
                            }
                        }
                        if ($diff_in_pickup) {
                            foreach ($diff_in_pickup as $key => $value) {
                                $vehicle->counter_location()->attach([$value => ['is_pickup' => 1, 'is_drop' => 0]]);
                            }
                        }
                        if ($diff_in_drop) {
                            foreach ($diff_in_drop as $key => $value) {
                                $vehicle->counter_location()->attach([$value => ['is_pickup' => 0, 'is_drop' => 1]]);
                            }
                        }
                    }
                    if ($request->hasFile('file')) {
                        $path = storage_path('app/Vehicle/' . $vehicle->id . '/');
                        if (!File::isDirectory($path)) {
                            File::makeDirectory($path, 0777, true);
                        }
                        $img = Image::make($_FILES['file']['tmp_name']);
                        $path = storage_path('app/Vehicle/' . $vehicle->id . '/' . $_FILES['file']['name']);
                        if ($img->save($path)) {
                            $attachment = array();
                            $attachment['filename'] = $_FILES['file']['name'];
                            $attachment['dir'] = 'app/Vehicle/' . $vehicle->id . '/';
                            $attachment['mimetype'] = $request->file('file')->getClientMimeType();
                            $attachment['filesize'] = $request->file('file')->getClientSize();
                            $att = Attachment::create($attachment);
                            $curuser = Vehicle::with(['attachments'])->where('id', '=', $vehicle->id)->first();
                            $curuser->attachments()->save($att);
                        }
                    }
                }
                if ($vehicle) {
                    return response()->json(['Success' => 'Vehicle has been added', 'id' => $vehicle->id], 200);
                } else {
                    throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle could not be added. Please, try again.');
                }
            } catch (\Exception $e) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle could not be added. Please, try again.',
                    array($e->getMessage()));
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle could not be added. Please, try again.', $validator->errors());
        }
    }

    /**
     * Edit the specified vehicle.
     * Edit the vehicle with a `id`.
     * @Get("/vehicles/{id}/edit")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"id": 1}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function edit($id)
    {
        $enabled_includes = array('counter_location', 'vehicle_make', 'vehicle_model', 'vehicle_type', 'vehicle_company', 'fuel_type', 'attachments');
        $vehicle = Vehicle::with($enabled_includes)->find($id);
        if (!$vehicle) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($vehicle, (new VehicleTransformer)->setDefaultIncludes($enabled_includes));
    }

    /**
     * Update the specified vehicle.
     * Update the vehicle with a `id`.
     * @Put("/vehicles/{id}")
     * @Transaction({
     *      @Response(200, body={"success": "Record has been updated."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {}, "status_code": 422})
     * })
     */
    public function update(Request $request, $id)
    {
        $user = $this->auth->user();
        $vehicle_data = $request->only('id', 'vehicle_make_id', 'vehicle_model_id', 'vehicle_type_id', 'driven_kilometer', 'vehicle_no', 'no_of_seats', 'no_of_doors', 'no_of_gears', 'is_manual_transmission', 'no_small_bags', 'no_large_bags', 'is_ac', 'minimum_age_of_driver', 'mileage', 'is_km', 'is_airbag', 'no_of_airbags', 'is_abs', 'per_hour_amount', 'per_day_amount', 'fuel_type_id', 'feedback_count');
        $vehicle = false;
        $valid_user = false;
        if ($request->has('id')) {
            $vehicle = Vehicle::find($id);
            $vehicle = ($request->id != $id) ? false : $vehicle;
            $valid_user = ($vehicle->user_id != $user->id) ? false : true;
        }
        $validator = Validator::make($vehicle_data, Vehicle::GetValidationRule(), Vehicle::GetValidationMessage());
        if ($validator->passes() && $vehicle && $valid_user && (($request->hasFile('file') && $request->file('file')->isValid()) || (!$request->hasFile('file')))) {
            try {
                $user = User::with('vehicle_company')->where('id', $user->id)->first();
                $vehicle_data['user_id'] = $user->id;
                $vehicle_data['vehicle_company_id'] = $user->vehicle_company->id;
                $vehicle_make = VehicleMake::where('id', $request->vehicle_make_id)->first();
                $vehicle_model = VehicleModel::where('id', $request->vehicle_model_id)->first();
                $vehicle_data['name'] = $vehicle_make->name . ':' . $vehicle_model->name. '#'.$vehicle->id;
                $vehicle_data['slug'] = EasySlug::generateUniqueSlug($vehicle_data['name'], 'vehicles');
                $vehicle->update($vehicle_data);
                // afterSave count updatae & dummy record put
                $this->vehicleService->afterSave($vehicle);
                if (!empty($request->pickup_counter_locations) || !empty($request->drop_counter_locations)) {
                    $common_loctions = array_intersect($request->pickup_counter_locations, $request->drop_counter_locations);
                    $diff_in_pickup = array_diff($request->pickup_counter_locations, $request->drop_counter_locations);
                    $diff_in_drop = array_diff($request->drop_counter_locations, $request->pickup_counter_locations);
                    $vehicle->counter_location()->detach();
                    if ($common_loctions) {
                        foreach ($common_loctions as $key => $value) {
                            $vehicle->counter_location()->attach([$value => ['is_pickup' => 1, 'is_drop' => 1]]);
                        }
                    }
                    if ($diff_in_pickup) {
                        foreach ($diff_in_pickup as $key => $value) {
                            $vehicle->counter_location()->attach([$value => ['is_pickup' => 1, 'is_drop' => 0]]);
                        }
                    }
                    if ($diff_in_drop) {
                        foreach ($diff_in_drop as $key => $value) {
                            $vehicle->counter_location()->attach([$value => ['is_pickup' => 0, 'is_drop' => 1]]);
                        }
                    }
                }
                if ($request->hasFile('file')) {
                    $path = storage_path('app/Vehicle/' . $vehicle->id . '/');
                    if (!File::isDirectory($path)) {
                        File::makeDirectory($path, 0777, true);
                    }
                    $img = Image::make($_FILES['file']['tmp_name']);
                    $path = storage_path('app/Vehicle/' . $vehicle->id . '/' . $_FILES['file']['name']);
                    if ($img->save($path)) {
                        $curVehicle = Vehicle::with(['attachments'])->where('id', '=', $vehicle->id)->first();
                        $attachment = array();
                        $attachment['filename'] = $_FILES['file']['name'];
                        $attachment['dir'] = 'app/Vehicle/' . $vehicle->id . '/';
                        $attachment['mimetype'] = $request->file('file')->getClientMimeType();
                        $attachment['filesize'] = $request->file('file')->getClientSize();
                        if ($curVehicle->attachments) {
                            $curVehicle->attachments()->update($attachment);
                        } else {
                            $att = Attachment::create($attachment);
                            $curVehicle = Vehicle::with(['attachments'])->where('id', '=', $vehicle->id)->first();
                            $curVehicle->attachments()->save($att);
                        }
                    }
                }
                return response()->json(['Success' => 'Vehicle has been updated'], 200);
            } catch (\Exception $e) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle could not be updated. Please, try again.',
                    array($e->getMessage()));
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle could not be updated. Please, try again.', $validator->errors());
        }
    }

    /**
     * Delete the specified vehicle.
     * Delete the vehicle with a `id`.
     * @Delete("/vehicles/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"success": "Record Deleted."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function destroy($id)
    {
        $vehicle = Vehicle::find($id);
        if (!$vehicle) {
            return $this->response->errorNotFound("Invalid Request");
        } else {
            $vehicle->delete();
        }
        return response()->json(['Success' => 'Vehicle deleted'], 200);
    }

    /**
     * Show the specified vehicle.
     * Show the vehicle with a `id`.
     * @Get("/vehicles/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"id": 1}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function show($id)
    {
        $enabled_includes = array('counter_location', 'vehicle_make', 'vehicle_model', 'vehicle_type', 'vehicle_company', 'fuel_type', 'attachments', 'user');
        $vehicle = Vehicle::with($enabled_includes)->find($id);
        if (!$vehicle) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($vehicle, (new VehicleTransformer)->setDefaultIncludes($enabled_includes));
    }

    public function getVehicleRelatedDetail()
    {
        $vehicle_company_list = $this->vehicleCompanyService->getVehicleCompanyList();
        $vehicle_make_list = $this->vehicleMakeService->getVehicleMakeList();
        $vehicle_model_list = $this->vehicleModelService->getVehicleModelList();
        $vehicle_type_list = $this->vehicleTypeService->getVehicleTypeList();
        $counter_location_list = $this->counterLocationService->getCounterLocationList();
        $fuel_type_list = $this->fuelTypeService->getFuelTypeList();
        $settings = array();
        $settings['seats'] = config('vehicle.no_of_seats');
        $settings['doors'] = config('vehicle.no_of_doors');
        $settings['gears'] = config('vehicle.no_of_gears');
        $settings['small_bags'] = config('vehicle.no_small_bags');
        $settings['large_bags'] = config('vehicle.no_large_bags');
        $settings['airbags'] = config('vehicle.no_of_airbags');
        return response()->json(compact('vehicle_company_list', 'vehicle_make_list', 'vehicle_model_list', 'vehicle_type_list', 'counter_location_list', 'fuel_type_list', 'settings'));
    }

    public function getVehicleRelatedFilters()
    {
        $vehicle_company_list = $this->vehicleCompanyService->getVehicleCompanyList();
        $vehicle_type_list = $this->vehicleTypeService->getVehicleTypeList();
        $vehicle_type_price = $this->vehicleTypeService->getVehicleTypePriceFilters();
        $counter_location_list = $this->counterLocationService->getCounterLocationList();
        $fuel_type_list = $this->fuelTypeService->getFuelTypeList();
        $settings = array();
        $settings['seats'] = config('vehicle.no_of_seats');
        $settings['doors'] = config('vehicle.no_of_doors');
        $settings['gears'] = config('vehicle.no_of_gears');
        $settings['small_bags'] = config('vehicle.no_small_bags');
        $settings['large_bags'] = config('vehicle.no_large_bags');
        $settings['airbags'] = config('vehicle.no_of_airbags');
        return response()->json(compact('vehicle_company_list', 'vehicle_type_list', 'counter_location_list', 'fuel_type_list', 'settings', 'vehicle_type_price'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function payNow(Request $request)
    {
        $user = $this->auth->user();
        $listing_fee = config('vehicle.listing_fee');
        if ($request->has('vehicle_id')) {
            $vehicle = Vehicle::where('id', $request->vehicle_id)->first();
            if ($vehicle->is_paid == 1) {
                return response()->json(['Success' => 'Already you have paid listing fee'], 200);
            } else if ($listing_fee == 0) {
                $vehicle->update(['is_paid' => 1]);
                return response()->json(['Success' => 'No vehicle listing fee'], 200);
            }
        } else {
            return $this->response->errorNotFound("Invalid Request");
        }
        if ($request->has('gateway_id') && ((isPluginEnabled('Paypal') && $request->gateway_id == config('constants.ConstPaymentGateways.PayPal')) || (isPluginEnabled('Sudopays') && $request->gateway_id == config('constants.ConstPaymentGateways.SudoPay')) || ($request->gateway_id == config('constants.ConstPaymentGateways.Wallet')))) {
            $data['amount'] = $listing_fee;
            if ($request->gateway_id == config('constants.ConstPaymentGateways.PayPal')) {
                $paypalLogService = new \Plugins\Paypal\Services\PaypalTransactionLogService();
                $paypalService = new \Plugins\Paypal\Services\PayPalService();
                $vehicle = Vehicle::with('paypal_transaction_log')->where('id', $request->vehicle_id)->first();
                if (!$vehicle) {
                    return $this->response->errorNotFound("Invalid Request");
                }
                // Already user have try and not paid case
                if (!is_null($vehicle->paypal_transaction_log)) {
                    $paypalLogService->updateLogById($data, $vehicle->paypal_transaction_log->id);
                    $paypal_transaction = $vehicle->paypal_transaction_log;
                } else {
                    $paypal_transaction = $paypalLogService->log($data);
                    $vehicle = Vehicle::with('paypal_transaction_log')->where('id', $request->vehicle_id)->first();
                    $vehicle->paypal_transaction_log()->save($paypal_transaction);
                }
                $response = $paypalService->createPayment($paypal_transaction->id, config('constants.ConstPaypalGatewaysProcess.sale'));
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
                $log_data['amount'] = $listing_fee;
                $log_data['gateway_id'] = $request['payment_id'];
                $this->logtService = new \Plugins\Sudopays\Services\SudopayTransactionLogService();
                $this->paymentService = new \Plugins\Sudopays\Services\SudopayService();
                $cur_vehicle = Vehicle::with(['sudopay_transaction_logs'])->where('id', '=', $request->vehicle_id)->first();
                if (is_null(!$cur_vehicle)) {
                    return $this->response->errorNotFound("Invalid Request");
                }
                // Already user have try and not paid case
                if (!is_null($cur_vehicle->sudopay_transaction_logs)) {
                    $this->logtService->updateLogById($log_data, $cur_vehicle->sudopay_transaction_logs->id);
                    $cur_transaction = $cur_vehicle->sudopay_transaction_logs;
                } else {
                    $cur_transaction = $this->logtService->log($log_data);
                    $cur_vehicle_rental = Vehicle::with('sudopay_transaction_logs')->where('id', $request->vehicle_id)->first();
                    $cur_vehicle_rental->sudopay_transaction_logs()->save($cur_transaction);
                }
                $response = $this->paymentService->createPayment($cur_transaction->id, $sudopay_data);
                if (!empty($response['gateway_callback_url'])) {
                    return response()->json(['url' => $response['gateway_callback_url']], 200);
                }
                if (!empty($response['pending'])) {
                    return response()->json(['Success' => 'Once payment is received, it will be processed'], 200);
                } elseif (!empty($response['success'])) {
                    return response()->json(['Success' => 'Vehicle listing fee paid successfully'], 200);
                } elseif (!empty($response['error'])) {
                    throw new \Dingo\Api\Exception\StoreResourceFailedException('Payment gateway could not be set. Please, try again.');
                }
            } elseif ($request->gateway_id == config('constants.ConstPaymentGateways.Wallet')) {
                $walletLogService = new \App\Services\WalletTransactionLogService();
                $walletService = new \App\Services\WalletService();
                $wallet_vehicle = Vehicle::with('wallet_transaction_log')->where('id', $request->vehicle_id)->first();
                if (!$wallet_vehicle) {
                    return $this->response->errorNotFound("Invalid Request");
                }
                $data['amount'] = $listing_fee;
                // Already user have try and not paid case
                if (is_null($wallet_vehicle->wallet_transaction_log)) {
                    $wallet_log = $walletLogService->log($data);
                    $wallet_vehicle = Vehicle::with('wallet_transaction_log')->where('id', $request->vehicle_id)->first();
                    $wallet_vehicle->wallet_transaction_log()->save($wallet_log);
                } else {
                    $walletLogService->updateLogById($data, $wallet_vehicle->wallet_transaction_log->id);
                    $wallet_log = $wallet_vehicle->wallet_transaction_log;
                }
                $response = $walletService->createPayment($wallet_log->id);
                if ($response) {
                    $walletService->executePayment($wallet_vehicle, 'MorphVehicle', $wallet_log->id);
                    return response()->json(['data' => 'wallet'], 200);
                }
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Payment gateway could not be set. Please, try again.');
        }
    }
}
