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
use Plugins\Vehicles\Model\VehicleSpecialPrice;



/**
 * Class VehicleSpecialPriceTransformer
 * @package Plugins\Vehicles\Transformers
 */
class VehicleSpecialPriceTransformer extends Fractal\TransformerAbstract
{

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'VehicleType'
    ];

    /**
     * @param VehicleSpecialPrice $vehicle_special_price
     * @return array
     */
    public function transform(VehicleSpecialPrice $vehicle_special_price)
    {
        $output = array_only($vehicle_special_price->toArray(), ['id', 'created_at', 'start_date', 'end_date', 'vehicle_type_id', 'discount_percentage', 'is_active']);
        $output['is_active'] = ($output['is_active'] == 1) ? true : false;
        return $output;
    }

    /**
     * @param VehicleTypePrice $vehicle_type_price
     * @return Fractal\Resource\Item|null
     */
    public function includeVehicleType(VehicleSpecialPrice $vehicle_special_price)
    {
        if ($vehicle_special_price->vehicle_type) {
            return $this->item($vehicle_special_price->vehicle_type, new VehicleTypeTransformer());
        } else {
            return null;
        }

    }
}
