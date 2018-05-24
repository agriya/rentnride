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
use Plugins\Withdrawals\Model\MoneyTransferAccount;
use Plugins\Withdrawals\Transformers\MoneyTransferAccountTransformer;
use JWTAuth;
use Validator;

/**
 * Money Transfer Accounts resource representation.
 * @Resource("MoneyTransferAccounts")
 */
class MoneyTransferAccountsController extends Controller
{
    /**
     * MoneyTransferAccountsController constructor.
     */
    public function __construct()
    {
        // Check the logged user authentication.
        $this->middleware('jwt.auth');
    }

    /**
     * Show all money transfer accounts
     * Get a JSON representation of all the money transfer account.
     *
     * @Get("/money_transfer_accounts?page={page}")
     * @Parameters({
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1)
     * })
     */
    public function index()
    {
        $user = $this->auth->user();
        $money_transfer_accounts = MoneyTransferAccount::with('user')->where('user_id', '=', $user->id)->get();
        return $this->response->collection($money_transfer_accounts, (new MoneyTransferAccountTransformer)->setDefaultIncludes(['user']));
    }

    /**
     * Store a new money transfer account.
     * Store a new money transfer account with a a `user_id` and `account`.
     * @Post("/money_transfer_accounts")
     * @Transaction({
     *      @Request({"user_id": 1, "account": "XXXXXX"}),
     *      @Response(200, body={"success": "Record has been added."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {"account": {}}, "status_code": 422})
     * })
     */
    public function store(Request $request)
    {
        $money_transfer_account_data = $request->only('account');
        $user = $this->auth->user();
        if ($user) {
            $money_transfer_account_data['user_id'] = $user->id;
        }
        $validator = Validator::make($money_transfer_account_data, MoneyTransferAccount::GetValidationRule(), MoneyTransferAccount::GetValidationMessage());
        if ($validator->passes()) {
            $money_transfer_account = MoneyTransferAccount::create($money_transfer_account_data);
            if ($money_transfer_account) {
                return response()->json(['Success' => 'Money Transfer Account has been added'], 200);
            } else {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('Money Transfer Account could not be updated. Please, try again.');
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Money Transfer Account could not be updated. Please, try again.', $validator->errors());
        }
    }

    /**
     * Delete the specified money transfer account.
     * Delete the money transfer account with a `id`.
     * @Delete("/money_transfer_accounts/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"success": "Record Deleted."}),
     *      @Response(500, body={"message": "Invalid Request", "status_code": 500})
     * })
     */
    public function destroy($id)
    {
        $money_transfer_account = MoneyTransferAccount::find($id);
        $user = $this->auth->user();
        if ($money_transfer_account && $money_transfer_account->user_id == $user->id) {
            $money_transfer_account_verify = MoneyTransferAccount::with('user_cash_withdrawal')->where("id", $id)->whereHas('user_cash_withdrawal', function ($query) {
                $query->whereIn('withdrawal_status_id', [config('constants.ConstWithdrawalStatus.Pending'), config('constants.ConstWithdrawalStatus.Approved')]);
            })->first();
            if (!$money_transfer_account_verify) {
                $money_transfer_account->delete();
            } else {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('One of your withdrawal request is in pending status,So you can\'t delete this now.');
            }
        } else {
            return $this->response->errorNotFound("Invalid Request");

        }
        return response()->json(['Success' => 'MoneyTransferAccount deleted'], 200);
    }
}