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

use Plugins\Sudopays\Model\Sudopay;
use File;

class SudopayAPIService
{
    // API URL
    private $live_api_url = 'https://zazpay.com/api/v1';
    private $sandbox_api_url = 'https://sandbox.zazpay.com/api/v1';
    public $api_url;
    private $api_key = '';
    private $merchant_id = '';
    private $is_live = '';
    private $website_id = '';
    private $secret_string = '';
    private $format = 'json';
    private $debug = false;
    private $cache_duration = '+48 hours';
    private $cache_path = '';
    private $url_replace_arr = array(
        ' ',
        '/',
        ':',
        '?',
        '&',
        '$',
        ',',
        '='
    );

    public function __construct()
    {
        $this->cache_path = base_path('storage/logs/');
        $sudopay_conf = config('sudopay');
        $this->merchant_id = $sudopay_conf['sudopay_merchant_id'];
        $this->website_id = $sudopay_conf['sudopay_website_id'];
        $this->api_key = $sudopay_conf['sudopay_api_key'];
        $this->secret_string = $sudopay_conf['sudopay_secret_string'];
        $this->is_live = $sudopay_conf['is_live_mode'];
        $this->api_url = ($this->is_live) ? $this->live_api_url : $this->sandbox_api_url;
    }

    private function _safe_json_decode($json)
    {
        $return = json_decode($json, true);
        if ($return === null) {
            $error['error']['code'] = 1;
            $error['error']['message'] = 'Syntax error, malformed JSON';
            return $error;
        }
        return $return;
    }

    private function _safe_xml_decode($xml)
    {
        libxml_use_internal_errors(true);
        $return = simplexml_load_string($xml);
        if ($return === false) {
            $error['error']['code'] = 1;
            $error['error']['message'] = 'Syntax error, malformed XML';
            return $error;
        }
        $return = json_decode(json_encode((array)$return), true);
        return $return;
    }

    public function _doDeleteCache($supported_query = '')
    {
        $url = $this->api_url . '/merchants/' . $this->merchant_id . '/websites/' . $this->website_id . '/gateways' . '.' . $this->format;
        if (!empty($supported_query)) {
            $url .= '?' . http_build_query($supported_query);
        }
        $filename = $this->cache_path . str_replace($this->url_replace_arr, '_', urldecode($url));
        if (File::exists($filename)){
            File::delete($filename);
        }
    }

    private function _doGet($url)
    {
        $filename = $this->cache_path . str_replace($this->url_replace_arr, '_', urldecode($url));
        if (file_exists($filename)) {
            $fh = fopen($filename, 'r');
            $content = unserialize(fread($fh, filesize($filename)));
            fclose($fh);
            if (strtotime('now') < $content['expires']) {
                return $content['response'];
            }
            @unlink($filename);
        }
        $return = $this->_execute($url);
        if (empty($return['error']['code'])) {
            $fh = fopen($filename, 'w+');
            $content['expires'] = strtotime('now ' . $this->cache_duration);
            $content['response'] = $return;
            fwrite($fh, serialize($content));
            fclose($fh);
        }
        return $return;
    }

    private function _doPost($url, $post = array())
    {
        return $this->_execute($url, 'post', $post);
    }

    private function _doPut($url, $post = array())
    {
        return $this->_execute($url, 'put', $post);
    }

    private function _doDelete($url)
    {
        return $this->_execute($url, 'delete');
    }

    private function _createSignature($post = array())
    {
        $query_string = '';
        foreach ($post as $key => $val) {
            $query_string .= $key . '=' . $val . '&';
        }
        $query_string = substr($query_string, 0, -1); // remove final &
        return md5($this->secret_string . $query_string);
    }

