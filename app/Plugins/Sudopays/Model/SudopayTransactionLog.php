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
 
namespace Plugins\Sudopays\Model;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class SudopayTransactionLog extends Model
{
    /**
     * @var string
     */
    protected $table = "sudopay_transaction_logs";

    /**
     * @var array
     */
    protected $fillable = [
        'amount', 'payment_id', 'sudopay_pay_key', 'merchant_id', 'gateway_id', 'gateway_name', 'status', 'payment_type', 'buyer_id', 'buyer_email', 'buyer_address', 'sudopay_transaction_fee'
    ];

    /**
     * @return mixed
     */
    public function sudopay_transaction_logable()
    {
        return $this->morphTo();
    }


    /**
     * @param         $query
     * @param Request $request
     * @return mixed
     */
    public function scopeFilterByRequest($query, Request $request)
    {
        $query->orderBy($request->input('sort', 'id'), $request->input('sortby', 'desc'));
        if ($request->has('q')) {
            $query->where('payment_id', 'LIKE', '%' . $request->input('q') . '%')
                ->orWhere('sudopay_transaction_logable_type', 'LIKE', '%' . $request->input('q') . '%')
                ->orWhere('amount', 'LIKE', '%' . $request->input('q') . '%')
                ->orWhere('sudopay_pay_key', 'LIKE', '%' . $request->input('q') . '%')
                ->orWhere('merchant_id', 'LIKE', '%' . $request->input('q') . '%')
                ->orWhere('gateway_name', 'LIKE', '%' . $request->input('q') . '%')
                ->orWhere('status', 'LIKE', '%' . $request->input('q') . '%')
                ->orWhere('payment_type', 'LIKE', '%' . $request->input('q') . '%');
        }
        return $query;
    }

}
