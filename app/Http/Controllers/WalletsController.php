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

use App\Http\Controllers\Controller;
use App\Wallet;
use App\Services\WalletService;
use JWTAuth;
use Validator;

/**
 * Money Transfer Accounts resource representation.
 * @Resource("Wallets")
 */
class WalletsController extends Controller
{
    /**
     * @var
     */
    protected $paymentService;
    /**
     * @var
     */
    protected $logtService;

    /**
     * WalletsController constructor.
     */
    public function __construct()
    {
        // Check the logged user authentication.
        $this->middleware('jwt.auth');
        $this->setWalletService();
    }

    public function setWalletService()
    {
        $this->walletService = new WalletService();
    }

    /**
     * Update available wallet amount to user.
     * Update available wallet amount with a `user_id`.
     * @GET("/wallets")
     * @Transaction({
     *      @Request({"user_id": 1}),
     *      @Response(200, body={"available_wallet_amount": 100}),
     *      @Response(500, body={"message": "Creating default object from empty value.", "status_code": 500})
     * })
     */
    public function addToWallet(Request $request)
    {
        $user = $this->auth->user();
        $request_amount = $request->only('amount');
        $request->sudopay_gateway_id = ($request->has('payment_id')) ? (int)$request->payment_id : 0;
        $validator = Validator::make($request_amount, Wallet::GetValidationRule(), Wallet::GetValidationMessage());
        if ($request->has('gateway_id') && ((isPluginEnabled('Paypal') && $request->gateway_id == config('constants.ConstPaymentGateways.PayPal')) || (isPluginEnabled('Sudopays') && $request->gateway_id == config('constants.ConstPaymentGateways.SudoPay')))) {
            if ($request->gateway_id == config('constants.ConstPaymentGateways.SudoPay')) {
                $sudopay_data = array('address', 'city', 'country', 'email', 'gateway_id', 'payment_id', 'phone', 'state', 'zip_code');
                if ($request->has('credit_card_code')) {
                    $sudopay_data = array_merge($sudopay_data, array('credit_card_code', 'credit_card_expire', 'credit_card_name_on_card', 'credit_card_number'));
                }
                if ($request->has('payment_note')) {
                    $sudopay_data = array_merge($sudopay_data, array('payment_note'));
                }
                $sudopay_data = $request->only($sudopay_data);
            }
            if ($validator->passes()) {
                try {
                    $wallet_data = new Wallet;
                    $wallet_data->user_id = $user->id;
                    $wallet_data->amount = $request_amount['amount'];
                    $wallet_data->description = "Amount added to wallet";
                    $wallet_data->payment_gateway_id = $request->gateway_id;
                    $wallet_data->sudopay_gateway_id = $request->sudopay_gateway_id;
                    $wallet = $wallet_data->save();
                    $log_data['amount'] = $request_amount['amount'];
                    $log_data['gateway_id'] = $wallet_data->sudopay_gateway_id;
                    $log_data['fee_payer'] = 'User';
                    if ($wallet) {
                        if ($request->gateway_id == config('constants.ConstPaymentGateways.SudoPay')) {
                            $this->logtService = new \Plugins\Sudopays\Services\SudopayTransactionLogService();
                            $this->paymentService = new \Plugins\Sudopays\Services\SudopayService();
                            $transaction_data = $this->logtService->log($log_data);
                            $cur_wallet = Wallet::with(['sudopay_transaction_logs'])->where('id', '=', $wallet_data->id)->first();
                            $cur_transaction = $cur_wallet->sudopay_transaction_logs()->save($transaction_data);
                            $response = $this->paymentService->createPayment($cur_transaction->id, $sudopay_data);
                            if (!empty($response['gateway_callback_url'])) {
                                return response()->json(['url' => $response['gateway_callback_url']], 200);
                            }
                            if (!empty($response['pending'])) {
                                return response()->json(['Success' => 'Once payment is received, it will be processed'], 200);
                            } elseif (!empty($response['success'])) {
                                return response()->json(['Success' => 'Amount Added successfully'], 200);
                            } elseif (!empty($response['error'])) {
                                throw new \Dingo\Api\Exception\StoreResourceFailedException('Your payment could not be completed. Please, try again.', $response['error_message']);
                            }
                        } else if ($request->gateway_id == config('constants.ConstPaymentGateways.PayPal')) {
                            $this->logtService = new \Plugins\Paypal\Services\PaypalTransactionLogService();
                            $this->paymentService = new \Plugins\Paypal\Services\PayPalService();
                            $transaction_data = $this->logtService->log($log_data);
                            $cur_wallet = Wallet::with(['paypal_transaction_logs'])->where('id', '=', $wallet_data->id)->first();
                            $cur_transaction = $cur_wallet->paypal_transaction_logs()->save($transaction_data);
                            $response = $this->paymentService->createPayment($cur_transaction->id, config('constants.ConstPaypalGatewaysProcess.sale'));
                            if (!empty($response['error'])) {
                                throw new \Dingo\Api\Exception\StoreResourceFailedException('Your payment could not be completed. Please, try again.', $response['error_message']);
                            } else if (empty($response['error'])) {
                                return response()->json(['url' => $response], 200);
                            }
                        }
                    } else {
                        throw new \Dingo\Api\Exception\StoreResourceFailedException('Wallet could not be updated. Please, try again.', $validator->errors());
                    }
                } catch (\Exception $e) {
                    throw new \Dingo\Api\Exception\StoreResourceFailedException('Wallet could not be updated. Please, try again.', array($e->getMessage()));
                }
            } else {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('Wallet could not be updated. Please, try again.', $validator->errors());
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Payment gateway could not be set. Please, try again.');
        }

    }
}