    private function _execute($url, $method = 'get', $post = array())
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $this->merchant_id . ':' . $this->api_key);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60); // 60 seconds (1min)
        curl_setopt($ch, CURLOPT_VERBOSE, ($this->debug) ? true : false);
        if ($method == 'get') {
            curl_setopt($ch, CURLOPT_POST, false);
        } elseif ($method == 'post') {
            //$post['signature'] = $this->_createSignature($post);
            $post_string = http_build_query($post, '', '&');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
        } elseif ($method == 'put') {
            $post_string = http_build_query($post, '', '&');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
        } elseif ($method == 'delete') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        }
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($this->debug) {
            $log_content = date('Y-m-d H:i:s') . __FILE__ . '#' . __LINE__ . "\n";
            $log_content .= 'URL: ' . $url . "\n";
            $log_content .= 'HTTP code: ' . $http_code . "\n";
            $log_content .= 'Response: ' . $response . "\n";
            error_log($log_content);
        }
        if (curl_errno($ch)) {
            $error['error']['code'] = 1;
            $error['error']['message'] = curl_error($ch);
            curl_close($ch);
            return $error;
        }
        switch ($http_code) {
            case 200:
                if ($this->format == 'json') {
                    $return = $this->_safe_json_decode($response);
                } else if ($this->format == 'xml') {
                    $return = $this->_safe_xml_decode($response);
                }
                if (!empty($return['error']['code']) && is_array($return['error']['message'])) {
                    $return['error']['message'] = implode(', ', $return['error']['message']);
                }
                break;

            case 401:
                $return['error']['code'] = 1;
                $return['error']['message'] = 'Unauthorized';
                break;

            case 400:
            case 500:
            case 504: /* Here we're not sure if anything got already triggered/saved in SudoPay */
                $return['error']['code'] = 1;
                $return['error']['message'] = 'Problem in gateway. Recheck later.';
                break;

            default:
                $return['error']['code'] = 1;
                $return['error']['message'] = 'Not Found';
        }
        curl_close($ch);
        return $return;
    }
    public function callPlan()
    {
        $url = $this->api_url . '/merchants/' . $this->merchant_id . '/websites/' . $this->website_id . '/plan' . '.' . $this->format;
        return $this->_doGet($url);
    }
    public function callGateways($supported_query = '')
    {
        $url = $this->api_url . '/merchants/' . $this->merchant_id . '/websites/' . $this->website_id . '/gateways' . '.' . $this->format;
        if (!empty($supported_query)) {
            $url .= '?' . http_build_query($supported_query);
        }
        return $this->_doGet($url);
    }
    public function callCreateReceiverAccount($post)
    {
        $url = $this->api_url . '/merchants/' . $this->merchant_id . '/websites/' . $this->website_id . '/gateways/' . $post['gateway_id'] . '/receiver_accounts' . '.' . $this->format;
        return $this->_doPost($url, $post);
    }
    public function callReceiverAccountListing($receiver_id = '', $gateway_id = '', $page = '')
    {
        if (!empty($gateway_id)) {
            $url = $this->api_url . '/merchants/' . $this->merchant_id . '/websites/' . $this->website_id . '/gateways/' . $gateway_id . '/receiver_accounts/' . $receiver_id . '.' . $this->format;
        } else {
            $page = !empty($page) ? '?page=' . $page : '';
            $url = $this->api_url . '/merchants/' . $this->merchant_id . '/websites/' . $this->website_id . '/receiver_accounts/' . $receiver_id . $page . '.' . $this->format;
        }
        return $this->_doGet($url);
    }
    public function callCapture($post)
    {
        $url = $this->api_url . '/merchants/' . $this->merchant_id . '/gateways/' . $post['gateway_id'] . '/payments/capture' . '.' . $this->format;
        $post['website_id'] = $this->website_id;
        return $this->_doPost($url, $post);
    }
    public function callDisconnectGateway($gateway_id = '', $receiver_id = '')
    {
        $url = $this->api_url . '/merchants/' . $this->merchant_id . '/websites/' . $this->website_id . '/gateways/' . $gateway_id . '/receiver_accounts/' . $receiver_id . '.' . $this->format;
        return $this->_doDelete($url);
    }
    public function callCaptureConfirm($post)
    {
        $url = $this->api_url . '/merchants/' . $this->merchant_id . '/gateways/' . $post['gateway_id'] . '/payments/capture/confirm' . '.' . $this->format;
        $post['website_id'] = $this->website_id;
        return $this->_doPost($url, $post);
    }

    public function callAuth($post)
    {
        $url = $this->api_url . '/merchants/' . $this->merchant_id . '/gateways/' . $post['gateway_id'] . '/payments/auth' . '.' . $this->format;
        $post['website_id'] = $this->website_id;
        return $this->_doPost($url, $post);
    }

    public function callAuthConfirm($post)
    {
        $url = $this->api_url . '/merchants/' . $this->merchant_id . '/gateways/' . $post['gateway_id'] . '/payments/auth/confirm' . '.' . $this->format;
        $post['website_id'] = $this->website_id;
        return $this->_doPost($url, $post);
    }

    public function callAuthCapture($post)
    {
        $url = $this->api_url . '/merchants/' . $this->merchant_id . '/gateways/' . $post['gateway_id'] . '/payments/' . $post['payment_id'] . '/auth-capture' . '.' . $this->format;
        return $this->_doPost($url, $post);
    }

    public function callVoid($post)
    {
        $url = $this->api_url . '/merchants/' . $this->merchant_id . '/gateways/' . $post['gateway_id'] . '/payments/' . $post['payment_id'] . '/void' . '.' . $this->format;
        return $this->_doPost($url, $post);
    }

    public function callRefund($post)
    {
        $url = $this->api_url . '/merchants/' . $this->merchant_id . '/gateways/' . $post['gateway_id'] . '/payments/' . $post['payment_id'] . '/refund' . '.' . $this->format;
        return $this->_doPost($url, $post);
    }
    public function callMarketplaceCapture($post)
    {
        $url = $this->api_url . '/merchants/' . $this->merchant_id . '/gateways/' . $post['gateway_id'] . '/payments/marketplace-capture' . '.' . $this->format;
        $post['website_id'] = $this->website_id;
        return $this->_doPost($url, $post);
    }
    public function callMarketplaceAuth($post)
    {
        $url = $this->api_url . '/merchants/' . $this->merchant_id . '/gateways/' . $post['gateway_id'] . '/payments/marketplace-auth' . '.' . $this->format;
        $post['website_id'] = $this->website_id;
        return $this->_doPost($url, $post);
    }
    public function callMarketplaceAuthCapture($post)
    {
        $url = $this->api_url . '/merchants/' . $this->merchant_id . '/gateways/' . $post['gateway_id'] . '/payments/' . $post['payment_id'] . '/marketplace-auth-capture' . '.' . $this->format;
        return $this->_doPost($url, $post);
    }
    public function callMarketplaceVoid($post)
    {
        $url = $this->api_url . '/merchants/' . $this->merchant_id . '/gateways/' . $post['gateway_id'] . '/payments/' . $post['payment_id'] . '/marketplace-void' . '.' . $this->format;
        return $this->_doPost($url, $post);
    }
    public function callMarketplaceRefund($post)
    {
        $url = $this->api_url . '/merchants/' . $this->merchant_id . '/gateways/' . $post['gateway_id'] . '/payments/' . $post['payment_id'] . '/marketplace-refund' . '.' . $this->format;
        return $this->_doPost($url, $post);
    }
    public function callPayment($payment_id = '')
    {
        $url = $this->api_url . '/merchants/' . $this->merchant_id . '/payment/' . $payment_id . '.' . $this->format;
        return $this->_doGet($url);
    }

    public function callMerchantPayments($page = '')
    {
        $page = !empty($page) ? '?page=' . $page : '';
        $url = $this->api_url . '/merchants/' . $this->merchant_id . '/payments' . $page . '.' . $this->format;
        return $this->_doGet($url);
    }

    public function callWebsitePayments($page = '')
    {
        $page = !empty($page) ? '&page=' . $page : '';
        $url = $this->api_url . '/merchants/' . $this->merchant_id . '/payments?website_id=' . $this->website_id . $page . '.' . $this->format;
        return $this->_doGet($url);
    }

    public function isValidIPNPost($post)
    {
        $signature = $post['signature'];
        unset($post['signature']);
        if ($this->_createSignature($post) == $signature) {
            return true;
        }
        return false;
    }
    public function callCreatePayPalSubscription($post)
    {
        $url = $this->api_url . '/merchants/' . $this->merchant_id . '/websites/' . $this->website_id . '/subscribe/create' . '.' . $this->format;
        return $this->_doPost($url, $post);
    }
    public function callCancelPayPalSubscription($subscription_id = '')
    {
        $url = $this->api_url . '/merchants/' . $this->merchant_id . '/websites/' . $this->website_id . '/subscribe/' . $subscription_id . '/cancel.' . $this->format;
        return $this->_doPut($url);
    }
    //get vault listing
    public function callGetVaults($user_handle = '', $page = '')
    {
        if (!empty($user_handle)) {
            $url = $this->api_url . '/merchants/' . $this->merchant_id . '/websites/' . $this->website_id . '/users/' . $user_handle . '/vaults/type/cc.' . $this->format;
        } else {
            $page = !empty($page) ? '?page=' . $page : '';
            $url = $this->api_url . '/merchants/' . $this->merchant_id . '/websites/' . $this->website_id . '/vaults/type/cc.' . $this->format . $page;
        }
        return $this->_doGet($url);
    }
    public function callAddVault($post)
    {
        $url = $this->api_url . '/merchants/' . $this->merchant_id . '/websites/' . $this->website_id . '/vaults/type/cc' . '.' . $this->format;
        return $this->_doPost($url, $post);
    }
    public function callEditVault($vault_id = '')
    {
        $url = $this->api_url . '/merchants/' . $this->merchant_id . '/websites/' . $this->website_id . '/vaults/' . $vault_id . '.' . $this->format;
        return $this->_doPut($url, $post);
    }
    public function callDeleteVault($vault_id = '')
    {
        $url = $this->api_url . '/merchants/' . $this->merchant_id . '/websites/' . $this->website_id . '/vaults/' . $vault_id . '.' . $this->format;
        return $this->_doDelete($url);
    }
}

?>
