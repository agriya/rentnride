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
 
namespace Plugins\VehicleFuelOptions\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Plugins\VehicleFuelOptions\Model\VehicleTypeFuelOption;
use JWTAuth;
use Validator;
use Plugins\VehicleFuelOptions\Transformers\VehicleTypeFuelOptionTransformer;
use DB;
/**
 * VehicleTypeFuelOptions resource representation.
 * @Resource("Admin/AdminVehicleTypeFuelOptions")
 */
class AdminVehicleTypeFuelOptionsController extends Controller
{
    /**
     * AdminVehicleTypeFuelOptionsController constructor.
     */
    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
        // Check the logged user role.
        $this->middleware('role');
    }

    /**
     * Show all vehicle_type_fuel_options
     * Get a JSON representation of all the vehicle_type_fuel_options.
     *
     * @Get("/admin/vehicle_type_fuel_options?sort={sort}&sortby={sortby}&page={page}&q={q}")
     * @Parameters({
     *      @Parameter("sort", type="string", required=false, description="Sort the vehicle_type_fuel_options list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort vehicle_type_fuel_options by Ascending / Descending Order.", default=null),
     *      @Parameter("q", type="string", required=false, description="Search VehicleTypeFuelOptions.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1)
     * })
     */
    public function index(Request $request)
    {
        $enabled_includes = array('vehicle_fuel_option', 'discount_type', 'duration_type', 'vehicle_type');
        $vehicle_type_fuel_options = VehicleTypeFuelOption::with($enabled_includes)
            ->select(DB::raw('vehicle_type_fuel_options.*'))
            ->leftJoin(DB::raw('(select id,name from vehicle_types) as vehicle_type'), 'vehicle_type.id', '=', 'vehicle_type_fuel_options.vehicle_type_id')
            ->leftJoin(DB::raw('(select id,name from fuel_options) as vehicle_fuel_option'), 'vehicle_fuel_option.id', '=', 'vehicle_type_fuel_options.fuel_option_id')
            ->leftJoin(DB::raw('(select id,type from discount_types) as discount_type'), 'discount_type.id', '=', 'vehicle_type_fuel_options.discount_type_id')
            ->leftJoin(DB::raw('(select id,name from duration_types) as duration_type'), 'duration_type.id', '=', 'vehicle_type_fuel_options.duration_type_id')
            ->filterByRequest($request)->paginate(config('constants.ConstPageLimit'));
        return $this->response->paginator($vehicle_type_fuel_options, (new VehicleTypeFuelOptionTransformer)->setDefaultIncludes($enabled_includes));
    }

    /**
     * Edit the specified vehicle_type_fuel_option.
     * Edit the vehicle_type_fuel_option with a `id`.
     * @Get("/admin/vehicle_type_fuel_options/{id}/edit")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body= {"id": 1, "vehicle_type_id": 1, "fuel_option_id": 1, "discount_type_id": 1, "duration_type_id": 1, "rate": 200, "max_allowed_amount": 1000, "vehicle_type": {}, "vehicle_fuel_option": {}, "duration_type": {}, "discount_type": {}}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function edit($id)
    {
        $enabled_includes = array('vehicle_fuel_option', 'discount_type', 'duration_type', 'vehicle_type');
        $vehicle_type_fuel_option = VehicleTypeFuelOption::with($enabled_includes)->find($id);
        if (!$vehicle_type_fuel_option) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($vehicle_type_fuel_option, (new VehicleTypeFuelOptionTransformer)->setDefaultIncludes($enabled_includes));
    }

    /**
     * Show the specified vehicle_type_fuel_option.
     * Show the vehicle_type_fuel_option with a `id`.
     * @Get("/admin/vehicle_type_fuel_options/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body= {"id": 1, "vehicle_type_id": 1, "fuel_option_id": 1, "discount_type_id": 1, "duration_type_id": 1, "rate": 200, "max_allowed_amount": 1000, "vehicle_type": {}, "vehicle_fuel_option": {}, "duration_type": {}, "discount_type": {}}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function show($id)
    {
        $enabled_includes = array('vehicle_fuel_option', 'discount_type', 'duration_type', 'vehicle_type');
        $vehicle_type_fuel_option = VehicleTypeFuelOption::with($enabled_includes)->find($id);
        if (!$vehicle_type_fuel_option) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($vehicle_type_fuel_option, (new VehicleTypeFuelOptionTransformer)->setDefaultIncludes($enabled_includes));
    }

    /**
     * Store a new vehicle_type_fuel_option.
     * Store a new vehicle_type_fuel_option with a `name`, `short_description`, and `description`.
     * @Post("/admin/vehicle_type_fuel_options")
     * @Transaction({
     *      @Request({"vehicle_type_id": 1, "fuel_option_id": 1, "discount_type_id": 1, "duration_type_id": 1, "rate": 200, "max_allowed_amount": 1000}),
     *      @Response(200, body={"success": "Record has been added."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {"name": {}}, "status_code": 422})
     * })
     */
    public function store(Request $request)
    {
        $vehicle_type_fuel_option_data = $request->only('vehicle_type_id', 'fuel_option_id', 'rate', 'discount_type_id', 'duration_type_id', 'max_allowed_amount');
        $validator = Validator::make($vehicle_type_fuel_option_data, VehicleTypeFuelOption::GetValidationRule(), VehicleTypeFuelOption::GetValidationMessage());
        if ($validator->passes()) {
			$vehicle_type_fuel_option_data['is_active'] = true;
            $vehicle_type_fuel_option = VehicleTypeFuelOption::create($vehicle_type_fuel_option_data);
            if ($vehicle_type_fuel_option) {
                return response()->json(['Success' => 'VehicleTypeFuelOption has been added'], 200);
            } else {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleTypeFuelOption could not be added. Please, try again.');
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleTypeFuelOption could not be added. Please, try again.', $validator->errors());
        }
    }


    /**
     * Update the specified vehicle_type_fuel_option
     * Update the vehicle_type_fuel_option with a `id`.
     * @Put("/admin/vehicle_type_fuel_options/{id}")
     * @Transaction({
     *      @Request({"id": 1, "vehicle_type_id": 1, "fuel_option_id": 1, "discount_type_id": 1, "duration_type_id": 1, "rate": 200, "max_allowed_amount": 1000}),
     *      @Response(200, body={"success": "Record has been updated."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {"name": {}}, "status_code": 422})
     * })
     */
    public function update(Request $request, $id)
    {
        $vehicle_type_fuel_option_data = $request->only('vehicle_type_id', 'fuel_option_id', 'rate', 'discount_type_id', 'duration_type_id', 'max_allowed_amount', 'is_active');
        $validator = Validator::make($vehicle_type_fuel_option_data, VehicleTypeFuelOption::GetValidationRule(), VehicleTypeFuelOption::GetValidationMessage());
        $vehicle_type_fuel_option = false;
        if ($request->has('id')) {
            $vehicle_type_fuel_option = VehicleTypeFuelOption::find($id);
            $vehicle_type_fuel_option = ($request->id != $id) ? false : $vehicle_type_fuel_option;
        }
        if ($validator->passes() && $vehicle_type_fuel_option) {
            try {
                $vehicle_type_fuel_option->update($vehicle_type_fuel_option_data);
                return response()->json(['Success' => 'VehicleTypeFuelOption has been updated'], 200);
            } catch (\Exception $e) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleTypeFuelOption could not be updated. Please, try again.');
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleTypeFuelOption could not be updated. Please, try again.', $validator->errors());
        }
    }

    /**
     * Delete the specified vehicle_type_fuel_option.
     * Delete the vehicle_type_fuel_option with a `id`.
     * @Delete("/admin/vehicle_type_fuel_options/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"success": "Record Deleted."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function destroy($id)
    {
        $vehicle_type_fuel_option = VehicleTypeFuelOption::find($id);
        if (!$vehicle_type_fuel_option) {
            return $this->response->errorNotFound("Invalid Request");
        } else {
            $vehicle_type_fuel_option->delete();
        }
        return response()->json(['Success' => 'VehicleTypeFuelOption deleted'], 200);
    }
}
