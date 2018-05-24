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
 
namespace Plugins\VehicleSurcharges\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Plugins\VehicleSurcharges\Model\VehicleSurcharge;
use JWTAuth;
use Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Plugins\VehicleSurcharges\Transformers\AdminVehicleSurchargeTransformer;
use Plugins\VehicleSurcharges\Transformers\VehicleSurchargeTransformer;
use EasySlug\EasySlug\EasySlugFacade as EasySlug;

/**
 * VehicleSurcharges resource representation.
 * @Resource("Admin/AdminVehicleSurcharges")
 */
class AdminVehicleSurchargesController extends Controller
{
    /**
     * AdminVehicleSurchargesController constructor.
     */
    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
        // Check the logged user role.
        $this->middleware('role');
    }

    /**
     * Show all vehicle_surcharges
     * Get a JSON representation of all the vehicle_surcharges.
     *
     * @Get("/admin/vehicle_surcharges?sort={sort}&sortby={sortby}&page={page}&q={q}")
     * @Parameters({
     *      @Parameter("sort", type="string", required=false, description="Sort the vehicle_surcharges list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort vehicle_surcharges by Ascending / Descending Order.", default=null),
     *      @Parameter("q", type="string", required=false, description="Search VehicleSurcharges.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1)
     * })
     */
    public function index(Request $request)
    {
        if($request->has('limit') && $request->limit == 'all') {
            $vehicle_count = VehicleSurcharge::count();
            $vehicles = VehicleSurcharge::filterByActiveRecord($request)->filterByRequest($request)->select('id', 'name')->paginate($vehicle_count);
            return $this->response->paginator($vehicles, new VehicleSurchargeTransformer);
        } else {
            $vehicle_surcharges = VehicleSurcharge::filterByRequest($request)->paginate(config('constants.ConstPageLimit'));
            return $this->response->paginator($vehicle_surcharges, new AdminVehicleSurchargeTransformer);
        }
    }

    /**
     * Edit the specified vehicle_surcharge.
     * Edit the vehicle_surcharge with a `id`.
     * @Get("/admin/vehicle_surcharges/{id}/edit")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body= {"id": 1, "name": "Energy Surcharge", "short_description": "The costs of energy needed to support our business operations have escalated considerably.", "description": "The costs of energy needed to support our business operations have escalated considerably. To offset the increasing costs of utilities, bus fuel, oil and grease, etc.,"}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function edit($id)
    {
        $vehicle_surcharge = VehicleSurcharge::find($id);
        if (!$vehicle_surcharge) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($vehicle_surcharge, (new AdminVehicleSurchargeTransformer));
    }

    /**
     * Show the specified vehicle_surcharge.
     * show the vehicle_surcharge with a `id`.
     * @Get("/admin/vehicle_surcharges/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body= {"id": 1, "name": "Energy Surcharge", "short_description": "The costs of energy needed to support our business operations have escalated considerably.", "description": "The costs of energy needed to support our business operations have escalated considerably. To offset the increasing costs of utilities, bus fuel, oil and grease, etc.,"}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function show($id)
    {
        $vehicle_surcharge = VehicleSurcharge::find($id);
        if (!$vehicle_surcharge) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($vehicle_surcharge, (new AdminVehicleSurchargeTransformer));
    }

    /**
     * Store a new vehicle_surcharge.
     * Store a new vehicle_surcharge with a `name`, `short_description`, and `description`.
     * @Post("/admin/vehicle_surcharges")
     * @Transaction({
     *      @Request({"name": "Energy Surcharge", "short_description": "The costs of energy needed to support our business operations have escalated considerably.", "description": "The costs of energy needed to support our business operations have escalated considerably. To offset the increasing costs of utilities, bus fuel, oil and grease, etc.,"}),
     *      @Response(200, body={"success": "Record has been added."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {"name": {}}, "status_code": 422})
     * })
     */
    public function store(Request $request)
    {
        $vehicle_surcharge_data = $request->only('name', 'short_description', 'description');
        $validator = Validator::make($vehicle_surcharge_data, VehicleSurcharge::GetValidationRule(), VehicleSurcharge::GetValidationMessage());
        $vehicle_surcharge_data['slug'] = EasySlug::generateUniqueSlug($request->name, 'surcharges');
        if ($validator->passes()) {
            $vehicle_surcharge_data['is_active'] = true;
            $vehicle_surcharge = VehicleSurcharge::create($vehicle_surcharge_data);
            if ($vehicle_surcharge) {
                return response()->json(['Success' => 'VehicleSurcharge has been added'], 200);
            } else {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleSurcharge could not be updated. Please, try again.');
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleSurcharge could not be updated. Please, try again.', $validator->errors());
        }
    }


    /**
     * Update the specified vehicle_surcharge
     * Update the vehicle_surcharge with a `id`.
     * @Put("/admin/vehicle_surcharges/{id}")
     * @Transaction({
     *      @Request({"id": 1, "name": "Energy Surcharge", "short_description": "The costs of energy needed to support our business operations have escalated considerably.", "description": "The costs of energy needed to support our business operations have escalated considerably. To offset the increasing costs of utilities, bus fuel, oil and grease, etc.,", "is_active": 1}),
     *      @Response(200, body={"success": "Record has been updated."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {"name": {}}, "status_code": 422})
     * })
     */
    public function update(Request $request, $id)
    {
        $vehicle_surcharge_data = $request->only('name', 'short_description', 'description', 'is_active');
        $validator = Validator::make($vehicle_surcharge_data, VehicleSurcharge::GetValidationRule(), VehicleSurcharge::GetValidationMessage());
        $vehicle_surcharge = false;
        if ($request->has('id')) {
            $vehicle_surcharge = VehicleSurcharge::find($id);
            $vehicle_surcharge = ($request->id != $id) ? false : $vehicle_surcharge;
            if ($vehicle_surcharge->name !== $request->name) {
                $vehicle_surcharge_data['slug'] = EasySlug::generateUniqueSlug($request->name, 'surcharges');
            }
        }
        if ($validator->passes() && $vehicle_surcharge) {
            try {
                $vehicle_surcharge->update($vehicle_surcharge_data);
                return response()->json(['Success' => 'VehicleSurcharge has been updated'], 200);
            } catch (\Exception $e) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleSurcharge could not be updated. Please, try again.');
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleSurcharge could not be updated. Please, try again.', $validator->errors());
        }
    }

    /**
     * Delete the specified vehicle_surcharge.
     * Delete the vehicle_surcharge with a `id`.
     * @Delete("/admin/vehicle_surcharges/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"success": "Record Deleted."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function destroy($id)
    {
        $vehicle_surcharge = VehicleSurcharge::find($id);
        if (!$vehicle_surcharge) {
            return $this->response->errorNotFound("Invalid Request");
        } else {
            $vehicle_surcharge->delete();
        }
        return response()->json(['Success' => 'VehicleSurcharge deleted'], 200);
    }
}
