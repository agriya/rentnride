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
use Plugins\Vehicles\Model\UnavailableVehicle;
use Plugins\Vehicles\Model\Vehicle;
use JWTAuth;
use Validator;
use Plugins\Vehicles\Transformers\UnavailableVehicleTransformer;
use App\User;
use Carbon;

/**
 * Class UnavailableVehiclesController
 * @package Plugins\Vehicles\Controllers
 */
class UnavailableVehiclesController extends Controller
{
    /**
     * UnavailableVehiclesController constructor.
     */
    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
    }

    /**
     * Show own all vehicle unavailable dates.
     * Get a JSON representation of vehicle unavailable dates.
     * @Get("GET /unavailable_vehicles?filter={filter}&sort={sort}&sortby={sortby}&q={q}")
     * @Parameters({
     *      @Parameter("vehicle_id", type="integer", required=false, description="Filter the vehicles ", default=null),
     *      @Parameter("filter", type="integer", required=false, description="Filter the vehicles list by status.", default=null),
     *      @Parameter("sort", type="string", required=false, description="Sort the vehicles list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort vehicles by Ascending / Descending Order.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1)
     * })
     */
    public function index(Request $request)
    {
        $user = $this->auth->user();
        $enabled_includes = array('vehicle');
        $vehicles = UnavailableVehicle::with($enabled_includes)->filterByRequest($request)->filterByMyVehicle($user->id)->paginate(config('constants.ConstPageLimit'));
        return $this->response->paginator($vehicles, (new UnavailableVehicleTransformer)->setDefaultIncludes($enabled_includes));
    }

    /**
     * Store a new unavailable vehicle.
     * Store a new unavailable vehicle with a 'vehicle_id', 'start_date', 'end_date'.
     * @Post("/unavailable_vehicles")
     * @Transaction({
     *      @Request({"vehicle_id": 1, "start_date": 2016-08-09, "end_date": "2016-09-09"}),
     *      @Response(200, body={"success": "Record has been added."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {"amount": {}}, "status_code": 422})
     * })
     */
    public function store(Request $request)
    {
        $unavailable_vehicle_data = $request->only('vehicle_id', 'start_date', 'end_date');
        $user = $this->auth->user();
        $vehicle = Vehicle::with('vehicle_company', 'user')->find($request->vehicle_id);
        if (!$vehicle || !$user || $vehicle->user_id != $user->id || $vehicle->vehicle_company->user_id != $user->id) {
            return $this->response->errorNotFound("Invalid Request");
        }
        if (!is_null($request['start_date'])) {
            $unavailable_vehicle_data['start_date'] = date("Y-m-d H:i:s", strtotime($request['start_date']));
        }
        if (!is_null($request['end_date'])) {
            $unavailable_vehicle_data['end_date'] = date("Y-m-d H:i:s", strtotime($request['end_date']));
        }
        $cur_date = Carbon::now()->toDateTimeString();
        if ($cur_date > $request->start_date || $request->start_date > $request->end_date) {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Start date should be less than end date and greater than current date.');
        }
        //check whether the given date already in list
        $unavailable_vehicle = UnavailableVehicle::where('vehicle_id', $request->vehicle_id)
            ->where(function ($query) use ($unavailable_vehicle_data) {
                $query->whereBetween('start_date', [$unavailable_vehicle_data['start_date'], $unavailable_vehicle_data['end_date']])
                    ->orWhereBetween('end_date', [$unavailable_vehicle_data['start_date'], $unavailable_vehicle_data['end_date']])
                    ->orwhere(function ($query) use ($unavailable_vehicle_data) {
                        $query->where('start_date', '>=', $unavailable_vehicle_data['start_date'])
                            ->where('end_date', '<=', $unavailable_vehicle_data['start_date']);
                    })->orwhere(function ($query) use ($unavailable_vehicle_data) {
                        $query->where('start_date', '<', $unavailable_vehicle_data['end_date'])
                            ->where('end_date', '>', $unavailable_vehicle_data['end_date']);
                    });
            })->first();
        if ($unavailable_vehicle) {
            if ($unavailable_vehicle->is_dummy == 2) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('Maintenance date already updated. Please, try again some other date.');
            } else if ($unavailable_vehicle->is_dummy == 0) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle booked that day. Please, try again some other date.');
            }

        }
        $unavailable_vehicle_data['is_dummy'] = 2;
        $validator = Validator::make($unavailable_vehicle_data, UnavailableVehicle::GetValidationRule(), UnavailableVehicle::GetValidationMessage());
        if ($validator->passes()) {
            try {
                $unavailable_vehicle = UnavailableVehicle::create($unavailable_vehicle_data);
                if ($unavailable_vehicle) {
                    return response()->json(['Success' => 'Vehicle maintenance date has been added'], 200);
                } else {
                    throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle maintenance date could not be added. Please, try again.');
                }
            } catch (\Exception $e) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle maintenance date could not be added. Please, try again.', array($e->getMessage()));
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle maintenance date could not be added. Please, try again.', $validator->errors());
        }
    }

    /**
     * Edit the specified unavailable vehicle.
     * Edit the unavailable vehicle with a `id`.
     * @Get("/unavailable_vehicles/{id}/edit")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"id": 1}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function edit($id)
    {
        $user = $this->auth->user();
        $unavailable_vehicle = UnavailableVehicle::with(['vehicle'])->where('id', $id)->where('is_dummy', 2)->filterByMyVehicle($user->id)->find($id);
        if (!$unavailable_vehicle) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($unavailable_vehicle, (new UnavailableVehicleTransformer)->setDefaultIncludes(['vehicle']));
    }

    /**
     * Update the specified unavailable vehicle.
     * Update the unavailable vehicle with a `id`.
     * @Put("/unavailable_vehicles/{id}")
     * @Transaction({
     *      @Request({"id": 1, "vehicle_id": 1, "start_date": 2016-08-09, "end_date": "2016-09-09"}),
     *      @Response(200, body={"success": "Record has been updated."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {}, "status_code": 422})
     * })
     */
    public function update(Request $request, $id)
    {
        $user = $this->auth->user();
        $unavailable_vehicle_data = $request->only('id', 'start_date', 'end_date');
        $unavailable_vehicle = false;
        if ($request->has('id')) {
            $unavailable_vehicle = UnavailableVehicle::with(['vehicle'])->where('is_dummy', 2)->filterByMyVehicle($user->id)->find($id);
            $unavailable_vehicle = ($request->id != $id) ? false : $unavailable_vehicle;
        }
        if (!$unavailable_vehicle || !$user) {
            return $this->response->errorNotFound("Invalid Request");
        }
        if ($request->start_date > $request->end_date) {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Start date should be less than end date');
        }
        if (!is_null($request['start_date'])) {
            $unavailable_vehicle_data['start_date'] = date("Y-m-d H:i:s", strtotime($request['start_date']));
        }
        if (!is_null($request['end_date'])) {
            $unavailable_vehicle_data['end_date'] = date("Y-m-d H:i:s", strtotime($request['end_date']));
        }
        //check whether the given date already in list
        $unavailable_vehicle_check = UnavailableVehicle::with(['vehicle'])->where('vehicle_id', $unavailable_vehicle->vehicle_id)
            ->where('id', '!=', [$id])
            ->where(function ($query) use ($unavailable_vehicle_data) {
                $query->whereBetween('start_date', [$unavailable_vehicle_data['start_date'], $unavailable_vehicle_data['end_date']])
                    ->orWhereBetween('end_date', [$unavailable_vehicle_data['start_date'], $unavailable_vehicle_data['end_date']])
                    ->orwhere(function ($query) use ($unavailable_vehicle_data) {
                        $query->where('start_date', '>', $unavailable_vehicle_data['start_date'])
                            ->where('end_date', '<', $unavailable_vehicle_data['start_date']);
                    })->orwhere(function ($query) use ($unavailable_vehicle_data) {
                        $query->where('start_date', '<', $unavailable_vehicle_data['end_date'])
                            ->where('end_date', '>', $unavailable_vehicle_data['end_date']);
                    });
            })->first();
        if ($unavailable_vehicle_check) {
            if ($unavailable_vehicle_check->is_dummy == 2) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('Maintenance date already updated. Please, try again some other date.');
            } else if ($unavailable_vehicle_check->is_dummy == 0) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle booked that day. Please, try again some other date.');
            }
        } else {
            $unavailable_vehicle = UnavailableVehicle::with(['vehicle'])->where('is_dummy', 2)->filterByMyVehicle($user->id)->find($id);
        }
        $validator = Validator::make($unavailable_vehicle_data, UnavailableVehicle::GetValidationRule(), UnavailableVehicle::GetValidationMessage());
        if ($validator->passes()) {
            try {
                $unavailable_vehicle->update($unavailable_vehicle_data);
                return response()->json(['Success' => 'Maintenance date has been updated', 'vehicle_id' => $unavailable_vehicle->vehicle_id], 200);
            } catch (\Exception $e) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('Maintenance date could not be updated. Please, try again.', array($e->getMessage()));
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Maintenance date could not be updated. Please, try again.', $validator->errors());
        }
    }

    /**
     * Delete the specified unavailable vehicle.
     * Delete the unavailable vehicle with a `id`.
     * @Delete("/unavailable_vehicles/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"success": "Record Deleted."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function destroy($id)
    {
        $user = $this->auth->user();
        $unavailable_vehicle = UnavailableVehicle::with(['vehicle'])->where('is_dummy', 2)->filterByMyVehicle($user->id)->find($id);
        if (!$unavailable_vehicle) {
            return $this->response->errorNotFound("Invalid Request");
        } else {
            $unavailable_vehicle->delete();
        }
        return response()->json(['Success' => 'Maintenance date deleted'], 200);
    }

}
