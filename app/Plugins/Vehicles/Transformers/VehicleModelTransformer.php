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
use Plugins\Vehicles\Model\VehicleModel;
use Plugins\Vehicles\Transformers\VehicleMakeTransformer;

/**
 * Class VehicleModelTransformer
 * @package Plugins\Vehicles\Transformers
 */
class VehicleModelTransformer extends Fractal\TransformerAbstract
{
    /**
     * @var array
     */
    protected $availableIncludes = [
        'VehicleMake'
    ];

    /**
     * @param VehicleModel $vehicle_model
     * @return array
     */
    public function transform(VehicleModel $vehicle_model)
    {
        $output = array_only($vehicle_model->toArray(), ['id', 'name']);
        return $output;
    }

    /**
     * @param VehicleModel $vehicle_model
     * @return Fractal\Resource\Item|null
     */
    public function includeVehicleMake(VehicleModel $vehicle_model)
    {
        if ($vehicle_model->vehicle_make) {
            return $this->item($vehicle_model->vehicle_make, new AdminVehicleMakeTransformer());
        } else {
            return null;
        }
    }
}
