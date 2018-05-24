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
 
namespace Plugins\VehicleFuelOptions\Services;

use Plugins\VehicleFuelOptions\Model\VehicleTypeFuelOption;
use Plugins\VehicleRentals\Model\VehicleRentalAdditionalCharge;

class VehicleFuelOptionService
{
    /**
     * VehicleFuelOptionService constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param $item_user_id
     * @param $amount
     * @param $fuel_options
     * @param $no_of_days
     * @return int
     */
    public function processFuelOptionAmount($item_user_id, $amount, $fuel_options, $no_of_days)
    {
        $res_amount = 0;
        $vehicle_type_fuel_options = VehicleTypeFuelOption::whereIn('id', $fuel_options)->get();
        if (!empty($vehicle_type_fuel_options)) {
            foreach ($vehicle_type_fuel_options as $vehicle_type_fuel_option) {
                $fuel_option_amount = 0;
                if (!empty($vehicle_type_fuel_option->rate)) {
                    if (!empty($vehicle_type_fuel_option->discount_type_id) && $vehicle_type_fuel_option->discount_type_id == 1) {
                        $fuel_option_amount = $amount * $vehicle_type_fuel_option->rate / 100;
                    } else if (!empty($vehicle_type_fuel_option->discount_type_id) && $vehicle_type_fuel_option->discount_type_id == 2) {
                        $fuel_option_amount = $vehicle_type_fuel_option->rate;
                    }
                    if (!empty($vehicle_type_fuel_option->duration_type_id) && $vehicle_type_fuel_option->duration_type_id == 1) {
                        $fuel_option_amount = $fuel_option_amount * $no_of_days;
                    }
                    if (!empty($vehicle_type_fuel_option->max_allowed_amount)) {
                        if ($fuel_option_amount > $vehicle_type_fuel_option->max_allowed_amount) {
                            $fuel_option_amount = $vehicle_type_fuel_option->max_allowed_amount;
                        }
                    }
                }
                $vehicle_rental_additional_data['item_user_id'] = $item_user_id;
                $vehicle_rental_additional_data['amount'] = $fuel_option_amount;
                $vehicle_rental_additional_charge = VehicleRentalAdditionalCharge::create($vehicle_rental_additional_data);
                $vehicle_fuel_option = VehicleTypeFuelOption::with(['vehicle_rental_additional_charges'])->where('id', '=', $vehicle_type_fuel_option->id)->first();
                $vehicle_fuel_option->vehicle_rental_additional_charges()->save($vehicle_rental_additional_charge);
                $res_amount = $res_amount + $fuel_option_amount;
            }
            return $res_amount;
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Given Fuel options not available. Please, try again.');
        }
    }

}
