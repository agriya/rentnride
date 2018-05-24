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
 
namespace Plugins\Withdrawals\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Plugins\Withdrawals\Model\WithdrawalStatus;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Plugins\Withdrawals\Transformers\WithdrawalStatusTransformer;

/**
 * WithdrawalStatuses resource representation.
 * @Resource("Admin/WithdrawalStatuses")
 */
class AdminWithdrawalStatusesController extends Controller
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
     * Show all withdrawal_statuses
     * Get a JSON representation of all the withdrawal_statuses.
     *
     * @Get("/withdrawal_statuses?filter={filter}&sort={sort}&sortby={sortby}&q={q}")
     * @Parameters({
     *      @Parameter("filter", type="integer", required=false, description="Filter the withdrawal_statuses list by status.", default=null),
     *      @Parameter("sort", type="string", required=false, description="Sort the withdrawal_statuses list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort withdrawal_statuses by Ascending / Descending Order.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1)
     * })
     */
    public function index(Request $request)
    {
        $withdrawal_statuses = WithdrawalStatus::filterByRequest($request)->paginate(config('constants.ConstPageLimit'));
        return $this->response->paginator($withdrawal_statuses, (new WithdrawalStatusTransformer));
    }
}
