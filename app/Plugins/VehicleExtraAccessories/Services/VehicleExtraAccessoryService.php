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
 
namespace Plugins\VehicleExtraAccessories\Services;

use Plugins\VehicleExtraAccessories\Model\VehicleTypeExtraAccessory;
use Plugins\VehicleRentals\Model\VehicleRentalAdditionalCharge;

class VehicleExtraAccessoryService
{
    /**
     * VehicleExtraAccessoryService constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param $item_user_id
     * @param $amount
     * @param $extra_accessories
     * @param $no_of_days
     * @return int
     */
    public function processExtraAccessoryAmount($item_user_id, $amount, $extra_accessories, $no_of_days)
    {
        $res_amount = 0;
        $vehicle_type_extra_accessories = VehicleTypeExtraAccessory::whereIn('id', $extra_accessories)->get();
        foreach ($vehicle_type_extra_accessories as $vehicle_type_extra_accessory) {
            if (!empty($vehicle_type_extra_accessory->rate)) {
                if (!empty($vehicle_type_extra_accessory->discount_type_id) && $vehicle_type_extra_accessory->discount_type_id == 1) {
                    $extra_accessory_amount = $amount * $vehicle_type_extra_accessory->rate / 100;
                } else if (!empty($vehicle_type_extra_accessory->discount_type_id) && $vehicle_type_extra_accessory->discount_type_id == 2) {
                    $extra_accessory_amount = $vehicle_type_extra_accessory->rate;
                }
                if (!empty($vehicle_type_extra_accessory->duration_type_id) && $vehicle_type_extra_accessory->duration_type_id == 1) {
                    $extra_accessory_amount = $extra_accessory_amount * $no_of_days;
                }
                if (!empty($vehicle_type_extra_accessory->max_allowed_amount)) {
                    if ($extra_accessory_amount > $vehicle_type_extra_accessory->max_allowed_amount) {
                        $extra_accessory_amount = $vehicle_type_extra_accessory->max_allowed_amount;
                    }
                }
            }
            $vehicle_rental_additional_data['item_user_id'] = $item_user_id;
            $vehicle_rental_additional_data['amount'] = $extra_accessory_amount;
            $vehicle_rental_additional_charge = VehicleRentalAdditionalCharge::create($vehicle_rental_additional_data);
            $vehicle_extra_accessory = VehicleTypeExtraAccessory::with(['vehicle_rental_additional_charges'])->where('id', '=', $vehicle_type_extra_accessory->id)->first();
            $vehicle_extra_accessory->vehicle_rental_additional_charges()->save($vehicle_rental_additional_charge);
            $res_amount = $res_amount + $extra_accessory_amount;
        }
        return $res_amount;
    }

}
