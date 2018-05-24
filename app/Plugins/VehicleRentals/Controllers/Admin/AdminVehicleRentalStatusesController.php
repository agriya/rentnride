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
 
namespace Plugins\VehicleRentals\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Plugins\VehicleRentals\Transformers\VehicleRentalStatusTransformer;
use Plugins\VehicleRentals\Model\VehicleRentalStatus;

/**
 * VehicleRentalStatuses resource representation.
 * @Resource("Admin/VehicleRentalStatuses")
 */
class AdminVehicleRentalStatusesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Check the logged user authentication.
        $this->middleware('jwt.auth');
        // Check the logged user role.
        $this->middleware('role');
    }

    /**
     * Show all vehicle_rental status.
     * Get a JSON representation of all the vehicle_rental status.
     *
     * @Get("/vehicle_rental_statuses?filter={filter}&sort={sort}&sortby={sortby}&q={q}&page={page}")
     * @Parameters({
     *      @Parameter("filter", type="integer", required=false, description="Filter the vehicle_rental status list by status.", default=null),
     *      @Parameter("sort", type="string", required=false, description="Sort the vehicle_rental status list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort vehicle_rental status by Ascending / Descending Order.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1)
     *		@Parameter("q", type="string", required=false, description="Search vehicle_rental status.", default=null)
     * })
     */
    public function index(Request $request)
    {
        $vehicle_rental_statuses = VehicleRentalStatus::filterByRequest($request)->paginate(config('constants.ConstPageLimit'));
        return $this->response->paginator($vehicle_rental_statuses, (new VehicleRentalStatusTransformer));
    }
}
