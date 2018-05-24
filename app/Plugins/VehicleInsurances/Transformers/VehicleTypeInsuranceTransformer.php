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
 
namespace Plugins\VehicleInsurances\Transformers;

use App\Transformers\DiscountTypeTransformer;
use App\Transformers\DurationTypeTransformer;
use League\Fractal;
use Plugins\VehicleInsurances\Model\VehicleTypeInsurance;

/**
 * Class VehicleInsuranceTransformer
 * @package VehicleInsurances\Transformers
 */
class VehicleTypeInsuranceTransformer extends Fractal\TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'VehicleInsurance', 'VehicleType', 'DurationType', 'DiscountType'
    ];

    /**
     * @param VehicleTypeInsurance $vehicle_type_insurance
     * @return array
     */
    public function transform(VehicleTypeInsurance $vehicle_type_insurance)
    {
        $output = array_only($vehicle_type_insurance->toArray(), ['id', 'created_at', 'vehicle_type_id', 'insurance_id', 'rate', 'discount_type_id', 'duration_type_id', 'max_allowed_amount', 'is_active']);
        $output['is_active'] = ($output['is_active']) ? true : false;
        return $output;
    }

    /**
     * @param VehicleTypeInsurance $vehicle_type_insurance
     * @return Fractal\Resource\Item|null
     */
    public function includeVehicleInsurance(VehicleTypeInsurance $vehicle_type_insurance)
    {
        if ($vehicle_type_insurance->vehicle_insurance) {
            return $this->item($vehicle_type_insurance->vehicle_insurance, new VehicleInsuranceTransformer());
        } else {
            return null;
        }

    }

    /**
     * @param VehicleTypeInsurance $vehicle_type_insurance
     * @return Fractal\Resource\Item|null
     */
    public function includeVehicleType(VehicleTypeInsurance $vehicle_type_insurance)
    {
        if ($vehicle_type_insurance->vehicle_type) {
            return $this->item($vehicle_type_insurance->vehicle_type, new \Plugins\Vehicles\Transformers\VehicleTypeTransformer());
        } else {
            return null;
        }

    }

    /**
     * @param VehicleTypeInsurance $vehicle_type_insurance
     * @return Fractal\Resource\Item|null
     */
    public function includeDiscountType(VehicleTypeInsurance $vehicle_type_insurance)
    {
        if ($vehicle_type_insurance->discount_type) {
            return $this->item($vehicle_type_insurance->discount_type, new DiscountTypeTransformer());
        } else {
            return null;
        }

    }

    /**
     * @param VehicleTypeInsurance $vehicle_type_insurance
     * @return Fractal\Resource\Item|null
     */
    public function includeDurationType(VehicleTypeInsurance $vehicle_type_insurance)
    {
        if ($vehicle_type_insurance->duration_type) {
            return $this->item($vehicle_type_insurance->duration_type, new DurationTypeTransformer());
        } else {
            return null;
        }

    }
}