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

use App\Transformers\DiscountTypeTransformer;
use App\Transformers\DurationTypeTransformer;
use League\Fractal;
use Plugins\VehicleSurcharges\Model\VehicleTypeSurcharge;

/**
 * Class VehicleSurchargeTransformer
 * @package VehicleSurcharges\Transformers
 */
class VehicleTypeSurchargeTransformer extends Fractal\TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'VehicleSurcharge', 'VehicleType', 'DurationType', 'DiscountType'
    ];

    /**
     * @param VehicleTypeSurcharge $vehicle_type_surcharge
     * @return array
     */
    public function transform(VehicleTypeSurcharge $vehicle_type_surcharge)
    {
        $output = array_only($vehicle_type_surcharge->toArray(), ['id', 'created_at', 'vehicle_type_id', 'surcharge_id', 'rate', 'discount_type_id', 'duration_type_id', 'max_allowed_amount', 'is_active']);
        $output['is_active'] = ($output['is_active']) ? true : false;
        return $output;
    }

    /**
     * @param VehicleTypeSurcharge $vehicle_type_surcharge
     * @return Fractal\Resource\Item|null
     */
    public function includeVehicleSurcharge(VehicleTypeSurcharge $vehicle_type_surcharge)
    {
        if ($vehicle_type_surcharge->vehicle_surcharge) {
            return $this->item($vehicle_type_surcharge->vehicle_surcharge, new VehicleSurchargeTransformer());
        } else {
            return null;
        }

    }

    /**
     * @param VehicleTypeSurcharge $vehicle_type_surcharge
     * @return Fractal\Resource\Item|null
     */
    public function includeVehicleType(VehicleTypeSurcharge $vehicle_type_surcharge)
    {
        if ($vehicle_type_surcharge->vehicle_type) {
            return $this->item($vehicle_type_surcharge->vehicle_type, new \Plugins\Vehicles\Transformers\VehicleTypeTransformer());
        } else {
            return null;
        }

    }

    /**
     * @param VehicleTypeSurcharge $vehicle_type_surcharge
     * @return Fractal\Resource\Item|null
     */
    public function includeDiscountType(VehicleTypeSurcharge $vehicle_type_surcharge)
    {
        if ($vehicle_type_surcharge->discount_type) {
            return $this->item($vehicle_type_surcharge->discount_type, new DiscountTypeTransformer());
        } else {
            return null;
        }

    }

    /**
     * @param VehicleTypeSurcharge $vehicle_type_surcharge
     * @return Fractal\Resource\Item|null
     */
    public function includeDurationType(VehicleTypeSurcharge $vehicle_type_surcharge)
    {
        if ($vehicle_type_surcharge->duration_type) {
            return $this->item($vehicle_type_surcharge->duration_type, new DurationTypeTransformer());
        } else {
            return null;
        }

    }
}