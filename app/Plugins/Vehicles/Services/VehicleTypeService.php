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

use Plugins\Vehicles\Model\VehicleType;

class VehicleTypeService
{
    /**
     * VehicleTypeService constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getVehicleTypeList()
    {
        $vehicle_type_list = VehicleType::where('is_active', true)->lists('id', 'name');
        foreach($vehicle_type_list as $value=>$key) {
            $vehicle_type_list[$value] = (integer)$key;
        }
        return $vehicle_type_list;
    }

    /**
     * @return mixed
     */
    public function getVehicleTypePriceFilters()
    {
        $vehicle_types = VehicleType::where('is_active', true)->get();
        $vehicle_type_filters = array();
        if ($vehicle_types) {
            $vehicle_type_filters['max_day_price'] = collect($vehicle_types)->max('maximum_day_price');
            $vehicle_type_filters['min_day_price'] = collect($vehicle_types)->min('minimum_day_price');
            $vehicle_type_filters['max_hour_price'] = collect($vehicle_types)->max('maximum_hour_price');
            $vehicle_type_filters['min_hour_price'] = collect($vehicle_types)->min('minimum_hour_price');
        }
        return $vehicle_type_filters;
    }
}
