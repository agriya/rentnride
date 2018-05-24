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
 
namespace Plugins\VehicleRentals\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Plugins\VehicleRentals\Transformers\VehicleRentalStatusTransformer;
use Plugins\VehicleRentals\Model\VehicleRentalStatus;

/**
 * VehicleRentalStatuses resource representation.
 * @Resource("VehicleRentalStatuses")
 */
class VehicleRentalStatusesController extends Controller
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
    }

    /**
     * Show all withdrawal statuses.
     * Get a JSON representation of all the withdrawal statuses.
     *
     * @Get("/vehicle_rental_statuses?sort={sort}&sortby={sortby}")
     * @Parameters({
     *      @Parameter("sort", type="string", required=false, description="Sort the vehicle_rental status list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort vehicle_rental status by Ascending / Descending Order.", default=null)
     * })
     */
    public function index(Request $request)
    {
        $vehicle_rental_statuses = VehicleRentalStatus::filterByRequest($request)->get();
        if ($request->has('filter') && $request->filter == 'booker') {
            $item_status_array = array(
                'Confirmed',
                'Waiting for Review',
                'Completed',
                'Attended',
                'Waiting For Acceptance',
                'Cancelled',
                'Rejected',
                'Expired',
                'Payment Pending'
            );
        } else if ($request->has('filter') && $request->filter == 'host') {
            $item_status_array = array(
                'Confirmed',
                'Waiting for Review',
                'Completed',
                'Attended',
                'Waiting For Acceptance',
                'Cancelled',
                'Rejected',
                'Expired',
                'Host Reviewed',
                'Waiting For Payment Cleared'
            );
        }
        $status_array['item_user_statuses'] = array();
        foreach ($vehicle_rental_statuses as $key => $val) {
            if (in_array($val['name'], $item_status_array)) {
                if($val['name'] == 'Host Reviewed') {
                    $val['name'] = 'Waiting For Booker Review';
                }
                if(!isPluginEnabled('VehicleFeedbacks')) {
                    if($val['name'] == 'Waiting for Review') {
                        $val['name'] = 'Waiting For Next Update';
                    }
                }
                $status_array['item_user_statuses'][] = $val;
            }
        }
        return $status_array;
    }
}
