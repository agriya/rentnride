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
use Plugins\Vehicles\Model\VehicleCompany;
use JWTAuth;
use Validator;
use App\User;
use Plugins\Vehicles\Transformers\AdminVehicleCompanyTransformer;
use EasySlug\EasySlug\EasySlugFacade as EasySlug;
use DB;

/**
 * Class AdminVehicleCompaniesController
 * @package Plugins\Vehicles\Controllers\Admin
 */
class AdminVehicleCompaniesController extends Controller
{

    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
        // Check the logged user role.
        $this->middleware('role');
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
        $vehicle_companies = VehicleCompany::with('user')
            ->select(DB::raw('vehicle_companies.*'))
            ->leftJoin(DB::raw('(select id,username from users) as user'), 'user.id', '=', 'vehicle_companies.user_id')
            ->filterByRequest($request)->paginate($vehicle_company_count);
        return $this->response->paginator($vehicle_companies, (new AdminVehicleCompanyTransformer)->setDefaultIncludes(['user']));
    }

    /**
     * Store a new vehicle company.
     * @Post("/vehicle_companies")
     * @Transaction({
     *      @Request({"name":"compan", "address":"chennai", "latitude":"45.1265", "longitude":"45.1325", "fax":"d336256", "phone":"546546546", "mobile":"54654654", "email":"sdfds@df.cin", "is_active":"1", "user_id":1}),
     *      @Response(200, body={"success": "Record has been added."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {"amount": {}}, "status_code": 422})
     * })
     */
    public function store(Request $request)
    {
        $vehicle_company_data = $request->only('user_id','name', 'address', 'latitude', 'longitude', 'phone', 'fax', 'mobile', 'email', 'is_active');
        $vehicle_company_data['slug'] = EasySlug::generateUniqueSlug($request->name, 'vehicle_companies');
        $validator = Validator::make($vehicle_company_data, VehicleCompany::GetValidationRule(), VehicleCompany::GetValidationMessage());
        if ($validator->passes()) {
            try {
                $vehicle_company = VehicleCompany::create($vehicle_company_data);
                if ($vehicle_company) {
                    return response()->json(['Success' => 'Vehicle company has been added'], 200);
                } else {
                    throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle company could not be added. Please, try again.');
                }
            } catch (\Exception $e) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle company could not be added. Please, try again.',
                    array($e->getMessage()));
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
    public function edit($id)
    {
        $vehicle_company = VehicleCompany::find($id);
        if (!$vehicle_company) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($vehicle_company, (new AdminVehicleCompanyTransformer));
    }

    /**
     * Update the specified Vehicle Company.
     * Update the Vehicle Company with a `id`.
     * @Put("/vehicle_companies/{id}")
     * @Transaction({
     *      @Request({"id": 1, "name":"compan", "address":"chennai", "latitude":"45.1265", "longitude":"45.1325", "fax":"d336256", "phone":"546546546", "mobile":"54654654", "email":"sdfds@df.cin", "is_active":"1", "user_id":1}),
     *      @Response(200, body={"success": "Record has been updated."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {}, "status_code": 422})
     * })
     */
    public function update(Request $request, $id)
    {
        $vehicle_company_data = $request->only('id', 'name', 'address', 'latitude', 'longitude', 'fax', 'phone', 'mobile', 'email', 'is_active', 'user_id');
        $vehicle_company = false;
        $validation_rules = VehicleCompany::GetValidationRule();
        if ($request->has('id')) {
            $vehicle_company = VehicleCompany::find($id);
            $vehicle_company = ($request->id != $id) ? false : $vehicle_company;
            if ($vehicle_company->name !== $request->name) {
                $vehicle_company_data['slug'] = EasySlug::generateUniqueSlug($request->name, 'vehicle_companies');
            } else {
                $vehicle_company_data['slug'] = $vehicle_company->slug;
            }
            if ($vehicle_company->user_id == $vehicle_company_data['user_id']) {
                $validation_rules['user_id'] = 'required';
            }
        }
        $validator = Validator::make($vehicle_company_data, $validation_rules, VehicleCompany::GetValidationMessage());
        if ($validator->passes() && $vehicle_company) {
            try {
                $vehicle_company->update($vehicle_company_data);
                return response()->json(['Success' => 'Vehicle Company has been updated'], 200);
            } catch (\Exception $e) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle company could not be updated. Please, try again.',
                    array($e->getMessage()));
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle Company could not be updated. Please, try again.', $validator->errors());
        }
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
        if (!$vehicle_company) {
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
    public function show($id)
    {
        $vehicle_company = VehicleCompany::with('user')->find($id);
        if (!$vehicle_company) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($vehicle_company, (new AdminVehicleCompanyTransformer)->setDefaultIncludes(['user']));
    }

    /**
     * Deactivate the company.
     * @Put("/vehicle_companies/{id}/deactive")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"success": "Record has been deactivated!."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404}),
     * })
     */
    public function deactive(Request $request, $id)
    {
        $vehicle_company = VehicleCompany::find($id);
        if (!$vehicle_company) {
            return $this->response->errorNotFound("Invalid Request");
        } else {
            $user_data['is_active'] = 0;
            if ($vehicle_company->update($user_data)) {
                return response()->json(['Success' => 'Record has been deactivated!'], 200);
            }
        }
    }

    /**
     * Activate the company.
     * @Put("/vehicle_companies/{id}/active")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"success": "Record has been activated!."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404}),
     * })
     */
    public function active(Request $request, $id)
    {
        $vehicle_company = VehicleCompany::find($id);
        if (!$vehicle_company) {
            return $this->response->errorNotFound("Invalid Request");
        } else {
            $user_data['is_active'] = 1;
            if ($vehicle_company->update($user_data)) {
                return response()->json(['Success' => 'Record has been activated!'], 200);
            }
        }
    }

    /**
     * Rejected the company.
     * @Put("/vehicle_companies/{id}/reject")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"success": "Record has been activated!."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404}),
     * })
     */
    public function reject(Request $request, $id)
    {
        $vehicle_company = VehicleCompany::find($id);
        if (!$vehicle_company) {
            return $this->response->errorNotFound("Invalid Request");
        } else {
            $user_data['is_active'] = 2;
            if ($vehicle_company->update($user_data)) {
                return response()->json(['Success' => 'Record has been rejected!'], 200);
            }
        }
    }
}
