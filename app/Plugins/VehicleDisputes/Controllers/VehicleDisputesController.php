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
 
namespace Plugins\VehicleDisputes\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use JWTAuth;
use Validator;
use Plugins\VehicleDisputes\Model\VehicleDispute;
use Plugins\VehicleRentals\Model\VehicleRental;
use Plugins\VehicleDisputes\Model\VehicleDisputeType;
use Plugins\VehicleDisputes\Model\VehicleDisputeClosedType;
use Plugins\VehicleDisputes\Transformers\VehicleDisputeTransformer;
use Plugins\VehicleDisputes\Services\VehicleDisputeService;
use Plugins\VehicleRentals\Services\VehicleRentalService;

/**
 * VehicleDisputes resource representation.
 * @Resource("disputes")
 */
class VehicleDisputesController extends Controller
{
    /**
     * @var vehicleDisputeService
     */
    protected $vehicleDisputeService;
    /**
     * @var VehicleRentalService
     */
    protected $vehicleRentalService;

    /**
     * VehicleDisputesController constructor.
     */
    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
        $this->setVehicleDisputeService();
        $this->setVehicleRentalService();
    }

    /**
     * Set VehicleRentalService
     */
    public function setVehicleRentalService()
    {
        $this->vehicleRentalService = new VehicleRentalService();
    }

    /**
     * Set VehicleDisputeService
     */
    public function setVehicleDisputeService()
    {
        $this->vehicleDisputeService = new VehicleDisputeService();
    }

    /**
     * Show all VehicleDisputes
     * Get a JSON representation of all the VehicleDisputes.
     *
     * @Get("/vehicle_disputes?sort={sort}&sortby={sortby}&page={page}&q={q}")
     * @Parameters({
     *      @Parameter("sort", type="string", required=false, description="Sort the disputes list by sort key.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort disputes by Ascending / Descending Order.", default=null),
     *      @Parameter("q", type="string", required=false, description="Search disputes.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1),
     *      @Parameter("filter", type="string", required=false, description="Filter disputes.", default=null)
     * })
     */
    public function index(Request $request)
    {
        $enabledIncludes = array('user', 'dispute_type', 'dispute_closed_type', 'dispute_status');
        $vehicle_disputes = VehicleDispute::with($enabledIncludes)->filterByVehicleRental()->filterByRequest($request)->paginate(config('constants.ConstPageLimit'));
        $enabledIncludes = array_merge($enabledIncludes, array('item_user_disputable'));
        return $this->response->paginator($vehicle_disputes, (new VehicleDisputeTransformer)->setDefaultIncludes($enabledIncludes));
    }

    /**
     * Store a new dispute.
     * Store a new dispute with a `item_user_id`, 'dispute_type_id', 'reason'.
     * @Post("/vehicle_disputes")
     * @Transaction({
     *      @Request({"item_user_id":1, "dispute_type_id":1, "reason": "Not matching the specification"}),
     *      @Response(200, body={"success": "VehicleDispute has been added."}),
     *      @Response(422, body={"message": "VehicleDispute could not be updated. Please, try again.", "errors": {"name": {}}, "status_code": 422})
     * })
     */
    public function store(Request $request)
    {
        $user = $this->auth->user();
        $vehicle_dispute_data = $request->only('item_user_id', 'dispute_type_id', 'reason');
        // checking conditions
        $vehicle_rental = VehicleRental::with('user')->where('id', '=', $request->item_user_id)
            ->where('is_dispute', '=', 0)
            ->whereIn('item_user_status_id', [config('constants.ConstItemUserStatus.WaitingForReview'), config('constants.ConstItemUserStatus.BookerReviewed')])->first();
        if (!$vehicle_rental || is_null($vehicle_rental->item_userable)) {
            return $this->response->errorNotFound("Invalid Request");
        }
        $booker_id = (int)$vehicle_rental->user_id;
        $host_id = (int)$vehicle_rental->item_userable->user_id;
        if ($user->role_id != config('constants.ConstUserTypes.Admin') && $booker_id != $user->id && $host_id != $user->id) {
            return $this->response->errorNotFound("Invalid Request");
        }
        if($booker_id == $user->id && ($vehicle_rental->item_user_status_id != config('constants.ConstItemUserStatus.WaitingForReview') || $request->dispute_type_id == config('constants.ConstDisputeTypes.Feedback') || $request->dispute_type_id == config('constants.ConstDisputeTypes.Security'))) {
            return $this->response->errorNotFound("Invalid Request");
        }
        if($host_id == $user->id && ($request->dispute_type_id == config('constants.ConstDisputeTypes.Specification') || ($request->dispute_type_id == config('constants.ConstDisputeTypes.Feedback') && $vehicle_rental->item_user_status_id == config('constants.ConstItemUserStatus.WaitingForReview')))) {
            return $this->response->errorNotFound("Invalid Request");
        }
        ($user->id === $booker_id) ? $vehicle_dispute_data['is_booker'] = 1 : $vehicle_dispute_data['is_booker'] = 0;
        $validator = Validator::make($vehicle_dispute_data, VehicleDispute::GetValidationRule(), VehicleDispute::GetValidationMessage());
        if ($validator->passes() && $vehicle_rental) {
            $vehicle_dispute_data['model_type'] = config('constants.ConstBookingTypes.Booking');
            $vehicle_dispute_data['dispute_status_id'] = config('constants.ConstDisputeStatuses.Open');
            $vehicle_dispute_data['dispute_conversation_count'] = 1;
            $vehicle_dispute_data['last_replied_user_id'] = $user->id;
            $vehicle_dispute_data['last_replied_date'] = date('Y-m-d h:i:s');
            $vehicle_dispute_data['user_id'] = $user->id;
            $vehicle_dispute = VehicleDispute::create($vehicle_dispute_data);
            if ($vehicle_dispute) {
                $item_user = VehicleRental::with(['item_user_dispute'])->where('id', '=', $request->item_user_id)->first();
                $item_user->item_user_dispute()->save($vehicle_dispute);
                // update dispute in item_users
                $this->vehicleRentalService->updateDispute($request->item_user_id);
                // send mail
                $this->vehicleDisputeService->sendDisputeOpenMail($vehicle_dispute, $vehicle_rental);
                return response()->json(['Success' => 'Vehicle Dispute has been added'], 200);
            } else {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle Dispute could not be sent. Please, try again.');
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Vehicle Dispute could not be sent. Please, try again.', $validator->errors());
        }
    }

    /**
     * @param $vehicle_rental_id
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response|void
     */
    public function getPossibleDisputeTypes($vehicle_rental_id)
    {
        $user = $this->auth->user();
        if (isPluginEnabled('VehicleRentals')) {
            $enabledIncludes = array('user', 'item_user_status', 'message');
            // check if plugin enabled and include
            (isPluginEnabled('VehicleFeedbacks')) ? $enabledIncludes[] = 'vehicle_feedback' : '';
            $vehicle_rental = \Plugins\VehicleRentals\Model\VehicleRental::with($enabledIncludes)
                ->where('id', '=', $vehicle_rental_id)
                ->whereIn('item_user_status_id', array(config('constants.ConstItemUserStatus.WaitingForReview'), config('constants.ConstItemUserStatus.BookerReviewed')))->first();
            if (!($vehicle_rental)) {
                return $this->response->errorNotFound("Invalid Request");
            }
            $booker_id = (int)$vehicle_rental->user_id;
            $host_id = (int)$vehicle_rental->item_userable->user_id;
            if ($user->role_id == config('constants.ConstUserTypes.Admin') && $booker_id != $user->id && $host_id != $user->id && $vehicle_rental->is_dispute) {
                $dispute = VehicleDispute::with('dispute_type', 'dispute_status', 'user', 'item_user_disputable')
                    ->where('item_user_disputable_id', '=', $vehicle_rental_id)
                    ->where('dispute_type_id', '!=', config('constants.ConstDisputeStatuses.Closed'))->first();
                $dispute_closed_types = VehicleDisputeClosedType::with('dispute_type')
                    ->where('dispute_type_id', '=', $dispute->dispute_type_id)->get();
                $dispute_array['dispute'] = $dispute;
                $dispute_array['dispute_close_types'] = $dispute_closed_types;
                $dispute_array['feedback'] = $vehicle_rental->vehicle_feedback;
                $now = time(); // or your date as well
                $dispute_date = strtotime($dispute->created_at);
                $datediff = $now - $dispute_date;
                $dispute_array['diff_days'] = floor($datediff / (60 * 60 * 24));
                return response()->json(['dispute_array' => $dispute_array], 200);
            } else {
                if ($vehicle_rental->is_dispute) {
                    return response()->json(['is_under_dispute' => true], 200);
                }

                ($user->id === $booker_id) ? $is_booker = 1 : $is_booker = 0;
                // all disputes for this user type
                $all_vehicle_dispute_types = VehicleDisputeType::where('is_booker', '=', $is_booker)->where('is_active', '=', 1)->get();
                $disputeTypes = false;
                foreach ($all_vehicle_dispute_types as $key => $dispute_type_det) {
                    if ((int)$vehicle_rental->item_user_status_id === config('constants.ConstItemUserStatus.WaitingForReview')) {
                        // property doesn't match host requirements (booker)
                        if ($booker_id === $user->id && $dispute_type_det && $dispute_type_det->id === config('constants.ConstDisputeTypes.Specification')) {
                            $disputeTypes[] = $dispute_type_det;
                        }
                        // Claim the security damage (host)
                        if ($host_id === $user->id && $dispute_type_det && $dispute_type_det->id === config('constants.ConstDisputeTypes.Security')) {
                            $disputeTypes[] = $dispute_type_det;
                        }
                    } else if ((int)$vehicle_rental->item_user_status_id === config('constants.ConstItemUserStatus.BookerReviewed') && $host_id === $user->id && $vehicle_rental->vehicle_feedback && $vehicle_rental->vehicle_feedback[0]->rating < config('dispute.rating_limit_to_raise_dispute')) {
                        // Poor Feedback dispute (host)
                        if ($dispute_type_det && $dispute_type_det->id === config('constants.ConstDisputeTypes.Feedback')) {
                            $disputeTypes[] = $dispute_type_det;
                        }
                        // Claim the security damage (host)
                        if ($dispute_type_det && $dispute_type_det->id === config('constants.ConstDisputeTypes.Security')) {
                            $disputeTypes[] = $dispute_type_det;
                        }
                    }
                }
                if ($disputeTypes) {
                    return response()->json(['dispute_types' => $disputeTypes], 200);
                } else if ($all_vehicle_dispute_types) {
                    return response()->json(['all_dispute_types' => $all_vehicle_dispute_types], 200);
                } else {
                    throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleDispute could not be raised.');
                }
            }
        } else {
            return $this->response->errorNotFound("Invalid Request");
        }
    }
}
