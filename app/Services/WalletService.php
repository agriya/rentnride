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

use App\Services\MailService;
use Illuminate\Database\Eloquent\Model;
use App\Wallet;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Services\TransactionService;
use App\Services\MessageService;
use App\WalletTransactionLog;
use App\Services\WalletTransactionLogService;
use Log;

class WalletService
{
    /**
     * @var
     */
    protected $mailService;
    /**
     * @var \App\Services\MessageService
     */
    protected $messageService;
    /**
     * @var
     */
    protected $transactionService;

    /**
     * WalletService constructor.
     */
    public function __construct()
    {
        $this->setMailService();
        $this->setMessageService();
        $this->setTransactionService();
    }

    /**
     * mail service object created
     */
    public function setMailService()
    {
        $this->mailService = new MailService();
    }

    /**
     * message service object created
     */
    public function setMessageService()
    {
        $this->messageService = new MessageService();
    }

    /**
     * TransactionService object created
     */
    public function setTransactionService()
    {
        $this->transactionService = new TransactionService();
    }

    /**
     * @param $walletId
     * @param $paymentGatewayId
     * @return bool
     */
    public function processAddToWallet($walletId, $paymentGatewayId, $transaction_fee)
    {
        $wallet = Wallet::with('user')->where('id', $walletId)->first();
        if (empty($wallet)) {
            return $this->response->errorNotFound("Invalid Request");
        }
        if (empty($wallet['is_success'])) {
            $this->transactionService->log($wallet->user->id, $wallet->user->id, config('constants.ConstTransactionTypes.AddedToWallet'), $wallet->amount, $wallet->id, 'Wallets', $wallet->payment_gateway_id, $transaction_fee, $wallet->description);
            $data['is_success'] = 1;
            $wallet->update($data);
            $user['id'] = $wallet->user->id;
            $user['available_wallet_amount'] = $wallet->user->available_wallet_amount + $wallet->amount;
            $wallet->user->update($user);
            //$this->sendAmountToWalletMail($wallet);
            return true;
        } else {
            return true;
        }
        return false;
    }

    public function getWalletDetails()
    {
        $wallet_response = array(
            'error' => array(
                'code' => 0
            ),
            'wallet_enabled' => true
        );
        return $wallet_response;
    }

    public function createPayment($log_id)
    {
        $transaction_log = WalletTransactionLog::where('id', '=', $log_id)->first();
        $related_details = $transaction_log->wallet_transaction_logable;
        $user = $related_details->user;
        if ((($user->available_wallet_amount >= $related_details->total_amount) && ($transaction_log->wallet_transaction_logable_type == 'MorphVehicleRental')) || ( ($user->available_wallet_amount >= config('vehicle.listing_fee') )&& ( $transaction_log->wallet_transaction_logable_type == 'MorphVehicle')) ) {
            $transaction_log->status = 'created';
            $transaction_log->payment_type = 'initiated';
            $transaction_log->save();
            return true;
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Your Wallet has insufficient money. Please, try again.');
        }
    }

    public function executePayment($related_model, $type, $log_id)
    {
        $process = false;
        if($type == 'MorphVehicle'){
            $user = $related_model->user;
            $user->available_wallet_amount = $user->available_wallet_amount - config('vehicle.listing_fee');
            if ($user->save()) {
                $transaction_log = WalletTransactionLog::where('id', $log_id)->first();
                $transaction_log->payment_type = 'Captured';
                $transaction_log->status = 'approved';
                $transaction_log->save();
                $vehicleService = new \Plugins\Vehicles\Services\VehicleService();
                $vehicleService->processVehicleLisitngFee($related_model->id, config('constants.ConstPaymentGateways.Wallet'), 0.00);
                $process = true;
            }
        }
        if($type == 'MorphVehicleRental'){
            $user = $related_model->user;
            if($user->available_wallet_amount >= $related_model->total_amount) {
                $user->available_wallet_amount = $user->available_wallet_amount - $related_model->total_amount;
                if ($user->save()) {
                    $transaction_log = WalletTransactionLog::where('id', $log_id)->first();
                    $transaction_log->payment_type = 'Captured';
                    $transaction_log->status = 'approved';
                    $transaction_log->save();
                    $vehicleRentalService = new \Plugins\VehicleRentals\Services\VehicleRentalService();
                    $vehicleRentalService->updateVehicleRental($related_model->id, config('constants.ConstPaymentGateways.Wallet'));
                    $process = true;
                }
            }
        }
        return array(
            'status' => $process,
        );
    }

    public function voidPayment($vehicle_rental)
    {
        $process_status = false;
        $transaction_log = array();
        if ($vehicle_rental && !is_null($vehicle_rental->wallet_transaction_log)) {
            if ($vehicle_rental->wallet_transaction_log->payment_type == 'Captured') {
                $transaction_log['payment_type'] = 'voided';
                $vehicle_rental->wallet_transaction_log->update($transaction_log);
                $vehicle_rental->user->increment('available_wallet_amount', $vehicle_rental->total_amount);
                $process_status = true;
            }
        }
        return $process_status;
    }

    /**
     * @param $user_id
     * @param $amount
     * @param $foreign_id
     * @param $morph_type
     */
    public function updateWalletForUser($user_id, $amount, $foreign_id, $morph_type)
    {
        // update user wallet amount
        User::where('id', '=', $user_id)->increment('available_wallet_amount', $amount);
        // update wallet transaction log
        $walletLogService = new \App\Services\WalletTransactionLogService();
        $transaction_log_data = array();
        $transaction_log_data['amount'] = $amount;
        $transaction_log_data['status'] = 'Completed';
        $transaction_log_data['payment_type'] = 'Paid';
        $wallet_log = $walletLogService->log($transaction_log_data);
        if ($morph_type == 'VehicleRentals') {
            $model_data = \Plugins\VehicleRentals\Model\VehicleRental::where('id', '=', $foreign_id)->first();
        }
        $model_data->wallet_transaction_log()->save($wallet_log);
    }

    public function updateRefund($sudopay_transaction_log, $gateway_id, $post_data)
    {
        $wallet = Wallet::with('user')->where('id', $sudopay_transaction_log->sudopay_transaction_logable_id)->first();
        if ($wallet) {
            $user['id'] = $wallet->user->id;
            $sudopay_revised_amount = $post_data['amount'] - $sudopay_transaction_log->sudopay_transaction_fee;
            $user['available_wallet_amount'] = $wallet->user->available_wallet_amount - $sudopay_revised_amount;
            $wallet->user->update($user);
            $this->transactionService->log(config('constants.ConstUserTypes.Admin'), $wallet->user_id, config('constants.ConstTransactionTypes.RefundForWallet'), $sudopay_revised_amount, $wallet->id, 'Wallets', $gateway_id);
            return true;
        }
    }
}
