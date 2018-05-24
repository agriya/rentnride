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
 
namespace Plugins\Vehicles\Controllers\Admin;


use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Plugins\Vehicles\Model\Vehicle;
use JWTAuth;
use Validator;
use Plugins\Vehicles\Transformers\AdminVehicleTransformer;
use Plugins\Vehicles\Transformers\VehicleSimpleTransformer;
use Plugins\Vehicles\Services\VehicleService;
use Plugins\Vehicles\Services\VehicleCompanyService;
use Plugins\Vehicles\Services\VehicleMakeService;
use Plugins\Vehicles\Services\VehicleModelService;
use Plugins\Vehicles\Services\VehicleTypeService;
use Plugins\Vehicles\Services\CounterLocationService;
use Plugins\Vehicles\Services\FuelTypeService;
use Plugins\Vehicles\Model\VehicleMake;
use Plugins\Vehicles\Model\VehicleModel;
use Plugins\Vehicles\Model\VehicleCompany;
use EasySlug\EasySlug\EasySlugFacade as EasySlug;
use File;
use Image;
use App\Attachment;
use DB;

/**
 * Class AdminVehiclesController
 * @package Plugins\Vehicles\Controllers\Admin
 */
class AdminVehiclesController extends Controller
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
     * AdminVehiclesController constructor.
     */
    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
        // Check the logged user role.
        $this->middleware('role');

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
    public function index(Request $request)
    {
        $enabled_includes = array('counter_location', 'vehicle_make', 'vehicle_model', 'vehicle_type', 'vehicle_company', 'fuel_type', 'attachments');
        $vehicle_count = config('constants.ConstPageLimit');
        if ($request->has('type') && $request->type == 'list') {
            $vehicle_count = Vehicle::count();
        }
        if ($request->has('limit') && $request->limit == 'all') {
            $vehicle_count = Vehicle::count();
            $vehicles = Vehicle::filterByRequest($request)->select('id', 'name')->paginate($vehicle_count);
            return $this->response->paginator($vehicles, new VehicleSimpleTransformer);
        } else if ($request->has('limit') && $request->limit != 'all') {
            $vehicle_count = $request->limit;
            $vehicles = Vehicle::with($enabled_includes)
                        ->select(DB::raw('vehicles.*'))
                        ->leftJoin(DB::raw('(select id,name from vehicle_companies) as vehicle_company'), 'vehicle_company.id', '=', 'vehicles.vehicle_company_id')
                        ->leftJoin(DB::raw('(select id,name from vehicle_makes) as vehicle_make'), 'vehicle_make.id', '=', 'vehicles.vehicle_make_id')
                        ->leftJoin(DB::raw('(select id,name from vehicle_models) as vehicle_model'), 'vehicle_model.id', '=', 'vehicles.vehicle_model_id')
                        ->leftJoin(DB::raw('(select id,name from vehicle_types) as vehicle_type'), 'vehicle_type.id', '=', 'vehicles.vehicle_type_id')
                        ->filterByRequest($request)->paginate($vehicle_count);
            return $this->response->paginator($vehicles, (new AdminVehicleTransformer)->setDefaultIncludes($enabled_includes));
        } else {
            $vehicles = Vehicle::with($enabled_includes)
                        ->select(DB::raw('vehicles.*'))
                        ->leftJoin(DB::raw('(select id,name from vehicle_companies) as vehicle_company'), 'vehicle_company.id', '=', 'vehicles.vehicle_company_id')
                        ->leftJoin(DB::raw('(select id,name from vehicle_makes) as vehicle_make'), 'vehicle_make.id', '=', 'vehicles.vehicle_make_id')
                        ->leftJoin(DB::raw('(select id,name from vehicle_models) as vehicle_model'), 'vehicle_model.id', '=', 'vehicles.vehicle_model_id')
                        ->leftJoin(DB::raw('(select id,name from vehicle_types) as vehicle_type'), 'vehicle_type.id', '=', 'vehicles.vehicle_type_id')
                        ->filterByRequest($request)->paginate($vehicle_count);
            return $this->response->paginator($vehicles, (new AdminVehicleTransformer)->setDefaultIncludes($enabled_includes));
        }
    }

    /**
     * Store a new vehicle.
     * Store a new vehicle with a 'amount', 'user_id', 'name', 'booking_type_id', 'description'.
     * @Post("/vehicles")
     * @Transaction({
     *      @Request({"vehicle_company_id": 1, "vehicle_make_id":1, "vehicle_model_id":1, "vehicle_type_id":!, "driven_kilometer":100, "vehicle_no":"tn109879", "no_of_seats":10, "no_of_doors":10, "no_of_gears":5, "is_manual_transmission":1, "no_small_bags":4, "no_large_bags":4, "is_ac":1, "minimum_age_of_driver":20, "mileage":200, "is_km":1, "is_airbag":1, "no_of_airbags":10, "is_abs":1, "per_hour_amount":100, "per_day_amount":500, "fuel_type_id":1, "feedback_count":20, "is_active":1}),
     *      @Response(200, body={"success": "Record has been added."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {"amount": {}}, "status_code": 422})
     * })
     */
    public function store(Request $request)
    {
        $vehicle_data = $request->only('vehicle_company_id', 'vehicle_make_id', 'vehicle_model_id', 'vehicle_type_id', 'driven_kilometer', 'vehicle_no', 'no_of_seats', 'no_of_doors', 'no_of_gears', 'is_manual_transmission', 'no_small_bags', 'no_large_bags', 'is_ac', 'minimum_age_of_driver', 'mileage', 'is_km', 'is_airbag', 'no_of_airbags', 'is_abs', 'per_hour_amount', 'per_day_amount', 'fuel_type_id', 'feedback_count', 'is_paid', 'is_active');
        $vehicle_data['is_active'] = ($vehicle_data['is_active']) ? true : false;
        $vehicle_data['is_paid'] = ($vehicle_data['is_paid']) ? true : false;
        $validator = Validator::make($vehicle_data, Vehicle::GetValidationRule(), Vehicle::GetValidationMessage());
        if ($validator->passes() && ($request->file('file')->isValid())) {
            try {
                $vehicle_company = VehicleCompany::where('id', $request->vehicle_company_id)->first();
                $vehicle_data['user_id'] = $vehicle_company->user_id;
                $vehicle_make = VehicleMake::where('id', $request->vehicle_make_id)->first();
                $vehicle_model = VehicleModel::where('id', $request->vehicle_model_id)->first();
                $vehicle_data['name'] = $vehicle_make->name . ':' . $vehicle_model->name;
                $vehicle_data['slug'] = EasySlug::generateUniqueSlug($vehicle_data['name'], 'vehicles');
                $vehicle = Vehicle::create($vehicle_data);
                if ($vehicle) {
                    $vehicle_data['name'] = $vehicle_make->name . ':' . $vehicle_model->name . ' #' . $vehicle->id;
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
     *      @Response(200, body={"id": 1, "amount": 1000, "user_id": 1, "name": "house for rent", "booking_type_id": 1, "description": "This house is for rent", "User": {}}),
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
        return $this->response->item($vehicle, (new AdminVehicleTransformer)->setDefaultIncludes($enabled_includes));
    }

    /**
     * Update the specified vehicle.
     * Update the vehicle with a `id`.
     * @Put("/vehicles/{id}")
     * @Transaction({
     *      @Request({"id":1,"vehicle_company_id": 1, "vehicle_make_id":1, "vehicle_model_id":1, "vehicle_type_id":!, "driven_kilometer":100, "vehicle_no":"tn109879", "no_of_seats":10, "no_of_doors":10, "no_of_gears":5, "is_manual_transmission":1, "no_small_bags":4, "no_large_bags":4, "is_ac":1, "minimum_age_of_driver":20, "mileage":200, "is_km":1, "is_airbag":1, "no_of_airbags":10, "is_abs":1, "per_hour_amount":100, "per_day_amount":500, "fuel_type_id":1, "feedback_count":20, "is_active":1}),
     *      @Response(200, body={"success": "Record has been updated."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {}, "status_code": 422})
     * })
     */
    public function update(Request $request, $id)
    {
        $vehicle_data = $request->only('id', 'vehicle_company_id', 'vehicle_make_id', 'vehicle_model_id', 'vehicle_type_id', 'driven_kilometer', 'vehicle_no', 'no_of_seats', 'no_of_doors', 'no_of_gears', 'is_manual_transmission', 'no_small_bags', 'no_large_bags', 'is_ac', 'minimum_age_of_driver', 'mileage', 'is_km', 'is_airbag', 'no_of_airbags', 'is_abs', 'per_hour_amount', 'per_day_amount', 'fuel_type_id', 'feedback_count', 'is_paid', 'is_active');
        $vehicle_data['is_active'] = ($vehicle_data['is_active']) ? true : false;
        $vehicle_data['is_paid'] = ($vehicle_data['is_paid']) ? true : false;
        $vehicle = false;
        if ($request->has('id')) {
            $vehicle = Vehicle::find($id);
            $vehicle = ($request->id != $id) ? false : $vehicle;
        }
        $validator = Validator::make($vehicle_data, Vehicle::GetValidationRule(), Vehicle::GetValidationMessage());
        if ($validator->passes() && $vehicle && (($request->hasFile('file') && $request->file('file')->isValid()) || (!$request->hasFile('file')))) {
            try {
                $vehicle_company = VehicleCompany::where('id', $request->vehicle_company_id)->first();
                $vehicle_data['user_id'] = $vehicle_company->user_id;
                $vehicle_make = VehicleMake::where('id', $request->vehicle_make_id)->first();
                $vehicle_model = VehicleModel::where('id', $request->vehicle_model_id)->first();
                $vehicle_data['name'] = $vehicle_make->name . ':' . $vehicle_model->name. '#'.$vehicle->id;;
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
     *      @Response(200, body={"id": 1, "amount": 1000, "user_id": 1, "name": "house for rent", "booking_type_id": 1, "description": "This house is for rent", "User": {}}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function show($id)
    {
        $enabled_includes = array('counter_location', 'vehicle_make', 'vehicle_model', 'vehicle_type', 'vehicle_company', 'fuel_type', 'attachments');
        $vehicle = Vehicle::with($enabled_includes)->find($id);
        if (!$vehicle) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($vehicle, (new AdminVehicleTransformer)->setDefaultIncludes($enabled_includes));
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

    /**
     * Deactivate the vehicle.
     * @Put("/vehicles/{id}/deactive")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"success": "Record has been deactivated!."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404}),
     * })
     */
    public function deactive(Request $request, $id)
    {
        $vehicle = Vehicle::find($id);
        if (!$vehicle) {
            return $this->response->errorNotFound("Invalid Request");
        } else {
            $vehicle_data['is_active'] = false;
            if ($vehicle->update($vehicle_data)) {
				// vehicle count update to realted table
				$this->vehicleService->updateVehicleCount($vehicle->vehicle_make_id, $vehicle->vehicle_model_id, $vehicle->vehicle_type_id, $vehicle->vehicle_company_id);
                return response()->json(['Success' => 'Record has been deactivated!'], 200);
            }
        }
    }

    /**
     * Activate the vehicle.
     * @Put("/vehicles/{id}/active")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"success": "Record has been activated!."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404}),
     * })
     */
    public function active(Request $request, $id)
    {
        $vehicle = Vehicle::find($id);
        if (!$vehicle) {
            return $this->response->errorNotFound("Invalid Request");
        } else {
            $vehicle_data['is_active'] = true;
            if ($vehicle->update($vehicle_data)) {
                $this->vehicleService->afterSave($vehicle);
                return response()->json(['Success' => 'Record has been activated!'], 200);
            }
        }
    }
}
