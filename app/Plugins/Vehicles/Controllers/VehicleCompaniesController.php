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
 
namespace Plugins\Vehicles\Controllers;


use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Plugins\Vehicles\Model\VehicleCompany;
use JWTAuth;
use Validator;
use Plugins\Vehicles\Transformers\VehicleCompanyTransformer;
use EasySlug\EasySlug\EasySlugFacade as EasySlug;


/**
 * Class VehicleCompaniesController
 * @package Plugins\Vehicles\Controllers
 */
class VehicleCompaniesController extends Controller
{

    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
    }

    /**
     * Show all vehicle companies.
     * Get a JSON representation of all the Vehicle Companies.
     *
     * @Get("/vehicle_companies?filter={filter}&sort={sort}&sortby={sortby}&q={q}")
     * @Parameters({
     *      @Parameter("filter", type="integer", required=false, description="Filter the Vehicle Companies list by status.", default=null),
     *      @Parameter("sort", type="string", required=false, description="Sort the Vehicle Companies list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort Vehicle Companies by Ascending / Descending Order.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1)
     * })
     */
    public function index(Request $request)
    {
        $vehicle_company_count = config('constants.ConstPageLimit');
        if ($request->has('type') && $request->type == 'list') {
            $vehicle_company_count = VehicleCompany::count();
        }
        $vehicle_companies = VehicleCompany::with('user')->filterByRequest($request)->paginate($vehicle_company_count);
        return $this->response->paginator($vehicle_companies, (new VehicleCompanyTransformer)->setDefaultIncludes(['user']));
    }

    /**
     * Store a new vehicle company.
     * @Post("/vehicle_companies")
     * @Transaction({
     *      @Request({}),
     *      @Response(200, body={"success": "Record has been added."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {"amount": {}}, "status_code": 422})
     * })
     */
    public function store(Request $request)
    {
        $vehicle_company_data = $request->only('name', 'address', 'latitude', 'longitude', 'phone', 'fax', 'mobile', 'email');
        $vehicle_company_data['slug'] = EasySlug::generateUniqueSlug($request->name, 'vehicle_companies');
        $user = $this->auth->user();
        if (!$user) {
            return $this->response->errorNotFound("Invalid Request");
        }
        $validation_rules = VehicleCompany::GetValidationRule();
        $vehicle_company_data['user_id'] = $user->id;
        $vehicle_company = VehicleCompany::where('user_id', $user->id)->first();
        if (!is_null($vehicle_company) && $vehicle_company->user_id == $vehicle_company_data['user_id']) {
            $validation_rules['user_id'] = 'required';
        }
        $validator = Validator::make($vehicle_company_data, $validation_rules, VehicleCompany::GetValidationMessage());
        if ($validator->passes()) {
            try {
                if($vehicle_company){
                    $vehicle_company->update($vehicle_company_data);
                    return $this->response->item($vehicle_company, (new VehicleCompanyTransformer));
                }else{
                    $vehicle_company_data['is_active'] = (config('vehicle.company_auto_approve')) ? 1 : 0;
                    VehicleCompany::create($vehicle_company_data);
                    return $this->response->item($vehicle_company, (new VehicleCompanyTransformer));
                }
            } catch (\Exception $e) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle company could not be added. Please, try again.', array($e->getMessage()));
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle company could not be added. Please, try again.', $validator->errors());
        }
    }

    /**
     * Edit the specified vehicle_company.
     * Edit the vehicle_company with a `id`.
     * @Get("/vehicle_companies/{id}/edit")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"id": 1}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function edit()
    {
        $user = $this->auth->user();
        $vehicle_company = VehicleCompany::where('user_id', $user->id)->first();
        if (!$user || !$vehicle_company || $user->id != $vehicle_company->user_id) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($vehicle_company, (new VehicleCompanyTransformer));
    }

    /**
     * Delete the specified Vehicle Company.
     * Delete the Vehicle Company with a `id`.
     * @Delete("/vehicle_companies/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"success": "Record Deleted."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function destroy($id)
    {
        $vehicle_company = VehicleCompany::find($id);
        $user = $this->auth->user();
        if (!$user || !$vehicle_company || $user->id != $vehicle_company->user_id) {
            return $this->response->errorNotFound("Invalid Request");
        } else {
            $vehicle_company->delete();
        }
        return response()->json(['Success' => 'Vehicle company deleted'], 200);
    }

    /**
     * Show the specified Vehicle Company.
     * Show the Vehicle Company with a `id`.
     * @Get("/vehicle_companies/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"id": 1, "amount": 1000, "user_id": 1, "name": "house for rent", "booking_type_id": 1, "description": "This house is for rent", "User": {}}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function show()
    {
        $user = $this->auth->user();
        $vehicle_company = VehicleCompany::where('user_id', $user->id)->first();
        if (!$user || !$vehicle_company || $user->id != $vehicle_company->user_id) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($vehicle_company, (new VehicleCompanyTransformer));
    }
}
