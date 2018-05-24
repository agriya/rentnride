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
 
namespace Plugins\VehicleSurcharges\Services;

use Plugins\VehicleSurcharges\Model\VehicleTypeSurcharge;
use Plugins\VehicleRentals\Model\VehicleRentalAdditionalCharge;

class VehicleSurchargeService
{
    /**
     * VehicleSurchargeService constructor.
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
    public function processSurchargeAmount($vehicle_type_id, $item_user_id, $booking_calculated_details)
    {
        $res_amount = 0;
        $vehicle_type_surcharges = VehicleTypeSurcharge::where('vehicle_type_id', $vehicle_type_id)->get();
        if(!empty($vehicle_type_surcharges)) {
            foreach ($vehicle_type_surcharges as $vehicle_type_surcharge) {
                if (!empty($vehicle_type_surcharge->rate)) {
                    if (!empty($vehicle_type_surcharge->discount_type_id) && $vehicle_type_surcharge->discount_type_id == 1) {
                        $surcharge_amount = $booking_calculated_details['booking_amount'] * $vehicle_type_surcharge->rate / 100;
                    } else if (!empty($vehicle_type_surcharge->discount_type_id) && $vehicle_type_surcharge->discount_type_id == 2) {
                        $surcharge_amount = $vehicle_type_surcharge->rate;
                    }
                    if (!empty($vehicle_type_surcharge->duration_type_id) && $vehicle_type_surcharge->duration_type_id == 1) {
                        if (!empty($booking_calculated_details['total_hours'])) {
                            $booking_calculated_details['total_days'] = $booking_calculated_details['total_days'] + 1;
                        }
                        $surcharge_amount = $surcharge_amount * $booking_calculated_details['total_days'];
                    }
                    if (!empty($vehicle_type_surcharge->max_allowed_amount)) {
                        if ($surcharge_amount > $vehicle_type_surcharge->max_allowed_amount) {
                            $surcharge_amount = $vehicle_type_surcharge->max_allowed_amount;
                        }
                    }
                }
                $vehicle_rental_additional_data['item_user_id'] = $item_user_id;
                $vehicle_rental_additional_data['amount'] = $surcharge_amount;
                $vehicle_rental_additional_charge = VehicleRentalAdditionalCharge::create($vehicle_rental_additional_data);
                $vehicle_surcharge = VehicleTypeSurcharge::with(['vehicle_rental_additional_charges'])->where('id', '=', $vehicle_type_surcharge->id)->first();
                $vehicle_surcharge->vehicle_rental_additional_charges()->save($vehicle_rental_additional_charge);
                $res_amount = $res_amount + $surcharge_amount;
            }
            return $res_amount;
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Given Surcharges not available. Please, try again.');
        }
    }

}
