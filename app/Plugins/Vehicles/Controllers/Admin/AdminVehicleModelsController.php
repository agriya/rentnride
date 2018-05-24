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
use Plugins\Vehicles\Model\VehicleModel;
use JWTAuth;
use Validator;
use Plugins\Vehicles\Transformers\AdminVehicleModelTransformer;
use EasySlug\EasySlug\EasySlugFacade as EasySlug;
use Plugins\Vehicles\Services\VehicleModelService;
use DB;

/**
 * Class AdminVehicleModelsController
 * @package Plugins\Vehicles\Controllers\Admin
 */
class AdminVehicleModelsController extends Controller
{
    /**
     * @var $vehicleModelService
     */
    protected $vehicleModelService;

    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
        // Check the logged user role.
        $this->middleware('role');

        $this->setVehicleModelService();
    }

    public function setVehicleModelService()
    {
        $this->vehicleModelService = new VehicleModelService();
    }

    /**
     * Show all vehicle models.
     * Get a JSON representation of all the vehicle models.
     *
     * @Get("/vehicle_models?filter={filter}&sort={sort}&sortby={sortby}&q={q}")
     * @Parameters({
     *      @Parameter("filter", type="integer", required=false, description="Filter the vehicle models list by status.", default=null),
     *      @Parameter("sort", type="string", required=false, description="Sort the vehicle models list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort vehicle models by Ascending / Descending Order.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1)
     * })
     */
    public function index(Request $request)
    {
        $vehicle_model_count = config('constants.ConstPageLimit');
        if ($request->has('type') && $request->type == 'list') {
            $vehicle_model_count = VehicleModel::count();
        }
        $vehicle_models = VehicleModel::with('vehicle_make')
            ->select(DB::raw('vehicle_models.*'))
            ->leftJoin(DB::raw('(select id,name from vehicle_makes) as vehicle_make'), 'vehicle_make.id', '=', 'vehicle_models.vehicle_make_id')
            ->filterByRequest($request)->paginate($vehicle_model_count);
        return $this->response->paginator($vehicle_models, (new AdminVehicleModelTransformer)->setDefaultIncludes(['vehicle_make']));
    }

    /**
     * Store a new vehicle model.
     * @Post("/vehicle_models")
     * @Transaction({
     *      @Request({"name": "suzuki", "vehicle_make_id":"1", "is_active": 1}),
     *      @Response(200, body={"success": "Record has been added."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {"amount": {}}, "status_code": 422})
     * })
     */
    public function store(Request $request)
    {
        $vehicle_model_data = $request->only('name', 'vehicle_make_id', 'is_active');
        $vehicle_model_data['slug'] = EasySlug::generateUniqueSlug($request->name, 'vehicle_models');
        $validator = Validator::make($vehicle_model_data, VehicleModel::GetValidationRule(), VehicleModel::GetValidationMessage());
        if ($validator->passes()) {
            try {
                $vehicle_model = VehicleModel::create($vehicle_model_data);
                if ($vehicle_model) {
                    // after save count update
                    $this->vehicleModelService->afterSave($vehicle_model, false);
                    return response()->json(['Success' => 'Vehicle model has been added'], 200);
                } else {
                    throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle model could not be added. Please, try again.');
                }
            } catch (\Exception $e) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle Model could not be added. Please, try again.',
                    array($e->getMessage()));
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle model could not be added. Please, try again.', $validator->errors());
        }
    }

    /**
     * Edit the specified vehicle model.
     * Edit the vehicle model with a `id`.
     * @Get("/vehicle_models/{id}/edit")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"id": 1, "amount": 1000, "user_id": 1, "name": "house for rent", "booking_type_id": 1, "description": "This house is for rent", "User": {}}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function edit($id)
    {
        $vehicle_type = VehicleModel::find($id);
        if (!$vehicle_type) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($vehicle_type, (new AdminVehicleModelTransformer));
    }

    /**
     * Update the specified vehicle model.
     * Update the vehicle model with a `id`.
     * @Put("/vehicle_models/{id}")
     * @Transaction({
     *      @Request({"id": 1, "name": "suzuki", "vehicle_make_id":"1", "is_active": 1}),
     *      @Response(200, body={"success": "Record has been updated."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {}, "status_code": 422})
     * })
     */
    public function update(Request $request, $id)
    {
        $vehicle_model_data = $request->only('id', 'name', 'vehicle_make_id', 'is_active');
        $vehicle_model = $vehicle_model_old = false;
        if ($request->has('id')) {
            $vehicle_model = $vehicle_model_old = VehicleModel::find($id);
            $vehicle_model = ($request->id != $id) ? false : $vehicle_model;
            if ($vehicle_model->name !== $request->name) {
                $vehicle_model_data['slug'] = EasySlug::generateUniqueSlug($request->name, 'vehicle_models');
            } else {
                $vehicle_model_data['slug'] = $vehicle_model->slug;
            }
        }
        $validator = Validator::make($vehicle_model_data, VehicleModel::GetValidationRule(), VehicleModel::GetValidationMessage());
        if ($validator->passes() && $vehicle_model) {
            try {
                $vehicle_model->update($vehicle_model_data);
                // after save count update
                $this->vehicleModelService->afterSave($vehicle_model, $vehicle_model_old);
                return response()->json(['Success' => 'Vehicle Model has been updated'], 200);
            } catch (\Exception $e) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle Model could not be updated. Please, try again.',
                    array($e->getMessage()));
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle Model could not be updated. Please, try again.', $validator->errors());
        }
    }

    /**
     * Delete the specified vehicle model.
     * Delete the vehicle model with a `id`.
     * @Delete("/vehicle_models/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"success": "Record Deleted."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function destroy($id)
    {
        $vehicle_model = VehicleModel::find($id);
        if (!$vehicle_model) {
            return $this->response->errorNotFound("Invalid Request");
        } else {
            $vehicle_model->delete();
        }
        return response()->json(['Success' => 'Vehicle model deleted'], 200);
    }

    /**
     * Show the specified vehicle model.
     * Show the vehicle model with a `id`.
     * @Get("/vehicle_models/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"id": 1}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function show($id)
    {
        $vehicle_model = VehicleModel::find($id);
        if (!$vehicle_model) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($vehicle_model, (new AdminVehicleModelTransformer));
    }
}
