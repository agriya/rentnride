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
use App\Http\Controllers\Controller;
use Plugins\VehicleFuelOptions\Model\VehicleFuelOption;
use JWTAuth;
use Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Plugins\VehicleFuelOptions\Transformers\AdminVehicleFuelOptionTransformer;
use Plugins\VehicleFuelOptions\Transformers\VehicleFuelOptionTransformer;
use EasySlug\EasySlug\EasySlugFacade as EasySlug;

/**
 * VehicleFuelOptions resource representation.
 * @Resource("Admin/AdminVehicleFuelOptions")
 */
class AdminVehicleFuelOptionsController extends Controller
{
    /**
     * AdminVehicleFuelOptionsController constructor.
     */
    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
        // Check the logged user role.
        $this->middleware('role');
    }

    /**
     * Show all vehicle_fuel_options
     * Get a JSON representation of all the vehicle_fuel_options.
     *
     * @Get("/admin/vehicle_fuel_options?sort={sort}&sortby={sortby}&page={page}&q={q}")
     * @Parameters({
     *      @Parameter("sort", type="string", required=false, description="Sort the vehicle_fuel_options list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort vehicle_fuel_options by Ascending / Descending Order.", default=null),
     *      @Parameter("q", type="string", required=false, description="Search VehicleFuelOptions.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1)
     * })
     */
    public function index(Request $request)
    {
        if($request->has('limit') && $request->limit == 'all') {
            $vehicle_fuel_option_count = VehicleFuelOption::count();
            $vehicle_fuel_options = VehicleFuelOption::filterByActiveRecord($request)->filterByRequest($request)->select('id', 'name')->paginate($vehicle_fuel_option_count);
            return $this->response->paginator($vehicle_fuel_options, new VehicleFuelOptionTransformer);
        } else {
            $vehicle_fuel_options = VehicleFuelOption::filterByRequest($request)->paginate(config('constants.ConstPageLimit'));
            return $this->response->paginator($vehicle_fuel_options, new AdminVehicleFuelOptionTransformer);
        }
    }

    /**
     * Edit the specified vehicle_fuel_option.
     * Edit the vehicle_fuel_option with a `id`.
     * @Get("/admin/vehicle_fuel_options/{id}/edit")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body= {"id": 1, "name": "Service FuelOption", "short_description": "The costs of service needed to support our business operations have escalated considerably.", "description": "The costs of service needed to support our business operations have escalated considerably. To offset the increasing costs of utilities, bus fuel, oil and grease, etc.,"}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function edit($id)
    {
        $vehicle_fuel_option = VehicleFuelOption::find($id);
        if (!$vehicle_fuel_option) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($vehicle_fuel_option, (new AdminVehicleFuelOptionTransformer));
    }

    /**
     * Show the specified vehicle_fuel_option.
     * Show the vehicle_fuel_option with a `id`.
     * @Get("/admin/vehicle_fuel_options/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body= {"id": 1, "name": "Service FuelOption", "short_description": "The costs of service needed to support our business operations have escalated considerably.", "description": "The costs of service needed to support our business operations have escalated considerably. To offset the increasing costs of utilities, bus fuel, oil and grease, etc.,"}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function show($id)
    {
        $vehicle_fuel_option = VehicleFuelOption::find($id);
        if (!$vehicle_fuel_option) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($vehicle_fuel_option, (new AdminVehicleFuelOptionTransformer));
    }

    /**
     * Store a new vehicle_fuel_option.
     * Store a new vehicle_fuel_option with a `name`, `short_description`, and `description`.
     * @Post("/admin/vehicle_fuel_options")
     * @Transaction({
     *      @Request({"name": "Service FuelOption", "short_description": "The costs of service needed to support our business operations have escalated considerably.", "description": "The costs of service needed to support our business operations have escalated considerably. To offset the increasing costs of utilities, bus fuel, oil and grease, etc.,"}),
     *      @Response(200, body={"success": "Record has been added."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {"name": {}}, "status_code": 422})
     * })
     */
    public function store(Request $request)
    {
        $vehicle_fuel_option_data = $request->only('name', 'short_description', 'description');
        $validator = Validator::make($vehicle_fuel_option_data, VehicleFuelOption::GetValidationRule(), VehicleFuelOption::GetValidationMessage());
        $vehicle_fuel_option_data['slug'] = EasySlug::generateUniqueSlug($request->name, 'fuel_options');
        if ($validator->passes()) {
            $vehicle_fuel_option_data['is_active'] = true;
            $vehicle_fuel_option = VehicleFuelOption::create($vehicle_fuel_option_data);
            if ($vehicle_fuel_option) {
                return response()->json(['Success' => 'VehicleFuelOption has been added'], 200);
            } else {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleFuelOption could not be updated. Please, try again.');
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleFuelOption could not be updated. Please, try again.', $validator->errors());
        }
    }


    /**
     * Update the specified vehicle_fuel_option
     * Update the vehicle_fuel_option with a `id`.
     * @Put("/admin/vehicle_fuel_options/{id}")
     * @Transaction({
     *      @Request({"id": 1, "name": "Energy FuelOption", "short_description": "The costs of energy needed to support our business operations have escalated considerably.", "description": "The costs of energy needed to support our business operations have escalated considerably. To offset the increasing costs of utilities, bus fuel, oil and grease, etc.,", "is_active": 1}),
     *      @Response(200, body={"success": "Record has been updated."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {"name": {}}, "status_code": 422})
     * })
     */
    public function update(Request $request, $id)
    {
        $vehicle_fuel_option_data = $request->only('name', 'short_description', 'description', 'is_active');
        $validator = Validator::make($vehicle_fuel_option_data, VehicleFuelOption::GetValidationRule(), VehicleFuelOption::GetValidationMessage());
        $vehicle_fuel_option = false;
        if ($request->has('id')) {
            $vehicle_fuel_option = VehicleFuelOption::find($id);
            $vehicle_fuel_option = ($request->id != $id) ? false : $vehicle_fuel_option;
            if ($vehicle_fuel_option->name !== $request->name) {
                $vehicle_fuel_option_data['slug'] = EasySlug::generateUniqueSlug($request->name, 'fuel_options');
            }
        }
        if ($validator->passes() && $vehicle_fuel_option) {
            try {
                $vehicle_fuel_option->update($vehicle_fuel_option_data);
                return response()->json(['Success' => 'VehicleFuelOption has been updated'], 200);
            } catch (\Exception $e) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleFuelOption could not be updated. Please, try again.');
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleFuelOption could not be updated. Please, try again.', $validator->errors());
        }
    }

    /**
     * Delete the specified vehicle_fuel_option.
     * Delete the vehicle_fuel_option with a `id`.
     * @Delete("/admin/vehicle_fuel_options/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"success": "Record Deleted."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function destroy($id)
    {
        $vehicle_fuel_option = VehicleFuelOption::find($id);
        if (!$vehicle_fuel_option) {
            return $this->response->errorNotFound("Invalid Request");
        } else {
            $vehicle_fuel_option->delete();
        }
        return response()->json(['Success' => 'VehicleFuelOption deleted'], 200);
    }
}
