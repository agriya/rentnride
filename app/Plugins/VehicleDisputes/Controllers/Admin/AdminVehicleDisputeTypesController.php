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
use Plugins\VehicleDisputes\Model\VehicleDisputeType;
use Plugins\VehicleDisputes\Transformers\VehicleDisputeTypeTransformer;

/**
 * Class AdminVehicleDisputeTypesController
 * @package Plugins\VehicleDisputes\Controllers
 */
class AdminVehicleDisputeTypesController extends Controller
{
    /**
     * AdminVehicleDisputeTypesController constructor.
     */
    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
        // Check the logged user role.
        $this->middleware('role');
    }

    /**
     * Show all VehicleDisputeTypes
     * Get a JSON representation of all the VehicleDisputeTypes.
     *
     * @Get("/vehicle_disputes?sort={sort}&sortby={sortby}&page={page}&q={q}")
     * @Parameters({
     *      @Parameter("sort", type="string", required=false, description="Sort the disputes list by sort key.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort disputes by Ascending / Descending Order.", default=null)
     * })
     */
    public function index(Request $request)
    {
        $vehicle_dispute_types = VehicleDisputeType::filterByRequest($request)->paginate(config('constants.ConstPageLimit'));
        return $this->response->paginator($vehicle_dispute_types, (new VehicleDisputeTypeTransformer));
    }

    /**
     * Edit the specified dispute type.
     * Edit the dispute type with a `id`.
     * @Get("/vehicle_dispute_types/{id}/edit")
     * @Transaction({
     *      @Request({"id": 2}),
     *      @Response(200, body={"id": 2, "name": "Booker given poor feedback", "is_booker": 0, "is_active": 1}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function edit($id)
    {
        $dispute_type = VehicleDisputeType::find($id);
        if (!$dispute_type) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($dispute_type, (new VehicleDisputeTypeTransformer));
    }

    /**
     * Update the specified dispute type.
     * Update the dispute type with a `id`.
     * @Put("/dispute_types/{id}")
     * @Transaction({
     *      @Request({"id": 1, "name": "chennai", "state_id": 1, "country_id": 1, "latitude": 10.10, "longitude": 12.12, "is_active": 1}),
     *      @Response(200, body={"success": "Record has been updated."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {"name": {}}, "status_code": 422})
     * })
     */
    public function update(Request $request, $id)
    {
        $dispute_type_data = $request->only('name', 'is_booker', 'is_active');
        $validator = Validator::make($dispute_type_data, VehicleDisputeType::GetValidationRule(), VehicleDisputeType::GetValidationMessage());
        $dispute_type = false;
        if ($request->has('id')) {
            $dispute_type = VehicleDisputeType::find($id);
            $dispute_type = ($request->id != $id) ? false : $dispute_type;
        }
        if ($validator->passes() && $dispute_type) {
            try {
                $dispute_type->update($dispute_type_data);
                return response()->json(['Success' => 'VehicleDisputeType has been updated'], 200);
            } catch (\Exception $e) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleDisputeType could not be updated. Please, try again.');
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleDisputeType could not be updated. Please, try again.', $validator->errors());
        }
    }
}
