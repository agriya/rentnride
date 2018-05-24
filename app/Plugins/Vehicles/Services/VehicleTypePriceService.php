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
 
namespace Plugins\Vehicles\Services;

use Plugins\Vehicles\Model\VehicleTypePrice;

class VehicleTypePriceService
{
    /**
     * VehicleTypePriceService constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getVehicleTypePriceList()
    {
        $vehicle_type_price_list = VehicleTypePrice::where('is_active', true)->lists('id', 'vehicle_type_id', 'minimum_no_of_day', 'maximum_no_of_day', 'discount_percentage');
        return $vehicle_type_price_list;
    }

    /**
     * @param $vehicle_type_id
     * @param $booking_amount
     * @param $date_diff
     * @return float|int
     */
    public function processTypePriceAmount($vehicle_type_id, $booking_amount, $date_diff)
    {
        $vehicle_type_price_amount = 0;
        if ($date_diff['total_hours']) {
            $date_diff['total_days'] = $date_diff['total_days'] + 1;
        }
        $vehicle_type_price = VehicleTypePrice::where('vehicle_type_id', $vehicle_type_id)
            ->where('minimum_no_of_day', '<=', $date_diff['total_days'])
            ->where('maximum_no_of_day', '>=', $date_diff['total_days'])
            ->where('is_active', 1)
            ->orderBy('discount_percentage', 'desc')->first();
        if (!empty($vehicle_type_price)) {
            $vehicle_type_price_amount = $booking_amount * $vehicle_type_price->discount_percentage / 100;
        }
        return $vehicle_type_price_amount;
    }
}
