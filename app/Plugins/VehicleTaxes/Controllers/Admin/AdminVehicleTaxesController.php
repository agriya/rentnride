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
 

namespace Plugins\VehicleTaxes\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Plugins\VehicleTaxes\Model\VehicleTax;
use JWTAuth;
use Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Plugins\VehicleTaxes\Transformers\AdminVehicleTaxTransformer;
use Plugins\VehicleTaxes\Transformers\VehicleTaxTransformer;
use EasySlug\EasySlug\EasySlugFacade as EasySlug;

/**
 * VehicleTaxes resource representation.
 * @Resource("Admin/AdminVehicleTaxes")
 */
class AdminVehicleTaxesController extends Controller
{
    /**
     * AdminVehicleTaxesController constructor.
     */
    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
        // Check the logged user role.
        $this->middleware('role');
    }

    /**
     * Show all vehicle_taxes
     * Get a JSON representation of all the vehicle_taxes.
     *
     * @Get("/admin/vehicle_taxes?sort={sort}&sortby={sortby}&page={page}&q={q}")
     * @Parameters({
     *      @Parameter("sort", type="string", required=false, description="Sort the vehicle_taxes list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort vehicle_taxes by Ascending / Descending Order.", default=null),
     *      @Parameter("q", type="string", required=false, description="Search VehicleTaxes.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1)
     * })
     */
    public function index(Request $request)
    {
        if($request->has('limit') && $request->limit == 'all') {
            $vehicle_tax_count = VehicleTax::count();
            $vehicle_taxes = VehicleTax::filterByActiveRecord($request)->filterByRequest($request)->select('id', 'name')->paginate($vehicle_tax_count);
            return $this->response->paginator($vehicle_taxes, new VehicleTaxTransformer);
        } else {
            $vehicle_taxes = VehicleTax::filterByRequest($request)->paginate(config('constants.ConstPageLimit'));
            return $this->response->paginator($vehicle_taxes, new AdminVehicleTaxTransformer);
        }
    }

    /**
     * Edit the specified vehicle_tax.
     * Edit the vehicle_tax with a `id`.
     * @Get("/admin/vehicle_taxes/{id}/edit")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body= {"id": 1, "name": "Service Tax", "short_description": "The costs of service needed to support our business operations have escalated considerably.", "description": "The costs of service needed to support our business operations have escalated considerably. To offset the increasing costs of utilities, bus fuel, oil and grease, etc.,"}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function edit($id)
    {
        $vehicle_tax = VehicleTax::find($id);
        if (!$vehicle_tax) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($vehicle_tax, (new AdminVehicleTaxTransformer));
    }

    /**
     * Show the specified vehicle_tax.
     * Show the vehicle_tax with a `id`.
     * @Get("/admin/vehicle_taxes/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body= {"id": 1, "name": "Service Tax", "short_description": "The costs of service needed to support our business operations have escalated considerably.", "description": "The costs of service needed to support our business operations have escalated considerably. To offset the increasing costs of utilities, bus fuel, oil and grease, etc.,"}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function show($id)
    {
        $vehicle_tax = VehicleTax::find($id);
        if (!$vehicle_tax) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($vehicle_tax, (new AdminVehicleTaxTransformer));
    }

    /**
     * Store a new vehicle_tax.
     * Store a new vehicle_tax with a `name`, `short_description`, and `description`.
     * @Post("/admin/vehicle_taxes")
     * @Transaction({
     *      @Request({"name": "Service Tax", "short_description": "The costs of service needed to support our business operations have escalated considerably.", "description": "The costs of service needed to support our business operations have escalated considerably. To offset the increasing costs of utilities, bus fuel, oil and grease, etc.,"}),
     *      @Response(200, body={"success": "Record has been added."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {"name": {}}, "status_code": 422})
     * })
     */
    public function store(Request $request)
    {
        $vehicle_tax_data = $request->only('name', 'short_description', 'description');
        $validator = Validator::make($vehicle_tax_data, VehicleTax::GetValidationRule(), VehicleTax::GetValidationMessage());
        $vehicle_tax_data['slug'] = EasySlug::generateUniqueSlug($request->name, 'taxes');
        if ($validator->passes()) {
            $vehicle_tax_data['is_active'] = true;
            $vehicle_tax = VehicleTax::create($vehicle_tax_data);
            if ($vehicle_tax) {
                return response()->json(['Success' => 'VehicleTax has been added'], 200);
            } else {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleTax could not be updated. Please, try again.');
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleTax could not be updated. Please, try again.', $validator->errors());
        }
    }


    /**
     * Update the specified vehicle_tax
     * Update the vehicle_tax with a `id`.
     * @Put("/admin/vehicle_taxes/{id}")
     * @Transaction({
     *      @Request({"id": 1, "name": "Energy Tax", "short_description": "The costs of energy needed to support our business operations have escalated considerably.", "description": "The costs of energy needed to support our business operations have escalated considerably. To offset the increasing costs of utilities, bus fuel, oil and grease, etc.,", "is_active": 1}),
     *      @Response(200, body={"success": "Record has been updated."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {"name": {}}, "status_code": 422})
     * })
     */
    public function update(Request $request, $id)
    {
        $vehicle_tax_data = $request->only('name', 'short_description', 'description', 'is_active');
        $validator = Validator::make($vehicle_tax_data, VehicleTax::GetValidationRule(), VehicleTax::GetValidationMessage());
        $vehicle_tax = false;
        if ($request->has('id')) {
            $vehicle_tax = VehicleTax::find($id);
            $vehicle_tax = ($request->id != $id) ? false : $vehicle_tax;
            if ($vehicle_tax->name !== $request->name) {
                $vehicle_tax_data['slug'] = EasySlug::generateUniqueSlug($request->name, 'taxes');
            }
        }
        if ($validator->passes() && $vehicle_tax) {
            try {
                $vehicle_tax->update($vehicle_tax_data);
                return response()->json(['Success' => 'VehicleTax has been updated'], 200);
            } catch (\Exception $e) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleTax could not be updated. Please, try again.');
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleTax could not be updated. Please, try again.', $validator->errors());
        }
    }

    /**
     * Delete the specified vehicle_tax.
     * Delete the vehicle_tax with a `id`.
     * @Delete("/admin/vehicle_taxes/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"success": "Record Deleted."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function destroy($id)
    {
        $vehicle_tax = VehicleTax::find($id);
        if (!$vehicle_tax) {
            return $this->response->errorNotFound("Invalid Request");
        } else {
            $vehicle_tax->delete();
        }
        return response()->json(['Success' => 'VehicleTax deleted'], 200);
    }
}
