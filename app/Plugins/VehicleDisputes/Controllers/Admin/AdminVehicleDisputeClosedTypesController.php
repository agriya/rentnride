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
 
namespace Plugins\VehicleDisputes\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;
use Validator;
use Plugins\VehicleDisputes\Model\VehicleDisputeClosedType;
use Plugins\VehicleDisputes\Transformers\VehicleDisputeClosedTypeTransformer;
use DB;

/**
 * Class AdminVehicleDisputeClosedTypesController
 * @package Plugins\VehicleDisputes\Controllers
 */
class AdminVehicleDisputeClosedTypesController extends Controller
{
    /**
     * AdminVehicleDisputeClosedTypesController constructor.
     */
    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
        // Check the logged user role.
        $this->middleware('role');
    }

    /**
     * Show all VehicleDisputeClosedTypes
     * Get a JSON representation of all the VehicleDisputeClosedTypes.
     *
     * @Get("/vehicle_dispute_closed_types?sort={sort}&sortby={sortby}&page={page}")
     * @Parameters({
     *      @Parameter("sort", type="string", required=false, description="Sort the disputes list by sort key.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort disputes by Ascending / Descending Order.", default=null)
     * })
     */
    public function index(Request $request)
    {
        $vehicle_dispute_closed_types = VehicleDisputeClosedType::with(['dispute_type'])
            ->select(DB::raw('dispute_closed_types.*'))
            ->leftJoin(DB::raw('(select id,name from dispute_types) as dispute_type'), 'dispute_type.id', '=', 'dispute_closed_types.dispute_type_id')
            ->filterByRequest($request)->paginate(config('constants.ConstPageLimit'));
        return $this->response->paginator($vehicle_dispute_closed_types, (new VehicleDisputeClosedTypeTransformer)->setDefaultIncludes(['dispute_type']));
    }

    /**
     * Edit the specified dispute_closed_type.
     * Edit the dispute_closed_type with a `id`.
     * @Get("/vehicle_dispute_closed_types/{id}/edit")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"id": 1, "name": "Favor Booker", "dispute_type_id": 1, "is_booker": 1, "resolved_type": "refunded", "reason": "Property doesn't match with the one mentioned in property specification", "DisputeType": {}}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function edit($id)
    {
        $dispute_closed_type = VehicleDisputeClosedType::with(['dispute_type'])->find($id);
        if (!$dispute_closed_type) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($dispute_closed_type, (new VehicleDisputeClosedTypeTransformer)->setDefaultIncludes(['dispute_type']));
    }

    /**
     * Update the specified dispute closed type.
     * Update the dispute_closed_type with a `id`.
     * @Put("/vehicle_dispute_closed_types/{id}")
     * @Transaction({
     *      @Request({"id": 1, "name": "Favor Booker", "dispute_type_id": 1, "is_booker": 1, "resolved_type": "refunded", "reason": "Property doesn't match with the one mentioned in property specification"}),
     *      @Response(200, body={"success": "Record has been updated."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {"name": {}}, "status_code": 422})
     * })
     */
    public function update(Request $request, $id)
    {
        $dispute_closed_type_data = $request->only('name', 'dispute_type_id', 'is_booker', 'resolved_type', 'reason');
        $validator = Validator::make($dispute_closed_type_data, VehicleDisputeClosedType::GetValidationRule(), VehicleDisputeClosedType::GetValidationMessage());
        $dispute_closed_type = false;
        if ($request->has('id')) {
            $dispute_closed_type = VehicleDisputeClosedType::find($id);
            $dispute_closed_type = ($request->id != $id) ? false : $dispute_closed_type;
        }
        if ($validator->passes() && $dispute_closed_type) {
            try {
                $dispute_closed_type->update($dispute_closed_type_data);
                return response()->json(['Success' => 'VehicleDisputeClosedType has been updated'], 200);
            } catch (\Exception $e) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleDisputeClosedType could not be updated. Please, try again.');
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleDisputeClosedType could not be updated. Please, try again.', $validator->errors());
        }
    }
}
