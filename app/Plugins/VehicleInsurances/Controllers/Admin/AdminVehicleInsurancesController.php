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
use App\Http\Controllers\Controller;
use Plugins\VehicleInsurances\Model\VehicleInsurance;
use JWTAuth;
use Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Plugins\VehicleInsurances\Transformers\AdminVehicleInsuranceTransformer;
use Plugins\VehicleInsurances\Transformers\VehicleInsuranceTransformer;
use EasySlug\EasySlug\EasySlugFacade as EasySlug;

/**
 * VehicleInsurances resource representation.
 * @Resource("Admin/AdminVehicleInsurances")
 */
class AdminVehicleInsurancesController extends Controller
{
    /**
     * AdminVehicleInsurancesController constructor.
     */
    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
        // Check the logged user role.
        $this->middleware('role');
    }

    /**
     * Show all vehicle_insurances
     * Get a JSON representation of all the vehicle_insurances.
     *
     * @Get("/admin/vehicle_insurances?sort={sort}&sortby={sortby}&page={page}&q={q}")
     * @Parameters({
     *      @Parameter("sort", type="string", required=false, description="Sort the vehicle_insurances list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort vehicle_insurances by Ascending / Descending Order.", default=null),
     *      @Parameter("q", type="string", required=false, description="Search VehicleInsurances.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1)
     * })
     */
    public function index(Request $request)
    {
        if($request->has('limit') && $request->limit == 'all') {
            $vehicle_insurance_count = VehicleInsurance::count();
            $vehicle_insurances = VehicleInsurance::filterByActiveRecord($request)->filterByRequest($request)->select('id', 'name')->paginate($vehicle_insurance_count);
            return $this->response->paginator($vehicle_insurances, new VehicleInsuranceTransformer);
        } else {
            $vehicle_insurances = VehicleInsurance::filterByRequest($request)->paginate(config('constants.ConstPageLimit'));
            return $this->response->paginator($vehicle_insurances, new AdminVehicleInsuranceTransformer);
        }
    }

    /**
     * Edit the specified vehicle_insurance.
     * Edit the vehicle_insurance with a `id`.
     * @Get("/admin/vehicle_insurances/{id}/edit")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body= {"id": 1, "name": "Service Insurance", "short_description": "The costs of service needed to support our business operations have escalated considerably.", "description": "The costs of service needed to support our business operations have escalated considerably. To offset the increasing costs of utilities, bus fuel, oil and grease, etc.,"}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function edit($id)
    {
        $vehicle_insurance = VehicleInsurance::find($id);
        if (!$vehicle_insurance) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($vehicle_insurance, (new AdminVehicleInsuranceTransformer));
    }

    /**
     * Show the specified vehicle_insurance.
     * Show the vehicle_insurance with a `id`.
     * @Get("/admin/vehicle_insurances/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body= {"id": 1, "name": "Service Insurance", "short_description": "The costs of service needed to support our business operations have escalated considerably.", "description": "The costs of service needed to support our business operations have escalated considerably. To offset the increasing costs of utilities, bus fuel, oil and grease, etc.,"}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function show($id)
    {
        $vehicle_insurance = VehicleInsurance::find($id);
        if (!$vehicle_insurance) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($vehicle_insurance, (new AdminVehicleInsuranceTransformer));
    }

    /**
     * Store a new vehicle_insurance.
     * Store a new vehicle_insurance with a `name`, `short_description`, and `description`.
     * @Post("/admin/vehicle_insurances")
     * @Transaction({
     *      @Request({"name": "Service Insurance", "short_description": "The costs of service needed to support our business operations have escalated considerably.", "description": "The costs of service needed to support our business operations have escalated considerably. To offset the increasing costs of utilities, bus fuel, oil and grease, etc.,"}),
     *      @Response(200, body={"success": "Record has been added."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {"name": {}}, "status_code": 422})
     * })
     */
    public function store(Request $request)
    {
        $vehicle_insurance_data = $request->only('name', 'short_description', 'description');
        $validator = Validator::make($vehicle_insurance_data, VehicleInsurance::GetValidationRule(), VehicleInsurance::GetValidationMessage());
        $vehicle_insurance_data['slug'] = EasySlug::generateUniqueSlug($request->name, 'insurances');
        if ($validator->passes()) {
            $vehicle_insurance_data['is_active'] = true;
            $vehicle_insurance = VehicleInsurance::create($vehicle_insurance_data);
            if ($vehicle_insurance) {
                return response()->json(['Success' => 'VehicleInsurance has been added'], 200);
            } else {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleInsurance could not be updated. Please, try again.');
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleInsurance could not be updated. Please, try again.', $validator->errors());
        }
    }


    /**
     * Update the specified vehicle_insurance
     * Update the vehicle_insurance with a `id`.
     * @Put("/admin/vehicle_insurances/{id}")
     * @Transaction({
     *      @Request({"id": 1, "name": "Energy Insurance", "short_description": "The costs of energy needed to support our business operations have escalated considerably.", "description": "The costs of energy needed to support our business operations have escalated considerably. To offset the increasing costs of utilities, bus fuel, oil and grease, etc.,", "is_active": 1}),
     *      @Response(200, body={"success": "Record has been updated."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {"name": {}}, "status_code": 422})
     * })
     */
    public function update(Request $request, $id)
    {
        $vehicle_insurance_data = $request->only('name', 'short_description', 'description', 'is_active');
        $validator = Validator::make($vehicle_insurance_data, VehicleInsurance::GetValidationRule(), VehicleInsurance::GetValidationMessage());
        $vehicle_insurance = false;
        if ($request->has('id')) {
            $vehicle_insurance = VehicleInsurance::find($id);
            $vehicle_insurance = ($request->id != $id) ? false : $vehicle_insurance;
            if ($vehicle_insurance->name !== $request->name) {
                $vehicle_insurance_data['slug'] = EasySlug::generateUniqueSlug($request->name, 'insurances');
            }
        }
        if ($validator->passes() && $vehicle_insurance) {
            try {
                $vehicle_insurance->update($vehicle_insurance_data);
                return response()->json(['Success' => 'VehicleInsurance has been updated'], 200);
            } catch (\Exception $e) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleInsurance could not be updated. Please, try again.');
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleInsurance could not be updated. Please, try again.', $validator->errors());
        }
    }

    /**
     * Delete the specified vehicle_insurance.
     * Delete the vehicle_insurance with a `id`.
     * @Delete("/admin/vehicle_insurances/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"success": "Record Deleted."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function destroy($id)
    {
        $vehicle_insurance = VehicleInsurance::find($id);
        if (!$vehicle_insurance) {
            return $this->response->errorNotFound("Invalid Request");
        } else {
            $vehicle_insurance->delete();
        }
        return response()->json(['Success' => 'VehicleInsurance deleted'], 200);
    }
}
