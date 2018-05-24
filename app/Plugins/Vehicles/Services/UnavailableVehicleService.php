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

use Plugins\Vehicles\Model\UnavailableVehicle;

class UnavailableVehicleService
{
    /**
     * UnavailableVehicleService constructor.
     */
    public function __construct()
    {
    }

    /**
     * Clear unavailable vehicle by id
     * @param $id
     */
    public function clearUnavaialablelist($item_user_id)
    {
        $unavailable_vehicle = UnavailableVehicle::where('item_user_id',$item_user_id)->first();
        if(!$unavailable_vehicle){
            return $this->response->errorNotFound("Invalid Request");
        } else {
            $unavailable_vehicle->delete();
        }
    }

    /**
     * check the booking is available before payment
     * @param $vehicle_rental
     */
    public function checkBookingAvailability($vehicle_rental){
        $start_date = $vehicle_rental->item_booking_start_date;
        $end_date = $vehicle_rental->item_booking_end_date;
        $unavailable_vehicle = UnavailableVehicle::where('vehicle_id', $vehicle_rental->item_userable_id)
            ->where(function ($query) use ($start_date, $end_date) {
                $query->whereBetween('start_date', [$start_date, $end_date])
                    ->orWhereBetween('end_date', [$start_date, $end_date])
                    ->orwhere(function ($query) use ($start_date, $end_date) {
                        $query->where('start_date', '>', $start_date)
                            ->where('end_date', '<', $start_date);
                    })->orwhere(function ($query) use ($start_date, $end_date) {
                        $query->where('start_date', '<', $end_date)
                            ->where('end_date', '>', $end_date);
                    });
            })
            ->where('is_dummy', '!=', 1)->first();
        return $unavailable_vehicle;
    }
}
