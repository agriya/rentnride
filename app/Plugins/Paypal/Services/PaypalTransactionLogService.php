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

use Plugins\Paypal\Model\PaypalTransactionLog;

class PaypalTransactionLogService
{
    /**
     * PayPalService constructor.
     */
    public function __construct()
    {
    }

    public function log($data)
    {
        return PaypalTransactionLog::create($data);
    }

    public function updateLogByPayId($data, $payId = '')
    {
        if ($payId) {
            $log = PaypalTransactionLog::where('payment_id', '=', $payId)->first();
            if ($log)
                $log->update($data);
        }
    }

    public function updateLogById($data, $id = '')
    {
        if ($id) {
            $log = PaypalTransactionLog::where('id', '=', $id)->first();
            if ($log)
                $log->update($data);
        }
    }

}
