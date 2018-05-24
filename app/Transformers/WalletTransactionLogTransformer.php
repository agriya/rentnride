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
 
namespace App\Transformers;

use League\Fractal;
use App\WalletTransactionLog;
use Plugins\VehicleRentals\Transformers\VehicleRentalTransformer;

/**
 * Class WalletTransactionLogTransformer
 * @package App\Transformers
 */
class WalletTransactionLogTransformer extends Fractal\TransformerAbstract
{
    /**
     * List of resources possible to include
     * @var array
     */
    protected $availableIncludes = [
        'VehicleRental'
    ];

    /**
     * @param Item $item
     * @return array
     */
    public function transform(WalletTransactionLog $wallet_transaction_log)
    {
        $output = array_only($wallet_transaction_log->toArray(), ['id', 'created_at', 'amount', 'wallet_transaction_logable_id', 'wallet_transaction_logable_type', 'status', 'payment_type']);
        return $output;
    }

    /**
     * @param VehicleRental $vehicle_rental
     * @return Fractal\Resource\Item
     */
    public function includeVehicleRental(WalletTransactionLog $wallet_transaction_log)
    {
        if ($wallet_transaction_log->wallet_transaction_logable) {
            if ($wallet_transaction_log->wallet_transaction_logable_type == 'MorphVehicleRental') {
                return $this->item($wallet_transaction_log->wallet_transaction_logable,  new VehicleRentalTransformer());
            }
        } else {
            return null;
        }
    }
}
