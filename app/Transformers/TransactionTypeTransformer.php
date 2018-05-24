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
use App\TransactionType;

/**
 * Class TransactionTypeTransformer
 * @package App\Transformers
 */
class TransactionTypeTransformer extends Fractal\TransformerAbstract
{
    /**
     * @param TransactionType $transaction_type
     * @return array
     */
    public function transform(TransactionType $transaction_type)
    {
        $output = array_only($transaction_type->toArray(), ['id', 'name', 'is_credit', 'is_credit_to_receiver', 'is_credit_to_admin', 'message', 'message_for_receiver', 'message_for_admin', 'transaction_type_group_id']);
        return $output;
    }

}
