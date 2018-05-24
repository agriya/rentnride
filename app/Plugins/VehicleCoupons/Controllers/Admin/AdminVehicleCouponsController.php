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
 
namespace Plugins\VehicleCoupons\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use JWTAuth;
use Validator;
use Plugins\VehicleCoupons\Model\VehicleCoupon;
use Plugins\VehicleCoupons\Transformers\VehicleCouponTransformer;
use DB;
use Carbon;

/**
 * VehicleCoupons resource representation.
 * @Resource("Admin/AdminVehicleCouponss")
 */
class AdminVehicleCouponsController extends Controller
{
    /**
     * AdminVehicleCouponsController constructor.
     */
    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
        // Check the logged user role.
        $this->middleware('role');
    }

    /**
     * Show all VehicleCoupons
     * Get a JSON representation of all the VehicleCoupons.
     * @Get("/vehicle_coupons?sort={sort}&sortby={sortby}&page={page}&q={q}")
     * @Parameters({
     *      @Parameter("sort", type="string", required=false, description="Sort the coupons list by sort key.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort coupons by Ascending / Descending Order.", default=null),
     *      @Parameter("q", type="string", required=false, description="Search coupons.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1),
     *      @Parameter("filter", type="string", required=false, description="Filter coupons.", default=null)
     * })
     */
    public function index(Request $request)
    {
        // todo: Need to change the query
        $vehicle_coupons = VehicleCoupon::select(DB::raw('coupons.*'))
            ->leftJoin(DB::raw('(select id, name from vehicles) as couponable'), 'couponable.id', '=', 'coupons.couponable_id')
            ->filterByRequest($request)
            ->paginate(config('constants.ConstPageLimit'));
        return $this->response->paginator($vehicle_coupons, (new VehicleCouponTransformer)->setDefaultIncludes(['couponable']));
    }

    /**
     * Store a new coupon.
     * Store a new coupon with a `name`, 'description', 'discount', 'discount_type', 'no_of_quantity',  'validity_start_date', 'validity_end_date', 'maximum_discount_amount'.
     * @Post("/vehicle_coupons")
     * @Transaction({
     *      @Request({"vehicle_id":1, "name": "coupon1", "description": "coupon description", "discount": 10, "discount_type": "%", "no_of_quantity": 2, "validity_start_date": "12-05-2016", "validity_end_date": "13-05-2016", "maximum_discount_amount":100}),
     *      @Response(200, body={"success": "VehicleCoupon has been added."}),
     *      @Response(422, body={"message": "VehicleCoupon could not be updated. Please, try again.", "errors": {"name": {}}, "status_code": 422})
     * })
     */
    public function store(Request $request)
    {
        $vehicle_coupon_data = $request->only('name', 'description', 'discount', 'discount_type_id', 'no_of_quantity', 'validity_start_date', 'validity_end_date', 'maximum_discount_amount', 'is_active');
        if (!is_null($vehicle_coupon_data['validity_start_date'])) {
            $vehicle_coupon_data['validity_start_date'] = date("Y-m-d", strtotime($vehicle_coupon_data['validity_start_date']));
        }
        if (!is_null($vehicle_coupon_data['validity_end_date'])) {
            $vehicle_coupon_data['validity_end_date'] = date("Y-m-d", strtotime($vehicle_coupon_data['validity_end_date']));
        }
		$cur_date = Carbon::now()->toDateTimeString();
		if ($cur_date > $vehicle_coupon_data['validity_start_date'] || $vehicle_coupon_data['validity_start_date'] > $vehicle_coupon_data['validity_end_date']) {
			throw new \Dingo\Api\Exception\StoreResourceFailedException('Start date should be less than end date and greater than current date.');
		}	
        $validator = Validator::make($vehicle_coupon_data, VehicleCoupon::GetValidationRule($request->method()), VehicleCoupon::GetValidationMessage());
        if ($validator->passes()) {
            $vehicle_coupon_data['model_type'] = config('constants.ConstBookingTypes.Booking');
            if (isPluginEnabled('Vehicles') && $request->has('vehicle_id')) {
                $vehicle = \Plugins\Vehicles\Model\Vehicle::find($request->vehicle_id);
                if (!$vehicle) {
                    return $this->response->errorNotFound("Invalid Request");
                }
            }
            $vehicle_coupon = VehicleCoupon::create($vehicle_coupon_data);
            if ($vehicle_coupon) {
                if (isPluginEnabled('Vehicles') && $request->has('vehicle_id')) {
                    $vehicle = \Plugins\Vehicles\Model\Vehicle::with(['vehicle_coupons'])->where('id', '=', $request->vehicle_id)->first();
                    $vehicle->vehicle_coupons()->save($vehicle_coupon);
                }
                return response()->json(['Success' => 'VehicleCoupon has been added'], 200);
            } else {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleCoupon could not be added. Please, try again.');
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleCoupon could not be added. Please, try again.', $validator->errors());
        }
    }

    /**
     * Edit the specified coupon.
     * Edit the coupon with a `id`.
     * @Get("vehicle_coupons/{id}/edit")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body= {"id": 1, "name": "coupon", "description": "coupon description", "discount": 10, "discount_type": "%", "no_of_quantity": 2, "validity_start_date": "12-05-2016", "validity_end_date": "13-05-2016", "maximum_discount_amount":100 }),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function edit($id)
    {
        $vehicle_coupon = VehicleCoupon::filterByVehicleRental()->find($id);
        if (!$vehicle_coupon) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($vehicle_coupon, (new VehicleCouponTransformer)->setDefaultIncludes(['couponable']));
    }

    /**
     * Update the specified coupon.
     * Update the coupon with a `id`.
     * @Put("vehicle_coupons/{id}")
     * @Transaction({
     *      @Request({"id": 1, "vehicle_id":1, "name": "coupon", "description": "coupon description", "discount": 10, "discount_type": "%", "no_of_quantity": 2, "validity_start_date": "12-05-2016", "validity_end_date": "13-05-2016", "maximum_discount_amount":100}),
     *      @Response(200, body={"success": "VehicleCoupon has been updated."}),
     *      @Response(422, body={"message": "VehicleCoupon could not be updated. Please, try again.", "errors": {"name": {}}, "status_code": 422})
     * })
     */
    public function update(Request $request, $id)
    {
        $vehicle_coupon_data = $request->only('name', 'description', 'discount', 'discount_type_id', 'no_of_quantity', 'validity_start_date', 'validity_end_date', 'maximum_discount_amount', 'is_active', 'vehicle_id');
        if (!is_null($vehicle_coupon_data['validity_start_date'])) {
            $vehicle_coupon_data['validity_start_date'] = date("Y-m-d", strtotime($vehicle_coupon_data['validity_start_date']));
        }
        if (!is_null($vehicle_coupon_data['validity_end_date'])) {
            $vehicle_coupon_data['validity_end_date'] = date("Y-m-d", strtotime($vehicle_coupon_data['validity_end_date']));
        }
		$cur_date = Carbon::now()->toDateTimeString();
		if ($cur_date > $vehicle_coupon_data['validity_start_date'] || $vehicle_coupon_data['validity_start_date'] > $vehicle_coupon_data['validity_end_date']) {
			throw new \Dingo\Api\Exception\StoreResourceFailedException('Start date should be less than end date and greater than current date.');
		}
        $vehicle_coupon = false;
        if ($request->has('id')) {
            $vehicle_coupon = VehicleCoupon::filterByVehicleRental()->find($id);
            $vehicle_coupon = ($request->id != $id) ? false : $vehicle_coupon;
        }
        if ($vehicle_coupon && $vehicle_coupon->name == $vehicle_coupon_data['name']) {
            unset($vehicle_coupon_data['name']);
        }
        $validator = Validator::make($vehicle_coupon_data, VehicleCoupon::GetValidationRule(), VehicleCoupon::GetValidationMessage());
        if ($validator->passes() && $vehicle_coupon) {
            if (isPluginEnabled('Vehicles') && $request->has('vehicle_id')) {
                $vehicle = \Plugins\Vehicles\Model\Vehicle::find($request->vehicle_id);
                if (!$vehicle) {
                    return $this->response->errorNotFound("Vehicle not found");
                }
            }
            try {
                $vehicle_coupon->update($vehicle_coupon_data);
                if (isPluginEnabled('Vehicles') && $request->has('vehicle_id')) {
                    $vehicle = \Plugins\Vehicles\Model\Vehicle::with(['vehicle_coupons'])->where('id', '=', $request->vehicle_id)->first();
                    $vehicle->vehicle_coupons()->save($vehicle_coupon);
                }
                return response()->json(['Success' => 'VehicleCoupon has been updated'], 200);
            } catch (\Exception $e) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleCoupon could not be updated. Please, try again.');
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleCoupon could not be updated. Please, try again.', $validator->errors());
        }
    }

    /**
     * Delete the specified coupon.
     * Delete the coupon with a `id`.
     * @Delete("vehicle_coupons/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"success": "VehicleCoupon Deleted."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function destroy($id)
    {
        $vehicle_coupon = VehicleCoupon::filterByVehicleRental()->find($id);
        if (!$vehicle_coupon) {
            return $this->response->errorNotFound("Invalid Request");
        } else {
            $vehicle_coupon->delete();
        }
        return response()->json(['Success' => 'VehicleCoupon deleted'], 200);
    }

    /**
     * Show the specified coupon.
     * Show the coupon with a `id`.
     * @Get("coupons/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"id": 1, "name": "coupon", "description": "coupon description", "discount": 10, "discount_type": "%", "no_of_quantity": 2, "validity_start_date": "12-05-2016", "validity_end_date": "13-05-2016", "maximum_discount_amount":100}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function show($id)
    {
        $vehicle_coupon = VehicleCoupon::filterByVehicleRental()->find($id);
        if (!$vehicle_coupon) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($vehicle_coupon, (new VehicleCouponTransformer)->setDefaultIncludes(['couponable']));
    }
}
