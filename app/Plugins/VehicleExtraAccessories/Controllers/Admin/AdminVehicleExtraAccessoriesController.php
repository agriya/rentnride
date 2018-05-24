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
 
namespace Plugins\VehicleExtraAccessories\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Plugins\VehicleExtraAccessories\Model\VehicleExtraAccessory;
use JWTAuth;
use Plugins\VehicleExtraAccessories\Transformers\VehicleExtraAccessoryTransformer;
use Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Plugins\VehicleExtraAccessories\Transformers\AdminVehicleExtraAccessoryTransformer;
use EasySlug\EasySlug\EasySlugFacade as EasySlug;

/**
 * VehicleExtraAccessories resource representation.
 * @Resource("Admin/AdminVehicleExtraAccessories")
 */
class AdminVehicleExtraAccessoriesController extends Controller
{
    /**
     * AdminVehicleExtraAccessoriesController constructor.
     */
    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
        // Check the logged user role.
        $this->middleware('role');
    }

    /**
     * Show all vehicle_extra_accessories
     * Get a JSON representation of all the vehicle_extra_accessories.
     *
     * @Get("/admin/vehicle_extra_accessories?sort={sort}&sortby={sortby}&page={page}&q={q}")
     * @Parameters({
     *      @Parameter("sort", type="string", required=false, description="Sort the vehicle_extra_accessories list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort vehicle_extra_accessories by Ascending / Descending Order.", default=null),
     *      @Parameter("q", type="string", required=false, description="Search VehicleExtraAccessories.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1)
     * })
     */
    public function index(Request $request)
    {
        if($request->has('limit') && $request->limit == 'all') {
            $vehicle_extra_accessory_count = VehicleExtraAccessory::count();
            $vehicle_extra_accessories = VehicleExtraAccessory::filterByActiveRecord($request)->filterByRequest($request)->select('id', 'name')->paginate($vehicle_extra_accessory_count);
            return $this->response->paginator($vehicle_extra_accessories, new VehicleExtraAccessoryTransformer);
        } else {
            $vehicle_extra_accessories = VehicleExtraAccessory::filterByRequest($request)->paginate(config('constants.ConstPageLimit'));
            return $this->response->paginator($vehicle_extra_accessories, new AdminVehicleExtraAccessoryTransformer);
        }
    }

    /**
     * Edit the specified vehicle_extra_accessory.
     * Edit the vehicle_extra_accessory with a `id`.
     * @Get("/admin/vehicle_extra_accessories/{id}/edit")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body= {"id": 1, "name": "Service ExtraAccessory", "short_description": "The costs of service needed to support our business operations have escalated considerably.", "description": "The costs of service needed to support our business operations have escalated considerably. To offset the increasing costs of utilities, bus fuel, oil and grease, etc.,"}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function edit($id)
    {
        $vehicle_extra_accessory = VehicleExtraAccessory::find($id);
        if (!$vehicle_extra_accessory) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($vehicle_extra_accessory, (new AdminVehicleExtraAccessoryTransformer));
    }

    /**
     * Show the specified vehicle_extra_accessory.
     * Show the vehicle_extra_accessory with a `id`.
     * @Get("/admin/vehicle_extra_accessories/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body= {"id": 1, "name": "Service ExtraAccessory", "short_description": "The costs of service needed to support our business operations have escalated considerably.", "description": "The costs of service needed to support our business operations have escalated considerably. To offset the increasing costs of utilities, bus fuel, oil and grease, etc.,"}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function show($id)
    {
        $vehicle_extra_accessory = VehicleExtraAccessory::find($id);
        if (!$vehicle_extra_accessory) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($vehicle_extra_accessory, (new AdminVehicleExtraAccessoryTransformer));
    }

    /**
     * Store a new vehicle_extra_accessory.
     * Store a new vehicle_extra_accessory with a `name`, `short_description`, and `description`.
     * @Post("/admin/vehicle_extra_accessories")
     * @Transaction({
     *      @Request({"name": "Service ExtraAccessory", "short_description": "The costs of service needed to support our business operations have escalated considerably.", "description": "The costs of service needed to support our business operations have escalated considerably. To offset the increasing costs of utilities, bus fuel, oil and grease, etc.,"}),
     *      @Response(200, body={"success": "Record has been added."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {"name": {}}, "status_code": 422})
     * })
     */
    public function store(Request $request)
    {
        $vehicle_extra_accessory_data = $request->only('name', 'short_description', 'description');
        $validator = Validator::make($vehicle_extra_accessory_data, VehicleExtraAccessory::GetValidationRule(), VehicleExtraAccessory::GetValidationMessage());
        $vehicle_extra_accessory_data['slug'] = EasySlug::generateUniqueSlug($request->name, 'extra_accessories');
        if ($validator->passes()) {
            $vehicle_extra_accessory_data['is_active'] = true;
            $vehicle_extra_accessory = VehicleExtraAccessory::create($vehicle_extra_accessory_data);
            if ($vehicle_extra_accessory) {
                return response()->json(['Success' => 'VehicleExtraAccessory has been added'], 200);
            } else {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleExtraAccessory could not be updated. Please, try again.');
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleExtraAccessory could not be updated. Please, try again.', $validator->errors());
        }
    }


    /**
     * Update the specified vehicle_extra_accessory
     * Update the vehicle_extra_accessory with a `id`.
     * @Put("/admin/vehicle_extra_accessories/{id}")
     * @Transaction({
     *      @Request({"id": 1, "name": "Energy ExtraAccessory", "short_description": "The costs of energy needed to support our business operations have escalated considerably.", "description": "The costs of energy needed to support our business operations have escalated considerably. To offset the increasing costs of utilities, bus fuel, oil and grease, etc.,", "is_active": 1}),
     *      @Response(200, body={"success": "Record has been updated."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {"name": {}}, "status_code": 422})
     * })
     */
    public function update(Request $request, $id)
    {
        $vehicle_extra_accessory_data = $request->only('name', 'short_description', 'description', 'is_active');
        $validator = Validator::make($vehicle_extra_accessory_data, VehicleExtraAccessory::GetValidationRule(), VehicleExtraAccessory::GetValidationMessage());
        $vehicle_extra_accessory = false;
        if ($request->has('id')) {
            $vehicle_extra_accessory = VehicleExtraAccessory::find($id);
            $vehicle_extra_accessory = ($request->id != $id) ? false : $vehicle_extra_accessory;
            if ($vehicle_extra_accessory->name !== $request->name) {
                $vehicle_extra_accessory_data['slug'] = EasySlug::generateUniqueSlug($request->name, 'extra_accessories');
            }
        }
        if ($validator->passes() && $vehicle_extra_accessory) {
            try {
                $vehicle_extra_accessory->update($vehicle_extra_accessory_data);
                return response()->json(['Success' => 'VehicleExtraAccessory has been updated'], 200);
            } catch (\Exception $e) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleExtraAccessory could not be updated. Please, try again.');
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleExtraAccessory could not be updated. Please, try again.', $validator->errors());
        }
    }

    /**
     * Delete the specified vehicle_extra_accessory.
     * Delete the vehicle_extra_accessory with a `id`.
     * @Delete("/admin/vehicle_extra_accessories/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"success": "Record Deleted."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function destroy($id)
    {
        $vehicle_extra_accessory = VehicleExtraAccessory::find($id);
        if (!$vehicle_extra_accessory) {
            return $this->response->errorNotFound("Invalid Request");
        } else {
            $vehicle_extra_accessory->delete();
        }
        return response()->json(['Success' => 'VehicleExtraAccessory deleted'], 200);
    }
}
