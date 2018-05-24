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
 
namespace Plugins\Withdrawals\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Plugins\Withdrawals\Model\UserCashWithdrawal;
use JWTAuth;
use Validator;
use Plugins\Withdrawals\Transformers\UserCashWithdrawalTransformer;
use Plugins\Withdrawals\Services\MoneyTransferAccountService;
use App\Services\TransactionService;

/**
 * UserCashWithdrawals resource representation.
 * @Resource("UserCashWithdrawals")
 */
class UserCashWithdrawalsController extends Controller
{
    /**
     * @var
     */
    protected $transactionService;
    /**
     * @var MoneyTransferAccountService
     */
    protected $MoneyTransferAccountService;

    /**
     * UserCashWithdrawalsController constructor.
     */
    public function __construct()
    {
        // Check the logged user authentication.
        $this->middleware('jwt.auth');
        $this->setMoneyTransferAccountService();
        $this->setTransactionService();
    }

    public function setMoneyTransferAccountService()
    {
        $this->MoneyTransferAccountService = new MoneyTransferAccountService();
    }

    public function setTransactionService()
    {
        $this->transactionService = new TransactionService();
    }

    /**
     * Show all user cash withdrawals.
     * Get a JSON representation of all the user cash withdrawals.
     *
     * @Get("/user_cash_withdrawals?filter={filter}&sort={sort}&sortby={sortby}&q={q}")
     * @Parameters({
     *      @Parameter("filter", type="integer", required=false, description="Filter the user_cash_withdrawals list by status.", default=null),
     *      @Parameter("sort", type="string", required=false, description="Sort the user_cash_withdrawals list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort user_cash_withdrawals by Ascending / Descending Order.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1)
     * })
     */
    public function index(Request $request)
    {
        $user_cash_withdrawals = UserCashWithdrawal::with('user', 'money_transfer_account', 'withdrawal_status')->filterByRequest($request)->paginate(config('constants.ConstPageLimit'));
        return $this->response->paginator($user_cash_withdrawals, (new UserCashWithdrawalTransformer)->setDefaultIncludes(['user', 'money_transfer_account', 'withdrawal_status']));
    }

    /**
     * Store a new user cash withdrawal.
     * Store a new user cash withdrawal with a 'amount', 'user_id', 'money_transfer_account_id'.
     * @Post("/user_cash_withdrawals")
     * @Transaction({
     *      @Request({"amount": 1000, "user_id": 1, "money_transfer_account_id": 1}),
     *      @Response(200, body={"success": "Record has been added."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {"amount": {}}, "status_code": 422})
     * })
     */
    public function store(Request $request)
    {
        $user_cash_withdrawal_data = $request->only('amount', 'user_id', 'money_transfer_account_id');
        $user = $this->auth->user();
        if ($user) {
            $user_cash_withdrawal_data['user_id'] = $user->id;
        }
        $user_cash_withdrawal_data['withdrawal_status_id'] = 1;
        $validator = Validator::make($user_cash_withdrawal_data, UserCashWithdrawal::GetValidationRule(), UserCashWithdrawal::GetValidationMessage());
        if ($validator->passes()) {
            if ($user_cash_withdrawal_data['amount'] <= $user->available_wallet_amount) {
                $user_cash_withdrawal = UserCashWithdrawal::create($user_cash_withdrawal_data);
                if ($user_cash_withdrawal) {
                    //transaction logs
                    $this->transactionService->log(config('constants.ConstUserTypes.Admin'), $user->id, config('constants.ConstTransactionTypes.CashWithdrawalRequest'), $user_cash_withdrawal_data['amount'], $user_cash_withdrawal->id, 'Withdrawals', config('constants.ConstPaymentGateways.Wallet'));
                    //get amount from wallet and stored to blocked amount
                    $user->available_wallet_amount = $user->available_wallet_amount - $user_cash_withdrawal_data['amount'];
                    $user->blocked_amount = $user->blocked_amount + $user_cash_withdrawal_data['amount'];
                    $user->save();
                    return response()->json(['Success' => 'User cash withdrawal request has been added'], 200);
                } else {
                    throw new \Dingo\Api\Exception\StoreResourceFailedException('User Cash Withdrawal could not be updated. Please, try again.');
                }
            } else {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('You Dont have sufficient amount in your wallet.');
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('User Cash Withdrawal could not be updated. Please, try again.', $validator->errors());
        }
    }
}
