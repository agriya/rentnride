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
 
namespace Plugins\Vehicles\Transformers;

use League\Fractal;
use Plugins\Vehicles\Model\VehicleType;


/**
 * Class VehiclTypeSimpleTransformer
 * @package Plugins\Vehicles\Transformers
 */
class VehicleTypeSimpleTransformer extends Fractal\TransformerAbstract
{
    /**
     * @param Vehicle $vehicle
     * @return array
     */
    public function transform(VehicleType $vehicle_type)
    {
        $output = array_only($vehicle_type->toArray(), ['id', 'name']);
        return $output;
    }

}
