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
use Plugins\Vehicles\Model\VehicleTypePrice;
use JWTAuth;
use Validator;
use Plugins\Vehicles\Transformers\AdminVehicleTypePriceTransformer;
use DB;

/**
 * VehicleTypePrices resource representation.
 * @Resource("Admin/AdminVehicleTypePrices")
 */
class AdminVehicleTypePricesController extends Controller
{
    /**
     * AdminVehicleTypePricesController constructor.
     */
    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
        // Check the logged user role.
        $this->middleware('role');
    }

    /**
     * Show all vehicle_type_prices
     * Get a JSON representation of all the vehicle_type_prices.
     *
     * @Get("/admin/vehicle_type_prices?sort={sort}&sortby={sortby}&page={page}&q={q}")
     * @Parameters({
     *      @Parameter("sort", type="string", required=false, description="Sort the vehicle_type_prices list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort vehicle_type_prices by Ascending / Descending Order.", default=null),
     *      @Parameter("q", type="string", required=false, description="Search VehicleTypePrices.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1)
     * })
     */
    public function index(Request $request)
    {
        $vehicle_type_price_count = config('constants.ConstPageLimit');
        if ($request->has('type') && $request->type == 'list') {
            $vehicle_type_price_count = VehicleTypePrice::count();
        }
        $vehicle_type_prices = VehicleTypePrice::with('vehicle_type')
                                ->select(DB::raw('vehicle_type_prices.*'))
                                ->leftJoin(DB::raw('(select id,name from vehicle_types) as vehicle_type'), 'vehicle_type.id', '=', 'vehicle_type_prices.vehicle_type_id')
                                ->filterByRequest($request)->paginate($vehicle_type_price_count);
        return $this->response->paginator($vehicle_type_prices, (new AdminVehicleTypePriceTransformer)->setDefaultIncludes(array('vehicle_type')));
    }

    /**
     * Edit the specified vehicle_type_price.
     * Edit the vehicle_type_price with a `id`.
     * @Get("/admin/vehicle_type_prices/{id}/edit")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body= {"id": 1, "vehicle_type_id": 1, "price_id": 1, "discount_type_id": 1, "duration_type_id": 1, "rate": 200, "max_allowed_amount": 1000, "vehicle_type": {}, "vehicle_price": {}, "duration_type": {}, "discount_type": {}}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function edit($id)
    {
        $vehicle_type_price = VehicleTypePrice::with('vehicle_type')->find($id);
        if (!$vehicle_type_price) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($vehicle_type_price, (new AdminVehicleTypePriceTransformer)->setDefaultIncludes(array('vehicle_type')));
    }

    /**
     * Edit the specified vehicle_type_price.
     * Edit the vehicle_type_price with a `id`.
     * @Get("/admin/vehicle_type_prices/{id}/edit")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body= {"id": 1, "vehicle_type_id": 1, "minimum_no_of_day": 1, "maximum_no_of_day": 1, "discount_percentage": 1, "is_active": 1, "vehicle_type": {}}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function show($id)
    {
        $vehicle_type_price = VehicleTypePrice::with('vehicle_type')->find($id);
        if (!$vehicle_type_price) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($vehicle_type_price, (new AdminVehicleTypePriceTransformer)->setDefaultIncludes(array('vehicle_type')));
    }

    /**
     * Store a new vehicle_type_price.
     * Store a new vehicle_type_price with a `name`, `short_description`, and `description`.
     * @Post("/admin/vehicle_type_prices")
     * @Transaction({
     *      @Request({"vehicle_type_id": 1, "minimum_no_of_day": 1, "maximum_no_of_day": 1, "discount_percentage": 1, "is_active": 1}),
     *      @Response(200, body={"success": "Record has been added."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {"name": {}}, "status_code": 422})
     * })
     */
    public function store(Request $request)
    {
        $vehicle_type_price_data = $request->only('vehicle_type_id', 'minimum_no_of_day', 'maximum_no_of_day', 'discount_percentage', 'is_active');
        $validator = Validator::make($vehicle_type_price_data, VehicleTypePrice::GetValidationRule(), VehicleTypePrice::GetValidationMessage());
        if ($validator->passes()) {
            try {
                //check whether the given type price date already in list
                $vehicle_type_price = VehicleTypePrice::where('vehicle_type_id', $request['vehicle_type_id'])
                    ->where(function ($query) use ($request) {
						$query->whereBetween('minimum_no_of_day', [$request->minimum_no_of_day, $request->maximum_no_of_day])
						->orWhereBetween('maximum_no_of_day', [$request->minimum_no_of_day, $request->maximum_no_of_day])
						->orwhere(function ($query) use ($request) {
							$query->where('minimum_no_of_day', '>', $request->minimum_no_of_day)
								->where('maximum_no_of_day', '<', $request->minimum_no_of_day);
						})->orwhere(function ($query) use ($request) {
							$query->where('minimum_no_of_day', '<', $request->maximum_no_of_day)
								->where('maximum_no_of_day', '>', $request->maximum_no_of_day);
						});
					})->first();
                if ($vehicle_type_price) {
                    throw new \Dingo\Api\Exception\StoreResourceFailedException('Type Price already added for the given days. Please try with different days');
                }
                if ($request->minimum_no_of_day < $request->maximum_no_of_day) {
                    $vehicle_type_price = VehicleTypePrice::create($vehicle_type_price_data);
                    if ($vehicle_type_price) {
                        return response()->json(['Success' => 'Vehicle discount Price has been added'], 200);
                    } else {
                        throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle discount Price could not be added. Please, try again.');
                    }
                } else {
                    throw new \Dingo\Api\Exception\StoreResourceFailedException('Minimum number of day should be less than maximum number of day');
                }
            } catch (\Exception $e) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle discount Price could not be added. Please, try again.',
                    array($e->getMessage()));
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle discount Price could not be added. Please, try again.', $validator->errors());
        }
    }


    /**
     * Update the specified vehicle_type_price
     * Update the vehicle_type_price with a `id`.
     * @Put("/admin/vehicle_type_prices/{id}")
     * @Transaction({
     *      @Request({"id": 1, "vehicle_type_id": 1, "minimum_no_of_day": 1, "maximum_no_of_day": 1, "discount_percentage": 1, "is_active":1}),
     *      @Response(200, body={"success": "Record has been updated."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {"name": {}}, "status_code": 422})
     * })
     */
    public function update(Request $request, $id)
    {
        $vehicle_type_price_data = $request->only('vehicle_type_id', 'minimum_no_of_day', 'maximum_no_of_day', 'discount_percentage', 'is_active');
        $validator = Validator::make($vehicle_type_price_data, VehicleTypePrice::GetValidationRule(), VehicleTypePrice::GetValidationMessage());
        $vehicle_type_price = false;
        if ($request->has('id')) {
            $vehicle_type_price = VehicleTypePrice::find($id);
            $vehicle_type_price = ($request->id != $id) ? false : $vehicle_type_price;
        }
        if ($validator->passes() && $vehicle_type_price) {
            try {
                //check whether the given type price date already in list
                $vehicle_type_price_check = VehicleTypePrice::where('vehicle_type_id', $request['vehicle_type_id'])
                    ->where('id', '!=', [$id])
                    ->where(function ($query) use ($request) {
                        $query->whereBetween('minimum_no_of_day', [$request->minimum_no_of_day, $request->maximum_no_of_day])
                            ->orWhereBetween('maximum_no_of_day', [$request->minimum_no_of_day, $request->maximum_no_of_day])
                            ->orwhere(function ($query) use ($request) {
                                $query->where('minimum_no_of_day', '>', $request->minimum_no_of_day)
                                    ->where('maximum_no_of_day', '<', $request->minimum_no_of_day);
                            })->orwhere(function ($query) use ($request) {
                                $query->where('minimum_no_of_day', '<', $request->maximum_no_of_day)
                                    ->where('maximum_no_of_day', '>', $request->maximum_no_of_day);
                            });
                    })->first();
                if ($vehicle_type_price_check) {
                    throw new \Dingo\Api\Exception\StoreResourceFailedException('Type Price already added for the given days. Please try with different days');
                }
                if ($request->minimum_no_of_day < $request->maximum_no_of_day) {
                    $vehicle_type_price->update($vehicle_type_price_data);
                } else {
                    throw new \Dingo\Api\Exception\StoreResourceFailedException('Minimum number of day should be less than maximum number of day');
                }
                return response()->json(['Success' => 'Vehicle discount Price has been updated'], 200);
            } catch (\Exception $e) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle discount Price could not be updated. Please, try again.',
                    array($e->getMessage()));
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle discount Price could not be updated. Please, try again.', $validator->errors());
        }
    }

    /**
     * Delete the specified vehicle_type_price.
     * Delete the vehicle_type_price with a `id`.
     * @Delete("/admin/vehicle_type_prices/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"success": "Record Deleted."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function destroy($id)
    {
        $vehicle_type_price = VehicleTypePrice::find($id);
        if (!$vehicle_type_price) {
            return $this->response->errorNotFound("Invalid Request");
        } else {
            $vehicle_type_price->delete();
        }
        return response()->json(['Success' => 'Vehicle discount Price deleted'], 200);
    }
}
