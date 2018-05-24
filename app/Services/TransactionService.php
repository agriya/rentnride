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
 
namespace App\Services;

use App\Transaction;
use Illuminate\Support\Facades\Auth;
use Carbon;
use App\TransactionType;

class TransactionService
{

    /**
     * TransactionService constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param $booker_id
     * @param $host_id
     * @param $transaction_type_id
     * @param $amount
     * @param $foreign_id
     * @param $plugin_name
     * @param null $payment_gateway_id
     * @param float $gateway_fees
     * @param null $description
     */
    public function log($booker_id, $host_id, $transaction_type_id, $amount, $foreign_id, $plugin_name, $payment_gateway_id = null, $gateway_fees = 0.00, $description = null)
    {

        $transaction_arr['user_id'] = (!is_null($booker_id)) ? $booker_id : '';
        $transaction_arr['receiver_user_id'] = (!is_null($host_id)) ? $host_id : '';
        $transaction_type = TransactionType::find($transaction_type_id);
        if (!empty($transaction_type_id)) {
            $transaction_arr['transaction_type_id'] = $transaction_type_id;
        }
        if (!empty($amount)) {
            $transaction_arr['amount'] = $amount;
        }
        if (!empty($payment_gateway_id)) {
            $transaction_arr['payment_gateway_id'] = $payment_gateway_id;
        }
        if (!empty($gateway_fees)) {
            $transaction_arr['gateway_fees'] = $gateway_fees;
        }
        $transaction_arr['description'] = $transaction_type->name;
        $transaction = Transaction::create($transaction_arr);
        if ($plugin_name == 'Vehicles' && isPluginEnabled('Vehicles')) {
            $vehicle_transaction = \Plugins\Vehicles\Model\Vehicle::with(['transactions'])->where('id', '=', $foreign_id)->first();
            $vehicle_transaction->transactions()->save($transaction);
        }
        if ($plugin_name == 'VehicleRentals' && isPluginEnabled('VehicleRentals')) {
            $vehicle_rental_transaction = \Plugins\VehicleRentals\Model\VehicleRental::with(['transactions'])->where('id', '=', $foreign_id)->first();
            $vehicle_rental_transaction->transactions()->save($transaction);
        }
        if ($plugin_name == 'Wallets') {
            $wallet_transaction = \App\Wallet::with(['transactions'])->where('id', '=', $foreign_id)->first();
            $wallet_transaction->transactions()->save($transaction);
        }
        if ($plugin_name == 'Withdrawals' && isPluginEnabled('Withdrawals')) {
            $Withdrawal_transaction = \Plugins\Withdrawals\Model\UserCashWithdrawal::with(['transactions'])->where('id', '=', $foreign_id)->first();
            $Withdrawal_transaction->transactions()->save($transaction);
        }
    }

