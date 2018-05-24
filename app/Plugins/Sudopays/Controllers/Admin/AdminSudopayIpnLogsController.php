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
 
namespace Plugins\Sudopays\Controllers\Admin;

use Illuminate\Http\Request;



use App\Http\Controllers\Controller;
use Plugins\Sudopays\Model\SudopayIpnLog;
use JWTAuth;
use Validator;
use Plugins\Sudopays\Transformers\SudopayIpnLogTransformer;

/**
 * Money Transfer Accounts resource representation.
 * @Resource("Sudopays")
 */
class AdminSudopayIpnLogsController extends Controller
{
    /**
     * SudopaysController constructor.
     */
    public function __construct()
    {
        // Check the logged user authentication.
        $this->middleware('jwt.auth');
        // Check the logged user role.
        $this->middleware('role');
    }

    /**
     * Show all sudopay transaction logs.
     * Get a JSON representation of all the sudopay transaction logs.
     *
     * @Get("/sudopay_transaction_logs?sort={sort}&sortby={sortby}&page={page}")
     * @Parameters({
     *      @Parameter("sort", type="string", required=false, description="Sort the sudopay transaction log list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort sudopay transaction log by Ascending / Descending Order.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1),
     * })
     */

    public function index(Request $request)
    {
        $sudopay_ipn_logs = SudopayIpnLog::paginate(config('constants.ConstPageLimit'));
        return $this->response->paginator($sudopay_ipn_logs, (new SudopayIpnLogTransformer));

    }

    /**
     * Show the sudopay transaction log.
     * Show the sudopay transaction log with a `id`.
     * @Get("/sudopay_transaction_logs/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"id": 1, "amount": 100, "payment_id": 1, "sudopay_transaction_logable_id": 1, "sudopay_transaction_logable_type": "1", "sudopay_pay_key": "1", "merchant_id": 1, "gateway_id": 1, "gateway_name": "sri", "status": "active", "payment_type": "1", "buyer_id": 1, "buyer_email": "1", "buyer_address": "1"}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function show($id)
    {
        $sudopay_ipn_log = SudopayIpnLog::find($id);
        if (!$sudopay_ipn_log) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($sudopay_ipn_log, (new SudopayIpnLogTransformer));
    }
}