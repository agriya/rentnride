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
 
namespace Plugins\VehicleFeedbacks\Services;


use App\Services\MailService;
use Plugins\VehicleFeedbacks\Model\VehicleFeedback;
use Carbon;
use Validator;

class VehicleFeedbackService
{
    /**
     * VehicleFeedbackService constructor.
     */
    public function __construct()
    {
        $this->setMailService();
    }

    public function setMailService()
    {
        $this->mailService = new MailService();
    }

    /**
     * get last Feedback record for admin dashboard
     * @return mixed
     */
    public function getLastFeedback()
    {
        $feedback_details = VehicleFeedback::select('created_at')->where('feedbackable_type', '=', 'MorphVehicle')->orderBy('created_at', 'desc')->first();
        return ($feedback_details) ? $feedback_details->created_at->diffForHumans() : '-';
    }

    /**
     * @param        $request
     * @param string $type
     * @return mixed
     */
    public function getFeedbackCount($request, $type = 'filter')
    {
        if ($type == 'filter') {
            $check_date = $this->getDateFilter($request);
            $check_date = Carbon::parse($check_date)->format('Y-m-d');
            $booking_count = VehicleFeedback::where('created_at', '>=', $check_date)->count();
        } else {
            $booking_count = VehicleFeedback::count();
        }
        return $booking_count;
    }

    /**
     * get the date filter
     * @return $check_date
     */
    public function getDateFilter($request)
    {
        $check_date = Carbon::now()->subDays(7);
        if ($request->has('filter')) {
            if ($request->filter == 'lastDays') {
                $check_date = Carbon::now()->subDays(7);
            } else if ($request->filter == 'lastWeeks') {
                $check_date = Carbon::now()->subWeeks(4);
            } else if ($request->filter == 'lastMonths') {
                $check_date = Carbon::now()->subMonths(3);
            } else if ($request->filter == 'lastYears') {
                $check_date = Carbon::now()->subYears(3);
            }
        }
        return $check_date;
    }

    public function sendFeedbackMail($from_user, $username, $email, $reviewer, $feedback)
    {
        $from = config('constants.ConstUserIds.Admin');
        if ($reviewer == 'host') {
            $template = $this->mailService->getTemplate('Feedback to Booker');
        }
        if ($reviewer == 'booker') {
            $template = $this->mailService->getTemplate('Feedback to Host');
        }
        $user_link = '<a href="' . url('/#/user/' . $from_user) . '">' . $from_user . '</a>';
        $emailFindReplace = array(
            '##USERNAME##' => $username,
            '##BOOKER##' => $from_user,
            '##BOOKER_URL##' => $user_link,
            '##HOST##' => $from_user,
            '##HOST_URL##' => $user_link,
            '##MESSAGE##' => $feedback
        );
        $this->mailService->sendMail($template, $emailFindReplace, $email, $username);
    }

    public function feedBackUpdate($request, $id, $vehicle_rental = null)
    {
        $vehicle_feedback_data = $request->only('feedback', 'rating');
        $validator = Validator::make($vehicle_feedback_data, VehicleFeedback::GetValidationRule(), VehicleFeedback::GetValidationMessage());
        $vehicle_feedback = false;
        if ($request->has('id')) {
            $vehicle_feedback = VehicleFeedback::find($id);
            $vehicle_feedback = ($request->id != $id) ? false : $vehicle_feedback;
        } else {
            $vehicle_feedback = $vehicle_rental->vehicle_feedback[0];
        }
        if ($validator->passes() && $vehicle_feedback) {
            try {
                $vehicle_feedback->update($vehicle_feedback_data);
                if ($request->has('dispute_closed_type_id')) {
                    if (isPluginEnabled('VehicleRentals')) {
                        if (!$vehicle_rental) {
                            return $this->response->errorNotFound("Invalid Request");
                        }
                    }
                    if (isPluginEnabled('VehicleDisputes')) {
                        $vehicle_dispute_data = $request->only('item_user_id', 'dispute_closed_type_id');
                        $VehicleDisputeService = new \Plugins\VehicleDisputes\Services\VehicleDisputeService();
                        $VehicleDisputeService->closeDispute($vehicle_dispute_data, $vehicle_rental, 0);
                    }
                }
                return response()->json(['Success' => 'Feedback has been updated'], 200);
            } catch (\Exception $e) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('Feedback could not be updated. Please, try again.');
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Feedback could not be updated. Please, try again.', $validator->errors());
        }
    }
}
