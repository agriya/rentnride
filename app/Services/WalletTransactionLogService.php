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

use App\WalletTransactionLog;

class WalletTransactionLogService
{
    public function log($data) {
        return WalletTransactionLog::create($data);
    }

    public function updateLogById($data, $id = '')
    {
        if ($id) {
            $log = WalletTransactionLog::where('id', '=', $id)->first();
            if ($log)
                $log->update($data);
        }
    }
}
