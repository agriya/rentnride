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
 
namespace Plugins\VehicleCoupons\Transformers;

use League\Fractal;
use Plugins\VehicleCoupons\Model\VehicleCoupon;

/**
 * Class VehicleCouponTransformer
 * @package VehicleCoupons\Transformers
 */
class VehicleCouponTransformer extends Fractal\TransformerAbstract
{
    /**
     * @var array
     */
    protected $availableIncludes = [
        'Couponable'
    ];

    /**
     * @param VehicleCoupon $vehicle_coupon
     * @return array
     */
    public function transform(VehicleCoupon $vehicle_coupon)
    {
        $output = array_only($vehicle_coupon->toArray(), ['id', 'morph_type', 'name', 'description', 'discount', 'discount_type_id', 'no_of_quantity', 'no_of_quantity_used', 'validity_start_date', 'validity_end_date', 'maximum_discount_amount', 'is_active', 'couponable_id']);
        $output['no_of_quantity'] = (int)$output['no_of_quantity'];
        $output['vehicle_id'] = $output['couponable_id'];
        $output['is_active'] = ($output['is_active']) ? true : false;
        return $output;
    }

    /**
     * @param VehicleCoupon $vehicle_coupon
     * @return Fractal\Resource\Item|null
     */
    public function includeCouponable(VehicleCoupon $vehicle_coupon)
    {
        if (!is_null($vehicle_coupon->couponable) && $vehicle_coupon->couponable_type == 'MorphVehicle') {
            //Todo Need to change the vehicle transformer
            return $this->item($vehicle_coupon->couponable, new \Plugins\Vehicles\Transformers\VehicleTransformer());
        } else {
            return null;
        }
    }
}