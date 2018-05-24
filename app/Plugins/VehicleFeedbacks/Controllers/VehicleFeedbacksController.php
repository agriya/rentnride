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
 
namespace Plugins\VehicleFeedbacks\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use JWTAuth;
use Plugins\VehicleRentals\Services\VehicleRentalService;
use Validator;
use App\Services\IpService;
use Plugins\VehicleFeedbacks\Model\VehicleFeedback;
use Plugins\VehicleFeedbacks\Transformers\VehicleFeedbackTransformer;
use App\User;
use App\Services\MessageService;
use App\Services\UserService;
use Plugins\VehicleFeedbacks\Services\VehicleFeedbackService;
use Plugins\Vehicles\Services\VehicleService;

/**
 * VehicleFeedbacks resource representation.
 * @Resource("VehicleFeedbacksController")
 */
class VehicleFeedbacksController extends Controller
{
    /**
     * @var
     */
    protected $vehicleFeedbackService;
    /**
     * @var
     */
    protected $ip_service;
    /**
     * @var
     */
    protected $messageService;

    /**
     * @var
     */
    protected $vehicleService;

    /**
     * @var
     */
    protected $userService;

    /**
     * @var
     */
    protected $vehicleRentalService;

    /**
     * VehicleFeedbacksController constructor.
     */
    public function __construct(UserService $userService)
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth', ['except' => ['index']]);
        $this->setIpService();
        $this->setMessageService();
        $this->setVehicleFeedbackService();
        $this->setVehicleService();
        $this->setVehicleRentalService();
        $this->userService = $userService;
    }

    /** Object created for ipservice */
    public function setIpService()
    {
        $this->IpService = new IpService();
    }

    /** Object created for messageservice*/
    public function setMessageService()
    {
        $this->messageService = new MessageService();
    }

    /** Object created for VehicleFeedback service */
    public function setVehicleFeedbackService()
    {
        $this->vehicleFeedbackService = new VehicleFeedbackService();
    }

    /**
     * setVehicleService
     */
    public function setVehicleService()
    {
        $this->vehicleService = new VehicleService();
    }

    /**
     * setVehicleRentalService
     */
    public function setVehicleRentalService()
    {
        $this->vehicleRentalService = new VehicleRentalService();
    }

    /**
     * Show all vehicle feedbacks
     * Get a JSON representation of all the vehicle feedbacks.
     *
     * @Get("/vehicle_feedbacks?sort={sort}&sortby={sortby}&page={page}&q={q}")
     * @Parameters({
     *      @Parameter("sort", type="string", required=false, description="Sort the vehicle feedbacks list by sort key.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort vehicle feedbacks by Ascending / Descending Order.", default=null),
     *      @Parameter("q", type="string", required=false, description="Search vehicle feedbacks.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1)
     * })
     */
    public function index(Request $request)
    {
        $enabledIncludes = array('user', 'to_user');
        $feedbacks = VehicleFeedback::with($enabledIncludes)->filterByRequest($request)->paginate(config('constants.ConstPageLimit'));
        $enabledIncludes = array_merge($enabledIncludes, array('feedbackable'));
        return $this->response->paginator($feedbacks, (new VehicleFeedbackTransformer)->setDefaultIncludes($enabledIncludes));
    }

    /**
     * Show the specified vehicle Feedback.
     * Show the vehicle Feedback with a `id`.
     * @Get("vehicle_feedbacks/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"id": 1, "user_id": 1, "to_user_id":1, "feedbackable_id": 1, "feedbackable_type"=>"MorphVehicleRental", "item_id": 1, "feedback": "item feedback", "ip_id": 2, "rating": "1", "User": {}, "Item": {}, "item_user": {}}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function show($id)
    {
        $enabledIncludes = array('user', 'to_user');
        $feedback = VehicleFeedback::with($enabledIncludes)->find($id);
        if (!$feedback) {
            return $this->response->errorNotFound("Invalid Request");
        }
        $enabledIncludes = array_merge($enabledIncludes, array('feedbackable'));
        return $this->response->item($feedback, (new VehicleFeedbackTransformer)->setDefaultIncludes($enabledIncludes));
    }

    /**
     * Booker review
     * @Get('booker/review')
     * @Transaction({
     *      @Request({"item_user_id": 1, "item_id":1, "feedback":"testing', "rating":"2"}),
     *      @Response(200, body={"success": "Feedback has been added."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404}),
     *      @Response(422, body={"message": "Feedback could not be added. Please, try again", "status_code": 422}),
     *      @Response(404, body={"message": "Unauthorized", "status_code": 404})
     * })
     */
    public function BookerReview(Request $request)
    {
        $feedback_data = $request->only('item_user_id', 'feedback', 'rating');
        $validator = Validator::make($feedback_data, VehicleFeedback::GetValidationRule(), VehicleFeedback::GetValidationMessage());
        $user = $this->auth->user();
        if ($validator->passes()) {
            $vehicle_rental = \Plugins\VehicleRentals\Model\VehicleRental::find($request->item_user_id);
            $item = $vehicle_rental->item_userable;
            if (is_null($item)) {
                return $this->response->errorNotFound("Invalid Request");
            }
            //Check vehicle_rental status must be waiting for review
            if ($vehicle_rental->item_user_status_id != config('constants.ConstItemUserStatus.WaitingForReview') && $vehicle_rental->item_user_status_id != config('constants.ConstItemUserStatus.HostReviewed')) {
                return $this->response->errorNotFound("Invalid Request");
            }
            //Check if item_id or item_user_id found or not
            if (!$vehicle_rental || ($vehicle_rental->item_userable_id != $item->id) || ($user->id != $vehicle_rental->user_id) || ($vehicle_rental->item_userable_type != 'MorphVehicle')) {
                return $this->response->errorNotFound("Invalid Request");
            }
            $feedback_data['user_id'] = $user->id;
            $feedback_data['to_user_id'] = $item->user_id;
            $feedback_data['ip_id'] = $this->IpService->getIpId($request->ip());
            $feedback = VehicleFeedback::create($feedback_data);
            $vehicle_feedback = \Plugins\Vehicles\Model\Vehicle::with(['vehicle_feedback'])->where('id', '=', $vehicle_rental->item_userable->id)->first();
            $feedback = $vehicle_feedback->vehicle_feedback()->save($feedback);
            $payment_status = false;
            if ($feedback) {
                if ($vehicle_rental->item_user_status_id == config('constants.ConstItemUserStatus.WaitingForReview')) {
                    $vehicle_rental->item_user_status_id = config('constants.ConstItemUserStatus.BookerReviewed');
                    $from_status = config('constants.ConstItemUserStatus.WaitingForReview');
                    $to_status = config('constants.ConstItemUserStatus.BookerReviewed');
                }
                if ($vehicle_rental->item_user_status_id == config('constants.ConstItemUserStatus.HostReviewed')) {
                    $vehicle_rental->item_user_status_id = config('constants.ConstItemUserStatus.WaitingForPaymentCleared');
                    $from_status = config('constants.ConstItemUserStatus.HostReviewed');
                    $to_status = config('constants.ConstItemUserStatus.BookerReviewed');
                    $payment_status = true;
                }
                $vehicle_rental->save();
                $average_rating = VehicleFeedback::where(['feedbackable_id' => $vehicle_rental->item_userable->id, 'feedbackable_type' => 'MorphVehicle', 'is_host' => 0])->avg('rating');
                $this->vehicleService->updateFeedbackDetails($vehicle_feedback, $average_rating);
                $message_content_arr = array();
                $message_content_arr['subject'] = 'Feedback';
                $message_content_arr['message'] = $user->username . ' has left a feedback on your item - ' . $feedback->feedback;
                $this->vehicleFeedbackService->sendFeedbackMail($user->username, $item->user->username, $item->user->email, 'booker', $feedback->feedback);
                $this->messageService->saveMessageContent($message_content_arr, $item->id, $vehicle_rental->id, $user->id, $item->user_id, config('constants.ConstItemUserStatus.BookerReviewed'), 'VehicleRental');
                $this->vehicleRentalService->changeStatusMail($vehicle_rental, $vehicle_rental->item_userable, $from_status, $to_status);
                if ($payment_status) {
                    $this->vehicleRentalService->changeStatusMail($vehicle_rental, $vehicle_rental->item_userable, config('constants.ConstItemUserStatus.BookerReviewed'), config('constants.ConstItemUserStatus.WaitingForPaymentCleared'));
                }
                return response()->json(['Success' => 'Feedback has been added'], 200);
            } else {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('Feedback could not be added. Please, try again.');
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Feedback could not be added. Please, try again.', $validator->errors());
        }
    }

    /**
     * Host review
     * @Get('host/review')
     * @Transaction({
     *      @Request({"item_user_id": 1, "item_id":1, "feedback":"testing', "rating":"2"}),
     *      @Response(200, body={"success": "Feedback has been added."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404}),
     *      @Response(422, body={"message": "Feedback could not be added. Please, try again", "status_code": 422}),
     *      @Response(404, body={"message": "Unauthorized", "status_code": 404})
     * })
     */
    public function HostReview(Request $request)
    {
        $feedback_data = $request->only('item_user_id', 'feedback', 'rating');
        $validator = Validator::make($feedback_data, VehicleFeedback::GetValidationRule(), VehicleFeedback::GetValidationMessage());
        $user = $this->auth->user();
        //Check if user is authenticated
        if (!$user) {
            return $this->response->errorNotFound("Unauthorized");
        }
        if ($validator->passes()) {
            $vehicle_rental = \Plugins\VehicleRentals\Model\VehicleRental::find($request->item_user_id);
            $item = $vehicle_rental->item_userable;
            if (is_null($item)) {
                return $this->response->errorNotFound("Invalid Request");
            }
            //Check vehicle_rental status must be waiting for review
            if ($vehicle_rental->item_user_status_id != config('constants.ConstItemUserStatus.WaitingForReview') && $vehicle_rental->item_user_status_id != config('constants.ConstItemUserStatus.BookerReviewed')) {
                return $this->response->errorNotFound("Invalid Request");
            }
            //Check if item_id or item_user_id found or not
            if ((!$vehicle_rental) || ($vehicle_rental->item_userable_id != $item->id) || ($user->id != $item->user_id) || ($vehicle_rental->item_userable_type != 'MorphVehicle')) {
                return $this->response->errorNotFound("Invalid Request");
            }
            $feedback_data['is_host'] = true;
            $feedback_data['user_id'] = $user->id;
            $feedback_data['to_user_id'] = $vehicle_rental->user_id;
            $feedback_data['ip_id'] = $this->IpService->getIpId($request->ip());
            $feedback = VehicleFeedback::create($feedback_data);
            $user_feedback = User::with(['vehicle_feedback'])->where('id', '=', $vehicle_rental->user_id)->first();
            $feedback = $user_feedback->vehicle_feedback()->save($feedback);
            $payment_status = false;
            if ($feedback) {
                if ($vehicle_rental->item_user_status_id == config('constants.ConstItemUserStatus.WaitingForReview')) {
                    $vehicle_rental->item_user_status_id = config('constants.ConstItemUserStatus.HostReviewed');
                    $from_status = config('constants.ConstItemUserStatus.WaitingForReview');
                    $to_status = config('constants.ConstItemUserStatus.HostReviewed');
                }
                if ($vehicle_rental->item_user_status_id == config('constants.ConstItemUserStatus.BookerReviewed')) {
                    $vehicle_rental->item_user_status_id = config('constants.ConstItemUserStatus.WaitingForPaymentCleared');
                    $from_status = config('constants.ConstItemUserStatus.BookerReviewed');
                    $to_status = config('constants.ConstItemUserStatus.HostReviewed');
                    $payment_status = true;
                }
                $vehicle_rental->save();
                $average_rating = VehicleFeedback::where(['feedbackable_id' => $vehicle_rental->user_id, 'feedbackable_type' => 'MorphUser', 'is_host' => 1])->avg('rating');
                $this->userService->updateFeedbackDetails($user_feedback, $average_rating);
                $message_content_arr = array();
                $message_content_arr['subject'] = 'Feedback';
                $message_content_arr['message'] = $user->username . ' has left a feedback about you - ' . $feedback->feedback;
                $this->vehicleFeedbackService->sendFeedbackMail($user->username, $vehicle_rental->user->username, $vehicle_rental->user->email, 'host', $feedback->feedback);
                $this->messageService->saveMessageContent($message_content_arr, $item->id, $vehicle_rental->id, $user->id, $vehicle_rental->user_id, config('constants.ConstItemUserStatus.HostReviewed'), 'VehicleRental');
                $this->vehicleRentalService->changeStatusMail($vehicle_rental, $vehicle_rental->item_userable, $from_status, $to_status);
                if ($payment_status) {
                    $this->vehicleRentalService->changeStatusMail($vehicle_rental, $vehicle_rental->item_userable, config('constants.ConstItemUserStatus.HostReviewed'), config('constants.ConstItemUserStatus.WaitingForPaymentCleared'));
                }
                return response()->json(['Success' => 'Feedback has been added'], 200);
            } else {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('Feedback could not be added. Please, try again.');
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Feedback could not be added. Please, try again.', $validator->errors());
        }
    }
}
