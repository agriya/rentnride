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
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Plugins\VehicleExtraAccessories\Model\VehicleTypeExtraAccessory;
use JWTAuth;
use Validator;
use Plugins\VehicleExtraAccessories\Transformers\VehicleTypeExtraAccessoryTransformer;
use DB;

/**
 * VehicleTypeExtraAccessories resource representation.
 * @Resource("Admin/AdminVehicleTypeExtraAccessories")
 */
class AdminVehicleTypeExtraAccessoriesController extends Controller
{
    /**
     * AdminVehicleTypeExtraAccessoriesController constructor.
     */
    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
        // Check the logged user role.
        $this->middleware('role');
    }

    /**
     * Show all vehicle_type_extra_accessories
     * Get a JSON representation of all the vehicle_type_extra_accessories.
     *
     * @Get("/admin/vehicle_type_extra_accessories?sort={sort}&sortby={sortby}&page={page}&q={q}")
     * @Parameters({
     *      @Parameter("sort", type="string", required=false, description="Sort the vehicle_type_extra_accessories list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort vehicle_type_extra_accessories by Ascending / Descending Order.", default=null),
     *      @Parameter("q", type="string", required=false, description="Search VehicleTypeExtraAccessories.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1)
     * })
     */
    public function index(Request $request)
    {
        $enabled_includes = array('vehicle_extra_accessory', 'discount_type', 'duration_type', 'vehicle_type');
        $vehicle_type_extra_accessories = VehicleTypeExtraAccessory::with($enabled_includes)
            ->select(DB::raw('vehicle_type_extra_accessories.*'))
            ->leftJoin(DB::raw('(select id,name from vehicle_types) as vehicle_type'), 'vehicle_type.id', '=', 'vehicle_type_extra_accessories.vehicle_type_id')
            ->leftJoin(DB::raw('(select id,name from extra_accessories) as vehicle_extra_accessory'), 'vehicle_extra_accessory.id', '=', 'vehicle_type_extra_accessories.extra_accessory_id')
            ->leftJoin(DB::raw('(select id,type from discount_types) as discount_type'), 'discount_type.id', '=', 'vehicle_type_extra_accessories.discount_type_id')
            ->leftJoin(DB::raw('(select id,name from duration_types) as duration_type'), 'duration_type.id', '=', 'vehicle_type_extra_accessories.duration_type_id')
            ->filterByRequest($request)->paginate(config('constants.ConstPageLimit'));
        return $this->response->paginator($vehicle_type_extra_accessories, (new VehicleTypeExtraAccessoryTransformer)->setDefaultIncludes($enabled_includes));
    }

    /**
     * Edit the specified vehicle_type_extra_accessory.
     * Edit the vehicle_type_extra_accessory with a `id`.
     * @Get("/admin/vehicle_type_extra_accessories/{id}/edit")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body= {"id": 1, "vehicle_type_id": 1, "extra_accessory_id": 1, "discount_type_id": 1, "duration_type_id": 1, "rate": 200, "max_allowed_amount": 1000, "vehicle_type": {}, "vehicle_extra_accessory": {}, "duration_type": {}, "discount_type": {}}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function edit($id)
    {
        $enabled_includes = array('vehicle_extra_accessory', 'discount_type', 'duration_type', 'vehicle_type');
        $vehicle_type_extra_accessory = VehicleTypeExtraAccessory::with($enabled_includes)->find($id);
        if (!$vehicle_type_extra_accessory) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($vehicle_type_extra_accessory, (new VehicleTypeExtraAccessoryTransformer)->setDefaultIncludes($enabled_includes));
    }

    /**
     * Show the specified vehicle_type_extra_accessory.
     * Show the vehicle_type_extra_accessory with a `id`.
     * @Get("/admin/vehicle_type_extra_accessories/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body= {"id": 1, "vehicle_type_id": 1, "extra_accessory_id": 1, "discount_type_id": 1, "duration_type_id": 1, "rate": 200, "max_allowed_amount": 1000, "vehicle_type": {}, "vehicle_extra_accessory": {}, "duration_type": {}, "discount_type": {}}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function show($id)
    {
        $enabled_includes = array('vehicle_extra_accessory', 'discount_type', 'duration_type', 'vehicle_type');
        $vehicle_type_extra_accessory = VehicleTypeExtraAccessory::with($enabled_includes)->find($id);
        if (!$vehicle_type_extra_accessory) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($vehicle_type_extra_accessory, (new VehicleTypeExtraAccessoryTransformer)->setDefaultIncludes($enabled_includes));
    }

    /**
     * Store a new vehicle_type_extra_accessory.
     * Store a new vehicle_type_extra_accessory with a `name`, `short_description`, and `description`.
     * @Post("/admin/vehicle_type_extra_accessories")
     * @Transaction({
     *      @Request({"vehicle_type_id": 1, "extra_accessory_id": 1, "discount_type_id": 1, "duration_type_id": 1, "rate": 200, "max_allowed_amount": 1000}),
     *      @Response(200, body={"success": "Record has been added."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {"name": {}}, "status_code": 422})
     * })
     */
    public function store(Request $request)
    {
        $vehicle_type_extra_accessory_data = $request->only('vehicle_type_id', 'extra_accessory_id', 'rate', 'discount_type_id', 'duration_type_id', 'max_allowed_amount', 'deposit_amount', 'is_active');
        $validator = Validator::make($vehicle_type_extra_accessory_data, VehicleTypeExtraAccessory::GetValidationRule(), VehicleTypeExtraAccessory::GetValidationMessage());
        if ($validator->passes()) {
			$vehicle_type_extra_accessory_data['is_active'] = true;
            $vehicle_type_extra_accessory = VehicleTypeExtraAccessory::create($vehicle_type_extra_accessory_data);
            if ($vehicle_type_extra_accessory) {
                return response()->json(['Success' => 'VehicleTypeExtraAccessory has been added'], 200);
            } else {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleTypeExtraAccessory could not be added. Please, try again.');
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleTypeExtraAccessory could not be added. Please, try again.', $validator->errors());
        }
    }


    /**
     * Update the specified vehicle_type_extra_accessory
     * Update the vehicle_type_extra_accessory with a `id`.
     * @Put("/admin/vehicle_type_extra_accessories/{id}")
     * @Transaction({
     *      @Request({"id": 1, "vehicle_type_id": 1, "extra_accessory_id": 1, "discount_type_id": 1, "duration_type_id": 1, "rate": 200, "max_allowed_amount": 1000}),
     *      @Response(200, body={"success": "Record has been updated."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {"name": {}}, "status_code": 422})
     * })
     */
    public function update(Request $request, $id)
    {
        $vehicle_type_extra_accessory_data = $request->only('vehicle_type_id', 'extra_accessory_id', 'rate', 'discount_type_id', 'duration_type_id', 'max_allowed_amount', 'deposit_amount', 'is_active');
        $validator = Validator::make($vehicle_type_extra_accessory_data, VehicleTypeExtraAccessory::GetValidationRule(), VehicleTypeExtraAccessory::GetValidationMessage());
        $vehicle_type_extra_accessory = false;
        if ($request->has('id')) {
            $vehicle_type_extra_accessory = VehicleTypeExtraAccessory::find($id);
            $vehicle_type_extra_accessory = ($request->id != $id) ? false : $vehicle_type_extra_accessory;
        }
        if ($validator->passes() && $vehicle_type_extra_accessory) {
            try {
                $vehicle_type_extra_accessory->update($vehicle_type_extra_accessory_data);
                return response()->json(['Success' => 'VehicleTypeExtraAccessory has been updated'], 200);
            } catch (\Exception $e) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleTypeExtraAccessory could not be updated. Please, try again.');
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleTypeExtraAccessory could not be updated. Please, try again.', $validator->errors());
        }
    }

    /**
     * Delete the specified vehicle_type_extra_accessory.
     * Delete the vehicle_type_extra_accessory with a `id`.
     * @Delete("/admin/vehicle_type_extra_accessories/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"success": "Record Deleted."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function destroy($id)
    {
        $vehicle_type_extra_accessory = VehicleTypeExtraAccessory::find($id);
        if (!$vehicle_type_extra_accessory) {
            return $this->response->errorNotFound("Invalid Request");
        } else {
            $vehicle_type_extra_accessory->delete();
        }
        return response()->json(['Success' => 'VehicleTypeExtraAccessory deleted'], 200);
    }
}
