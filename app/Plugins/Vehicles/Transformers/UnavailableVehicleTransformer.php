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
use Plugins\Vehicles\Model\UnavailableVehicle;
use Plugins\Vehicles\Transformers\VehicleTransformer;
use Carbon;

/**
 * Class UnavailableVehicleTransformer
 * @package Plugins\Vehicles\Transformers
 */
class UnavailableVehicleTransformer extends Fractal\TransformerAbstract
{
    /**
     * @var array
     */
    protected $availableIncludes = [
        'Vehicle'
    ];

    /**
     * @param UnavailableVehicle $unavailable_vehicle
     * @return array
     */
    public function transform(UnavailableVehicle $unavailable_vehicle)
    {
        $output = array_only($unavailable_vehicle->toArray(), ['id', 'item_user_id', 'vehicle_id', 'start_date', 'end_date', 'is_dummy']);
        return $output;
    }

    /**
     * @param UnavailableVehicle $unavailable_vehicle
     * @return Fractal\Resource\Item|null
     */
    public function includeVehicle(UnavailableVehicle $unavailable_vehicle)
    {
        if ($unavailable_vehicle->vehicle) {
            return $this->item($unavailable_vehicle->vehicle, new VehicleTransformer());
        } else {
            return null;
        }
    }
}
