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
 
namespace Plugins\Sudopays\Services;

use Cache;
use Plugins\Sudopays\Services\SudopayAPIService;
use Plugins\Sudopays\Model\Sudopay;
use Plugins\Sudopays\Model\SudopayTransactionLog;
use Plugins\Sudopays\Model\SudopayPaymentGateway;
use Plugins\Sudopays\Services\SudopayTransactionLogService;
use App\User;

class SudopayService
{

    /**
     * @var
     */
    protected $sudopayAPIService;

    /**
     * @var
     */
    protected $sudopayTransactionLogService;

    /**
     * SudopayService constructor.
     */
    public function __construct()
    {
        $this->setSudopayAPIService();
        $this->setSudopayTransactionLogService();
    }

    public function setSudopayAPIService()
    {
        $this->sudopayAPIService = new SudopayAPIService();
    }

    public function setSudopayTransactionLogService()
    {
        $this->sudopayTransactionLogService = new SudopayTransactionLogService();
    }

    public function createPayment($logable_id, $data = array())
    {
        $return['error'] = 0;
        $sudopay_transaction_log = SudopayTransactionLog::where('id', '=', $logable_id)->first();
        $details = $sudopay_transaction_log->sudopay_transaction_logable;
        $post_data = array();
        $return['fees_payer'] = 'buyer';
        if ($sudopay_transaction_log->sudopay_transaction_logable_type == 'MorphWallet') {
            $post_data['item_name'] = 'Add to Wallet';
            $post_data['item_description'] = 'Add amount to wallet';
            $post_data['callAction'] = 'callCapture';
            $post_data['amount'] = $details['amount'];
        }
        if ($sudopay_transaction_log->sudopay_transaction_logable_type == 'MorphVehicle') {
            $post_data['item_name'] = 'Vehicle Listing fee';
            $post_data['item_description'] = 'Vehicle Listing fee';
            $post_data['callAction'] = 'callCapture';
            $post_data['amount'] = config('vehicle.listing_fee');
        }
        if ($sudopay_transaction_log->sudopay_transaction_logable_type == 'MorphVehicleRental') {
            $post_data['item_name'] = 'RentItem';
            $post_data['item_description'] = 'VehicleRental an item';
            if (config('vehicle_rental.is_auto_approve')) {
                $post_data['callAction'] = 'callCapture';
            } else {
                $post_data['callAction'] = 'callAuth';
            }
            $post_data['amount'] = $details['total_amount'];
        }
        $sudopay_payment_gateway = SudopayPaymentGateway::where('sudopay_gateway_id', '=', $sudopay_transaction_log->gateway_id)->first();
        $sudopayGatewayDetails = unserialize($sudopay_payment_gateway->sudopay_gateway_details);
        $gateway_response = $this->sudopayAPIService->callGateways(array('supported_actions' => 'auth,capture'));
        if (isset($sudopayGatewayDetails['_form_fields']['_extends_tpl'])) {
            foreach ($sudopayGatewayDetails['_form_fields']['_extends_tpl'] as $k => $value) {
                if (isset($gateway_response['_form_fields_tpls'][$value]['_fields'])) {
                    foreach ($gateway_response['_form_fields_tpls'][$value]['_fields'] as $key => $field) {
                        $form_field_arr[] = $key;
                    }
                }
            }
        }
        if ((isset($post_data['fees_payer']) && $post_data['fees_payer'] == 'buyer') && $details->sudopay_gateway_id != 1) {
            $post_data['buyer_fees_payer_confirmation_token'] = $sudopayGatewayDetails['buyer_fees_payer_confirmation_token'];
        }
        $post_data['buyer_ip'] = $_SERVER['REMOTE_ADDR'];
        $post_data['gateway_id'] = $sudopay_transaction_log->gateway_id;
        $post_data['buyer_email'] = $data['email'];
        $post_data['buyer_address'] = $data['address'];
        $post_data['buyer_city'] = $data['city'];
        $post_data['buyer_state'] = $data['state'];
        $post_data['buyer_country'] = $data['country'];
        $post_data['buyer_phone'] = $data['phone'];
        $post_data['buyer_zip_code'] = $data['zip_code'];
        $post_data['currency_code'] = Config('site.currency_code');
        if (!empty($data['credit_card_number'])) {
            $post_data['credit_card_number'] = $data['credit_card_number'];
        }
        if (!empty($data['credit_card_expire'])) {
            $post_data['credit_card_expire'] = $data['credit_card_expire'];
        }
        if (!empty($data['credit_card_name_on_card'])) {
            $post_data['credit_card_name_on_card'] = $data['credit_card_name_on_card'];
        }
        if (!empty($data['credit_card_code'])) {
            $post_data['credit_card_code'] = $data['credit_card_code'];
        }
        if (!empty($data['payment_note'])) {
            $post_data['payment_note'] = $data['payment_note'];
        }
        $post_data['notify_url'] = Cache::get('site_url_for_shell') . '/api/sudopay/process_payment/' . $logable_id;
        $post_data['cancel_url'] = Cache::get('site_url_for_shell') . '/api/sudopay/cancel_payment/' . $logable_id;
        $post_data['success_url'] = Cache::get('site_url_for_shell') . '/api/sudopay/success_payment/' . $logable_id;
        $sudo_data['status'] = 'init';
        $sudo_data['payment_type'] = $post_data['callAction'];
        $sudo_data['buyer_email'] = $data['email'];
        $sudo_data['buyer_address'] = $data['address'] . " " . $data['city'] . " " . $data['state'] . " " . $data['country'] . " " . $data['zip_code'] . " " . $data['phone'];
        $this->sudopayTransactionLogService->updateLogById($sudo_data, $sudopay_transaction_log->id);
        // sudopay process trigger
        $response = $this->sudopayAPIService->{$post_data['callAction']}($post_data);
        if ($response['error']['code'] <= 0) {
            if (!empty($response['status']) && $response['status'] == 'Pending') {
                $sudo_data['status'] = $response['status'];
                $sudo_data['payment_id'] = $response['id'];
                $this->sudopayTransactionLogService->updateLogById($sudo_data, $sudopay_transaction_log->id);
                $return['pending'] = 1;
            } elseif (!empty($response['status']) && ($response['status'] == 'Captured' || $response['status'] == 'Authorized')) {
                /*if ($response['status'] == 'Authorized') {
                    $data['id'] = $details->id;
                    $data['item_user_status_id'] = config('constants.ConstItemUserStatus.WaitingForAcceptance');
                    $details->update($data);
                }*/
                $return['success'] = 1;
            } elseif (!empty($response['confirmation'])) {
                /* $data['id'] = $details->id;
                 $data['sudopay_revised_amount'] = $response['revised_amount'];
                 $data['sudopay_token'] = $response['confirmation']['token'];
                 $details->update($data);*/
            } elseif (!empty($response['gateway_callback_url'])) {
                $return['gateway_callback_url'] = $response['gateway_callback_url'];
            }
        } else {
            $return['error'] = 1;
            $return['error_message'] = $response['error']['message'];
        }
        return $return;
    }

