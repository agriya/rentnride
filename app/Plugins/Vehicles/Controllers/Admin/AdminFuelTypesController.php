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
use Plugins\Vehicles\Model\FuelType;
use JWTAuth;
use Validator;
use Plugins\Vehicles\Transformers\AdminFuelTypeTransformer;
use EasySlug\EasySlug\EasySlugFacade as EasySlug;

/**
 * Class AdminFuelTypesController
 * @package Plugins\Vehicles\Controllers\Admin
 */
class AdminFuelTypesController extends Controller
{

    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
        // Check the logged user role.
        $this->middleware('role');
    }

    /**
     * Show all fuel types.
     * Get a JSON representation of all the fuel types.
     *
     * @Get("/fuel_types?filter={filter}&sort={sort}&sortby={sortby}&q={q}")
     * @Parameters({
     *      @Parameter("filter", type="integer", required=false, description="Filter the fuel types list by status.", default=null),
     *      @Parameter("sort", type="string", required=false, description="Sort the fuel types list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort fuel types by Ascending / Descending Order.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1)
     * })
     */
    public function index(Request $request)
    {
        $fuel_type_count = config('constants.ConstPageLimit');
        if ($request->has('type') && $request->type == 'list') {
            $fuel_type_count = FuelType::count();
        }
        $fuel_types = FuelType::filterByRequest($request)->paginate($fuel_type_count);
        return $this->response->paginator($fuel_types, new AdminFuelTypeTransformer);
    }

    /**
     * Store a new fuel type.
     * Store a new fuel type with a 'amount', 'user_id', 'name', 'booking_type_id', 'description'.
     * @Post("/fuel_types")
     * @Transaction({
     *      @Request({}),
     *      @Response(200, body={"success": "Record has been added."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {"amount": {}}, "status_code": 422})
     * })
     */
    public function store(Request $request)
    {
        $fuel_type_data = $request->only('name', 'is_active');
        $validator = Validator::make($fuel_type_data, FuelType::GetValidationRule(), FuelType::GetValidationMessage());
        if ($validator->passes()) {
            try {
                $fuel_type = FuelType::create($fuel_type_data);
                if ($fuel_type) {
                    return response()->json(['Success' => 'Fuel type has been added'], 200);
                } else {
                    throw new \Dingo\Api\Exception\StoreResourceFailedException('Fuel type could not be added. Please, try again.');
                }
            } catch (\Exception $e) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('Fuel type could not be added. Please, try again.',
                    array($e->getMessage()));
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Fuel type could not be added. Please, try again.', $validator->errors());
        }
    }

    /**
     * Edit the specified fuel type.
     * Edit the fuel type with a `id`.
     * @Get("/fuel_types/{id}/edit")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"id": 1}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function edit($id)
    {
        $fuel_type = FuelType::find($id);
        if (!$fuel_type) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($fuel_type, (new AdminFuelTypeTransformer));
    }

    /**
     * Update the specified fuel type.
     * Update the fuel type with a `id`.
     * @Put("/fuel_types/{id}")
     * @Transaction({
     *      @Response(200, body={"success": "Record has been updated."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {}, "status_code": 422})
     * })
     */
    public function update(Request $request, $id)
    {
        $fuel_type_data = $request->only('id', 'name', 'is_active');
        $fuel_type = false;
        if ($request->has('id')) {
            $fuel_type = FuelType::find($id);
            $fuel_type = ($request->id != $id) ? false : $fuel_type;
        }
        $validator = Validator::make($fuel_type_data, FuelType::GetValidationRule(), FuelType::GetValidationMessage());
        if ($validator->passes() && $fuel_type) {
            try {
                $fuel_type->update($fuel_type_data);
                return response()->json(['Success' => 'Fuel Type has been updated'], 200);
            } catch (\Exception $e) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('Fuel Type could not be updated. Please, try again.');
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Fuel Type could not be updated. Please, try again.', $validator->errors());
        }
    }

    /**
     * Delete the specified fuel type.
     * Delete the fuel type with a `id`.
     * @Delete("/fuel_types/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"success": "Record Deleted."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function destroy($id)
    {
        $fuel_type = FuelType::find($id);
        if (!$fuel_type) {
            return $this->response->errorNotFound("Invalid Request");
        } else {
            $fuel_type->delete();
        }
        return response()->json(['Success' => 'Fuel Type deleted'], 200);
    }

    /**
     * Show the specified fuel type.
     * Show the fuel type with a `id`.
     * @Get("/fuel_types/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"id": 1}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function show($id)
    {
        $fuel_type = FuelType::find($id);
        if (!$fuel_type) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($fuel_type, (new AdminFuelTypeTransformer));
    }
}
