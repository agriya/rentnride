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
use Plugins\Vehicles\Model\CounterLocation;
use JWTAuth;
use Validator;
use Plugins\Vehicles\Transformers\AdminCounterLocationTransformer;
use Plugins\Vehicles\Transformers\CounterLocationTransformer;


/**
 * Class AdminCounterLocationsController
 * @package Plugins\Vehicles\Controllers\Admin
 */
class AdminCounterLocationsController extends Controller
{

    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
        // Check the logged user role.
        $this->middleware('role');
    }

    /**
     * Show all counter locations.
     * Get a JSON representation of all the counter locations.
     *
     * @Get("/counter_locations?filter={filter}&sort={sort}&sortby={sortby}&q={q}")
     * @Parameters({
     *      @Parameter("filter", type="integer", required=false, description="Filter the counter_locations list by status.", default=null),
     *      @Parameter("sort", type="string", required=false, description="Sort the counter_locations list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort counter_locations by Ascending / Descending Order.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1)
     * })
     */
    public function index(Request $request)
    {
        $counter_location_count = config('constants.ConstPageLimit');
        if ($request->has('limit') && $request->limit == 'all') {
            $counter_location_count = CounterLocation::filterByRequest($request)->count();
            $counter_locations = CounterLocation::filterByRequest($request)->paginate($counter_location_count);
            return $this->response->paginator($counter_locations, new CounterLocationTransformer);
        } elseif ($request->has('vehicle_id')) {
            $counter_locations = CounterLocation::filterByRequest($request)->paginate($counter_location_count);
            return $this->response->paginator($counter_locations, new CounterLocationTransformer);
        } else {
            $counter_locations = CounterLocation::filterByRequest($request)->paginate($counter_location_count);
            return $this->response->paginator($counter_locations, new AdminCounterLocationTransformer);
        }
    }

    /**
     * Store a new counter_location.
     * Store a new counter_location with a 'address', 'latitude', 'longitude'.
     * @Post("/items")
     * @Transaction({
     *      @Request({"address": 1000, "user_id": 1, "name": "house for rent", "booking_type_id": 1, "description": "This house is for rent"}),
     *      @Response(200, body={"success": "Record has been added."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {"amount": {}}, "status_code": 422})
     * })
     */
    public function store(Request $request)
    {
        $counter_location_data = $request->only('address', 'latitude', 'longitude', 'fax', 'phone', 'mobile', 'email');
        $validator = Validator::make($counter_location_data, CounterLocation::GetValidationRule(), CounterLocation::GetValidationMessage());
        $check_location = CounterLocation::where('address', $request->address)->first();
        if ($check_location) {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Location could not be updated. Location already exists.');
        }
        if ($validator->passes()) {
            try {
                $counter_location = CounterLocation::create($counter_location_data);
                if ($counter_location) {
                    return response()->json(['Success' => 'Counter location has been added'], 200);
                } else {
                    throw new \Dingo\Api\Exception\StoreResourceFailedException('Counter location could not be added. Please, try again.');
                }
            } catch (\Exception $e) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('Counter location could not be added. Please, try again.',
                    array($e->getMessage()));
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Counter location could not be added. Please, try again.', $validator->errors());
        }
    }

    /**
     * Edit the specified counter_location.
     * Edit the counter_location with a `id`.
     * @Get("/admin/counter_locations/{id}/edit")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body= {"id": 1, "address": "chennai airport", "mobile": "123546853"}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function edit($id)
    {
        $counter_location = CounterLocation::find($id);
        if (!$counter_location) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($counter_location, (new AdminCounterLocationTransformer));
    }

    /**
     * Update the specified counter_location
     * Update the counter_location with a `id`.
     * @Put("/admin/counter_locations/{id}")
     * @Transaction({
     *      @Request({"id": 1, "address": "chennai airport", "mobile": "123546853"}),
     *      @Response(200, body={"success": "Record has been updated."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {"name": {}}, "status_code": 422})
     * })
     */
    public function update(Request $request, $id)
    {
        $counter_location_data = $request->only('id', 'address', 'latitude', 'longitude', 'fax', 'phone', 'mobile', 'email');
        $counter_location = false;
        $validation_rules = CounterLocation::GetValidationRule();
        if ($request->has('id')) {
            $counter_location = CounterLocation::find($id);
            $counter_location = ($request->id != $id) ? false : $counter_location;
            if ($counter_location->address == $counter_location_data['address']) {
                $validation_rules['address'] = 'required|min:5';
            }
        }
        $validator = Validator::make($counter_location_data, $validation_rules, CounterLocation::GetValidationMessage());
        $check_location = CounterLocation::where('address', $request->address)->where('id', '!=', $id)->first();
        if ($check_location) {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Location could not be updated. Location already exists.');
        }
        if ($validator->passes() && $counter_location) {
            try {
                $counter_location->update($counter_location_data);
                return response()->json(['Success' => 'Counter Location has been updated'], 200);
            } catch (\Exception $e) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('Counter Location could not be updated. Please, try again.');
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Counter Location could not be updated. Please, try again.', $validator->errors());
        }
    }

    /**
     * Delete the specified Counter Location.
     * Delete the Counter Location with a `id`.
     * @Delete("/counter_locations/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"success": "Record Deleted."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function destroy($id)
    {
        $counter_location = CounterLocation::find($id);
        if (!$counter_location) {
            return $this->response->errorNotFound("Invalid Request");
        } else {
            $counter_location->delete();
        }
        return response()->json(['Success' => 'Counter location deleted'], 200);
    }

    /**
     * Show the specified counter locations.
     * Show the counter locations with a `id`.
     * @Get("/counter_locations/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"id": 1),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     *
     */
    public function show($id)
    {
        $counter_location = CounterLocation::find($id);
        if (!$counter_location) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($counter_location, (new AdminCounterLocationTransformer));
    }
}