    /**
     * @param $transactions
     * @return string
     */
    function transactionDescription($transactions)
    {
        if (!empty($transactions)) {
            try {
                foreach ($transactions as $transaction) {
                    if (in_array($transaction->transaction_type_id, array(config('constants.ConstTransactionTypes.AddedToWallet'), config('constants.ConstTransactionTypes.AdminDeductFundFromWallet')))) {
                        $user_link = (!is_null($transaction->from_user)) ? '<a href="' . url('/#/user/' . $transaction->from_user->username) . '">' . $transaction->from_user->username . '</a>' : '';
                    } else {
                        $user_link = (!is_null($transaction->to_user)) ? '<a href="' . url('/#/user/' . $transaction->to_user->username) . '">' . $transaction->to_user->username . '</a>' : '';
                    }
                    $item_link = $host_link = $booker_link = $order_link = '';
                    if ($transaction->transactionable_type == 'MorphVehicleRental' && isPluginEnabled('VehicleRentals')) {                      //
                        if (!is_null($transaction->transactionable)) {
                            if(!is_null($transaction->transactionable->item_userable)) {
                                $item_link = '<a href="#/vehicle/' . $transaction->transactionable->item_userable->id . '/' . $transaction->transactionable->item_userable->slug . '">' . $transaction->transactionable->item_userable->name . '</a>';
                                $host_link = (!is_null($transaction->transactionable) && !is_null($transaction->transactionable->item_userable->user)) ? '<a href="' . url('/#/user/' . $transaction->transactionable->item_userable->user->username) . '">' . $transaction->transactionable->item_userable->user->username . '</a>' : '';
                                $booker_link = (!is_null($transaction->transactionable) && !is_null($transaction->transactionable->user)) ? '<a href="' . url('/#/user/' . $transaction->transactionable->user->username) . '">' . $transaction->transactionable->user->username . '</a>' : '';
                                $order_link = (!is_null($transaction->transactionable)) ? '<a href="' . url('/#/activity/' . $transaction->transactionable->id . '/all') . '">' . $transaction->transactionable->id . '</a>' : '';
                            }
                        }
                    } elseif ($transaction->transactionable_type == 'MorphVehicle' && isPluginEnabled('Vehicles')) {
                        if (!is_null($transaction->transactionable)) {
                            $item_link = '<a href="#/vehicle/' . $transaction->transactionable->id . '/' . $transaction->transactionable->slug . '">' . $transaction->transactionable->name . '</a>';
                        }
                        if (!is_null($transaction->from_user)) {
                            $host_link = '<a href="' . url('/#/user/' . $transaction->from_user->username) . '">' . $transaction->from_user->username . '</a>';
                        }
                    }
                    $transactionReplace = array(
                        '##USER##' => $user_link,
                        '##BOOKER##' => $booker_link,
                        '##HOST##' => $host_link,
                        '##ITEM##' => $item_link,
                        '##ORDER_NO##' => $order_link
                    );
                    $user = Auth::user();
                    if (!empty($transaction->transaction_type->message_for_receiver) && $transaction->receiver_user_id == $user->id) {
                        $transaction->description = strtr($transaction->transaction_type->message_for_receiver, $transactionReplace);
                    } elseif ($user->id == config('constants.ConstUserTypes.Admin')) {
                        $transaction->description = strtr($transaction->transaction_type->message_for_admin, $transactionReplace);
                    } else {
                        $transaction->description = strtr($transaction->transaction_type->message, $transactionReplace);
                    }
                }
                return $transactions;
            } catch (Exception $e) {
                return array('error' => 1, 'error_message' => $e->getMessage());
            }
        }
    }

    /**
     * @param        $request
     * @param string $type
     * @return mixed
     */
    public function getTranactionCount($request, $type = 'filter')
    {
        $check_date = $this->getDateFilter($request);
        $check_date = Carbon::parse($check_date)->format('Y-m-d');
        $tranaction_count = Transaction::where('created_at', '>=', $check_date)->count();
        return $tranaction_count;
    }

    /**
     * get the date filter
     * @return $check_date
     */
    public function getDateFilter($request)
    {
        $check_date = Carbon::now()->subDays(7);
        if ($request->has('filter')) {
            if ($request->filter == 'lastDays') {
                $check_date = Carbon::now()->subDays(7);
            } else if ($request->filter == 'lastWeeks') {
                $check_date = Carbon::now()->subWeeks(4);
            } else if ($request->filter == 'lastMonths') {
                $check_date = Carbon::now()->subMonths(3);
            } else if ($request->filter == 'lastYears') {
                $check_date = Carbon::now()->subYears(3);
            }
        }
        return $check_date;
    }

    public function getAdminTotalRevenue()
    {
        $transaction_type_ids = array(config('constants.ConstTransactionTypes.AdminCommission'),config('constants.ConstTransactionTypes.VehicleListingFee'));
        $admin_revenue = Transaction::whereIn('transaction_type_id', $transaction_type_ids)->sum('amount');
        return $admin_revenue;
    }

}
