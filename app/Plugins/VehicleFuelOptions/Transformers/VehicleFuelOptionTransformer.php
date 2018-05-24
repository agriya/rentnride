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
 
namespace Plugins\VehicleFuelOptions\Transformers;

use League\Fractal;
use Plugins\VehicleFuelOptions\Model\VehicleFuelOption;
use Plugins\VehicleFuelOptions\Model\VehicleTypeFuelOption;

/**
 * Class VehicleFuelOptionTransformer
 * @package VehicleFuelOptions\Transformers
 */
class VehicleFuelOptionTransformer extends Fractal\TransformerAbstract
{
    /**
     * @param VehicleFuelOption $vehicle_fuel_option
     * @return array
     */
    public function transform(VehicleFuelOption $vehicle_fuel_option)
    {
        $output = array_only($vehicle_fuel_option->toArray(), ['id', 'name', 'description']);
        return $output;
    }
}