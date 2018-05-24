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
 

namespace Plugins\VehicleFeedbacks\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use JWTAuth;
use Validator;
use Plugins\VehicleFeedbacks\Model\VehicleFeedback;
use Plugins\VehicleFeedbacks\Transformers\VehicleFeedbackTransformer;
use Plugins\VehicleFeedbacks\Services\VehicleFeedbackService;
use DB;

/**
 * AdminVehicleFeedbacksController resource representation.
 * @Resource("Admin/AdminVehicleFeedbacksController")
 */
class AdminVehicleFeedbacksController extends Controller
{
    /**
     * @var vehicleService
     */
    protected $vehicleFeedbackService;

    /**
     * AdminVehicleFeedbacksController constructor.
     */

    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
        // Check the logged user role.
        $this->middleware('role');
        $this->setVehicleFeedbackService();
    }

    public function setVehicleFeedbackService()
    {
        $this->vehicleFeedbackService = new VehicleFeedbackService();
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
        $enabledIncludes = array('user', 'ip', 'to_user', 'vehicle_rental');
        $vehicle_feedbacks = VehicleFeedback::with($enabledIncludes)
            ->select(DB::raw('feedbacks.*'))
            ->leftJoin(DB::raw('(select id,username from users) as user'), 'user.id', '=', 'feedbacks.user_id')
            ->leftJoin(DB::raw('(select id,username from users) as to_user'), 'to_user.id', '=', 'feedbacks.to_user_id')
            ->leftJoin(DB::raw('(select id,name from vehicles) as feedbackable'), 'feedbackable.id', '=', 'feedbacks.feedbackable_id')
            ->leftJoin(DB::raw('(select id,ip from ips) as ip'), 'ip.id', '=', 'feedbacks.ip_id')
            ->filterByRequest($request)->paginate(config('constants.ConstPageLimit'));
        $enabledIncludes = array_merge($enabledIncludes, array('feedbackable'));
        return $this->response->paginator($vehicle_feedbacks, (new VehicleFeedbackTransformer)->setDefaultIncludes($enabledIncludes));
    }

    /**
     * Edit the specified vehicle feedbacks.
     * Edit the vehicle feedbacks with a `id`.
     * @Get("vehicle_feedbacks/{id}/edit")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body= {"id": 1, "user_id": 1, "to_user_id":1, "feedbackable_id": 1, "feedbackable_type"=>"MorphVehicleRental", "item_id": 1, "feedback": "item feedback", "ip_id": 2, "rating": "1", "User": {}, "Item": {}, "item_user": {}}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function edit($id)
    {
        $enabledIncludes = array('user', 'to_user');
        $vehicle_feedback = VehicleFeedback::with($enabledIncludes)->find($id);
        if (!$vehicle_feedback) {
            return $this->response->errorNotFound("Invalid Request");
        }
        $enabledIncludes = array_merge($enabledIncludes, array('feedbackable'));
        return $this->response->item($vehicle_feedback, (new VehicleFeedbackTransformer)->setDefaultIncludes($enabledIncludes));
    }

    /**
     * Update the specified vehicle feedbacks.
     * Update the vehicle feedbacks with a `id`.
     * @Put("vehicle_feedbacks/{id}")
     * @Transaction({
     *      @Request({"feedback": "item feedback","rating": "1"}),
     *      @Response(200, body={"success": "Feedback has been updated."}),
     *      @Response(422, body={"message": "Feedback could not be updated. Please, try again.", "errors": {"name": {}}, "status_code": 422})
     * })
     */
    public function update(Request $request, $id)
    {
        if ($request->has('id') && $request->id == $id) {
            $vehicle_rental = null;
            if ($request->has('dispute_closed_type_id') && $request->has('item_user_id')) {
                $vehicle_rental = \Plugins\VehicleRentals\Model\VehicleRental::find($request->item_user_id);
            }
            $this->vehicleFeedbackService->feedBackUpdate($request, $id, $vehicle_rental);
            return response()->json(['Success' => 'Feedback has been updated'], 200);
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Feedback could not be updated. Please, try again.');
        }
    }

    /**
     * Delete the specified vehicle feedbacks.
     * Delete the vehicle feedbacks with a `id`.
     * @Delete("vehicle_feedbacks/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"success": "Feedback Deleted."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function destroy($id)
    {
        $vehicle_feedback = VehicleFeedback::find($id);
        if (!$vehicle_feedback) {
            return $this->response->errorNotFound("Invalid Request");
        } else {
            $vehicle_feedback->delete();
        }
        return response()->json(['Success' => 'Feedback deleted'], 200);
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
        $vehicle_feedback = VehicleFeedback::with($enabledIncludes)->find($id);
        if (!$vehicle_feedback) {
            return $this->response->errorNotFound("Invalid Request");
        }
        $enabledIncludes = array_merge($enabledIncludes, array('feedbackable'));
        return $this->response->item($vehicle_feedback, (new VehicleFeedbackTransformer)->setDefaultIncludes($enabledIncludes));
    }
}
