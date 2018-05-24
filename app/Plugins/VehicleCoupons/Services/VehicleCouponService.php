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
 
namespace Plugins\VehicleCoupons\Services;

use Plugins\VehicleCoupons\Model\VehicleCoupon;
class VehicleCouponService
{

    public function updateCouponquantity($coupon_id)
    {
        $vehicle_coupon = VehicleCoupon::find($coupon_id);
        if ($vehicle_coupon) {
            $data['no_of_quantity_used'] = \Plugins\VehicleRentals\Model\VehicleRental::where('coupon_id',$vehicle_coupon->id)->where('item_user_status_id' ,'!=', config('constants.ConstItemUserStatus.PaymentPending'))->count();
            $vehicle_coupon->update($data);
        }
    }

}
