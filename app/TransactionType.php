<?php
/**
 * APP
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
 
namespace App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

/**
 * Class TransactionType
 * @package App
 */
class TransactionType extends Model
{
    /**
     * @var string
     */
    protected $table = "transaction_types";

    /**
     * @param $query
     * @param Request $request
     * @return mixed
     */
    public function scopeFilterByRequest($query, Request $request)
    {
        $query->orderBy($request->input('sort', 'id'), $request->input('sortby', 'desc'));
        if ($request->has('q')) {
            $query->where('name', 'LIKE', '%' . $request->input('q') . '%')
                ->orWhere('message', 'LIKE', '%' . $request->input('q') . '%');
        }
        if ($request->has('transaction_type_group_id')) {
            if ($request->input('transaction_type_group_id') == config('constants.ConstTransactionTypeGroups.Wallet')) {
                $query->where('transaction_type_group_id', '=', config('constants.ConstTransactionTypeGroups.Wallet'));
            } else if ($request->input('transaction_type_group_id') == config('constants.ConstTransactionTypeGroups.CashWithdrawal')) {
                $query->where('transaction_type_group_id', '=', config('constants.ConstTransactionTypeGroups.CashWithdrawal'));
            } else if ($request->input('transaction_type_group_id') == config('constants.ConstTransactionTypeGroups.Renting')) {
                $query->where('transaction_type_group_id', '=', config('constants.ConstTransactionTypeGroups.Renting'));
            }
        }
        return $query;
    }

    /**
     * @return array
     */
    public function scopeGetValidationRule()
    {
        return [
            'message' => 'required|min:10'
        ];
    }

    public function scopeGetValidationMessage()
    {
        return [
            'message.required' => 'Required',
            'message.min' => 'message - Minimum length is 10!',
        ];
    }
}
