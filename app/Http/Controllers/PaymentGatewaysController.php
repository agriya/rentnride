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

class PaymentGatewaysController extends Controller
{
    /**
     * @var \Plugins\Sudopays\Services\SudopayAPIService
     */
    protected $sudopayService;

    /**
     * PaymentGatewaysController constructor.
     */
    public function __construct()
    {
        $this->setSudopayService();
    }

    public function setSudopayService()
    {
        if (isPluginEnabled('Sudopays')) {
            $this->sudopayService = new \Plugins\Sudopays\Services\SudopayAPIService();
        }
    }

    /**
     * Show all enabled payment gateways.
     * @Get("/get_gateways")
     * @Transaction({
     *      @Response(200, body={"paypal": {"error": {"code": 0}, "paypal_enabled": true}, "sudopay": [], "wallet": {"error": {"code": 0},"wallet_enabled": true}})
     * })
     */
    public function getGateways(Request $request)
    {
        $sudopay_gateway_response = $paypal_gateway_response = $wallet_response = $payment_gateways = array();
        $selected_payment_gateway_id = $selected_gateway_id = '';
        // wallet
        $wallet = new \App\Services\WalletService();
        $wallet_response = $wallet->getWalletDetails();
        // paypal
        if (isPluginEnabled('Paypal')) {
            $paypal = new \Plugins\Paypal\Services\PayPalService();
            $paypal_gateway_response = $paypal->getPaypalDetails();
        }
        if (isPluginEnabled('Sudopays')) {
            $gateways = $this->sudopayService->callGateways(array('supported_actions' => 'auth,capture'));
            $i = 0;
            if (!empty($gateways['gateways'])) {
                foreach ($gateways['gateways'] as $group_gateway) {
                    $gateway_groups[] = $group_gateway;
                }
                $unset_array = array();
                foreach ($gateways['gateways'] as $key => $group_gateway) {
                    if (count($group_gateway['gateways']) > 0) {
                        foreach ($group_gateway['gateways'] as $gateway) {
                            $gateway['group_id'] = $group_gateway['id'];
                            $gateway_array[] = $gateway;
                        }
                    } else {
                        $unset_array[] = $key;
                    }
                }
                if (count($unset_array) > 0) {
                    foreach ($unset_array as $unset_val) {
                        unset($gateway_groups[$unset_val]);
                    }
                }
                $payment_gateway_arrays = array();
                if (!empty($gateway_array)) {
                    foreach ($gateway_array as $gateway) {
                        $payment_gateway_arrays[$i]['id'] = $gateway['id'];
                        $payment_gateway_arrays[$i]['payment_id'] = 'sp_' . $gateway['id'];
                        $payment_gateway_arrays[$i]['group_id'] = $gateway['group_id'];
                        $payment_gateway_arrays[$i]['display_name'] = $gateway['display_name'];
                        $payment_gateway_arrays[$i]['thumb_url'] = $gateway['thumb_url'];
                        $payment_gateway_arrays[$i]['sp_' . $gateway['id']] = implode($gateway['_form_fields']['_extends_tpl'], ",");
                        $payment_gateway_arrays[$i]['form_fields'] = implode($gateway['_form_fields']['_extends_tpl'], ",");
                        $i++;
                    }
                    $payment_gateways = $payment_gateway_arrays;
                }
                //Load form fields
                $form_fields_tpls = $gateways['_form_fields_tpls'];
                //Get first payment
                if (!empty($gateway_groups)) {
                    $default_group_id = '';
                    $default_gateway_id = '';
                    foreach ($gateway_groups As $key => $value) {
                        $default_group_id = $value['id'];
                        break;
                    }
                    foreach ($payment_gateways As $key => $value) {
                        $default_gateway_id = $value['payment_id'];
                        break;
                    }
                    $selected_payment_gateway_id = $default_gateway_id;
                    $selected_gateway_id = $default_group_id;
                }

                $sudopay_gateway_response['gateway_groups'] = $gateway_groups;
                $sudopay_gateway_response['payment_gateways'] = $payment_gateways;
                $sudopay_gateway_response['form_fields_tpls'] = $form_fields_tpls;
                $sudopay_gateway_response['selected_payment_gateway_id'] = $selected_payment_gateway_id;
                $sudopay_gateway_response['selected_gateway_id'] = $selected_gateway_id;

            }
        }
        if ($request->has('page') && $request->page == 'wallet') {
            $response['sudopay'] = $sudopay_gateway_response;
            $response['paypal'] = $paypal_gateway_response;
        } else {
            $response['paypal'] = $paypal_gateway_response;
            $response['sudopay'] = $sudopay_gateway_response;
            $response['wallet'] = $wallet_response;
        }
        return $response;
    }

}
