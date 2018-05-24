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
 
namespace Plugins\VehicleExtraAccessories\Transformers;

use League\Fractal;
use Plugins\VehicleExtraAccessories\Model\VehicleExtraAccessory;

/**
 * Class VehicleExtraAccessoryTransformer
 * @package VehicleExtraAccessories\Transformers
 */
class AdminVehicleExtraAccessoryTransformer extends Fractal\TransformerAbstract
{

    /**
     * @param VehicleExtraAccessory $vehicle_extra_accessory
     * @return array
     */
    public function transform(VehicleExtraAccessory $vehicle_extra_accessory)
    {
        $output = array_only($vehicle_extra_accessory->toArray(), ['id', 'created_at', 'name', 'short_description', 'description', 'is_active']);
        $output['is_active'] = ($output['is_active']) ? true : false;
        return $output;
    }


}