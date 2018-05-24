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
 
namespace Plugins\Sudopays\Transformers;

use League\Fractal;
use Plugins\Sudopays\Model\SudopayTransactionLog;

/**
 * Class SudopayTransactionLogTransformer
 * @package Plugins\Sudopays\Transformers
 */
class SudopayTransactionLogTransformer extends Fractal\TransformerAbstract
{
    /**
     * @param SudopayTransactionLog $sudopay_transaction_log
     * @return array
     */
    public function transform(SudopayTransactionLog $sudopay_transaction_log)
    {
        $output = array_only($sudopay_transaction_log->toArray(), ['id', 'created_at', 'amount', 'payment_id', 'sudopay_transaction_logable_id', 'sudopay_transaction_logable_type', 'sudopay_pay_key', 'merchant_id', 'gateway_id', 'gateway_name', 'status', 'payment_type', 'buyer_id', 'buyer_email', 'buyer_address', 'sudopay_transaction_fee']);
        return $output;
    }
}
