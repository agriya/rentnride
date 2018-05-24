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
use App\WalletTransactionLog;
use JWTAuth;
use Validator;
use App\Transformers\WalletTransactionLogTransformer;

/**
 * Money Transfer Accounts resource representation.
 * @Resource("Wallets")
 */
class AdminWalletTransactionLogsController extends Controller
{
    /**
     * WalletsController constructor.
     */
    public function __construct()
    {
        // Check the logged user authentication.
        $this->middleware('jwt.auth');
        // Check the logged user role.
        $this->middleware('role');
    }

    /**
     * Show all wallet transaction logs.
     * Get a JSON representation of all the wallet transaction logs.
     *
     * @Get("/wallet_transaction_logs?sort={sort}&sortby={sortby}&page={page}")
     * @Parameters({
     *      @Parameter("sort", type="string", required=false, description="Sort the wallet transaction log list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort wallet transaction log by Ascending / Descending Order.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1),
     * })
     */

    public function index(Request $request)
    {
        $wallet_transaction_logs = WalletTransactionLog::filterByRequest($request)->paginate(config('constants.ConstPageLimit'));
        return $this->response->paginator($wallet_transaction_logs, (new WalletTransactionLogTransformer));
    }

    /**
     * Show the wallet transaction log.
     * Show the wallet transaction log with a `id`.
     * @Get("/wallet_transaction_logs/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"id": 1, "created_at": "2016-05-19 00:00:00", "amount": 500, "wallet_transaction_logable_type": "Wallet", "wallet_transaction_logable_id": 1, "status": "Capture", "payment_type": "Success"}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function show($id)
    {
        $wallet_transaction_log = WalletTransactionLog::find($id);
        if (!$wallet_transaction_log) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($wallet_transaction_log, (new WalletTransactionLogTransformer));
    }
}