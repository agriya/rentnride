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
use App\Http\Controllers\Controller;
use Plugins\Vehicles\Model\VehicleSpecialPrice;
use JWTAuth;
use Validator;
use Carbon;
use Plugins\Vehicles\Transformers\AdminVehicleSpecialPriceTransformer;
use DB;

/**
 * VehicleSpecialPrices resource representation.
 * @Resource("Admin/AdminVehicleSpecialPrices")
 */
class AdminVehicleSpecialPricesController extends Controller
{
    /**
     * AdminVehicleSpecialPricesController constructor.
     */
    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
        // Check the logged user role.
        $this->middleware('role');
    }

    /**
     * Show all vehicle_special_prices
     * Get a JSON representation of all the vehicle_special_prices.
     *
     * @Get("/admin/vehicle_special_prices?sort={sort}&sortby={sortby}&page={page}&q={q}")
     * @Parameters({
     *      @Parameter("sort", type="string", required=false, description="Sort the vehicle_special_prices list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort vehicle_special_prices by Ascending / Descending Order.", default=null),
     *      @Parameter("q", type="string", required=false, description="Search VehicleSpecialPrices.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1)
     * })
     */
    public function index(Request $request)
    {
        $vehicle_special_price_count = config('constants.ConstPageLimit');
        if ($request->has('type') && $request->type == 'list') {
            $vehicle_special_price_count = VehicleSpecialPrice::count();
        }
        $vehicle_special_prices = VehicleSpecialPrice::with('vehicle_type')
                                ->select(DB::raw('vehicle_special_prices.*'))
                                ->leftJoin(DB::raw('(select id,name from vehicle_types) as vehicle_type'), 'vehicle_type.id', '=', 'vehicle_special_prices.vehicle_type_id')
                                ->filterByRequest($request)->paginate($vehicle_special_price_count);
        return $this->response->paginator($vehicle_special_prices, (new AdminVehicleSpecialPriceTransformer)->setDefaultIncludes(array('vehicle_type')));
    }

    /**
     * Edit the specified vehicle_special_price.
     * Edit the vehicle_special_price with a `id`.
     * @Get("/admin/vehicle_special_prices/{id}/edit")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body= {"id": 1, "vehicle_special_id": 1, "price_id": 1, "discount_type_id": 1, "duration_type_id": 1, "rate": 200, "max_allowed_amount": 1000, "vehicle_type": {}, "vehicle_price": {}, "duration_type": {}, "discount_type": {}}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function edit($id)
    {
        $vehicle_special_price = VehicleSpecialPrice::with('vehicle_type')->find($id);
        if (!$vehicle_special_price) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($vehicle_special_price, (new AdminVehicleSpecialPriceTransformer)->setDefaultIncludes(array('vehicle_type')));
    }

    /**
     * Edit the specified vehicle_special_price.
     * Edit the vehicle_special_price with a `id`.
     * @Get("/admin/vehicle_special_prices/{id}/edit")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body= {"id": 1, "vehicle_type_id": 1, "minimum_no_of_day": 1, "maximum_no_of_day": 1, "discount_percentage": 1, "is_active": 1, "vehicle_type": {}}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function show($id)
    {
        $vehicle_special_price = VehicleSpecialPrice::with('vehicle_type')->find($id);
        if (!$vehicle_special_price) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($vehicle_special_price, (new AdminVehicleSpecialPriceTransformer)->setDefaultIncludes(array('vehicle_type')));
    }

    /**
     * Store a new vehicle_special_price.
     * Store a new vehicle_special_price with a `name`, `short_description`, and `description`.
     * @Post("/admin/vehicle_special_prices")
     * @Transaction({
     *      @Request({"vehicle_type_id": 1, "start_date": 2016-05-05, "end_date": 2016-05-06, "discount_percentage": 1., "is_active": 1}),
     *      @Response(200, body={"success": "Record has been added."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {"name": {}}, "status_code": 422})
     * })
     */
    public function store(Request $request)
    {
        $vehicle_special_price_data = $request->only('start_date', 'end_date', 'vehicle_type_id', 'discount_percentage', 'is_active');
        if (!is_null($request['start_date'])) {
            $vehicle_special_price_data['start_date'] = date("Y-m-d H:i:s", strtotime($request['start_date']));
        }
        if (!is_null($request['end_date'])) {
            $vehicle_special_price_data['end_date'] = date("Y-m-d H:i:s", strtotime($request['end_date']));
        }
        $validator = Validator::make($vehicle_special_price_data, VehicleSpecialPrice::GetValidationRule(), VehicleSpecialPrice::GetValidationMessage());
        if ($validator->passes()) {
            $cur_date = Carbon::now()->toDateTimeString();
            if($cur_date > $vehicle_special_price_data['start_date'] || $vehicle_special_price_data['start_date'] > $vehicle_special_price_data['end_date']) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('Start date should be less than end date and greater than current date.');
            }
            //check whether the given special price date already in list
            $vehicle_special_price = VehicleSpecialPrice::where('vehicle_type_id', $request['vehicle_type_id'])
                ->where(function ($query) use ($request) {
                    $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                        ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                        ->orwhere(function ($query) use ($request) {
                            $query->where('start_date', '>', $request->start_date)
                                ->where('end_date', '<', $request->start_date);
                        })->orwhere(function ($query) use ($request) {
                            $query->where('start_date', '<', $request->end_date)
                                ->where('end_date', '>', $request->end_date);
                        });
                })->first();
            if ($vehicle_special_price) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle special Price already added for the given dates. Please try with different dates');
            }
            try {
                $vehicle_special_price = VehicleSpecialPrice::create($vehicle_special_price_data);
                if ($vehicle_special_price) {
                    return response()->json(['Success' => 'Vehicle special discount price has been added'], 200);
                } else {
                    throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle special discount price could not be added. Please, try again.');
                }
            } catch (\Exception $e) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle special discount price could not be added. Please, try again.',
                    array($e->getMessage()));
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle special discount price could not be added. Please, try again.', $validator->errors());
        }
    }


    /**
     * Update the specified vehicle_special_price
     * Update the vehicle_special_price with a `id`.
     * @Put("/admin/vehicle_special_prices/{id}")
     * @Transaction({
     *      @Request({"id": 1, "vehicle_type_id": 1, "start_date": 2016-05-05, "end_date": 2016-05-06, "discount_percentage": 1., "is_active": 1}),
     *      @Response(200, body={"success": "Record has been updated."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {"name": {}}, "status_code": 422})
     * })
     */
    public function update(Request $request, $id)
    {
        $vehicle_special_price_data = $request->only('start_date', 'end_date', 'vehicle_type_id', 'discount_percentage', 'is_active');
        if (!is_null($request['start_date'])) {
            $vehicle_special_price_data['start_date'] = date("Y-m-d H:i:s", strtotime($request['start_date']));
        }
        if (!is_null($request['end_date'])) {
            $vehicle_special_price_data['end_date'] = date("Y-m-d H:i:s", strtotime($request['end_date']));
        }
        $validator = Validator::make($vehicle_special_price_data, VehicleSpecialPrice::GetValidationRule(), VehicleSpecialPrice::GetValidationMessage());
        $vehicle_special_price = false;
        if ($request->has('id')) {
            $vehicle_special_price = VehicleSpecialPrice::find($id);
            $vehicle_special_price = ($request->id != $id) ? false : $vehicle_special_price;
        }
        $cur_date = Carbon::now()->toDateTimeString();
        if($cur_date > $vehicle_special_price_data['start_date'] || $vehicle_special_price_data['start_date'] > $vehicle_special_price_data['end_date']) {
            //if($vehicle_special_price_data['start_date'] > $vehicle_special_price_data['end_date']) {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Start date should be less than end date and greater than current date');
        }
        if ($validator->passes() && $vehicle_special_price) {
            //check whether the given special price date already in list
            $vehicle_special_price_check = VehicleSpecialPrice::where('vehicle_type_id', $request['vehicle_type_id'])
                ->where('id', '!=', [$id])
                ->where(function ($query) use ($request) {
                    $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                        ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                        ->orwhere(function ($query) use ($request) {
                            $query->where('start_date', '>', $request->start_date)
                                ->where('end_date', '<', $request->start_date);
                        })->orwhere(function ($query) use ($request) {
                            $query->where('start_date', '<', $request->end_date)
                                ->where('end_date', '>', $request->end_date);
                        });
                })->first();
            if($vehicle_special_price_check) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('Special Price already added for the given dates. Please try with different dates');
            }
            try {
                $vehicle_special_price->update($vehicle_special_price_data);
                return response()->json(['Success' => 'Vehicle special discount price has been updated'], 200);
            } catch (\Exception $e) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle special discount price could not be updated. Please, try again.',
                    array($e->getMessage()));
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle special discount price could not be updated. Please, try again.', $validator->errors());
        }
    }

    /**
     * Delete the specified vehicle_special_price.
     * Delete the vehicle_special_price with a `id`.
     * @Delete("/admin/vehicle_special_prices/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"success": "Record Deleted."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function destroy($id)
    {
        $vehicle_special_price = VehicleSpecialPrice::find($id);
        if (!$vehicle_special_price) {
            return $this->response->errorNotFound("Invalid Request");
        } else {
            $vehicle_special_price->delete();
        }
        return response()->json(['Success' => 'Vehicle special discount price deleted'], 200);
    }
}
