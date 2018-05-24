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
use App\Transaction;
use JWTAuth;
use Illuminate\Support\Facades\Auth;

/**
 * Class TransactionTransformer
 * @package App\Transformers
 */
class TransactionTransformer extends Fractal\TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'FromUser', 'ToUser', 'TransactionType'
    ];

    /**
     * @param Transaction $converted_transactions
     * @return array
     */
    public function transform(Transaction $converted_transactions)
    {
        $user = Auth::user();
        $output = array_only($converted_transactions->toArray(), ['id', 'user_id', 'receiver_user_id', 'description', 'transaction_type_id', 'transactionable_type', 'amount', 'created_at']);
        if ($user->role_id == config('constants.ConstUserTypes.Admin')) {
            if ($converted_transactions->receiver_user_id == $user->id) {
                $output['debit_amount'] = 0;
                $output['credit_amount'] = $output['amount'];
            } elseif ($converted_transactions->transactionable_type == "MorphWallet" && $converted_transactions->transaction_type_id == config('constants.ConstTransactionTypes.AddedToWallet')) {
                $output['debit_amount'] = 0;
                $output['credit_amount'] = $output['amount'];
            } else {
                $output['debit_amount'] = $output['amount'];
                $output['credit_amount'] = 0;
            }
        } else {
            if ($converted_transactions->receiver_user_id == $user->id) {
                $output['debit_amount'] = 0;
                $output['credit_amount'] = $output['amount'];
            } elseif ($converted_transactions->transactionable_type == "MorphWallet" && $converted_transactions->transaction_type_id == config('constants.ConstTransactionTypes.AddedToWallet')) {
                $output['debit_amount'] = 0;
                $output['credit_amount'] = $output['amount'];
            } elseif ($converted_transactions->user_id == $user->id) {
                $output['debit_amount'] = $output['amount'];
                $output['credit_amount'] = 0;
            }
        }

        return $output;
    }

    /**
     * @param Transaction $transaction
     * @return Fractal\Resource\Item
     */
    public function includeFromUser(Transaction $transaction)
    {
        if ($transaction->from_user) {
            return $this->item($transaction->from_user, new UserTransformer());
        } else {
            return null;
        }
    }

    /**
     * @param Transaction $transaction
     * @return Fractal\Resource\Item
     */
    public function includeToUser(Transaction $transaction)
    {
        if ($transaction->to_user) {
            return $this->item($transaction->to_user, new UserTransformer());
        } else {
            return null;
        }
    }

    /**
     * @param Transaction $transaction
     * @return Fractal\Resource\Item
     */
    public function includeTransactionType(Transaction $transaction)
    {
        if ($transaction->transaction_type) {
            $a = $this->item($transaction->transaction_type, new TransactionTypeTransformer());
            return $a;
        } else {
            return null;
        }
    }

}
