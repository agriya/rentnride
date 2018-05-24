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
use Plugins\Vehicles\Model\VehicleType;
use JWTAuth;
use Validator;
use Plugins\Vehicles\Transformers\AdminVehicleTypeTransformer;
use Plugins\Vehicles\Transformers\VehicleTypeSimpleTransformer;
use EasySlug\EasySlug\EasySlugFacade as EasySlug;

/**
 * Class AdminVehicleTypesController
 * @package Plugins\Vehicles\Controllers\Admin
 */
class AdminVehicleTypesController extends Controller
{

    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
        // Check the logged user role.
        $this->middleware('role');
    }

    /**
     * Show all vehicle types.
     * Get a JSON representation of all the vehicle types.
     *
     * @Get("/vehicle_types?filter={filter}&sort={sort}&sortby={sortby}&q={q}")
     * @Parameters({
     *      @Parameter("filter", type="integer", required=false, description="Filter the vehicle types list by status.", default=null),
     *      @Parameter("sort", type="string", required=false, description="Sort the vehicle types list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort vehicle types by Ascending / Descending Order.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1)
     * })
     */
    public function index(Request $request)
    {
        $vehicle_type_count = config('constants.ConstPageLimit');
        if ($request->has('type') && $request->type == 'list') {
            $vehicle_type_count = VehicleType::count();
        }
        if ($request->has('limit') && $request->limit == 'all') {
            $vehicle_type_count = VehicleType::count();
            $vehicle_types = VehicleType::filterByRequest($request)->filterByActiveRecord($request)->select('id', 'name')->paginate($vehicle_type_count);
            return $this->response->paginator($vehicle_types, new VehicleTypeSimpleTransformer);
        } else {
            $vehicle_types = VehicleType::filterByRequest($request)->paginate($vehicle_type_count);
            return $this->response->paginator($vehicle_types, new AdminVehicleTypeTransformer);
        }
    }

    /**
     * Store a new vehicle type.
     * Store a new vehicle type with a 'name'.
     * @Post("/vehicle_types")
     * @Transaction({
     *      @Request({}),
     *      @Response(200, body={"success": "Record has been added."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {"amount": {}}, "status_code": 422})
     * })
     */
    public function store(Request $request)
    {
        $vehicle_type_data = $request->only('name', 'minimum_hour_price', 'maximum_hour_price', 'minimum_day_price', 'maximum_day_price', 'drop_location_differ_unit_price', 'drop_location_differ_additional_fee', 'deposit_amount', 'is_active');
        $vehicle_type_data['slug'] = EasySlug::generateUniqueSlug($request->name, 'vehicle_types');
        $validator = Validator::make($vehicle_type_data, VehicleType::GetValidationRule(), VehicleType::GetValidationMessage());
        if ($vehicle_type_data['minimum_hour_price'] >= $vehicle_type_data['maximum_hour_price']) {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Maximum hour price should be greater than minimum hour price');
        }
        if ($vehicle_type_data['minimum_day_price'] >= $vehicle_type_data['maximum_day_price']) {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Maximum day price should be greater than minimum day price');
        }
        if ($validator->passes()) {
            try {
                $vehicle_type = VehicleType::create($vehicle_type_data);
                if ($vehicle_type) {
                    return response()->json(['Success' => 'Vehicle type has been added'], 200);
                } else {
                    throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle type could not be updated. Please, try again.');
                }
            } catch (\Exception $e) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle type could not be added. Please, try again.',
                    array($e->getMessage()));
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle type could not be updated. Please, try again.', $validator->errors());
        }
    }

    /**
     * Edit the specified vehicle type.
     * Edit the vehicle type with a `id`.
     * @Get("/vehicle_types/{id}/edit")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"id": 1}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function edit($id)
    {
        $vehicle_type = VehicleType::find($id);
        if (!$vehicle_type) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($vehicle_type, (new AdminVehicleTypeTransformer));
    }

    /**
     * Update the specified vehicle type.
     * Update the vehicle type with a `id`.
     * @Put("/vehicle_types/{id}")
     * @Transaction({
     *      @Response(200, body={"success": "Record has been updated."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {}, "status_code": 422})
     * })
     */
    public function update(Request $request, $id)
    {
        $vehicle_type_data = $request->only('id', 'name', 'minimum_hour_price', 'maximum_hour_price', 'minimum_day_price', 'maximum_day_price', 'drop_location_differ_unit_price', 'drop_location_differ_additional_fee', 'deposit_amount', 'is_active');
        $vehicle_type = false;
        if ($request->has('id')) {
            $vehicle_type = VehicleType::find($id);
            $vehicle_type = ($request->id != $id) ? false : $vehicle_type;
            if ($vehicle_type->name !== $request->name) {
                $vehicle_type_data['slug'] = EasySlug::generateUniqueSlug($request->name, 'vehicle_types');
            } else {
                $vehicle_type_data['slug'] = $vehicle_type->slug;
            }
        }
        $validator = Validator::make($vehicle_type_data, VehicleType::GetValidationRule(), VehicleType::GetValidationMessage());
        if ($vehicle_type_data['minimum_hour_price'] >= $vehicle_type_data['maximum_hour_price']) {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Maximum hour price should be greater than minimum hour price');
        }
        if ($vehicle_type_data['minimum_day_price'] >= $vehicle_type_data['maximum_day_price']) {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Maximum day price should be greater than minimum day price');
        }
        if ($validator->passes() && $vehicle_type) {
            try {
                $vehicle_type->update($vehicle_type_data);
                return response()->json(['Success' => 'VehicleType has been updated'], 200);
            } catch (\Exception $e) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle type could not be updated. Please, try again.',
                    array($e->getMessage()));
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleType could not be updated. Please, try again.', $validator->errors());
        }
    }

    /**
     * Delete the specified vehicle type.
     * Delete the vehicle type with a `id`.
     * @Delete("/vehicle_types/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"success": "Record Deleted."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function destroy($id)
    {
        $vehicle_type = VehicleType::find($id);
        if (!$vehicle_type) {
            return $this->response->errorNotFound("Invalid Request");
        } else {
            $vehicle_type->delete();
        }
        return response()->json(['Success' => 'Vehicle type deleted'], 200);
    }

    /**
     * Show the specified vehicle type.
     * Show the vehicle type with a `id`.
     * @Get("/vehicle_types/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"id": 1}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function show($id)
    {
        $vehicle_type = VehicleType::find($id);
        if (!$vehicle_type) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($vehicle_type, (new AdminVehicleTypeTransformer));
    }
}
