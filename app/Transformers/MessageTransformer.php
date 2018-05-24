<?php
/**
 * Rent & Ride
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
 
namespace App\Transformers;

use League\Fractal;
use App\Message;

/**
 * Class MessageTransformer
 * @package Messages\Transformers
 */
class MessageTransformer extends Fractal\TransformerAbstract
{
    /**
     * @var array
     */
    protected $availableIncludes = [
        'FromUser', 'ToUser', 'Messageable', 'MessageContent', 'ItemUserStatus', 'DisputeStatus'
    ];

    /**
     * @param Message $message
     * @return array
     */
    public function transform(Message $message)
    {
        $output = array_only($message->toArray(), ['id', 'created_at', 'user_id', 'to_user_id', 'item_id', 'message_content_id', 'message_folder_id', 'messageable_id', 'messageable_type', 'is_sender', 'is_starred', 'is_read', 'is_deleted', 'is_archived', 'is_review', 'is_communication', 'hash', 'size', 'dispute_status_id']);
        $output['is_sender'] = ($output['is_sender'] == 1) ? 1 : 0;
        $output['is_starred'] = ($output['is_starred'] == 1) ? 1 : 0;
        $output['is_read'] = ($output['is_read'] == 1) ? 1 : 0;
        $output['is_deleted'] = ($output['is_deleted'] == 1) ? 1 : 0;
        $output['is_archived'] = ($output['is_archived'] == 1) ? 1 : 0;
        $output['is_review'] = ($output['is_review'] == 1) ? 1 : 0;
        $output['is_communication'] = ($output['is_communication'] == 1) ? 1 : 0;
        return $output;
    }

    /**
     * @param Message $message
     * @return Fractal\Resource\Item|null
     */
    public function includeFromUser(Message $message)
    {
        if ($message->from_user) {
            return $this->item($message->from_user, new UserTransformer());
        } else {
            return null;
        }
    }

    /**
     * @param Message $message
     * @return Fractal\Resource\Item|null
     */
    public function includeToUser(Message $message)
    {
        if ($message->to_user) {
            return $this->item($message->to_user, new UserTransformer());
        } else {
            return null;
        }
    }

    /**
     * @param Message $message
     * @return Fractal\Resource\Item|null
     */
    public function includeMessageable(Message $message)
    {
        if ($message->messageable) {
            if ($message->messageable_type == 'MorphVehicle') {
                return $this->item($message->messageable, new \Plugins\Vehicles\Transformers\VehicleTransformer());
            }
            if ($message->messageable_type == 'MorphVehicleRental') {
                return $this->item($message->messageable, new \Plugins\VehicleRentals\Transformers\VehicleRentalTransformer());
            }
            if ($message->messageable_type == 'MorphVehicleRentalDispute') {
                return $this->item($message->messageable, new \Plugins\VehicleDisputes\Transformers\VehicleDisputeTransformer());
            }
        } else {
            return null;
        }
    }


    /**
     * @param Message $message
     * @return Fractal\Resource\Item|null
     */
    public function includeMessageContent(Message $message)
    {
        if ($message->message_content) {
            return $this->item($message->message_content, new MessageContentTransformer());
        } else {
            return null;
        }
    }

    /**
     * @param Message $message
     * @return Fractal\Resource\Item|null
     */
    public function includeItemUserStatus(Message $message)
    {
        if ($message->item_user_status) {
            return $this->item($message->item_user_status, new \Plugins\VehicleRentals\Transformers\VehicleRentalStatusTransformer());
        } else {
            return null;
        }
    }

    /**
     * @param Message $message
     * @return Fractal\Resource\Item|null
     */
    public function includeDisputeStatus(Message $message)
    {
        if ($message->dispute_status) {
            return $this->item($message->dispute_status, new \Plugins\VehicleDisputes\Transformers\VehicleDisputeStatusTransformer());
        } else {
            return null;
        }
    }
}