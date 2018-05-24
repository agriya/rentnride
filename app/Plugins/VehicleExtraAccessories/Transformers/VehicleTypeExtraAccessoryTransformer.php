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

use App\Transformers\DiscountTypeTransformer;
use App\Transformers\DurationTypeTransformer;
use League\Fractal;
use Plugins\VehicleExtraAccessories\Model\VehicleTypeExtraAccessory;

/**
 * Class VehicleExtraAccessoryTransformer
 * @package VehicleExtraAccessories\Transformers
 */
class VehicleTypeExtraAccessoryTransformer extends Fractal\TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'VehicleExtraAccessory', 'VehicleType', 'DurationType', 'DiscountType'
    ];

    /**
     * @param VehicleTypeExtraAccessory $vehicle_type_extra_accessory
     * @return array
     */
    public function transform(VehicleTypeExtraAccessory $vehicle_type_extra_accessory)
    {
        $output = array_only($vehicle_type_extra_accessory->toArray(), ['id', 'created_at', 'vehicle_type_id', 'extra_accessory_id', 'rate', 'discount_type_id', 'duration_type_id', 'max_allowed_amount', 'deposit_amount', 'is_active']);
        $output['is_active'] = ($output['is_active']) ? true : false;
        return $output;
    }

    /**
     * @param VehicleTypeExtraAccessory $vehicle_type_extra_accessory
     * @return Fractal\Resource\Item|null
     */
    public function includeVehicleExtraAccessory(VehicleTypeExtraAccessory $vehicle_type_extra_accessory)
    {
        if ($vehicle_type_extra_accessory->vehicle_extra_accessory) {
            return $this->item($vehicle_type_extra_accessory->vehicle_extra_accessory, new VehicleExtraAccessoryTransformer());
        } else {
            return null;
        }

    }

    /**
     * @param VehicleTypeExtraAccessory $vehicle_type_extra_accessory
     * @return Fractal\Resource\Item|null
     */
    public function includeVehicleType(VehicleTypeExtraAccessory $vehicle_type_extra_accessory)
    {
        if ($vehicle_type_extra_accessory->vehicle_type) {
            return $this->item($vehicle_type_extra_accessory->vehicle_type, new \Plugins\Vehicles\Transformers\VehicleTypeTransformer());
        } else {
            return null;
        }

    }

    /**
     * @param VehicleTypeExtraAccessory $vehicle_type_extra_accessory
     * @return Fractal\Resource\Item|null
     */
    public function includeDiscountType(VehicleTypeExtraAccessory $vehicle_type_extra_accessory)
    {
        if ($vehicle_type_extra_accessory->discount_type) {
            return $this->item($vehicle_type_extra_accessory->discount_type, new DiscountTypeTransformer());
        } else {
            return null;
        }

    }

    /**
     * @param VehicleTypeExtraAccessory $vehicle_type_extra_accessory
     * @return Fractal\Resource\Item|null
     */
    public function includeDurationType(VehicleTypeExtraAccessory $vehicle_type_extra_accessory)
    {
        if ($vehicle_type_extra_accessory->duration_type) {
            return $this->item($vehicle_type_extra_accessory->duration_type, new DurationTypeTransformer());
        } else {
            return null;
        }

    }
}