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
 
namespace Plugins\VehicleRentals\Services;

use Plugins\VehicleRentals\Model\VehicleRentalLatePaymentDetail;
use Carbon;

class VehicleRentalLatePaymentDetailService
{

    /**
     * VehicleRentalService constructor.
     */
    public function __construct()
    {

    }

    public function addRentalDetail($vehicle_rental)
    {
        $vehicle_rental_late_payment_data['item_user_id'] = $vehicle_rental->id;
        $vehicle_rental_late_payment_data['booking_start_date'] = $vehicle_rental->item_booking_start_date;
        $vehicle_rental_late_payment_data['booking_end_date'] = $vehicle_rental->item_booking_end_date;
        $vehicle_rental_late_payment_data['checkin_date'] = Carbon::now()->toDateTimeString();
        $vehicle_rental_late_payment_data['booking_amount'] = $vehicle_rental->total_amount;
        VehicleRentalLatePaymentDetail::create($vehicle_rental_late_payment_data);
    }

    public function updateRentalDetail($vehicle_rental, $total_late_hours)
    {
        $vehicle_rental_late_payment_data['late_checkout_fee'] = $vehicle_rental->late_fee;
        $vehicle_rental_late_payment_data['extra_time_taken'] = ($total_late_hours > 0) ? $total_late_hours : 0;
        $vehicle_rental_late_payment_data['checkout_date'] = Carbon::now()->toDateTimeString();
        VehicleRentalLatePaymentDetail::where('id', '=', $vehicle_rental->late_payment_detail->id)->update($vehicle_rental_late_payment_data);
    }
}
