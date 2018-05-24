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
 
namespace Plugins\Withdrawals\Model;

use App\Message;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

use JWTAuth;
use App\User;
use App\Transaction;

/**
 * Class UserCashWithdrawal
 * @package App
 */
class UserCashWithdrawal extends Model
{
    /**
     * @var string
     */
    protected $table = "user_cash_withdrawals";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'amount', 'user_id', 'money_transfer_account_id', 'withdrawal_status_id'
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function money_transfer_account()
    {
        return $this->belongsTo(MoneyTransferAccount::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function withdrawal_status()
    {
        return $this->belongsTo(WithdrawalStatus::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function message()
    {
        return $this->morphMany(Message::class, 'messageable');
    }

    /**
     * @param         $query
     * @param Request $request
     * @return mixed
     */
    public function scopeFilterByRequest($query, Request $request)
    {
        $query->orderBy($request->input('sort', 'id'), $request->input('sortby', 'desc'));
        if ($request->has('filter')) {
            $query->where('withdrawal_status_id', '=', $request->input('filter'));
        }
        if ($request->has('q')) {
            $query->where('user_cash_withdrawals.amount', 'like', '%' . $request->input('q') . '%');
            $query->orWhereHas('user', function ($q) use ($request) {
                $q->where('username', 'like', '%' . $request->input('q') . '%');
            });
            $query->orWhereHas('money_transfer_account', function ($q) use ($request) {
                $q->where('account', 'like', '%' . $request->input('q') . '%');
            });
            $query->orWhereHas('withdrawal_status', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('q') . '%');
            });
        }
        $user = JWTAuth::parseToken()->authenticate();
        if ($user->id != config('constants.ConstUserTypes.Admin')) {
            $query->where('user_id', '=', $user->id);
        }
        return $query;
    }

    /**
     * @return array
     */
    public function scopeGetValidationRule()
    {
        return [
            'amount' => 'sometimes|required|min:1|max:4',
            'user_id' => 'sometimes|required|integer',
            'money_transfer_account_id' => 'sometimes|required|integer',
            'withdrawal_status_id' => 'required|integer'
        ];
    }

    public function scopeGetValidationMessage()
    {
        return [
            'amount.required' => 'Required',
            'amount.min' => 'amount - Minimum length is 1!',
            'amount.max' => 'amount - Maximum length is 4!',
            'user_id.required' => 'Required',
            'user_id.integer' => 'user_id must be anumber!',
            'money_transfer_account_id.required' => 'Required',
            'money_transfer_account_id.integer' => 'money_transfer_account_id must be a number',
            'withdrawal_status_id.required' => 'Required',
            'withdrawal_status_id.integer' => 'withdrawal_status_id must be a number',
        ];
    }

}
