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
 
namespace Plugins\Paypal\Transformers;

use League\Fractal;
use Plugins\Paypal\Model\PaypalTransactionLog;

/**
 * Class PaypalTransactionLogTransformer
 * @package App\Transformers
 */
class PaypalTransactionLogTransformer extends Fractal\TransformerAbstract
{

    /**
     * @param PaypalTransactionLog $paypal_transaction_log
     * @return array
     */
    public function transform(PaypalTransactionLog $paypal_transaction_log)
    {
        $output = array_only($paypal_transaction_log->toArray(), ['id', 'created_at', 'amount', 'payment_id', 'paypal_transaction_logable_type', 'paypal_transaction_logable_id', 'paypal_pay_key', 'payer_id', 'authorization_id', 'capture_id', 'void_id', 'refund_id', 'status', 'payment_type', 'buyer_email', 'buyer_address', 'paypal_transaction_fee', 'fee_payer']);
        return $output;
    }
}
