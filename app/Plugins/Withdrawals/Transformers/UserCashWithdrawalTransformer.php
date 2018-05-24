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
 
namespace Plugins\Withdrawals\Transformers;

use League\Fractal;
use Plugins\Withdrawals\Model\UserCashWithdrawal;
use App\Transformers\UserTransformer;
use Carbon\Carbon;

/**
 * Class UserCashWithdrawalsTransformer
 * @package App\Transformers
 */
class UserCashWithdrawalTransformer extends Fractal\TransformerAbstract
{
    /**
     * List of resources possible to include
     * @var array
     */
    protected $availableIncludes = [
        'User', 'WithdrawalStatus', 'MoneyTransferAccount'
    ];

    /**
     * @param UserCashWithdrawal $user_cash_withdrawal
     * @return array
     */
    public function transform(UserCashWithdrawal $user_cash_withdrawal)
    {
        $output = array_only($user_cash_withdrawal->toArray(), ['id', 'created_at', 'amount', 'withdrawal_status_id', 'user_id', 'money_transfer_account_id']);
        $output['created_at'] = $user_cash_withdrawal->created_at->toDateTimeString();
        return $output;
    }


    /**
     * @param UserCashWithdrawal $user_cash_withdrawal
     * @return Fractal\Resource\Item
     */
    public function includeUser(UserCashWithdrawal $user_cash_withdrawal)
    {
        if ($user_cash_withdrawal->user) {
            return $this->item($user_cash_withdrawal->user, new UserTransformer());
        } else {
            return null;
        }

    }


    /**
     * @param UserCashWithdrawal $user_cash_withdrawal
     * @return Fractal\Resource\Item
     */
    public function includeWithdrawalStatus(UserCashWithdrawal $user_cash_withdrawal)
    {
        if ($user_cash_withdrawal->withdrawal_status) {
            return $this->item($user_cash_withdrawal->withdrawal_status, new WithdrawalStatusTransformer());
        } else {
            return null;
        }

    }

    /**
     * @param UserCashWithdrawal $user_cash_withdrawal
     * @return Fractal\Resource\Item
     */
    public function includeMoneyTransferAccount(UserCashWithdrawal $user_cash_withdrawal)
    {
        if ($user_cash_withdrawal->money_transfer_account) {
            return $this->item($user_cash_withdrawal->money_transfer_account, new MoneyTransferAccountTransformer());
        } else {
            return null;
        }

    }

}
