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
 
namespace Plugins\VehicleDisputes\Services;

use Plugins\VehicleDisputes\Model\VehicleDisputeClosedType;

class VehicleDisputeClosedTypeService
{


    /**
     * VehicleDisputeService constructor.
     */
    public function __construct()
    {

    }

    /**
     * @param $close_type_id
     * @return string
     */
    public function getDisputeClosedType($close_type_id)
    {
        $dispute_close_type = VehicleDisputeClosedType::where('id', '=', $close_type_id)->first();
        if (!empty($dispute_close_type)) {
            return $dispute_close_type;
        } else {
            return '';
        }
    }

    /**
     * @param $dispute_type_id
     * @return string
     */
    public function getClosedTypeByDisputeType($dispute_type_id)
    {
        $dispute_close_type = VehicleDisputeClosedType::where('dispute_type_id', '=', $dispute_type_id)->pluck('id')->all();
        if (!empty($dispute_close_type)) {
            return $dispute_close_type;
        } else {
            return '';
        }
    }
}
