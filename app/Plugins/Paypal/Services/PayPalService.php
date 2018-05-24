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
 
namespace Plugins\Paypal\Services;

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use Paypal;
use App\User;
use Plugins\Paypal\Model\PaypalTransactionLog;
use Log;

class PayPalService
{
    /**
     * @var
     */
    private $_api_context;

    /**
     * PayPalService constructor.
     */
    public function __construct()
    {
        // setup PayPal api context
        $paypal_conf = config('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['api_id'], $paypal_conf['secret']));
        $this->_api_context->setConfig($paypal_conf['settings']);
    }

    public function createPayment($logable_id, $payment_type)
    {
        $transaction_log = PaypalTransactionLog::where('id', '=', $logable_id)->first();
        $related_details = $transaction_log->paypal_transaction_logable;
        $desc = '';
        $cancel_url = '/';
        if ($transaction_log->paypal_transaction_logable_type == 'MorphWallet') {
            $desc = $related_details->description;
            $item1 = PayPal::Item();
            $item1->setName($desc)
                ->setCurrency(config('site.currency_code'))
                ->setQuantity(1)
                ->setPrice((double)$transaction_log->amount);
            $itemList = PayPal::ItemList();
            $itemList->setItems(array($item1));
            $cancel_url = '/#/wallets/fail';
        }
        if ($transaction_log->paypal_transaction_logable_type == 'MorphVehicle') {
            $desc = "Vehicle listing Fee";
            $item1 = PayPal::Item();
            $item1->setName($desc)
                ->setCurrency(config('site.currency_code'))
                ->setQuantity(1)
                ->setPrice((double)$transaction_log->amount);
            $itemList = PayPal::ItemList();
            $itemList->setItems(array($item1));
            $cancel_url = '/#/vehicle/fail';
        }
        if ($transaction_log->paypal_transaction_logable_type == 'MorphVehicleRental') {
            if (!is_null($transaction_log->paypal_transaction_logable) && !is_null($transaction_log->paypal_transaction_logable->item_userable)) {
                $item1 = PayPal::Item();
                $item1->setName($transaction_log->paypal_transaction_logable->item_userable->name)
                    ->setCurrency(config('site.currency_code'))
                    ->setQuantity((int)$transaction_log->paypal_transaction_logable->quantity)
                    ->setPrice((double)$transaction_log->amount);
                $itemList = PayPal::ItemList();
                $itemList->setItems(array($item1));
                $cancel_url = '/#/vehicle_rental/status/fail';
            } else {
                return array('error' => 1, 'error_message' => "unable to get item detail");
            }
        }

        $payer = PayPal::Payer();
        $payer->setPaymentMethod('paypal');

        $amount = PayPal::Amount();
        $amount->setCurrency(config('site.currency_code'));
        $amount->setTotal((double)$transaction_log->amount);

        $transaction = PayPal::Transaction();
        $transaction->setItemList($itemList);
        $transaction->setAmount($amount);
        $transaction->setDescription($desc);

        $redirectUrls = PayPal:: RedirectUrls();
        $redirectUrls->setReturnUrl(url('api/paypal/process_payment'));
        $redirectUrls->setCancelUrl(url($cancel_url));

        $payment = PayPal::Payment();
        $payment->setIntent($payment_type);
        $payment->setPayer($payer);
        $payment->setRedirectUrls($redirectUrls);
        $payment->setTransactions(array($transaction));
        try {
            $response = $payment->create($this->_api_context);
            if ($payment->getState() == 'created') {
                $data = array();
                $data['payment_id'] = $payment->getId();
                $data['status'] = $payment->getState();
                $data['payment_type'] = 'initiated';
                $transaction_log->update($data);
                return $payment->getApprovalLink();
            } else {
                return array('error' => 1, 'error_message' => "Payment could not be initialized, please try again");
            }
        } catch (Exception $ex) {
            return array('error' => 1, 'error_message' => $ex->getMessage());
        }
    }

    public function executePayment($payID, $payerID, $token)
    {
        $process = false;
        $error_message = '';
        $returnUrl = '';
        $transaction_log = PaypalTransactionLog::where('payment_id', '=', $payID)->first();
        if ($transaction_log) {
            try {
                $data = array();
                $payment = Paypal::getById($payID, $this->_api_context);
                $paymentExecution = Paypal::PaymentExecution();
                $paymentExecution->setPayerId($payerID);
                $payment->execute($paymentExecution, $this->_api_context);
                $data['payer_id'] = $payerID;
                $data['paypal_pay_key'] = $token;
                if ($payment->getIntent() == 'sale' && $payment->getState() == 'approved') {
                    $transactions = $payment->getTransactions();
                    $relatedResources = $transactions[0]->getRelatedResources();
                    $sale = $relatedResources[0]->getSale();
                    $transaction_fee = $sale->getTransactionFee();
                    $data['status'] = $payment->getState();
                    $data['capture_id'] = $sale->getId();
                    $data['payment_type'] = $sale->getState();
                    $process = true;
                    if ($transaction_log->paypal_transaction_logable_type == "MorphWallet") {
                        $transaction_log->paypal_transaction_fee = 0.00;
                        if ($transaction_log->fee_payer == 'User' && !is_null($transaction_fee)) {
                            $transaction_log->paypal_transaction_fee = $transaction_fee->getValue();
                            $transaction_log->save();
                        }
                        $walletService = new \App\Services\WalletService();
                        $walletService->processAddToWallet($transaction_log->paypal_transaction_logable_id, config('constants.ConstPaymentGateways.PayPal'), $transaction_log->paypal_transaction_fee);
                        $returnUrl = '/#/wallets/success';
                    }
                    if ($transaction_log->paypal_transaction_logable_type == "MorphVehicle") {
                        $transaction_log->paypal_transaction_fee = 0.00;
                        if (isPluginEnabled('Vehicles')) {
                            if ($transaction_log->fee_payer == 'User' && !is_null($transaction_fee)) {
                                $transaction_log->paypal_transaction_fee = $transaction_fee->getValue();
                                $transaction_log->save();
                            }
                            $vehicleService = new \Plugins\Vehicles\Services\VehicleService();
                            $vehicleService->processVehicleLisitngFee($transaction_log->paypal_transaction_logable_id, config('constants.ConstPaymentGateways.PayPal'), $transaction_log->paypal_transaction_fee);
                            $returnUrl = '/#/vehicle/success';
                        }
                    }
                    if ($transaction_log->paypal_transaction_logable_type == "MorphVehicleRental") {
                        if (isPluginEnabled('VehicleRentals')) {
                            $vehicleRentalService = new \Plugins\VehicleRentals\Services\VehicleRentalService();
                            $vehicleRentalService->updateVehicleRental($transaction_log->paypal_transaction_logable_id, config('constants.ConstPaymentGateways.PayPal'));
                            $returnUrl = '/#/vehicle_rental/status/success';
                        }
                    }
                } else if ($payment->getIntent() == 'authorize' && $payment->getState() == 'approved') {
                    $transactions = $payment->getTransactions();
                    $relatedResources = $transactions[0]->getRelatedResources();
                    $authorization = $relatedResources[0]->getAuthorization();
                    $data['status'] = $payment->getState();
                    $data['authorization_id'] = $authorization->getId();
                    $data['payment_type'] = $authorization->getState();
                    $process = true;
                    if ($transaction_log->paypal_transaction_logable_type == "MorphWallet") {
                        $returnUrl = '/#/wallets/success';
                    } elseif ($transaction_log->paypal_transaction_logable_type == "MorphVehicleRental") {
                        if (isPluginEnabled('VehicleRentals')) {
                            $vehicleRentalService = new \Plugins\VehicleRentals\Services\VehicleRentalService();
                            $vehicleRentalService->updateVehicleRental($transaction_log->paypal_transaction_logable_id, config('constants.ConstPaymentGateways.PayPal'));
                            $returnUrl = '/#/vehicle_rental/status/success';
                        }
                    } else {
                        $returnUrl = '/#/';
                    }
                }
                if (!empty($data))
                    $transaction_log->update($data);
            } catch (\PayPal\Exception\PayPalConnectionException $ex) {
                if ($transaction_log->paypal_transaction_logable_type == "MorphWallet") {
                    $returnUrl = '/#/wallets/fail';
                } elseif ($transaction_log->paypal_transaction_logable_type == "MorphVehicleRental") {
                    $returnUrl = '/#/vehicle_rental/status/fail';
                }elseif ($transaction_log->paypal_transaction_logable_type == "MorphVehicle") {
                    $returnUrl = '/#/vehicle/fail';
                } else {
                    $returnUrl = '/#/';
                }
                $error_message = $ex->getMessage();
            } catch (Exception $ex) {
                if ($transaction_log->paypal_transaction_logable_type == "MorphWallet") {
                    $returnUrl = '/#/wallets/fail';
                } elseif ($transaction_log->paypal_transaction_logable_type == "MorphVehicleRental") {
                    $returnUrl = '/#/vehicle_rental/status/fail';
                }elseif ($transaction_log->paypal_transaction_logable_type == "MorphVehicle") {
                    $returnUrl = '/#/vehicle/fail';
                } else {
                    $returnUrl = '/#/';
                }
                $error_message = $ex->getMessage();
            }
        }
        return array(
            'status' => $process,
            'returnUrl' => $returnUrl,
            'message' => $error_message
        );
    }

    public function getPaypalDetails()
    {
        try {
            $paypal_gateway_response = array(
                'error' => array(
                    'code' => 0
                ),
                'paypal_enabled' => true
            );
            return $paypal_gateway_response;
        } catch (Exception $e) {
            return array('error' => 1, 'error_message' => $e->getMessage());
        }
    }

    public function authorizePayment($id)
    {
        try {
            $authorization = Paypal::Authorization();
            $result = $authorization->get($id, $this->_api_context);
            return $result;
        } catch (Exception $e) {
            return array('error' => 1, 'error_message' => $e->getMessage());
        }
    }

    public function capturePayment($authorization, $transaction_log)
    {
        try {
            $amt = Paypal::Amount();
            $amt->setCurrency(config('site.currency_code'))
                ->setTotal((double)$transaction_log->amount);
            $capture = Paypal::Capture();
            $capture->setAmount($amt);
            $capture->setIsFinalCapture(true);
            $getCapture = $authorization->capture($capture, $this->_api_context);
            return $getCapture;
        } catch (Exception $e) {
            return array('error' => 1, 'error_message' => $e->getMessage());
        }
    }

    public function voidPayment($authorization_id)
    {
        try {
            $authorization = Paypal::Authorization();
            $getAuth = $authorization->get($authorization_id, $this->_api_context);
            $voidedAuth = $getAuth->void($this->_api_context);
            return $voidedAuth;
        } catch (Exception $e) {
            return array('error' => 1, 'error_message' => $e->getMessage());
        }
    }

    public function refundPayment($transaction_log)
    {
        $amt = Paypal::Amount();
        $amt->setCurrency(config('site.currency_code'))
            ->setTotal((double)$transaction_log->amount);
        $refund = Paypal::Refund();
        $refund->setAmount($amt);
        try {
            if (empty(config('vehicle_rental.is_auto_approve'))) {
                $capture = Paypal::Capture();
                $capture_details = $capture->get($transaction_log->capture_id, $this->_api_context);
                $captureRefund = $capture_details->refund($refund, $this->_api_context);
                return $captureRefund;
            } else {
                $sale = Paypal::Sale();
                $sale->setId($transaction_log->capture_id);
                $refundedSale = $sale->refund($refund, $this->_api_context);
                return $refundedSale;
            }
        } catch (Exception  $ex) {
            return array('error' => 1, 'error_message' => $ex->getMessage());
        }
    }
}
