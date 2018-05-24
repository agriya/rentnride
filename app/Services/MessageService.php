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
 
namespace App\Services;

use App\Message;
use App\MessageContent;
use App\User;
use Illuminate\Support\Facades\Auth;

class MessageService
{

    /**
     * MessageService constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param $item_id
     * @param $vehicle_rental_id
     * @param $from_user_id
     * @param $from_username
     * @param $to_user_id
     * @param $morph_type
     */
    public function saveMessageContent($message_content_arr, $item_id, $vehicle_rental_id, $from_user_id, $to_user_id, $status_id, $morph_type, $dispute_status_id = null)
    {
        //Save Message Contents
        $message_content = MessageContent::create($message_content_arr);
        //get message content id and save to message table
        $message_array = array();
        $message_array['message_content_id'] = $message_content->id;
        $message_array['item_user_status_id'] = $status_id;
        $message_array['dispute_status_id'] = $dispute_status_id;
        $message_array['user_id'] = $from_user_id;
        $message_array['to_user_id'] = $to_user_id;
        $message_array['is_sender'] = 0;
        $message_array['is_read'] = 0;
        $message_array['message_folder_id'] = config('constants.ConstMessageFolder.Inbox');
        $this->saveMessage($message_array, $vehicle_rental_id, $morph_type);
        $message_array['user_id'] = $to_user_id;
        $message_array['to_user_id'] = $from_user_id;
        $message_array['is_sender'] = 1;
        $message_array['is_read'] = 1;
        $message_array['message_folder_id'] = config('constants.ConstMessageFolder.SentMail');
        $this->saveMessage($message_array, $vehicle_rental_id, $morph_type);
    }

    /**
     * @param $message
     * @param $id
     * @param $morph_type
     */
    public function saveMessage($message, $id, $morph_type)
    {
        $message = Message::create($message);
        if ($morph_type == 'VehicleRental') {
            $message_data = \Plugins\VehicleRentals\Model\VehicleRental::with(['message'])->where('id', '=', $id)->first();
        }
        if ($morph_type == 'User') {
            $message_data = User::with(['message'])->where('id', '=', $id)->first();
        }
        if ($morph_type == 'Withdrawals') {
            $message_data = \Plugins\Withdrawals\Model\UserCashWithdrawal::with(['message'])->where('id', '=', $id)->first();
        }
        $message_data->message()->save($message);
    }

    /**
     * @param $vehicle_rental
     * @param $auth_user
     * @return array
     */
    public function getMessages($vehicle_rental, $user)
    {

        $message_data_res = array();
        $return_data_res = array();
        if (!$vehicle_rental) {
            return $this->response->errorNotFound('Invalid Request');
        }
        $booker = $vehicle_rental->user->username;
        $host = (!is_null($vehicle_rental->item_userable->user)) ? $vehicle_rental->item_userable->user->username : "";
        $check_user_id = $user->id;
        if ($user->role_id == config('constants.ConstUserTypes.Admin')) {
            $check_user_id = $vehicle_rental->user_id;
        }
        if ($vehicle_rental->message) {
            foreach ($vehicle_rental->message as $message) {
                if ($message->to_user_id == $check_user_id) {
                    $replace_content = array(
                        '##BOOKER##' => $booker,
                        '##CREATED_DATE##' => $message->created_at,
                        '##HOSTER##' => $host,
                        '##ACCEPTED_DATE##' => $message->created_at
                    );
                    $message_data_res[$message->id]['id'] = $message->id;
                    $message_data_res[$message->id]['status_id'] = $message->item_user_status_id;
                    $message_data_res[$message->id]['status'] = $message->item_user_status->name;
                    $message_data_res[$message->id]['created_at'] = $message->created_at;

                    if ($message->message_content->subject === 'Private Note' || $message->message_content->subject === 'Feedback') {
                        $message_data_res[$message->id]['description'] = $message->message_content->message;
                    } else if ($message->dispute_status_id) {
                        $message_data_res[$message->id]['description'] = $message->message_content->subject;
                        $message_data_res[$message->id]['status_id'] = $message->dispute_status_id;
                        $message_data_res[$message->id]['status'] = $message->dispute_status->name;
                    } else {
                        $message_data_res[$message->id]['description'] = strtr($message->item_user_status->description, $replace_content);
                    }
                }
            }
        }
        $return_data_res['vehicle_rentalDetails'] = $vehicle_rental;
        $return_data_res['item'] = $vehicle_rental->item_userable;
        $return_data_res['messages'] = $message_data_res;
        return $return_data_res;
    }
}
