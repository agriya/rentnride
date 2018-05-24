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

use Plugins\Sudopays\Model\SudopayTransactionLog;

class SudopayTransactionLogService
{
    /**
     * PayPalService constructor.
     */
    public function __construct()
    {
    }

    public function log($data)
    {
        return SudopayTransactionLog::create($data);
    }

    public function updateLogByPayId($data, $payId = '')
    {
        if ($payId) {
            $log = SudopayTransactionLog::where('payment_id', '=', $payId)->first();
            if ($log)
                $log->update($data);
        }
    }

    public function updateLogById($data, $id = '')
    {
        if ($id) {
            $log = SudopayTransactionLog::where('id', '=', $id)->first();
            if ($log)
                $log->update($data);
        }
    }

}
