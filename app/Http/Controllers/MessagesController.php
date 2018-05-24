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

namespace App\Http\Controllers;

use Carbon;
use Illuminate\Http\Request;
use JWTAuth;
use App\Message;
use App\MessageContent;
use Validator;
use App\Transformers\MessageTransformer;
use App\Services\MessageService;

/**
 * Messages resource representation.
 * @Resource("Messages")
 */
class MessagesController extends Controller
{
    /**
     * MessagesController constructor.
     * @param MessageService $msgService
     */
    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
        $this->setMessageService();
        if (isPluginEnabled('VehicleDisputes')) {
            $this->setVehicleDisputeService();
        }
    }

    /**
     * set MessageService
     */
    public function setMessageService()
    {
        $this->msgService = new MessageService();
    }

    /**
     * set VehicleDisputeService
     */
    public function setVehicleDisputeService()
    {
        if (isPluginEnabled('VehicleDisputes')) {
            $this->vehicleDisputeService = new \Plugins\VehicleDisputes\Services\VehicleDisputeService();
        }
    }

    /**
     * Show all Messages
     * Get a JSON representation of all the Messages.
     *
     * @Get("/admin/messages?sort={sort}&sortby={sortby}&page={page}&q={q}")
     * @Parameters({
     *      @Parameter("sort", type="string", required=false, description="Sort the messages list by sort key.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort messages by Ascending / Descending Order.", default=null),
     *      @Parameter("q", type="string", required=false, description="Search messages.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1),
     *      @Parameter("filter", type="string", required=false, description="Filter messages.", default=null)
     * })
     */
    public function inbox(Request $request)
    {
        $enabledIncludes = array('from_user', 'to_user', 'message_content', 'item_user_status', 'dispute_status');
        $user = $this->auth->user();
        $data = ['to_user_id' => $user->id, 'message_folder_id' => config('constants.ConstMessageFolder.Inbox')];
        $messages = Message::with($enabledIncludes)->where($data)->filterByRequest($request)->paginate(config('constants.ConstPageLimit'));
        $enabledIncludes = array_merge($enabledIncludes, array('messageable'));
        return $this->response->paginator($messages, (new MessageTransformer)->setDefaultIncludes($enabledIncludes));
    }

    /**
     * Show all sent Messages
     * Get a JSON representation of all the sent Messages.
     *
     * @Get("/admin/sentMessages?sort={sort}&sortby={sortby}&page={page}&q={q}")
     * @Parameters({
     *      @Parameter("sort", type="string", required=false, description="Sort the sent messages list by sort key.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort sent messages by Ascending / Descending Order.", default=null),
     *      @Parameter("q", type="string", required=false, description="Search sent messages.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1),
     *      @Parameter("filter", type="string", required=false, description="Filter sent messages.", default=null)
     * })
     */
    public function sentMessage(Request $request)
    {
        $enabledIncludes = array('from_user', 'to_user', 'message_content', 'item_user_status', 'dispute_status');
        $user = $this->auth->user();
        $data = ['to_user_id' => $user->id, 'message_folder_id' => config('constants.ConstMessageFolder.SentMail')];
        $messages = Message::with($enabledIncludes)->where($data)->filterByRequest($request)->paginate(config('constants.ConstPageLimit'));
        $enabledIncludes = array_merge($enabledIncludes, array('messageable'));
        return $this->response->paginator($messages, (new MessageTransformer)->setDefaultIncludes($enabledIncludes));
    }
    /**
     * Show all starred Messages
     * Get a JSON representation of all the starred Messages.
     *
     * @Get("/starMessages?sort={sort}&sortby={sortby}&page={page}&q={q}")
     * @Parameters({
     *      @Parameter("sort", type="string", required=false, description="Sort the star messages list by sort key.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort star messages by Ascending / Descending Order.", default=null),
     *      @Parameter("q", type="string", required=false, description="Search star messages.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1),
     *      @Parameter("filter", type="string", required=false, description="Filter star messages.", default=null)
     * })
     */
    public function starMessage(Request $request)
    {
        $enabledIncludes = array('from_user', 'to_user', 'message_content', 'item_user_status', 'dispute_status');
        $user = $this->auth->user();
        $data = ['to_user_id' => $user->id, 'is_starred' => 1];
        $messages = Message::with($enabledIncludes)->where($data)->filterByRequest($request)->paginate(config('constants.ConstPageLimit'));
        $enabledIncludes = array_merge($enabledIncludes, array('messageable'));
        return $this->response->paginator($messages, (new MessageTransformer)->setDefaultIncludes($enabledIncludes));
    }
    /**
     * Get item activities
     *
     * @Get("/item_messages/{item_id}")
     * @Transaction({
     *      @Request({"item_id": 1}),
     *      @Response(200, body={"user_id":1,"item_id":1,"to_user_id":2,"message_content_id":1,"message_folder_id":1,"messageable_id":1,"messageable_type":"App\Model\User","is_sender":1,"is_starred":1,"is_read":1,"is_deleted":1,"is_archived":1,"is_review":1,"is_communication":0,"user":{},"item_user":{},"item":{},"message_content":{}}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function itemActivities($item_id)
    {
        $enabledIncludes = array('from_user', 'to_user', 'message_content', 'item_user_status', 'dispute_status');
        $item_messages = Message::with($enabledIncludes)->where('messageable_id', $item_id)->get();
        if (!$item_messages) {
            return $this->response->errorNotFound('Invalid Request');
        }
        $enabledIncludes = array_merge($enabledIncludes, array('messageable'));
        return $this->response->Collection($item_messages, (new MessageTransformer)->setDefaultIncludes($enabledIncludes));
    }

    /**
     * Get item user activities
     *
     * @Get("/item_user_messages/{item_user_id}")
     * @Transaction({
     *      @Request({"item_user_id": 1}),
     *      @Response(200, body={"user_id":1,"item_id":1,"to_user_id":2,"message_content_id":1,"message_folder_id":1,"messageable_id":1,"messageable_type":"App\Model\User","is_sender":1,"is_starred":1,"is_read":1,"is_deleted":1,"is_archived":1,"is_review":1,"is_communication":0,"user":{},"item_user":{},"item":{},"message_content":{}}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function bookerActivities($item_user_id)
    {
        $user = $this->auth->user();
        $enabledIncludes = array('user', 'message', 'item_user_status');
        (isPluginEnabled('VehicleFeedbacks')) ? $enabledIncludes[] = 'vehicle_feedback' : '';
        $vehicle_rental = \Plugins\VehicleRentals\Model\VehicleRental::with($enabledIncludes)->where('id', $item_user_id)->first();
        $messages = $this->msgService->getMessages($vehicle_rental, $user);
        return response()->json(['messages' => $messages]);
    }

    /**
     * store messages
     *
     * @Get("/messages/{user_id}/user}")
     * @Transaction({
     *      @Request({"to_user_id": 1,  "subject":"Welcome Mail", "message":"Welcome to lumen"}),
     *      @Response(200, body={"message":"Message Send Successfully"}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function store(Request $request)
    {
        $message_data = $request->only('to_user_id', 'message', 'subject');
        $validator = Validator::make($message_data, Message::GetValidationRule(), Message::GetValidationMessage());
        if ($validator->passes()) {
            $user = $this->auth->user();
            //if to user and current user equal could not send message
            if ($user->id == $request->to_user_id) {
                return $this->response->errorNotFound("Message could not be sent");
            }
            if (!$user) {
                return $this->response->errorNotFound("User not found");
            }
            $message_content = MessageContent::create($message_data);
            if ($message_content) {
                $message_array = array();
                $message_array['message_content_id'] = $message_content->id;
                $message_array['user_id'] = $user->id;
                $message_array['to_user_id'] = $request->to_user_id;
                $message_array['message_folder_id'] = config('constants.ConstMessageFolder.Inbox');
                $this->msgService->saveMessage($message_array, $user->id, 'User');
                $message_array['user_id'] = $request->to_user_id;
                $message_array['to_user_id'] = $user->id;
                $message_array['message_folder_id'] = config('constants.ConstMessageFolder.SentMail');
                $this->msgService->saveMessage($message_array, $user->id, 'User');
            }
            return response()->json(['Success' => 'Message has been sent'], 200);
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Message could not be added. Please, try again.', $validator->errors());
        }
    }

    /**
     * show the specified message.
     * show the message with a `id`.
     * @get("messages/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function show($id)
    {
        $user = $this->auth->user();
        $enabledIncludes = array('from_user', 'to_user', 'message_content', 'item_user_status', 'dispute_status');
        $message = Message::with($enabledIncludes)->find($id);
        if (!$message || ($user->role_id != config('constans.ConstUserTypes.Admin') && ($user->id != $message->user_id && $user->id != $message->to_user_id))) {
            return $this->response->errorNotFound("Invalid Request");
        }
        $enabledIncludes = array_merge($enabledIncludes, array('messageable'));
        return $this->response->item($message, (new MessageTransformer)->setDefaultIncludes($enabledIncludes));
    }

    /**
     * reply messages
     *
     * @Get("/messages/{id}/reply}")
     * @Transaction({
     *      @Request({"to_user_id": 1,"message_id":"1",  "subject":"Welcome Mail", "message":"Welcome to lumen"}),
     *      @Response(200, body={"message":"Message Send Successfully"}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function reply(Request $request)
    {
        $message = Message::find($request->message_id);
        if (!$message) {
            return $this->response->errorNotFound("Invalid request");
        }
        $message_data = $request->only('message', 'subject');
        $validator = Validator::make($message_data, Message::GetValidationRule(), Message::GetValidationMessage());
        if ($validator->passes()) {
            $user = $this->auth->user();
            //if to user and current user equal could not send message
            if ($user->id == $request->to_user_id) {
                return $this->response->errorNotFound("Message could not be sent");
            }
            if (!$user) {
                return $this->response->errorNotFound("User not found");
            }
            $message_content = MessageContent::create($message_data);
            if ($message_content) {
                $message_array = array();
                $message_array['message_content_id'] = $message_content->id;
                $message_array['user_id'] = $user->id;
                $message_array['to_user_id'] = $request->to_user_id;
                $message_array['message_folder_id'] = config('constants.ConstMessageFolder.SentMail');
                $message_array['message_id'] = $request->message_id;
                $this->msgService->saveMessage($message_array);
                $message_array['user_id'] = $request->to_user_id;
                $message_array['to_user_id'] = $user->id;
                $message_array['message_folder_id'] = config('constants.ConstMessageFolder.Inbox');
                $message_array['message_id'] = $request->message_id;
                $this->msgService->saveMessage($message_array);
            }
            return response()->json(['Success' => 'Message has been added'], 200);
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Message could not be added. Please, try again.', $validator->errors());
        }
    }

    /**
     * Store private note.
     * Store the private note with a `id`, `message`.
     * @post("private_notes/{id}")
     * @Transaction({
     *      @Request({"id": 1, "message":"XXXX"}),
     *      @Response(200, body={"message": "Private Note has been added", "status_code": 200}),
     *      @Response(404, body={"message": "Private Note could not be added. Please, try again.", "status_code": 404})
     * })
     */
    public function privateNote(Request $request)
    {
        $user = $this->auth->user();
        $private_note_data = $request->only('message');
        $validator = Validator::make($private_note_data, Message::GetValidationRule($private_note_data), Message::GetValidationMessage($private_note_data));
        if ($validator->passes()) {
            $private_note_data['subject'] = 'Private Note';
            $item_id = '';
            $vehicle_rental_id = '';
            if (isPluginEnabled('VehicleRentals') && $request->has('id')) {
                $enabledIncludes = array('user');
                (isPluginEnabled('VehicleDisputes')) ? $enabledIncludes[] = 'item_user_dispute' : '';
                $vehicle_rental = \Plugins\VehicleRentals\Model\VehicleRental::with($enabledIncludes)->find($request->id);
                $item_id = $vehicle_rental->item_userable_id;
                $vehicle_rental_id = $vehicle_rental->id;
            }
            $item_user_status_id = config('constants.ConstItemUserStatus.PrivateConversation');
            if ($user->id == $vehicle_rental->user_id) {
                $this->msgService->saveMessageContent($private_note_data, $item_id, $vehicle_rental_id, $vehicle_rental->user_id, $vehicle_rental->item_userable->user_id, $item_user_status_id, 'VehicleRental');
            }
            if ($user->id == $vehicle_rental->item_userable->user_id) {
                $this->msgService->saveMessageContent($private_note_data, $item_id, $vehicle_rental_id, $vehicle_rental->item_userable->user_id, $vehicle_rental->user_id, $item_user_status_id, 'VehicleRental');
            }
            if (isPluginEnabled('VehicleDisputes')) {
                if ($vehicle_rental->is_dispute) {
                    // update status in vehicle_rental disputes
                    $this->vehicleDisputeService->updateConversation($vehicle_rental, $request->message);
                }
            }
            return response()->json(['Success' => 'Private Note has been added'], 200);
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Private Note could not be added. Please, try again.', $validator->errors());
        }
    }

    /**
     * update message as starred message.
     * update the message with a `id`.
     * @put("messages/{id}")
     * @Transaction({
     *      @Request({"id": 1,"stared":0}),
     *      @Response(200, body={"message":"Message Marked as Starred message", "status_code": 200}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function update($id, Request $request)
    {
        $message = Message::find($id);
        if($request->has('stared')) {
            $message['is_starred'] = ($request['stared'] == 0) ? 1 : 0;
        }
        if($message->save()) {
            return response()->json(['Success' => 'Message updated successfully'], 200);
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Message could not be updated. Please, try again.');
        }
    }
}
