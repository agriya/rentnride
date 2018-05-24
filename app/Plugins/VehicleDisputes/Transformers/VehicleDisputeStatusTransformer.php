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
use Plugins\VehicleDisputes\Model\VehicleDisputeStatus;

class VehicleDisputeStatusTransformer extends Fractal\TransformerAbstract
{

    public function transform(VehicleDisputeStatus $vehicle_dispute_status)
    {
        $output = array_only($vehicle_dispute_status->toArray(), ['id', 'name']);
        return $output;
    }
}
