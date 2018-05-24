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
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Plugins\VehicleTaxes\Model\VehicleTypeTax;
use JWTAuth;
use Validator;
use Plugins\VehicleTaxes\Transformers\VehicleTypeTaxTransformer;
use DB;

/**
 * VehicleTypeTaxes resource representation.
 * @Resource("Admin/AdminVehicleTypeTaxes")
 */
class AdminVehicleTypeTaxesController extends Controller
{
    /**
     * AdminVehicleTypeTaxesController constructor.
     */
    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
        // Check the logged user role.
        $this->middleware('role');
    }

    /**
     * Show all vehicle_type_taxes
     * Get a JSON representation of all the vehicle_type_taxes.
     *
     * @Get("/admin/vehicle_type_taxes?sort={sort}&sortby={sortby}&page={page}&q={q}")
     * @Parameters({
     *      @Parameter("sort", type="string", required=false, description="Sort the vehicle_type_taxes list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort vehicle_type_taxes by Ascending / Descending Order.", default=null),
     *      @Parameter("q", type="string", required=false, description="Search VehicleTypeTaxes.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1)
     * })
     */
    public function index(Request $request)
    {
        $enabled_includes = array('vehicle_tax', 'discount_type', 'duration_type', 'vehicle_type');
        $vehicle_type_taxes = VehicleTypeTax::with($enabled_includes)
            ->select(DB::raw('vehicle_type_taxes.*'))
            ->leftJoin(DB::raw('(select id,name from vehicle_types) as vehicle_type'), 'vehicle_type.id', '=', 'vehicle_type_taxes.vehicle_type_id')
            ->leftJoin(DB::raw('(select id,name from taxes) as vehicle_tax'), 'vehicle_tax.id', '=', 'vehicle_type_taxes.tax_id')
            ->leftJoin(DB::raw('(select id,type from discount_types) as discount_type'), 'discount_type.id', '=', 'vehicle_type_taxes.discount_type_id')
            ->leftJoin(DB::raw('(select id,name from duration_types) as duration_type'), 'duration_type.id', '=', 'vehicle_type_taxes.duration_type_id')
            ->filterByRequest($request)->paginate(config('constants.ConstPageLimit'));
        return $this->response->paginator($vehicle_type_taxes, (new VehicleTypeTaxTransformer)->setDefaultIncludes($enabled_includes));
    }

    /**
     * Edit the specified vehicle_type_tax.
     * Edit the vehicle_type_tax with a `id`.
     * @Get("/admin/vehicle_type_taxes/{id}/edit")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body= {"id": 1, "vehicle_type_id": 1, "tax_id": 1, "discount_type_id": 1, "duration_type_id": 1, "rate": 200, "max_allowed_amount": 1000, "vehicle_type": {}, "vehicle_tax": {}, "duration_type": {}, "discount_type": {}}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function edit($id)
    {
        $enabled_includes = array('vehicle_tax', 'discount_type', 'duration_type', 'vehicle_type');
        $vehicle_type_tax = VehicleTypeTax::with($enabled_includes)->find($id);
        if (!$vehicle_type_tax) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($vehicle_type_tax, (new VehicleTypeTaxTransformer)->setDefaultIncludes($enabled_includes));
    }

    /**
     * Show the specified vehicle_type_tax.
     * Show the vehicle_type_tax with a `id`.
     * @Get("/admin/vehicle_type_taxes/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body= {"id": 1, "vehicle_type_id": 1, "tax_id": 1, "discount_type_id": 1, "duration_type_id": 1, "rate": 200, "max_allowed_amount": 1000, "vehicle_type": {}, "vehicle_tax": {}, "duration_type": {}, "discount_type": {}}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function show($id)
    {
        $enabled_includes = array('vehicle_tax', 'discount_type', 'duration_type', 'vehicle_type');
        $vehicle_type_tax = VehicleTypeTax::with($enabled_includes)->find($id);
        if (!$vehicle_type_tax) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($vehicle_type_tax, (new VehicleTypeTaxTransformer)->setDefaultIncludes($enabled_includes));
    }

    /**
     * Store a new vehicle_type_tax.
     * Store a new vehicle_type_tax with a `name`, `short_description`, and `description`.
     * @Post("/admin/vehicle_type_taxes")
     * @Transaction({
     *      @Request({"vehicle_type_id": 1, "tax_id": 1, "discount_type_id": 1, "duration_type_id": 1, "rate": 200, "max_allowed_amount": 1000}),
     *      @Response(200, body={"success": "Record has been added."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {"name": {}}, "status_code": 422})
     * })
     */
    public function store(Request $request)
    {
        $vehicle_type_tax_data = $request->only('vehicle_type_id', 'tax_id', 'rate', 'discount_type_id', 'duration_type_id', 'max_allowed_amount');
        $validator = Validator::make($vehicle_type_tax_data, VehicleTypeTax::GetValidationRule(), VehicleTypeTax::GetValidationMessage());
        $vehicle_type_tax_data['is_active'] = true;
        if ($validator->passes()) {
            $vehicle_type_tax = VehicleTypeTax::create($vehicle_type_tax_data);
            if ($vehicle_type_tax) {
                return response()->json(['Success' => 'VehicleTypeTax has been added'], 200);
            } else {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleTypeTax could not be added. Please, try again.');
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleTypeTax could not be added. Please, try again.', $validator->errors());
        }
    }


    /**
     * Update the specified vehicle_type_tax
     * Update the vehicle_type_tax with a `id`.
     * @Put("/admin/vehicle_type_taxes/{id}")
     * @Transaction({
     *      @Request({"id": 1, "vehicle_type_id": 1, "tax_id": 1, "discount_type_id": 1, "duration_type_id": 1, "rate": 200, "max_allowed_amount": 1000}),
     *      @Response(200, body={"success": "Record has been updated."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {"name": {}}, "status_code": 422})
     * })
     */
    public function update(Request $request, $id)
    {
        $vehicle_type_tax_data = $request->only('vehicle_type_id', 'tax_id', 'rate', 'discount_type_id', 'duration_type_id', 'max_allowed_amount', 'is_active');
        $validator = Validator::make($vehicle_type_tax_data, VehicleTypeTax::GetValidationRule(), VehicleTypeTax::GetValidationMessage());
        $vehicle_type_tax = false;
        if ($request->has('id')) {
            $vehicle_type_tax = VehicleTypeTax::find($id);
            $vehicle_type_tax = ($request->id != $id) ? false : $vehicle_type_tax;
        }
        if ($validator->passes() && $vehicle_type_tax) {
            try {
                $vehicle_type_tax->update($vehicle_type_tax_data);
                return response()->json(['Success' => 'VehicleTypeTax has been updated'], 200);
            } catch (\Exception $e) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleTypeTax could not be updated. Please, try again.');
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleTypeTax could not be updated. Please, try again.', $validator->errors());
        }
    }

    /**
     * Delete the specified vehicle_type_tax.
     * Delete the vehicle_type_tax with a `id`.
     * @Delete("/admin/vehicle_type_taxes/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"success": "Record Deleted."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function destroy($id)
    {
        $vehicle_type_tax = VehicleTypeTax::find($id);
        if (!$vehicle_type_tax) {
            return $this->response->errorNotFound("Invalid Request");
        } else {
            $vehicle_type_tax->delete();
        }
        return response()->json(['Success' => 'VehicleTypeTax deleted'], 200);
    }
}
