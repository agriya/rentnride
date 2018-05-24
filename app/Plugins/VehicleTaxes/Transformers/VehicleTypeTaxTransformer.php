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
 
namespace Plugins\VehicleTaxes\Transformers;

use App\Transformers\DiscountTypeTransformer;
use App\Transformers\DurationTypeTransformer;
use League\Fractal;
use Plugins\VehicleTaxes\Model\VehicleTypeTax;

/**
 * Class VehicleTaxTransformer
 * @package VehicleTaxes\Transformers
 */
class VehicleTypeTaxTransformer extends Fractal\TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'VehicleTax', 'VehicleType', 'DurationType', 'DiscountType'
    ];

    /**
     * @param VehicleTypeTax $vehicle_type_tax
     * @return array
     */
    public function transform(VehicleTypeTax $vehicle_type_tax)
    {
        $output = array_only($vehicle_type_tax->toArray(), ['id', 'created_at', 'vehicle_type_id', 'tax_id', 'rate', 'discount_type_id', 'duration_type_id', 'max_allowed_amount', 'is_active']);
        $output['is_active'] = ($output['is_active']) ? true : false;
        return $output;
    }

    /**
     * @param VehicleTypeTax $vehicle_type_tax
     * @return Fractal\Resource\Item|null
     */
    public function includeVehicleTax(VehicleTypeTax $vehicle_type_tax)
    {
        if ($vehicle_type_tax->vehicle_tax) {
            return $this->item($vehicle_type_tax->vehicle_tax, new VehicleTaxTransformer());
        } else {
            return null;
        }

    }

    /**
     * @param VehicleTypeTax $vehicle_type_tax
     * @return Fractal\Resource\Item|null
     */
    public function includeVehicleType(VehicleTypeTax $vehicle_type_tax)
    {
        if ($vehicle_type_tax->vehicle_type) {
            return $this->item($vehicle_type_tax->vehicle_type, new \Plugins\Vehicles\Transformers\AdminVehicleTypeTransformer());
        } else {
            return null;
        }

    }

    /**
     * @param VehicleTypeTax $vehicle_type_tax
     * @return Fractal\Resource\Item|null
     */
    public function includeDiscountType(VehicleTypeTax $vehicle_type_tax)
    {
        if ($vehicle_type_tax->discount_type) {
            return $this->item($vehicle_type_tax->discount_type, new DiscountTypeTransformer());
        } else {
            return null;
        }

    }

    /**
     * @param VehicleTypeTax $vehicle_type_tax
     * @return Fractal\Resource\Item|null
     */
    public function includeDurationType(VehicleTypeTax $vehicle_type_tax)
    {
        if ($vehicle_type_tax->duration_type) {
            return $this->item($vehicle_type_tax->duration_type, new DurationTypeTransformer());
        } else {
            return null;
        }

    }
}