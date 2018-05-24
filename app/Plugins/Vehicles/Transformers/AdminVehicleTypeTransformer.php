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
use Plugins\Vehicles\Model\VehicleType;


/**
 * Class AdminVehicleTypeTransformer
 * @package Plugins\Vehicles\Transformers
 */
class AdminVehicleTypeTransformer extends Fractal\TransformerAbstract
{

    /**
     * List of resources possible to include
     * @var array
     */
    protected $availableIncludes = [
        'VehicleTypeExtraAccessory', 'VehicleTypeFuelOption', 'VehicleTypeInsurance', 'VehicleTypeSurcharge', 'VehicleTypeTax'
    ];

    /**
     * @param VehicleType $vehicle_type
     * @return array
     */
    public function transform(VehicleType $vehicle_type)
    {
        $output = array_only($vehicle_type->toArray(), ['id', 'created_at', 'name', 'slug', 'minimum_hour_price', 'maximum_hour_price', 'minimum_day_price', 'maximum_day_price', 'drop_location_differ_unit_price', 'drop_location_differ_additional_fee', 'deposit_amount', 'vehicle_count', 'is_active']);
        $output['is_active'] = ($output['is_active'] == 1) ? true : false;
        return $output;
    }

    /**
     * @param VehicleType $vehicle_type
     * @return Fractal\Resource\Item|null
     */
    public function includeVehicleTypeExtraAccessory(VehicleType $vehicle_type)
    {
        if (!empty($vehicle_type->vehicle_type_extra_accessory)) {
            return $this->collection($vehicle_type->vehicle_type_extra_accessory, (new \Plugins\VehicleExtraAccessories\Transformers\VehicleTypeExtraAccessoryTransformer())->setDefaultIncludes(['vehicle_extra_accessory']));
        } else {
            return null;
        }

    }

    public function includeVehicleTypeFuelOption(VehicleType $vehicle_type)
    {
        if (!empty($vehicle_type->vehicle_type_fuel_option)) {
            return $this->collection($vehicle_type->vehicle_type_fuel_option, (new \Plugins\VehicleFuelOptions\Transformers\VehicleTypeFuelOptionTransformer())->setDefaultIncludes(['vehicle_fuel_option']));
        } else {
            return null;
        }

    }

    /**
     * @param VehicleType $vehicle_type
     * @return Fractal\Resource\Collection|null
     */
    public function includeVehicleTypeInsurance(VehicleType $vehicle_type)
    {
        if (!empty($vehicle_type->vehicle_type_insurance)) {
            return $this->collection($vehicle_type->vehicle_type_insurance, (new \Plugins\VehicleInsurances\Transformers\VehicleTypeInsuranceTransformer())->setDefaultIncludes(['vehicle_insurance']));
        } else {
            return null;
        }

    }

    /**
     * @param VehicleType $vehicle_type
     * @return Fractal\Resource\Collection|null
     */
    public function includeVehicleTypeSurcharge(VehicleType $vehicle_type)
    {
        if (!empty($vehicle_type->vehicle_type_surcharge)) {
            return $this->collection($vehicle_type->vehicle_type_surcharge, (new \Plugins\VehicleSurcharges\Transformers\VehicleTypeSurchargeTransformer())->setDefaultIncludes(['vehicle_surcharge']));
        } else {
            return null;
        }

    }

    /**
     * @param VehicleType $vehicle_type
     * @return Fractal\Resource\Collection|null
     */
    public function includeVehicleTypeTax(VehicleType $vehicle_type)
    {
        if (!empty($vehicle_type->vehicle_type_tax)) {
            return $this->collection($vehicle_type->vehicle_type_tax, (new \Plugins\VehicleTaxes\Transformers\VehicleTypeTaxTransformer())->setDefaultIncludes(['vehicle_tax']));
        } else {
            return null;
        }

    }

}
