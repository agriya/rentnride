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

use Plugins\Withdrawals\Model\MoneyTransferAccount;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Plugins\Withdrawals\Model\UserCashWithdrawal;
use JWTAuth;
use Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Plugins\Withdrawals\Transformers\UserCashWithdrawalTransformer;
use App\User;
use App\Services\TransactionService;
use Plugins\Withdrawals\Services\UserCashWithdrawalService;
use DB;

/**
 * UserCashWithdrawals resource representation.
 * @Resource("Admin/AdminUserCashWithdrawals")
 */
class AdminUserCashWithdrawalsController extends Controller
{
    /**
     * @var
     */
    protected $withdrawalService;
    /**
     * @var TransactionService
     */
    protected $transactionService;

    /**
     * AdminUserCashWithdrawalsController constructor.
     */
    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
        // Check the logged user role.
        $this->middleware('role');
        $this->setTransactionService();
        $this->setUserCashWithdrawalService();
    }

    public function setTransactionService()
    {
        $this->transactionService = new TransactionService();
    }

    public function setUserCashWithdrawalService()
    {
        $this->withdrawalService = new UserCashWithdrawalService();
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
        $user_cash_withdrawals = UserCashWithdrawal::with('user', 'money_transfer_account', 'withdrawal_status')
                                    ->select(DB::raw('user_cash_withdrawals.*'))
                                    ->leftJoin(DB::raw('(select id,username from users) as user'), 'user.id', '=', 'user_cash_withdrawals.user_id')
                                    ->leftJoin(DB::raw('(select id,name from withdrawal_statuses) as withdrawal_status'), 'withdrawal_status.id', '=', 'user_cash_withdrawals.withdrawal_status_id')
                                    ->leftJoin(DB::raw('(select id,account from money_transfer_accounts) as money_transfer_account'), 'money_transfer_account.id', '=', 'user_cash_withdrawals.money_transfer_account_id')
                                    ->filterByRequest($request)->paginate(config('constants.ConstPageLimit'));
        return $this->response->paginator($user_cash_withdrawals, (new UserCashWithdrawalTransformer)->setDefaultIncludes(['user', 'money_transfer_account', 'withdrawal_status']));
    }

    /**
     * Edit the specified user cash withdrawal.
     * Edit the user cash withdrawal with a `id`.
     * @Get("/user_cash_withdrawals/{id}/edit")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"id": 1, "user_id": 1, "withdrwa_status_id": 1, "amount": 100, "money_transfer_account_id": 1, "User": {}, "WithdrawalStatus": {}, "MoneyTransferAccount": {}}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function edit($id)
    {
        $user_cash_withdrawal = UserCashWithdrawal::with('user', 'money_transfer_account', 'withdrawal_status')->find($id);
        if (!$user_cash_withdrawal) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($user_cash_withdrawal, (new UserCashWithdrawalTransformer)->setDefaultIncludes(['user', 'money_transfer_account', 'withdrawal_status']));
    }

    /**
     * Update the specified user cash withdrawal.
     * Update the user cash withdrawal with a `id`.
     * @Put("/user_cash_withdrawals/{id}")
     * @Transaction({
     *      @Request({"id": 1, "withdrwa_status_id": 1, "amount": 100}),
     *      @Response(200, body={"success": "Record has been updated."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {}, "status_code": 422})
     * })
     */
    public function update(Request $request, $id)
    {
        $user_cash_withdrawal_data = $request->only('withdrawal_status_id');
        $validator = Validator::make($user_cash_withdrawal_data, UserCashWithdrawal::GetValidationRule(), UserCashWithdrawal::GetValidationMessage());
        $user_cash_withdrawal = false;
        if ($request->has('id')) {
            $user_cash_withdrawal = UserCashWithdrawal::find($id);
            $user_cash_withdrawal = ($request->id != $id) ? false : $user_cash_withdrawal;
        }
        if (($user_cash_withdrawal->withdrawal_status_id == config('constants.ConstWithdrawalStatus.Rejected')) || ($user_cash_withdrawal->withdrawal_status_id == config('constants.ConstWithdrawalStatus.Success'))) {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Status already changed.');
        }
        $check_money_transfer_account = MoneyTransferAccount::find($user_cash_withdrawal->money_transfer_account_id);
        if (!$check_money_transfer_account) {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('No Money Transfer Account is added or set as primary.');
        }
        if ($validator->passes() && $user_cash_withdrawal) {
            try {
                $user_cash_withdrawal->update($user_cash_withdrawal_data);
                if ($user_cash_withdrawal) {
                    $user = User::find($user_cash_withdrawal->user_id);
                    if ($user_cash_withdrawal->withdrawal_status_id == config('constants.ConstWithdrawalStatus.Rejected')) {
                        //transaction logs
                        $this->transactionService->log(config('constants.ConstUserTypes.Admin'), $user->id, config('constants.ConstTransactionTypes.CashWithdrawalRequestRejected'), $user_cash_withdrawal->amount, $user_cash_withdrawal->id, 'Withdrawals', config('constants.ConstPaymentGateways.Wallet'));
                        $user->available_wallet_amount = $user->available_wallet_amount + $user_cash_withdrawal->amount;
                        $user->blocked_amount = $user->blocked_amount - $user_cash_withdrawal->amount;
                        $user->save();
                    }
                    if ($user_cash_withdrawal->withdrawal_status_id == config('constants.ConstWithdrawalStatus.Success')) {
                        //transaction logs
                        $this->transactionService->log(config('constants.ConstUserTypes.Admin'), $user->id, config('constants.ConstTransactionTypes.CashWithdrawalRequestApproved'), $user_cash_withdrawal->amount, $user_cash_withdrawal->id, 'Withdrawals', config('constants.ConstPaymentGateways.Wallet'));
                        $user->blocked_amount = $user->blocked_amount - $user_cash_withdrawal->amount;
                        $user->save();
                    }
                    $this->withdrawalService->withdrawMail($user->id, $user->username, $user->email, $user_cash_withdrawal);
                    return response()->json(['Success' => 'UserCashWithdrawal has been updated'], 200);
                }
            } catch (\Exception $e) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('UserCashWithdrawal could not be updated. Please, try again.');
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('UserCashWithdrawal could not be updated. Please, try again.', $validator->errors());
        }
    }

    /**
     * Delete the specified user cash withdrawal.
     * Delete the user cash withdrawal with a `id`.
     * @Delete("/user_cash_withdrawals/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"success": "Record Deleted."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function destroy($id)
    {
        $user_cash_withdrawal = UserCashWithdrawal::find($id);
        if (!$user_cash_withdrawal) {
            return $this->response->errorNotFound("Invalid Request");
        } else {
            $user_cash_withdrawal->delete();
        }
        return response()->json(['Success' => 'UserCashWithdrawal deleted'], 200);
    }
}
