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
 
namespace Plugins\VehicleFuelOptions\Transformers;

use App\Transformers\DiscountTypeTransformer;
use App\Transformers\DurationTypeTransformer;
use League\Fractal;
use Plugins\VehicleFuelOptions\Model\VehicleTypeFuelOption;

/**
 * Class VehicleFuelOptionTransformer
 * @package VehicleFuelOptions\Transformers
 */
class VehicleTypeFuelOptionTransformer extends Fractal\TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'VehicleFuelOption', 'VehicleType', 'DurationType', 'DiscountType'
    ];

    /**
     * @param VehicleTypeFuelOption $vehicle_type_fuel_option
     * @return array
     */
    public function transform(VehicleTypeFuelOption $vehicle_type_fuel_option)
    {
        $output = array_only($vehicle_type_fuel_option->toArray(), ['id', 'created_at', 'vehicle_type_id', 'fuel_option_id', 'rate', 'discount_type_id', 'duration_type_id', 'max_allowed_amount', 'is_active']);
        $output['is_active'] = ($output['is_active']) ? true : false;
        return $output;
    }

    /**
     * @param VehicleTypeFuelOption $vehicle_type_fuel_option
     * @return Fractal\Resource\Item|null
     */
    public function includeVehicleFuelOption(VehicleTypeFuelOption $vehicle_type_fuel_option)
    {
        if ($vehicle_type_fuel_option->vehicle_fuel_option) {
            return $this->item($vehicle_type_fuel_option->vehicle_fuel_option, new VehicleFuelOptionTransformer());
        } else {
            return null;
        }

    }

    /**
     * @param VehicleTypeFuelOption $vehicle_type_fuel_option
     * @return Fractal\Resource\Item|null
     */
    public function includeVehicleType(VehicleTypeFuelOption $vehicle_type_fuel_option)
    {
        if ($vehicle_type_fuel_option->vehicle_type) {
            return $this->item($vehicle_type_fuel_option->vehicle_type, new \Plugins\Vehicles\Transformers\VehicleTypeTransformer());
        } else {
            return null;
        }

    }

    /**
     * @param VehicleTypeFuelOption $vehicle_type_fuel_option
     * @return Fractal\Resource\Item|null
     */
    public function includeDiscountType(VehicleTypeFuelOption $vehicle_type_fuel_option)
    {
        if ($vehicle_type_fuel_option->discount_type) {
            return $this->item($vehicle_type_fuel_option->discount_type, new DiscountTypeTransformer());
        } else {
            return null;
        }

    }

    /**
     * @param VehicleTypeFuelOption $vehicle_type_fuel_option
     * @return Fractal\Resource\Item|null
     */
    public function includeDurationType(VehicleTypeFuelOption $vehicle_type_fuel_option)
    {
        if ($vehicle_type_fuel_option->duration_type) {
            return $this->item($vehicle_type_fuel_option->duration_type, new DurationTypeTransformer());
        } else {
            return null;
        }

    }
}