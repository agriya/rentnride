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
 
namespace Plugins\VehicleInsurances\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Plugins\VehicleInsurances\Model\VehicleTypeInsurance;
use JWTAuth;
use Validator;
use Plugins\VehicleInsurances\Transformers\VehicleTypeInsuranceTransformer;
use DB;

/**
 * VehicleTypeInsurances resource representation.
 * @Resource("Admin/AdminVehicleTypeInsurances")
 */
class AdminVehicleTypeInsurancesController extends Controller
{
   /**
     * AdminVehicleTypeInsurancesController constructor.
     */
    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
        // Check the logged user role.
        $this->middleware('role');
    }

    /**
     * Show all vehicle_type_insurances
     * Get a JSON representation of all the vehicle_type_insurances.
     *
     * @Get("/admin/vehicle_type_insurances?sort={sort}&sortby={sortby}&page={page}&q={q}")
     * @Parameters({
     *      @Parameter("sort", type="string", required=false, description="Sort the vehicle_type_insurances list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort vehicle_type_insurances by Ascending / Descending Order.", default=null),
     *      @Parameter("q", type="string", required=false, description="Search VehicleTypeInsurances.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1)
     * })
     */
    public function index(Request $request)
    {
        $vehicle_type_insurances_count = config('constants.ConstPageLimit');
        if($request->has('limit') && $request->limit == 'all') {
            $vehicle_type_insurances_count = VehicleModel::count();
            $vehicle_type_insurances = VehicleTypeInsurance::filterByActiveRecord($request)->filterByRequest($request)->paginate($vehicle_type_insurances_count);
            return $this->response->paginator($vehicle_type_insurances, new VehicleTypeInsuranceTransformer);
        }else{
            $enabled_includes = array('vehicle_insurance', 'discount_type', 'duration_type', 'vehicle_type');
            $vehicle_type_insurances = VehicleTypeInsurance::with($enabled_includes)
                ->select(DB::raw('vehicle_type_insurances.*'))
                ->leftJoin(DB::raw('(select id,name from vehicle_types) as vehicle_type'), 'vehicle_type.id', '=', 'vehicle_type_insurances.vehicle_type_id')
                ->leftJoin(DB::raw('(select id,name from insurances) as vehicle_insurance'), 'vehicle_insurance.id', '=', 'vehicle_type_insurances.insurance_id')
                ->leftJoin(DB::raw('(select id,type from discount_types) as discount_type'), 'discount_type.id', '=', 'vehicle_type_insurances.discount_type_id')
                ->leftJoin(DB::raw('(select id,name from duration_types) as duration_type'), 'duration_type.id', '=', 'vehicle_type_insurances.duration_type_id')
                ->filterByRequest($request)->paginate($vehicle_type_insurances_count);
            return $this->response->paginator($vehicle_type_insurances, (new VehicleTypeInsuranceTransformer)->setDefaultIncludes($enabled_includes));
        }

    }

    /**
     * Edit the specified vehicle_type_insurance.
     * Edit the vehicle_type_insurance with a `id`.
     * @Get("/admin/vehicle_type_insurances/{id}/edit")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body= {"id": 1, "vehicle_type_id": 1, "insurance_id": 1, "discount_type_id": 1, "duration_type_id": 1, "rate": 200, "max_allowed_amount": 1000, "vehicle_type": {}, "vehicle_insurance": {}, "duration_type": {}, "discount_type": {}}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function edit($id)
    {
        $enabled_includes = array('vehicle_insurance', 'discount_type', 'duration_type', 'vehicle_type');
        $vehicle_type_insurance = VehicleTypeInsurance::with($enabled_includes)->find($id);
        if (!$vehicle_type_insurance) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($vehicle_type_insurance, (new VehicleTypeInsuranceTransformer)->setDefaultIncludes($enabled_includes));
    }

    /**
     * Show the specified vehicle_type_insurance.
     * Show the vehicle_type_insurance with a `id`.
     * @Get("/admin/vehicle_type_insurances/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body= {"id": 1, "vehicle_type_id": 1, "insurance_id": 1, "discount_type_id": 1, "duration_type_id": 1, "rate": 200, "max_allowed_amount": 1000, "vehicle_type": {}, "vehicle_insurance": {}, "duration_type": {}, "discount_type": {}}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function show($id)
    {
        $enabled_includes = array('vehicle_insurance', 'discount_type', 'duration_type', 'vehicle_type');
        $vehicle_type_insurance = VehicleTypeInsurance::with($enabled_includes)->find($id);
        if (!$vehicle_type_insurance) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($vehicle_type_insurance, (new VehicleTypeInsuranceTransformer)->setDefaultIncludes($enabled_includes));
    }

    /**
     * Store a new vehicle_type_insurance.
     * Store a new vehicle_type_insurance with a `name`, `short_description`, and `description`.
     * @Post("/admin/vehicle_type_insurances")
     * @Transaction({
     *      @Request({"vehicle_type_id": 1, "insurance_id": 1, "discount_type_id": 1, "duration_type_id": 1, "rate": 200, "max_allowed_amount": 1000}),
     *      @Response(200, body={"success": "Record has been added."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {"name": {}}, "status_code": 422})
     * })
     */
    public function store(Request $request)
    {
        $vehicle_type_insurance_data = $request->only('vehicle_type_id', 'insurance_id', 'rate', 'discount_type_id', 'duration_type_id', 'max_allowed_amount');
        $validator = Validator::make($vehicle_type_insurance_data, VehicleTypeInsurance::GetValidationRule(), VehicleTypeInsurance::GetValidationMessage());
        $vehicle_type_insurance_data['is_active'] = true;
        if ($validator->passes()) {
            $vehicle_type_insurance = VehicleTypeInsurance::create($vehicle_type_insurance_data);
            if ($vehicle_type_insurance) {
                return response()->json(['Success' => 'VehicleTypeInsurance has been added'], 200);
            } else {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleTypeInsurance could not be added. Please, try again.');
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleTypeInsurance could not be added. Please, try again.', $validator->errors());
        }
    }


    /**
     * Update the specified vehicle_type_insurance
     * Update the vehicle_type_insurance with a `id`.
     * @Put("/admin/vehicle_type_insurances/{id}")
     * @Transaction({
     *      @Request({"id": 1, "vehicle_type_id": 1, "insurance_id": 1, "discount_type_id": 1, "duration_type_id": 1, "rate": 200, "max_allowed_amount": 1000}),
     *      @Response(200, body={"success": "Record has been updated."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {"name": {}}, "status_code": 422})
     * })
     */
    public function update(Request $request, $id)
    {
        $vehicle_type_insurance_data = $request->only('vehicle_type_id', 'insurance_id', 'rate', 'discount_type_id', 'duration_type_id', 'max_allowed_amount', 'is_active');
        $validator = Validator::make($vehicle_type_insurance_data, VehicleTypeInsurance::GetValidationRule(), VehicleTypeInsurance::GetValidationMessage());
        $vehicle_type_insurance = false;
        if ($request->has('id')) {
            $vehicle_type_insurance = VehicleTypeInsurance::find($id);
            $vehicle_type_insurance = ($request->id != $id) ? false : $vehicle_type_insurance;
        }
        if ($validator->passes() && $vehicle_type_insurance) {
            try {
                $vehicle_type_insurance->update($vehicle_type_insurance_data);
                return response()->json(['Success' => 'VehicleTypeInsurance has been updated'], 200);
            } catch (\Exception $e) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleTypeInsurance could not be updated. Please, try again.');
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleTypeInsurance could not be updated. Please, try again.', $validator->errors());
        }
    }

    /**
     * Delete the specified vehicle_type_insurance.
     * Delete the vehicle_type_insurance with a `id`.
     * @Delete("/admin/vehicle_type_insurances/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"success": "Record Deleted."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function destroy($id)
    {
        $vehicle_type_insurance = VehicleTypeInsurance::find($id);
        if (!$vehicle_type_insurance) {
            return $this->response->errorNotFound("Invalid Request");
        } else {
            $vehicle_type_insurance->delete();
        }
        return response()->json(['Success' => 'VehicleTypeInsurance deleted'], 200);
    }
}
