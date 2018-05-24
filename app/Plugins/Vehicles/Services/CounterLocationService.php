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

use Plugins\Vehicles\Model\CounterLocation;
use Plugins\Vehicles\Model\CounterLocationVehicle;
use Plugins\Vehicles\Model\Vehicle;

class CounterLocationService
{
    /**
     * CounterLocationService constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getCounterLocationList()
    {
        $counter_location_list = CounterLocation::lists('id', 'address');
        return $counter_location_list;
    }

    public function getVehicleCounterLocation($vehicle_id)
    {
        $vehicle_locations = Vehicle::with(array('counter_location'))->where('id', '=', $vehicle_id)->first();
        $vehicle_counter_locations = array();
        $i = 0;

        foreach ($vehicle_locations->counter_location as $counter_location) {
            if ($counter_location->pivot->is_pickup == 1) {
                $vehicle_counter_locations['pickup'][$i]['id'] = $counter_location->id;
                $vehicle_counter_locations['pickup'][$i]['address'] = $counter_location->address;
                $vehicle_counter_locations['pickup'][$i]['latitude'] = $counter_location->latitude;
                $vehicle_counter_locations['pickup'][$i]['longitude'] = $counter_location->longitude;
            }
            if ($counter_location->pivot->is_drop == 1) {
                $vehicle_counter_locations['drop'][$i]['id'] = $counter_location->id;
                $vehicle_counter_locations['drop'][$i]['address'] = $counter_location->address;
                $vehicle_counter_locations['drop'][$i]['latitude'] = $counter_location->latitude;
                $vehicle_counter_locations['drop'][$i]['longitude'] = $counter_location->longitude;
            }
            $i++;
        }
        return $vehicle_counter_locations;

    }
}
