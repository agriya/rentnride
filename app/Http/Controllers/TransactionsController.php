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

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use Validator;
use App\Transformers\TransactionTransformer;
use App\Transaction;
use App\Services\TransactionService;

/**
 * Transactions resource representation.
 * @Resource("Transactions")
 */
class TransactionsController extends Controller
{
    /**
     * TransactionsController constructor.
     */
    public function __construct()
    {
        // Check the logged user authentication.
        $this->middleware('jwt.auth');
        $this->setTransactionService();
    }
    public function setTransactionService()
    {
        $this->transactionService = new TransactionService();
    }

    /**
     * Show all transaction types.
     * Get a JSON representation of all the transaction types.
     *
     * @Get("/transactions?filter={filter}&sort={sort}&sortby={sortby}&page={q}&page={q}")
     * @Parameters({
     *      @Parameter("sort", type="string", required=false, description="Sort the transactions list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort transactions by Ascending / Descending Order.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1),
     *      @Parameter("filter", type="integer", required=false, description="Filter transactions list by status.", default=null),
     *      @Parameter("q", type="string", required=false, description="Search transactions.", default=null)
     * })
     */
    public function index(Request $request)
    {
        $user = $this->auth->user();
        $transactions = Transaction::with('from_user','to_user', 'transaction_type')->where('user_id', $user->id)->orWhere('receiver_user_id', $user->id)->filterByRequest($request)->paginate(config('constants.ConstPageLimit'));
        $converted_transactions = $this->transactionService->transactionDescription($transactions);
        $transaction_details = $this->response->paginator($converted_transactions, (new TransactionTransformer)->setDefaultIncludes(['from_user','to_user', 'transaction_type']));
        return $transaction_details;
    }
}
