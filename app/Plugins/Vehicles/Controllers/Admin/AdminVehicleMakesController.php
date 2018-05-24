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
use Plugins\Vehicles\Model\VehicleMake;
use JWTAuth;
use Validator;
use Plugins\Vehicles\Transformers\AdminVehicleMakeTransformer;
use Plugins\Vehicles\Transformers\VehicleMakeTransformer;
use EasySlug\EasySlug\EasySlugFacade as EasySlug;

/**
 * Class AdminVehicleMakesController
 * @package Plugins\Vehicles\Controllers\Admin
 */
class AdminVehicleMakesController extends Controller
{

    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
        // Check the logged user role.
        $this->middleware('role');
    }

    /**
     * Show all vehicle makes.
     * Get a JSON representation of all the vehicle makes.
     *
     * @Get("/vehicle_makes?filter={filter}&sort={sort}&sortby={sortby}&q={q}")
     * @Parameters({
     *      @Parameter("filter", type="integer", required=false, description="Filter the vehicle makes list by status.", default=null),
     *      @Parameter("sort", type="string", required=false, description="Sort the vehicle makes list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort vehicle makes by Ascending / Descending Order.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1)
     * })
     */
    public function index(Request $request)
    {
        $vehicle_make_count = config('constants.ConstPageLimit');
        if ($request->has('type') && $request->type == 'list') {
            $vehicle_make_count = VehicleMake::count();
        }
        if($request->has('limit') && $request->limit == 'all') {
            $vehicle_make_count = VehicleMake::count();
            $vehicle_makes = VehicleMake::filterByRequest($request)->select('id', 'name')->paginate($vehicle_make_count);
            return $this->response->paginator($vehicle_makes, new VehicleMakeTransformer);
        } else {
            $vehicle_makes = VehicleMake::filterByRequest($request)->paginate($vehicle_make_count);
            return $this->response->paginator($vehicle_makes, new AdminVehicleMakeTransformer);
        }
    }

    /**
     * Store a new vehicle make.
     * Store a new vehicle make with a 'amount', 'user_id', 'name', 'booking_type_id', 'description'.
     * @Post("/vehicle_makes")
     * @Transaction({
     *      @Request({"name":"maruti", "is_active": 1}),
     *      @Response(200, body={"success": "Record has been added."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {"amount": {}}, "status_code": 422})
     * })
     */
    public function store(Request $request)
    {
        $vehicle_make_data = $request->only('name', 'is_active');
        $vehicle_make_data['slug'] = EasySlug::generateUniqueSlug($request->name, 'vehicle_makes');
        $validator = Validator::make($vehicle_make_data, VehicleMake::GetValidationRule(), VehicleMake::GetValidationMessage());
        if ($validator->passes()) {
            try {
                $vehicle_make = VehicleMake::create($vehicle_make_data);
                if ($vehicle_make) {
                    return response()->json(['Success' => 'Vehicle make has been added'], 200);
                } else {
                    throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle make could not be added. Please, try again.');
                }
            } catch (\Exception $e) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle make could not be added. Please, try again.',
                    array($e->getMessage()));
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle make could not be added. Please, try again.', $validator->errors());
        }
    }

    /**
     * Edit the specified vehicle make.
     * Edit the vehicle make with a `id`.
     * @Get("/vehicle_makes/{id}/edit")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"id": 1}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function edit($id)
    {
        $vehicle_make = VehicleMake::find($id);
        if (!$vehicle_make) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($vehicle_make, (new AdminVehicleMakeTransformer));
    }

    /**
     * Update the specified vehicle make.
     * Update the vehicle make with a `id`.
     * @Put("/vehicle_makes/{id}")
     * @Transaction({
     *      @Request({"id": 1, "name":"maruti", "is_active": 1}),
     *      @Response(200, body={"success": "Record has been updated."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {}, "status_code": 422})
     * })
     */
    public function update(Request $request, $id)
    {
        $vehicle_make_data = $request->only('id', 'name', 'is_active');
        $vehicle_make = false;
        if ($request->has('id')) {
            $vehicle_make = VehicleMake::find($id);
            $vehicle_make = ($request->id != $id) ? false : $vehicle_make;
            if ($vehicle_make->name !== $request->name) {
                $vehicle_make_data['slug'] = EasySlug::generateUniqueSlug($request->name, 'vehicle_makes');
            } else {
                $vehicle_make_data['slug'] = $vehicle_make->slug;
            }
        }
        $validator = Validator::make($vehicle_make_data, VehicleMake::GetValidationRule(), VehicleMake::GetValidationMessage());
        if ($validator->passes() && $vehicle_make) {
            try {
                $vehicle_make->update($vehicle_make_data);
                return response()->json(['Success' => 'Vehicle Make has been updated'], 200);
            } catch (\Exception $e) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle Make could not be updated. Please, try again.');
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle Make could not be updated. Please, try again.', $validator->errors());
        }
    }

    /**
     * Delete the specified vehicle make.
     * Delete the vehicle make with a `id`.
     * @Delete("/vehicle_makes/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"success": "Record Deleted."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function destroy($id)
    {
        $vehicle_make = VehicleMake::find($id);
        if (!$vehicle_make) {
            return $this->response->errorNotFound("Invalid Request");
        } else {
            $vehicle_make->delete();
        }
        return response()->json(['Success' => 'Vehicle Make deleted'], 200);
    }

    /**
     * Show the specified vehicle make.
     * Show the vehicle make with a `id`.
     * @Get("/vehicle_makes/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"id": 1}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function show($id)
    {
        $vehicle_make = VehicleMake::find($id);
        if (!$vehicle_make) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($vehicle_make, (new AdminVehicleMakeTransformer));
    }
}
