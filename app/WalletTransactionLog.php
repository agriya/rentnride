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

class WalletTransactionLog extends Model
{
    protected $table = "wallet_transaction_logs";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'amount', 'status', 'payment_type'
    ];

    /**
     * @param $query
     * @param Request $request
     * @return mixed
     */
    public function scopeFilterByRequest($query, Request $request)
    {
        $query->orderBy($request->input('sort', 'id'), $request->input('sortby', 'desc'));
        if ($request->has('q')) {
            $query->where('wallet_transaction_logable_type', 'LIKE', '%' . $request->input('q') . '%')
                ->orWhere('amount', 'LIKE', '%' . $request->input('q') . '%')
                ->orWhere('status', 'LIKE', '%' . $request->input('q') . '%')
                ->orWhere('payment_type', 'LIKE', '%' . $request->input('q') . '%');
        }
        return $query;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function wallet_transaction_logable()
    {
        return $this->morphTo();
    }


}
