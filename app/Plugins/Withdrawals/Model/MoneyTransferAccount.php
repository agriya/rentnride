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

use Illuminate\Database\Eloquent\Model;
use App\User;

class MoneyTransferAccount extends Model
{
    protected $table = "money_transfer_accounts";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account', 'user_id', 'is_primary'
    ];

    public function scopeGetValidationRule()
    {
        return [
            'account' => 'required',
            'user_id' => 'required|integer'
        ];
    }

    public function scopeGetValidationMessage()
    {
        return [
            'account.required' => 'Required',
            'user_id.required' => 'Required',
            'user_id.integer' => 'user_id must be a number!',
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function user_cash_withdrawal()
    {
        return $this->hasMany(UserCashWithdrawal::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
