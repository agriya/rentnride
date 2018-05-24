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

use App\Dashboard;

use JWTAuth;

use App\Services\UserService;
use App\Services\UserLoginService;
use App\Services\TransactionService;

/**
 * Dashboards resource representation.
 * @Resource("Admin/AdminDashboards")
 */
class AdminDashboardsController extends Controller
{
    /**
     * @var UserService
     */
    protected $UserService;
    /**
     * @var UserLoginService
     */
    protected $UserLoginService;
    /**
     * @var
     */
    protected $transactionService;

    /**
     * AdminDashboardsController constructor.
     */
    public function __construct(UserService $user_service)
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
        // Check the logged user role.
        $this->middleware('role');
        $this->UserService = $user_service;
        $this->setUserLoginService();
        $this->setTransactionService();
    }

    public function setUserLoginService()
    {
        $this->UserLoginService = new UserLoginService();
    }

    public function setTransactionService()
    {
        $this->transactionService = new TransactionService();
    }

    /**
     * Show all Dashboards stats
     * Get a JSON representation of all the Dashboards statistics.
     *
     * @Get("/stats")
     * @Parameters({
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1)
     * })
     */
    public function stats(Request $request)
    {
        if (!$request->has('filter')) {
            $last_user_login = $this->UserLoginService->getLastLogin();
            $last_register = $this->UserService->getLastRegistered();
            $total_users = $this->UserService->getTotalUsers();
            $site_version = config('site.version');
            if (isPluginEnabled('VehicleRentals')) {
                $this->VehicleRentalService = new \Plugins\VehicleRentals\Services\VehicleRentalService();
                $last_vehicle_rental = $this->VehicleRentalService->getLastVehicleRental();
                $total_booking_count = $this->VehicleRentalService->getVehicleRentalCount($request, 'total');
                $total_revenue = $this->transactionService->getAdminTotalRevenue();
            }
            if (isPluginEnabled('Vehicles')) {
                $this->vehicleService = new \Plugins\Vehicles\Services\VehicleService();
                $last_item = $this->vehicleService->getLastAddVehicle();
                $total_vehicle_count = $this->vehicleService->getVehicleCount($request, 'total');
            }
            if (isPluginEnabled('VehicleFeedbacks')) {
                $this->feedbackService = new \Plugins\VehicleFeedbacks\Services\VehicleFeedbackService();
                $last_feedback = $this->feedbackService->getLastFeedback();
                $total_feedback_count = $this->feedbackService->getFeedbackCount($request, 'total');
            }

        }
        $user_login = $this->UserService->getLoginCount($request);
        $user_register = $this->UserService->getRegisterCount($request);
        $transaction_count = $this->transactionService->getTranactionCount($request);
        if (isPluginEnabled('VehicleRentals')) {
            $this->VehicleRentalService = new \Plugins\VehicleRentals\Services\VehicleRentalService();
            $booking_count = $this->VehicleRentalService->getVehicleRentalCount($request);
        }
        if (isPluginEnabled('Vehicles')) {
            $this->vehicleService = new \Plugins\Vehicles\Services\VehicleService();
            $vehicle_count = $this->vehicleService->getVehicleCount($request);
        }
        if (isPluginEnabled('VehicleFeedbacks')) {
            $this->feedbackService = new \Plugins\VehicleFeedbacks\Services\VehicleFeedbackService();
            $feedback_count = $this->feedbackService->getFeedbackCount($request);
        }

        if (!$request->has('filter')) {
            return response()->json(compact('user_login', 'user_register', 'last_user_login', 'last_register', 'total_users', 'site_version', 'last_vehicle_rental', 'last_item', 'last_feedback', 'total_booking_count', 'booking_count', 'vehicle_count', 'total_vehicle_count', 'total_feedback_count', 'feedback_count', 'transaction_count', 'total_revenue'));
        } else {
            return response()->json(compact('user_login', 'user_register', 'booking_count', 'vehicle_count', 'feedback_count', 'transaction_count'));
        }

    }
}