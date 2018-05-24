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

use Plugins\Vehicles\Model\VehicleMake;

class VehicleMakeService
{
    /**
     * VehicleMakeService constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getVehicleMakeList()
    {
        $vehicle_make_list = VehicleMake::where('is_active', true)->lists('id', 'name');
        foreach($vehicle_make_list as $value=>$key) {
            $vehicle_make_list[$value] = (integer)$key;
        }
        return $vehicle_make_list;
    }
}
