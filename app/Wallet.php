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
use App\User;

class Wallet extends Model
{
    protected $table = "user_add_wallet_amounts";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'description', 'amount', 'payment_gateway_id', 'user_paypal_connection_id', 'payment_id', 'pay_key', 'is_success'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function paypal_transaction_logs()
    {
        return $this->morphMany(\Plugins\Paypal\Model\PaypalTransactionLog::class, 'paypal_transaction_logable');
    }

    /**
     * @return mixed
     */
    public function sudopay_transaction_logs()
    {
        return $this->morphMany(\Plugins\Sudopays\Model\SudopayTransactionLog::class, 'sudopay_transaction_logable');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function transactions()
    {
        return $this->morphMany(\App\Transaction::class, 'transactionable');
    }

    /**
     * @return array
     */
    public function scopeGetValidationRule()
    {
        $min = config('wallet.min_wallet_amount');
        $max = config('wallet.max_wallet_amount');
        return [
            'amount' => 'required|numeric|min:' . $min . '|max:' . $max
        ];
    }

    public function scopeGetValidationMessage()
    {
        return [
            'amount.required' => 'Required',
            'amount.numeric' => 'Enter valid amount',
            'amount.min' => 'amount - Enter greater or equal minimum amount',
            'amount.max' => 'amount - Enter less or equal maximum amount'
        ];
    }

}
