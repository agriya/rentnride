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
 
namespace Plugins\VehicleDisputes\Transformers;

use League\Fractal;
use Plugins\VehicleDisputes\Model\VehicleDisputeClosedType;
use Plugins\VehicleDisputes\Transformers\VehicleDisputeTypeTransformer;

class VehicleDisputeClosedTypeTransformer extends Fractal\TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'dispute_type'
    ];

    public function transform(VehicleDisputeClosedType $vehicle_dispute_closed_type)
    {
        $output = array_only($vehicle_dispute_closed_type->toArray(), ['id', 'name', 'dispute_type_id', 'is_booker', 'resolved_type', 'reason']);
		$output['is_booker'] = (int)$output['is_booker'];
        return $output;
    }

    /**
     * @param VehicleDisputeClosedType $vehicle_dispute_closed_type
     * @return Fractal\Resource\Item
     */
    public function includeDisputeType(VehicleDisputeClosedType $vehicle_dispute_closed_type)
    {
        if ($vehicle_dispute_closed_type->dispute_type) {
            return $this->item($vehicle_dispute_closed_type->dispute_type, new \Plugins\VehicleDisputes\Transformers\VehicleDisputeTypeTransformer());
        } else {
            return null;
        }

    }
}
