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
 
namespace Plugins\VehicleTaxes\Services;

use Plugins\VehicleTaxes\Model\VehicleTypeTax;
use Plugins\VehicleRentals\Model\VehicleRentalAdditionalCharge;

class VehicleTaxService
{
    /**
     * VehicleTaxService constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param $vehicle_type_id
     * @param $item_user_id
     * @param $booking_calculated_details
     * @return int
     */
    public function processTaxAmount($vehicle_type_id, $item_user_id, $booking_calculated_details)
    {
        $res_amount = 0;
        $vehicle_type_taxes = VehicleTypeTax::where('vehicle_type_id', $vehicle_type_id)->get();
        if (!empty($vehicle_type_taxes)) {
            foreach ($vehicle_type_taxes as $vehicle_type_tax) {
                if (!empty($vehicle_type_tax->rate)) {
                    if (!empty($vehicle_type_tax->discount_type_id) && $vehicle_type_tax->discount_type_id == 1) {
                        $tax_amount = $booking_calculated_details['booking_amount'] * $vehicle_type_tax->rate / 100;
                    } else if (!empty($vehicle_type_tax->discount_type_id) && $vehicle_type_tax->discount_type_id == 2) {
                        $tax_amount = $vehicle_type_tax->rate;
                    }
                    if (!empty($vehicle_type_tax->duration_type_id) && $vehicle_type_tax->duration_type_id == 1) {
                        if (!empty($booking_calculated_details['total_hours'])) {
                            $booking_calculated_details['total_days'] = $booking_calculated_details['total_days'] + 1;
                        }
                        $tax_amount = $tax_amount * $booking_calculated_details['total_days'];
                    }
                    if (!empty($vehicle_type_tax->max_allowed_amount)) {
                        if ($tax_amount > $vehicle_type_tax->max_allowed_amount) {
                            $tax_amount = $vehicle_type_tax->max_allowed_amount;
                        }
                    }
                }
                $vehicle_rental_additional_data['item_user_id'] = $item_user_id;
                $vehicle_rental_additional_data['amount'] = $tax_amount;
                $vehicle_rental_additional_charge = VehicleRentalAdditionalCharge::create($vehicle_rental_additional_data);
                $vehicle_tax = VehicleTypeTax::with(['vehicle_rental_additional_charges'])->where('id', '=', $vehicle_type_tax->id)->first();
                $vehicle_tax->vehicle_rental_additional_charges()->save($vehicle_rental_additional_charge);
                $res_amount = $res_amount + $tax_amount;
            }
            return $res_amount;
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Given Taxes not available. Please, try again.');
        }
    }

}
