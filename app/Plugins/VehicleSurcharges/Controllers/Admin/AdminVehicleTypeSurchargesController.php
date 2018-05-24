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
use Plugins\VehicleSurcharges\Model\VehicleTypeSurcharge;
use JWTAuth;
use Validator;
use Plugins\VehicleSurcharges\Transformers\VehicleTypeSurchargeTransformer;
use DB;

/**
 * VehicleTypeSurcharges resource representation.
 * @Resource("Admin/AdminVehicleTypeSurcharges")
 */
class AdminVehicleTypeSurchargesController extends Controller
{
    /**
     * AdminVehicleTypeSurchargesController constructor.
     */
    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
        // Check the logged user role.
        $this->middleware('role');
    }

    /**
     * Show all vehicle_type_surcharges
     * Get a JSON representation of all the vehicle_type_surcharges.
     *
     * @Get("/admin/vehicle_type_surcharges?sort={sort}&sortby={sortby}&page={page}&q={q}")
     * @Parameters({
     *      @Parameter("sort", type="string", required=false, description="Sort the vehicle_type_surcharges list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort vehicle_type_surcharges by Ascending / Descending Order.", default=null),
     *      @Parameter("q", type="string", required=false, description="Search VehicleTypeSurcharges.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1)
     * })
     */
    public function index(Request $request)
    {
        $enabled_includes = array('vehicle_surcharge', 'discount_type', 'duration_type', 'vehicle_type');
        $vehicle_type_surcharges = VehicleTypeSurcharge::with($enabled_includes)
            ->select(DB::raw('vehicle_type_surcharges.*'))
            ->leftJoin(DB::raw('(select id,name from vehicle_types) as vehicle_type'), 'vehicle_type.id', '=', 'vehicle_type_surcharges.vehicle_type_id')
            ->leftJoin(DB::raw('(select id,name from surcharges) as vehicle_surcharge'), 'vehicle_surcharge.id', '=', 'vehicle_type_surcharges.surcharge_id')
            ->leftJoin(DB::raw('(select id,type from discount_types) as discount_type'), 'discount_type.id', '=', 'vehicle_type_surcharges.discount_type_id')
            ->leftJoin(DB::raw('(select id,name from duration_types) as duration_type'), 'duration_type.id', '=', 'vehicle_type_surcharges.duration_type_id')
            ->filterByRequest($request)->paginate(config('constants.ConstPageLimit'));
        return $this->response->paginator($vehicle_type_surcharges, (new VehicleTypeSurchargeTransformer)->setDefaultIncludes($enabled_includes));
    }

    /**
     * Edit the specified vehicle_type_surcharge.
     * Edit the vehicle_type_surcharge with a `id`.
     * @Get("/admin/vehicle_type_surcharges/{id}/edit")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body= {"id": 1, "vehicle_type_id": 1, "surcharge_id": 1, "discount_type_id": 1, "duration_type_id": 1, "rate": 200, "max_allowed_amount": 1000, "vehicle_type": {}, "vehicle_surcharge": {}, "duration_type": {}, "discount_type": {}}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function edit($id)
    {
        $enabled_includes = array('vehicle_surcharge', 'discount_type', 'duration_type', 'vehicle_type');
        $vehicle_type_surcharge = VehicleTypeSurcharge::with($enabled_includes)->find($id);
        if (!$vehicle_type_surcharge) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($vehicle_type_surcharge, (new VehicleTypeSurchargeTransformer)->setDefaultIncludes($enabled_includes));
    }
	
	/**
     * Show the specified vehicle_type_surcharge.
     * Show the vehicle_type_surcharge with a `id`.
     * @Get("/admin/vehicle_type_surcharges/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body= {"id": 1, "vehicle_type_id": 1, "surcharge_id": 1, "discount_type_id": 1, "duration_type_id": 1, "rate": 200, "max_allowed_amount": 1000, "vehicle_type": {}, "vehicle_surcharge": {}, "duration_type": {}, "discount_type": {}}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function show($id)
    {
        $enabled_includes = array('vehicle_surcharge', 'discount_type', 'duration_type', 'vehicle_type');
        $vehicle_type_surcharge = VehicleTypeSurcharge::with($enabled_includes)->find($id);
        if (!$vehicle_type_surcharge) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($vehicle_type_surcharge, (new VehicleTypeSurchargeTransformer)->setDefaultIncludes($enabled_includes));
    }

    /**
     * Store a new vehicle_type_surcharge.
     * Store a new vehicle_type_surcharge with a `name`, `short_description`, and `description`.
     * @Post("/admin/vehicle_type_surcharges")
     * @Transaction({
     *      @Request({"vehicle_type_id": 1, "surcharge_id": 1, "discount_type_id": 1, "duration_type_id": 1, "rate": 200, "max_allowed_amount": 1000}),
     *      @Response(200, body={"success": "Record has been added."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {"name": {}}, "status_code": 422})
     * })
     */
    public function store(Request $request)
    {
        $vehicle_type_surcharge_data = $request->only('vehicle_type_id', 'surcharge_id', 'rate', 'discount_type_id', 'duration_type_id', 'max_allowed_amount');
        $validator = Validator::make($vehicle_type_surcharge_data, VehicleTypeSurcharge::GetValidationRule(), VehicleTypeSurcharge::GetValidationMessage());
        $vehicle_type_surcharge_data['is_active'] = true;
        if ($validator->passes()) {
            $vehicle_type_surcharge = VehicleTypeSurcharge::create($vehicle_type_surcharge_data);
            if ($vehicle_type_surcharge) {
                return response()->json(['Success' => 'VehicleTypeSurcharge has been added'], 200);
            } else {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleTypeSurcharge could not be Added. Please, try again.');
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleTypeSurcharge could not be Added. Please, try again.', $validator->errors());
        }
    }


    /**
     * Update the specified vehicle_type_surcharge
     * Update the vehicle_type_surcharge with a `id`.
     * @Put("/admin/vehicle_type_surcharges/{id}")
     * @Transaction({
     *      @Request({"id": 1, "vehicle_type_id": 1, "surcharge_id": 1, "discount_type_id": 1, "duration_type_id": 1, "rate": 200, "max_allowed_amount": 1000}),
     *      @Response(200, body={"success": "Record has been updated."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {"name": {}}, "status_code": 422})
     * })
     */
    public function update(Request $request, $id)
    {
        $vehicle_type_surcharge_data = $request->only('vehicle_type_id', 'surcharge_id', 'rate', 'discount_type_id', 'duration_type_id', 'max_allowed_amount', 'is_active');
        $validator = Validator::make($vehicle_type_surcharge_data, VehicleTypeSurcharge::GetValidationRule(), VehicleTypeSurcharge::GetValidationMessage());
        $vehicle_type_surcharge = false;
        if ($request->has('id')) {
            $vehicle_type_surcharge = VehicleTypeSurcharge::find($id);
            $vehicle_type_surcharge = ($request->id != $id) ? false : $vehicle_type_surcharge;
        }
        if ($validator->passes() && $vehicle_type_surcharge) {
            try {
                $vehicle_type_surcharge->update($vehicle_type_surcharge_data);
                return response()->json(['Success' => 'VehicleTypeSurcharge has been updated'], 200);
            } catch (\Exception $e) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleTypeSurcharge could not be updated. Please, try again.');
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleTypeSurcharge could not be updated. Please, try again.', $validator->errors());
        }
    }

    /**
     * Delete the specified vehicle_type_surcharge.
     * Delete the vehicle_type_surcharge with a `id`.
     * @Delete("/admin/vehicle_type_surcharges/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"success": "Record Deleted."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function destroy($id)
    {
        $vehicle_type_surcharge = VehicleTypeSurcharge::find($id);
        if (!$vehicle_type_surcharge) {
            return $this->response->errorNotFound("Invalid Request");
        } else {
            $vehicle_type_surcharge->delete();
        }
        return response()->json(['Success' => 'VehicleTypeSurcharge deleted'], 200);
    }
}
