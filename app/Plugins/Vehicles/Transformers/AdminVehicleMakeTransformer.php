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
use Plugins\Vehicles\Model\VehicleMake;


/**
 * Class AdminVehicleMakeTransformer
 * @package Plugins\Vehicles\Transformers
 */
class AdminVehicleMakeTransformer extends Fractal\TransformerAbstract
{

    /**
     * @param VehicleMake $vehicle_make
     * @return array
     */
    public function transform(VehicleMake $vehicle_make)
    {
        $output = array_only($vehicle_make->toArray(), ['id', 'created_at', 'name', 'slug', 'vehicle_count', 'vehicle_model_count', 'is_active']);
        $output['is_active'] = ($output['is_active'] == 1) ? true : false;
        return $output;
    }


}
