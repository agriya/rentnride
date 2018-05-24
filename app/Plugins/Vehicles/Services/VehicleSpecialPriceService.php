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

use Plugins\Vehicles\Model\VehicleSpecialPrice;

class VehicleSpecialPriceService
{
    /**
     * VehicleSpecialPriceService constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getVehicleSpecialPriceList()
    {
        $vehicle_special_price_list = VehicleSpecialPrice::where('is_active', true)->lists('id', 'vehicle_type_id', 'start_date', 'end_date', 'discount_percentage');
        return $vehicle_special_price_list;
    }

    /**
     * @param $vehicle_type_id
     * @param $booking_amount
     * @param $vehicle_rental_start_date
     * @param $vehicle_rental_end_date
     * @return float|int
     */
    public function processSpecialPriceAmount($vehicle_type_id, $booking_amount, $vehicle_rental_start_date, $vehicle_rental_end_date)
    {
        $special_price_amount = 0;
        $vehicle_special_price = VehicleSpecialPrice::where('vehicle_type_id', $vehicle_type_id)
            ->where('start_date', '<=', $vehicle_rental_start_date)
            ->where('end_date', '>=', $vehicle_rental_end_date)
            ->where('is_active', 1)
            ->orderBy('discount_percentage', 'desc')->first();
        if (!empty($vehicle_special_price)) {
            $special_price_amount = $booking_amount * $vehicle_special_price->discount_percentage / 100;
        }
        return $special_price_amount;
    }
}
