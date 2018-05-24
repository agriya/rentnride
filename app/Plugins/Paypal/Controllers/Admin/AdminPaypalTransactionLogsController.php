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
 
namespace Plugins\Paypal\Controllers\Admin;

use Illuminate\Http\Request;



use App\Http\Controllers\Controller;
use Plugins\Paypal\Model\PaypalTransactionLog;
use JWTAuth;
use Validator;
use Plugins\Paypal\Transformers\PaypalTransactionLogTransformer;

/**
 * Money Transfer Accounts resource representation.
 * @Resource("Sudopays")
 */
class AdminPaypalTransactionLogsController extends Controller
{
    /**
     * PaypalController constructor.
     */
    public function __construct()
    {
        // Check the logged user authentication.
        $this->middleware('jwt.auth');
        // Check the logged user role.
        $this->middleware('role');
    }

    /**
     * Show all paypal transaction logs.
     * Get a JSON representation of all the paypal transaction logs.
     *
     * @Get("/paypal_transaction_logs?sort={sort}&sortby={sortby}&page={page}")
     * @Parameters({
     *      @Parameter("sort", type="string", required=false, description="Sort the paypal transaction log list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort paypal transaction log by Ascending / Descending Order.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1),
     * })
     */

    public function index(Request $request)
    {
        $paypal_transaction_logs = PaypalTransactionLog::filterByRequest($request)->paginate(config('constants.ConstPageLimit'));
        return $this->response->paginator($paypal_transaction_logs, (new PaypalTransactionLogTransformer));

    }

    /**
     * Show the paypal transaction log.
     * Show the paypal transaction log with a `id`.
     * @Get("/paypal_transaction_logs/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"id": 1, "amount": 100, "payment_id": 1, "paypal_transaction_logable_id": 1, "paypal_transaction_logable_type": "1", "paypal_pay_key": "1", "payer_id": 1, "authorization_id": 1, "capture_id": "sri", "void_id": "1", "refund_id": "1", "status": "approved", "payment_type": "1", "buyer_id": 1, "buyer_email": "1", "buyer_address": "1", "paypal_transaction_fee": "0.00", "fee_payer": ""}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function show($id)
    {
        $paypal_transaction_log = PaypalTransactionLog::find($id);
        if (!$paypal_transaction_log) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($paypal_transaction_log, (new PaypalTransactionLogTransformer));
    }
}