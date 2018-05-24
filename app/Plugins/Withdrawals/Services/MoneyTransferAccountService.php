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
 
namespace Plugins\Withdrawals\Services;

use Plugins\Withdrawals\Model\MoneyTransferAccount;

class MoneyTransferAccountService
{
    /**
     * get the active users MoneyTransferAccount
     * @param $user_id
     * @return $money_transfer_account
     */
    public function getUserMoneyTransferAccount($user_id)
    {
        $money_transfer_account = MoneyTransferAccount::where('user_id', '=', $user_id)
            ->where('is_primary', '=', 1)
            ->first();
        return $money_transfer_account;
    }
}
