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
 
namespace Plugins\VehicleInsurances\Services;

use Plugins\VehicleInsurances\Model\VehicleTypeInsurance;
use Plugins\VehicleRentals\Model\VehicleRentalAdditionalCharge;

class VehicleInsuranceService
{
    /**
     * VehicleInsuranceService constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param $item_user_id
     * @param $amount
     * @param $insurances
     * @param $no_of_days
     * @return int
     */
    public function processInsuranceAmount($item_user_id, $amount, $insurances, $no_of_days)
    {
        $res_amount = 0;
        $vehicle_type_insurances = VehicleTypeInsurance::whereIn('id', $insurances)->get();
        if (!empty($vehicle_type_insurances)) {
            foreach ($vehicle_type_insurances as $vehicle_type_insurance) {
                if (!empty($vehicle_type_insurance->rate)) {
                    if (!empty($vehicle_type_insurance->discount_type_id) && $vehicle_type_insurance->discount_type_id == 1) {
                        $insurance_amount = $amount * $vehicle_type_insurance->rate / 100;
                    } else if (!empty($vehicle_type_insurance->discount_type_id) && $vehicle_type_insurance->discount_type_id == 2) {
                        $insurance_amount = $vehicle_type_insurance->rate;
                    }
                    if (!empty($vehicle_type_insurance->duration_type_id) && $vehicle_type_insurance->duration_type_id == 1) {
                        $insurance_amount = $insurance_amount * $no_of_days;
                    }
                    if (!empty($vehicle_type_insurance->max_allowed_amount)) {
                        if ($insurance_amount > $vehicle_type_insurance->max_allowed_amount) {
                            $insurance_amount = $vehicle_type_insurance->max_allowed_amount;
                        }
                    }
                }
                $vehicle_rental_additional_data['item_user_id'] = $item_user_id;
                $vehicle_rental_additional_data['amount'] = $insurance_amount;
                $vehicle_rental_additional_charge = VehicleRentalAdditionalCharge::create($vehicle_rental_additional_data);
                $vehicle_insurance = VehicleTypeInsurance::with(['vehicle_rental_additional_charges'])->where('id', '=', $vehicle_type_insurance->id)->first();
                $vehicle_insurance->vehicle_rental_additional_charges()->save($vehicle_rental_additional_charge);
                $res_amount = $res_amount + $insurance_amount;
            }
            return $res_amount;
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Given Insurances not available. Please, try again.');
        }
    }

}
