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

use Plugins\Vehicles\Model\VehicleModel;
use Plugins\Vehicles\Model\VehicleMake;

class VehicleModelService
{
    /**
     * VehicleModelService constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getVehicleModelList()
    {
        $vehicle_model_list = VehicleModel::where('is_active', true)->lists('id', 'name');
        return $vehicle_model_list;
    }

    public function afterSave($vehicle_model, $vehicle_model_old = false){
        // vehicle_model_count update to VehicleMake
        if($vehicle_model){
            $model_count = VehicleModel::where('is_active', 1)->where('vehicle_make_id', $vehicle_model->vehicle_make_id)->count();
            VehicleMake::where('id', '=', $vehicle_model->vehicle_make_id)->update(['vehicle_model_count' => $model_count]);
        }
        if($vehicle_model_old){
            if($vehicle_model_old->vehicle_make_id !=  $vehicle_model->vehicle_make_id){
                $model_count = VehicleModel::where('is_active', 1)->where('vehicle_make_id', $vehicle_model_old->vehicle_make_id)->count();
                VehicleMake::where('id', '=', $vehicle_model_old->vehicle_make_id)->update(['vehicle_model_count' => $model_count]);
            }

        }
    }
}
