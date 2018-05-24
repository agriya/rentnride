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

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use JWTAuth;
use App\Message;
use Validator;
use App\Transformers\MessageTransformer;
use DB;

/**
 * AdminMessages resource representation.
 * @Resource("Admin/AdminMessages")
 */
class AdminMessagesController extends Controller
{
    /**
     * AdminMessagesController constructor.
     */
    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
        // Check the logged user role.
        $this->middleware('role');
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
    public function index(Request $request)
    {
        /*$enabledIncludes = "";
        // check if plugin enabled and include
        (isPluginEnabled('Items')) ? $enabledIncludes[] = 'item' : '';
        (isPluginEnabled('VehicleRentals')) ? $enabledIncludes[] = 'item_users' : '';*/
        $messages = Message::select(DB::raw('messages.*'))
            ->leftJoin(DB::raw('(select id,username from users) as from_user'), 'from_user.id', '=', 'messages.user_id')
            ->leftJoin(DB::raw('(select id,username from users) as to_user'), 'to_user.id', '=', 'messages.to_user_id')
            ->leftJoin(DB::raw('(select id,message, subject from message_contents) as message_content'), 'message_content.id', '=', 'messages.message_content_id')
            ->filterByRequest($request)
            ->paginate(config('constants.ConstPageLimit'));
        return $this->response->paginator($messages, (new MessageTransformer)->setDefaultIncludes(['from_user', 'to_user', 'message_content', 'item_user_status', 'dispute_status', 'messageable']));
    }

    /**
     * Get item activities
     *
     * @Get("/admin/item_messages/{item_id}")
     * @Transaction({
     *      @Request({"item_id": 1}),
     *      @Response(200, body={"user_id":1,"item_id":1,"to_user_id":2,"message_content_id":1,"message_folder_id":1,"messageable_id":1,"messageable_type":"App\Model\User","is_sender":1,"is_starred":1,"is_read":1,"is_deleted":1,"is_archived":1,"is_review":1,"is_communication":0,"user":{},"item_user":{},"item":{},"message_content":{}}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    /*public function itemActivities($item_id)
    {
        $enabledIncludes = array('user', 'message_content');
        // check if plugin enabled and include
        (isPluginEnabled('Items')) ? $enabledIncludes[] = 'item' : '';
        (isPluginEnabled('VehicleRentals')) ? $enabledIncludes[] = 'item_users' : '';
        $item_message = Message::with($enabledIncludes)->where('item_id', $item_id)->get();
        if (!$item_message) {
            return $this->response->errorNotFound('Invalid Request');
        }
        return $this->response->Collection($item_message, (new MessageTransformer)->setDefaultIncludes($enabledIncludes));
    }*/

    /**
     * Get item user activities
     *
     * @Get("/admin/item_user_messages/{item_user_id}")
     * @Transaction({
     *      @Request({"item_user_id": 1}),
     *      @Response(200, body={"user_id":1,"item_id":1,"to_user_id":2,"message_content_id":1,"message_folder_id":1,"messageable_id":1,"messageable_type":"App\Model\User","is_sender":1,"is_starred":1,"is_read":1,"is_deleted":1,"is_archived":1,"is_review":1,"is_communication":0,"user":{},"item_user":{},"item":{},"message_content":{}}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    /*public function bookerActivities($item_user_id)
    {
        $enabledIncludes = array('user', 'message_content');
        // check if plugin enabled and include
        (isPluginEnabled('Items')) ? $enabledIncludes[] = 'item' : '';
        (isPluginEnabled('VehicleRentals')) ? $enabledIncludes[] = 'item_users' : '';
        $item_user_messages = Message::with($enabledIncludes)->where('messageable_id',$item_user_id)->get();
        if(!$item_user_messages)
        {
            return $this->response->errorNotFound('Invalid Request');
        }
        return $this->response->Collection($item_user_messages, (new MessageTransformer)->setDefaultIncludes($enabledIncludes));

    }*/

    /**
     * Get user activities
     *
     * @Get("/admin/user_messages/{user_id}")
     * @Transaction({
     *      @Request({"user_id": 1}),
     *      @Response(200, body={"user_id":1,"item_id":1,"to_user_id":2,"message_content_id":1,"message_folder_id":1,"messageable_id":1,"messageable_type":"App\Model\User","is_sender":1,"is_starred":1,"is_read":1,"is_deleted":1,"is_archived":1,"is_review":1,"is_communication":0,"user":{},"item_user":{},"item":{},"message_content":{}}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    /*public function userActivities($user_id)
    {
        $enabledIncludes = array('user', 'message_content');
        // check if plugin enabled and include
        (isPluginEnabled('Items')) ? $enabledIncludes[] = 'item' : '';
        (isPluginEnabled('VehicleRentals')) ? $enabledIncludes[] = 'item_users' : '';
        $user_messages = Message::with($enabledIncludes)->where('messageable_id',$user_id)->get();
        if(!$user_messages)
        {
            return $this->response->errorNotFound('Invalid Request');
        }
        return $this->response->Collection($user_messages, (new MessageTransformer)->setDefaultIncludes($enabledIncludes));
    }*/

    /**
     * Show the specified message.
     * Show the message with a `id`.
     * @Get("/messages/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"id": 1, "created_at": "2016-05-10 13:12:13", "user_id": 4, "to_user_id": 1, "item_id": 1, "user": {}, "message_content": {}, "item": {}, "item_users": {}}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function show($id)
    {
        $enabledIncludes = array('from_user', 'to_user', 'message_content', 'item_user_status', 'dispute_status');
        $message = Message::with($enabledIncludes)->find($id);
        if (!$message) {
            return $this->response->errorNotFound("Invalid Request");
        }
        $enabledIncludes = array_merge($enabledIncludes, array('messageable'));
        return $this->response->item($message, (new MessageTransformer)->setDefaultIncludes($enabledIncludes));
    }

    /**
     * Delete the specified message.
     * Delete the message with a `id`.
     * @Delete("messages/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"success": "Messages Deleted."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function destroy($id)
    {
        $message = Message::find($id);
        if (!$message) {
            return $this->response->errorNotFound("Invalid Request");
        } else {
            $message->delete();
        }
        return response()->json(['Success' => 'Messages deleted'], 200);
    }

}
