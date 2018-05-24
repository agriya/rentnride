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
use Plugins\VehicleDisputes\Model\VehicleDispute;
use App\Transformers\UserTransformer;

class VehicleDisputeTransformer extends Fractal\TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'user', 'dispute_status', 'dispute_type', 'dispute_closed_type', 'LastRepliedUser', 'item_user_disputable', 'message'
    ];

    /**
     * @param VehicleDispute $vehicle_dispute
     * @return array
     */
    public function transform(VehicleDispute $vehicle_dispute)
    {
        $output = array_only($vehicle_dispute->toArray(), ['id', 'created_at', 'user_id', 'item_user_disputable_id', 'item_user_disputable_type', 'dispute_type_id', 'dispute_status_id', 'last_replied_user_id', 'dispute_closed_type_id', 'is_favor_booker', 'is_booker', 'last_replied_date', 'resolved_date', 'dispute_conversation_count', 'reason']);
        ($output['is_booker']) ? $output['is_booker'] = 'Booker' : $output['is_booker'] = 'Host';
        if ($output['is_favor_booker'] == 0 && $output['dispute_status_id'] == config('constants.ConstDisputeStatuses.Closed')) {
            $output['is_favor_booker'] = 'Host';
        } elseif ($output['is_favor_booker'] == 1 && $output['dispute_status_id'] == config('constants.ConstDisputeStatuses.Closed')) {
            $output['is_favor_booker'] = 'Booker';
        } else {
			$output['is_favor_booker'] = '';
		}
        return $output;
    }

    /**
     * @param VehicleDispute $vehicle_dispute
     * @return Fractal\Resource\Item|null
     */
    public function includeUser(VehicleDispute $vehicle_dispute)
    {
        if ($vehicle_dispute->user) {
            return $this->item($vehicle_dispute->user, new UserTransformer());
        } else {
            return null;
        }
    }

    /**
     * @param VehicleDispute $vehicle_dispute
     * @return Fractal\Resource\Item|null
     */
    public function LastRepliedUser(VehicleDispute $vehicle_dispute)
    {
        if ($vehicle_dispute->LastRepliedUser) {
            return $this->item($vehicle_dispute->LastRepliedUser, new UserTransformer());
        } else {
            return null;
        }
    }

    /**
     * @param VehicleDispute $vehicle_dispute
     * @return Fractal\Resource\Item|null
     */
    public function includeDisputeStatus(VehicleDispute $vehicle_dispute)
    {
        if ($vehicle_dispute->dispute_status) {
            return $this->item($vehicle_dispute->dispute_status, new \Plugins\VehicleDisputes\Transformers\VehicleDisputeStatusTransformer());
        } else {
            return null;
        }
    }

    /**
     * @param VehicleDispute $vehicle_dispute
     * @return Fractal\Resource\Item|null
     */
    public function includeDisputeType(VehicleDispute $vehicle_dispute)
    {
        if ($vehicle_dispute->dispute_type) {
            return $this->item($vehicle_dispute->dispute_type, new \Plugins\VehicleDisputes\Transformers\VehicleDisputeTypeTransformer());
        } else {
            return null;
        }
    }

    /**
     * @param VehicleDispute $vehicle_dispute
     * @return Fractal\Resource\Item|null
     */
    public function includeItemUserDisputable(VehicleDispute $vehicle_dispute)
    {
        if ($vehicle_dispute->item_user_disputable) {
            if ($vehicle_dispute->item_user_disputable_type == 'MorphVehicleRental') {
                $enabledIncludes = array('item_user_status', 'user', 'item_userable');
                return $this->item($vehicle_dispute->item_user_disputable, (new \Plugins\VehicleRentals\Transformers\VehicleRentalTransformer)->setDefaultIncludes($enabledIncludes));
            }
        } else {
            return null;
        }
    }

    /**
     * @param VehicleDispute $vehicle_dispute
     * @return Fractal\Resource\Item|null
     */
    public function includeDisputeClosedType(VehicleDispute $vehicle_dispute)
    {
        if ($vehicle_dispute->dispute_Closed_type) {
            return $this->item($vehicle_dispute->dispute_Closed_type, new \Plugins\VehicleDisputes\Transformers\VehicleDisputeClosedTypeTransformer());
        } else {
            return null;
        }
    }

    /**
     * @param VehicleDispute $vehicle_dispute
     * @return Fractal\Resource\Item|null
     */
    public function includeMessage(VehicleDispute $vehicle_dispute)
    {
        if ($vehicle_dispute->messageable) {
            if ($vehicle_dispute->messageable_type == 'MorphVehicleRentalDispute') {
                return $this->item($vehicle_dispute->messageable, (new \App\Transformers\MessageTransformer)->setDefaultIncludes('message_content'));
            }
        } else {
            return null;
        }
    }
}
