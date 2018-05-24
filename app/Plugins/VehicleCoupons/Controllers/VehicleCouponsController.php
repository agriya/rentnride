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
 
namespace Plugins\VehicleCoupons\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use JWTAuth;
use Validator;
use Plugins\VehicleCoupons\Model\VehicleCoupon;

/**
 * VehicleCoupons resource representation.
 * @Resource("coupons")
 */
class VehicleCouponsController extends Controller
{
    /**
     * VehicleCouponsController constructor.
     */
    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
    }

    /**
     * Apply coupon from item user.
     * Apply coupon with a `item_id`, `item_user_id` and `name`.
     * @Get("/coupons/{iem_id}/{iem_user_id}")
     * @Transaction({
     *      @Request({"item_user_id": 1, "name": "XXXX"}),
     *      @Response(200, body={"success": "Coupon Code Applied!."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404}),
     *      @Response(404, body={"message": "Invalid Coupon Code!", "status_code": 404})
     * })
     */

    Public function applyCoupon(Request $request, $item_user_id)
    {
        $apply_coupon = array();
        $vehicle_coupon_name = $request->only('name');		
        if (isPluginEnabled('VehicleRentals')) {
            $vehicle_rental = \Plugins\VehicleRentals\Model\VehicleRental::find($item_user_id);
            $vehicle = $vehicle_rental->item_userable;
            if (!$vehicle || !$vehicle_rental || ($vehicle_rental->item_userable_type != 'MorphVehicle')) {
                return $this->response->errorNotFound("Invalid Request");
            }
        }
        $vehicle_coupon = VehicleCoupon::where(['is_active' => true, 'name' => $vehicle_coupon_name])->first();
        if (!$vehicle_coupon) {
            return $this->response->errorNotFound("Invalid Coupon Code!");
        }
        if(is_null($vehicle_coupon->couponable) || $vehicle_coupon->couponable_id != $vehicle_rental->item_userable_id) {
            return $this->response->errorNotFound("Invalid Coupon Code!");
        }
        if($vehicle_rental->coupon_id || $vehicle_rental->coupon_id == $vehicle_coupon->id){
            return $this->response->errorNotFound("Coupon already used");
        }
		if(($vehicle_coupon->validity_start_date != '0000-00-00 00:00:00') && ($vehicle_coupon->validity_end_date != '0000-00-00 00:00:00')) {
			$current_date = date('Y-m-d H:i:s');
			$current_date=date('Y-m-d H:i:s', strtotime($current_date));;
			$validity_start_date = date('Y-m-d H:i:s', strtotime($vehicle_coupon->validity_start_date));
			$validity_end_date = date('Y-m-d H:i:s', strtotime($vehicle_coupon->validity_end_date));
			if ($current_date < $validity_start_date)
			{
				return $this->response->errorNotFound("Invalid Coupon");
			}
			if (($current_date > $validity_end_date) || ($vehicle_coupon->no_of_quantity_used == $vehicle_coupon->no_of_quantity))
			{
				return $this->response->errorNotFound("Coupon Validity Expired");
			}
		}
		try {
			$amount = $vehicle_coupon->maximum_discount_amount;
			if($vehicle_coupon->discount_type_id == config('constants.ConstDiscountTypes.percentage')) {
				$amount = ($vehicle_coupon->discount / 100) * $vehicle_rental->total_amount;
				if($amount > $vehicle_coupon->maximum_discount_amount) {
					$amount = $vehicle_coupon->maximum_discount_amount;
				}
			}
			if(isPluginEnabled('VehicleRentals')) {
				$apply_coupon['coupon_id'] = $vehicle_coupon->id;
				$apply_coupon['coupon_discount_amount'] = $amount;
				$apply_coupon['total_amount'] = $vehicle_rental->total_amount - $amount;
				$vehicle_rental->update($apply_coupon);
				return response()->json(['Success' => 'Coupon Code Applied!'], 200);
			}
		} catch (\Exception $e) {
			throw new \Dingo\Api\Exception\StoreResourceFailedException('Coupon could not be applied. Please, try again.');
		}
    }
}
