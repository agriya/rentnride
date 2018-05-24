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
 
namespace Plugins\VehicleDisputes\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;
use Validator;
use Plugins\VehicleDisputes\Model\VehicleDispute;
use Plugins\VehicleRentals\Model\VehicleRental;
use Plugins\VehicleDisputes\Transformers\VehicleDisputeTransformer;
use Plugins\VehicleDisputes\Services\VehicleDisputeService;
use Plugins\VehicleDisputes\Services\VehicleDisputeClosedTypeService;
use DB;
/**
 * VehicleDisputes resource representation.
 * @Resource("Admin/AdminVehicleDisputess")
 */
class AdminVehicleDisputesController extends Controller
{
    /**
     * @var vehicleDisputeService
     */
    protected $vehicleDisputeService;
    /**
     * @var
     */
    protected $disputeClosedTypeService;

    /**
     * AdminVehicleDisputesController constructor.
     */
    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
        // Check the logged user role.
        $this->middleware('role');
        $this->setVehicleDisputeService();
        $this->setVehicleDisputeClosedTypeService();
    }

    /**
     * Set VehicleDisputeClosedTypeService
     */
    public function setVehicleDisputeClosedTypeService()
    {
        $this->disputeClosedTypeService = new VehicleDisputeClosedTypeService();
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
        $vehicle_disputes = VehicleDispute::with($enabledIncludes)
            ->select(DB::raw('item_user_disputes.*'))
            ->leftJoin(DB::raw('(select id,username from users) as user'), 'user.id', '=', 'item_user_disputes.user_id')
            ->leftJoin(DB::raw('(select id,name from dispute_types) as dispute_type'), 'dispute_type.id', '=', 'item_user_disputes.dispute_type_id')
            ->leftJoin(DB::raw('(select id,name from dispute_statuses) as dispute_status'), 'dispute_status.id', '=', 'item_user_disputes.dispute_status_id')
            ->leftJoin(DB::raw('(select id from item_users) as item_user_disputable'), 'item_user_disputable.id', '=', 'item_user_disputes.item_user_disputable_id')
            ->leftJoin(DB::raw('(select id, name from vehicles) as item_userable'), 'item_userable.id', '=', 'item_user_disputable.id')
            ->leftJoin(DB::raw('(select id, name from item_user_statuses) as item_user_status'), 'item_user_status.id', '=', 'item_userable.id')
            ->filterByVehicleRental()->filterByRequest($request)->paginate(config('constants.ConstPageLimit'));
        $enabledIncludes = array_merge($enabledIncludes, array('item_user_disputable'));
        return $this->response->paginator($vehicle_disputes, (new VehicleDisputeTransformer)->setDefaultIncludes($enabledIncludes));
    }

    /**
     * Store a new dispute.
     * Store a new dispute with a `item_user_id`, 'dispute_closed_type_id', 'discount', 'discount_type', 'no_of_quantity',  'validity_start_date', 'validity_end_date', 'maximum_discount_amount'.
     * @Post("/vehicle_disputes")
     * @Transaction({
     *      @Request({"item_id":1, "dispute_closed_type_id": 1}),
     *      @Response(200, body={"success": "VehicleDispute has been resolved."}),
     *      @Response(422, body={"message": "VehicleDispute could not be resolved. Please, try again.", "errors": {"name": {}}, "status_code": 422})
     * })
     */
    public function resolve(Request $request)
    {
        $user = $this->auth->user();
        $vehicle_dispute_data = $request->only('item_user_id', 'dispute_closed_type_id');
        // checking conditions
        $enabledIncludes = array('user');
        // check if plugin enabled and include
        (isPluginEnabled('VehicleFeedbacks')) ? $enabledIncludes[] = 'vehicle_feedback' : '';
        $vehicle_rental = VehicleRental::with($enabledIncludes)->where('id', '=', $request->item_user_id)
            ->where('is_dispute', '=', 1)->first();
        if (!$vehicle_rental) {
            return $this->response->errorNotFound("Invalid Request");
        }
        $booker_id = $vehicle_rental->user_id;
        if ($vehicle_rental->item_user_dispute->dispute_status_id === config('constants.ConstDisputeStatuses.Closed')) {
            return $this->response->errorNotFound("Invalid Request");
        }
        ($user->id === $booker_id) ? $vehicle_dispute_data['is_booker'] = 1 : $vehicle_dispute_data['is_booker'] = 0;
        $dispute_closed_types = $this->disputeClosedTypeService->getClosedTypeByDisputeType($vehicle_rental->item_user_dispute->dispute_type_id);
        $dispute_closed_type_id = (int)$request->dispute_closed_type_id;
        if (!in_array($dispute_closed_type_id, $dispute_closed_types)) {
            return $this->response->errorNotFound("Invalid Request");
        }
        $validator = Validator::make($vehicle_dispute_data, VehicleDispute::GetValidationRule(), VehicleDispute::GetValidationMessage());
        if ($validator->passes() && $vehicle_rental) {
            if ($dispute_closed_type_id === config('constants.ConstDisputeClosedTypes.SpecificationFavourBookerRefund') || $request->dispute_closed_type_id === config('constants.ConstDisputeClosedTypes.SpecificationResponseFavourBooker')) {
                $is_favour_booker = 1;
                $this->vehicleDisputeService->resolveByRefund($vehicle_rental);
            } elseif ($dispute_closed_type_id === config('constants.ConstDisputeClosedTypes.FeedbackFavourHost') || $request->dispute_closed_type_id === config('constants.ConstDisputeClosedTypes.FeedbackResponseFavourHost')) {
                $is_favour_booker = 0;
                if (isPluginEnabled('VehicleFeedbacks') && !empty($vehicle_rental->vehicle_feedback)) {
                    $vehicleFeedbackService = new \Plugins\VehicleFeedbacks\Services\VehicleFeedbackService();
                    $vehicleFeedbackService->feedBackUpdate($request, $vehicle_rental->vehicle_feedback[0]->id, $vehicle_rental);
                } else {
                    throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleDispute could not be resolved. Please, try again.');
                }
            } elseif ($dispute_closed_type_id === config('constants.ConstDisputeClosedTypes.FeedbackFavourBooker')) {
                $is_favour_booker = 1;
            } elseif ($dispute_closed_type_id === config('constants.ConstDisputeClosedTypes.SpecificationFavourHost')) {
                $is_favour_booker = 0;
            } elseif ($dispute_closed_type_id === config('constants.ConstDisputeClosedTypes.SecurityFavourBooker')) {
                $is_favour_booker = 1;
                $this->vehicleDisputeService->resolveByDepositAmountRefund($vehicle_rental, $dispute_closed_type_id);
            } elseif ($dispute_closed_type_id === config('constants.ConstDisputeClosedTypes.SecurityFavourHost') || $dispute_closed_type_id === config('constants.ConstDisputeClosedTypes.SecurityResponseFavourHost')) {
                $is_favour_booker = 0;
                $this->vehicleDisputeService->resolveByDepositAmountRefund($vehicle_rental, $dispute_closed_type_id);
            }
            // Closing Dispute //
            $this->vehicleDisputeService->closeDispute($request, $vehicle_rental, $is_favour_booker);
            return response()->json(['Success' => 'VehicleDispute has been Closed'], 200);
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleDispute could not be resolved. Please, try again.', $validator->errors());
        }
    }
}
