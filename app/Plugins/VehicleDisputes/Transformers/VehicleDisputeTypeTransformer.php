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
 
namespace Plugins\VehicleDisputes\Transformers;

use League\Fractal;
use Plugins\VehicleDisputes\Model\VehicleDisputeType;

class VehicleDisputeTypeTransformer extends Fractal\TransformerAbstract
{

    public function transform(VehicleDisputeType $vehicle_dispute_type)
    {
        $output = array_only($vehicle_dispute_type->toArray(), ['id', 'name', 'is_booker', 'is_active']);
        $output['is_active'] = ($output['is_active'] == 1) ? true : false;
        $output['is_booker'] = ($output['is_booker'] == 1) ? true : false;
        return $output;
    }
}
