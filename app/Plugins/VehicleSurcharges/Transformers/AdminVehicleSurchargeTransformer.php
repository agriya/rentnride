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
 
namespace Plugins\VehicleSurcharges\Transformers;

use League\Fractal;
use Plugins\VehicleSurcharges\Model\VehicleSurcharge;

/**
 * Class VehicleSurchargeTransformer
 * @package VehicleSurcharges\Transformers
 */
class AdminVehicleSurchargeTransformer extends Fractal\TransformerAbstract
{

    /**
     * @param VehicleSurcharge $vehicle_surcharge
     * @return array
     */
    public function transform(VehicleSurcharge $vehicle_surcharge)
    {
        $output = array_only($vehicle_surcharge->toArray(), ['id', 'created_at', 'name', 'short_description', 'description', 'is_active']);
        $output['is_active'] = ($output['is_active']) ? true : false;
        return $output;
    }


}