    /**
     * IPN reposnse update in transaction log and related table
     * @param       $logable_id
     * @param array $post_data
     * @return bool
     */
    public function executePayment($logable_id, $post_data = array())
    {
        $sudopay_transaction_log = SudopayTransactionLog::where('id', '=', $logable_id)->first();
        if ($sudopay_transaction_log && $post_data['error_code'] == '0') {
            $sudo_data = array();
            if (isset($post_data['status']))
                $sudo_data['status'] = $post_data['status'];
            if (isset($post_data['paykey']))
                $sudo_data['sudopay_pay_key'] = $post_data['paykey'];
            if (isset($post_data['id']))
                $sudo_data['payment_id'] = $post_data['id'];
            if (isset($post_data['action']))
                $sudo_data['payment_type'] = $post_data['action'];
            if (isset($post_data['gateway_name']))
                $sudo_data['gateway_name'] = $post_data['gateway_name'];
            if (isset($post_data['gateway_id']))
                $sudo_data['gateway_id'] = $post_data['gateway_id'];
            if (isset($post_data['merchant_id']))
                $sudo_data['merchant_id'] = $post_data['merchant_id'];
            if (isset($post_data['amount']))
                $sudo_data['sudopay_transaction_fee'] = $post_data['amount'] - $sudopay_transaction_log->amount;
            if (!empty($sudo_data)) {
                $this->sudopayTransactionLogService->updateLogById($sudo_data, $sudopay_transaction_log->id);
                if ($sudopay_transaction_log->sudopay_transaction_logable_type == 'MorphWallet') {
                    $walletService = new \App\Services\WalletService();
                    if ($post_data['status'] == 'Captured') {
                        $walletService->processAddToWallet($sudopay_transaction_log->sudopay_transaction_logable_id, config('constants.ConstPaymentGateways.SudoPay'), $sudo_data['sudopay_transaction_fee']);
                    }
                    if ($post_data['status'] == 'Refunded' && $sudopay_transaction_log->status != 'Refunded') {
                        $walletService->updateRefund($sudopay_transaction_log, config('constants.ConstPaymentGateways.SudoPay'), $post_data);
                    }
                }
                if ($sudopay_transaction_log->sudopay_transaction_logable_type == 'MorphVehicle') {
                    $vehicleService = new \Plugins\Vehicles\Services\VehicleService();
                    if ($post_data['status'] == 'Captured') {
                        $vehicleService->processVehicleLisitngFee($sudopay_transaction_log->sudopay_transaction_logable_id, config('constants.ConstPaymentGateways.SudoPay'), $sudo_data['sudopay_transaction_fee']);
                    }
                }
                if ($sudopay_transaction_log->sudopay_transaction_logable_type == 'MorphVehicleRental') {
                    $vehicleRentalService = new \Plugins\VehicleRentals\Services\VehicleRentalService();
                    if ($post_data['status'] == 'Canceled') {
                        $vehicleRentalService->updateVoid($sudopay_transaction_log->sudopay_transaction_logable_id, config('constants.ConstPaymentGateways.SudoPay'));
                    } else if ($post_data['status'] == 'Refunded') {
                        $vehicleRentalService->updateRefund($sudopay_transaction_log->sudopay_transaction_logable_id, config('constants.ConstPaymentGateways.SudoPay'));
                    } else if ($post_data['status'] == 'Captured' && $post_data['action'] != 'Auth-Capture') {
                        $vehicleRentalService->updateVehicleRental($sudopay_transaction_log->sudopay_transaction_logable_id, config('constants.ConstPaymentGateways.SudoPay'));
                    }else if ($post_data['status'] == 'Authorized' && $post_data['action'] == 'Auth') {
                        $vehicleRentalService->updateVehicleRental($sudopay_transaction_log->sudopay_transaction_logable_id, config('constants.ConstPaymentGateways.SudoPay'));
                    }
                }
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * @param $transaction_log
     * @return mixed
     */
    public function capturePayment($transaction_log)
    {
        try {
            $post_data = array();
            $post_data['gateway_id'] = $transaction_log->gateway_id;
            $post_data['payment_id'] = $transaction_log->payment_id;
            $post_data['paykey'] = $transaction_log->sudopay_pay_key;
            $post_data['callAction'] = 'callAuthCapture';
            $response = $this->sudopayAPIService->{$post_data['callAction']}($post_data);
            return $response;
        } catch (Exception $e) {
            return array('error' => 1, 'error_message' => $e->getMessage());
        }
    }

    /**
     * @param $authorization_id
     * @return array'
     */
    public function voidPayment($transaction_log)
    {
        try {
            $post_data = array();
            $post_data['gateway_id'] = $transaction_log->gateway_id;
            $post_data['payment_id'] = $transaction_log->payment_id;
            $post_data['paykey'] = $transaction_log->sudopay_pay_key;
            $post_data['callAction'] = 'callVoid';
            $response = $this->sudopayAPIService->{$post_data['callAction']}($post_data);
            return $response;
        } catch (Exception $e) {
            return array('error' => 1, 'error_message' => $e->getMessage());
        }
    }

    public function refundPayment($transaction_log)
    {
        try {
            $post_data = array();
            $post_data['gateway_id'] = $transaction_log->gateway_id;
            $post_data['payment_id'] = $transaction_log->payment_id;
            $post_data['paykey'] = $transaction_log->sudopay_pay_key;
            $post_data['callAction'] = 'callRefund';
            $response = $this->sudopayAPIService->{$post_data['callAction']}($post_data);
            return $response;
        } catch (Exception $e) {
            return array('error' => 1, 'error_message' => $e->getMessage());
        }
    }

    public function successPayment($logable_id) {
        $error_message = '';
        $transaction_log = SudopayTransactionLog::where('id', '=', $logable_id)->first();
        if($transaction_log) {
            try {
                if ($transaction_log->sudopay_transaction_logable_type == 'MorphWallet') {
                    $walletService = new \App\Services\WalletService();
                    $walletService->processAddToWallet($transaction_log->sudopay_transaction_logable_id, config('constants.ConstPaymentGateways.SudoPay'), $transaction_log->sudopay_transaction_fee);
                    $returnUrl = '/#/wallets/success';
                }
                if ($transaction_log->sudopay_transaction_logable_type == 'MorphVehicle') {
                    if (isPluginEnabled('Vehicles')) {
                        $vehicleService = new \Plugins\Vehicles\Services\VehicleService();
                        $vehicleService->processVehicleLisitngFee($transaction_log->sudopay_transaction_logable_id, config('constants.ConstPaymentGateways.SudoPay'), $transaction_log->sudopay_transaction_fee);
                        $returnUrl = '/#/vehicle/success';
                    }
                }
                if ($transaction_log->sudopay_transaction_logable_type == 'MorphVehicleRental') {
                    if (isPluginEnabled('VehicleRentals')) {
                        $vehicleRentalService = new \Plugins\VehicleRentals\Services\VehicleRentalService();
                        $vehicleRentalService->updateVehicleRental($transaction_log->sudopay_transaction_logable_id, config('constants.ConstPaymentGateways.SudoPay'));
                        $returnUrl = '/#/vehicle_rental/status/success';
                    }
                }
            } catch(Exception $e) {
                if ($transaction_log->sudopay_transaction_logable_type == "MorphWallet") {
                    $returnUrl = '/#/wallets/fail';
                } elseif ($transaction_log->sudopay_transaction_logable_type == "MorphVehicleRental") {
                    $returnUrl = '/#/vehicle_rental/status/fail';
                }elseif ($transaction_log->sudopay_transaction_logable_type == "MorphVehicle") {
                    $returnUrl = '/#/vehicle/fail';
                } else {
                    $returnUrl = '/#/';
                }
                $error_message = $e->getMessage();
            }
        }
        return array(
            'returnUrl' => $returnUrl,
            'message' => $error_message
        );

    }
	
	public function cancelPayment($logable_id) {
        $error_message = '';
        $transaction_log = SudopayTransactionLog::where('id', '=', $logable_id)->first();
        if($transaction_log) {
			if ($transaction_log->sudopay_transaction_logable_type == "MorphWallet") {
			   $returnUrl = '/#/wallets/fail';
			} elseif ($transaction_log->sudopay_transaction_logable_type == "MorphVehicleRental") {
				$returnUrl = '/#/vehicle_rental/status/fail';
			}elseif ($transaction_log->sudopay_transaction_logable_type == "MorphVehicle") {
				$returnUrl = '/#/vehicle/fail';
			}
		} else {
			$returnUrl = '/#/';
		}         
        return array(
            'returnUrl' => $returnUrl
        );

    }

}
