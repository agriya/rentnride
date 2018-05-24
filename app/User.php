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

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Http\Request;

class User extends Model implements AuthenticatableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'role_id', 'username', 'email', 'password', 'available_wallet_amount', 'blocked_amount', 'Vehicle_count', 'vehicle_rental_count', 'vehicle_rental_order_count', 'user_login_count', 'is_agree_terms_conditions', 'is_active', 'is_email_confirmed', 'register_ip_id', 'last_login_ip_id', 'user_avatar_source_id', 'activate_hash'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @param         $query
     * @param Request $request
     * @return mixed
     */
    public function scopeFilterByRequest($query, Request $request)
    {
        if ($request->input('sort', 'id') == 'last_login_ip.ip') {
            $query->orderBy('ip', $request->input('sortby', 'desc'));
        } else {
            $query->orderBy($request->input('sort', 'id'), $request->input('sortby', 'desc'));
        }
        if ($request->has('filter')) {
            $filter = false;
            if ($request->input('filter') == 'active') {
                $filter = true;
            }
            $query->where('is_active', '=', $filter);
        }
        if ($request->has('is_email_confirmed')) {
            $filter = false;
            if ($request->input('is_email_confirmed') == 'yes') {
                $filter = true;
            }
            $query->where('is_email_confirmed', '=', $filter);
        }
        if ($request->has('role_id')) {
            if ($request->input('role_id') == 'userpass') {
                $query->where('role_id', '=', config('constants.ConstUserTypes.User'));
            } else if ($request->input('role_id') == 'admin') {
                $query->where('role_id', '=', config('constants.ConstUserTypes.Admin'));
            }
        }
        if ($request->has('q')) {
            $query->where('username', 'LIKE', '%' . $request->input('q') . '%')
                ->orWhere('email', 'LIKE', '%' . $request->input('q') . '%');
        }
        if ($request->has('username')) {
            $query->where('username', 'LIKE', '%' . $request->input('q') . '%');
        }
        return $query;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function provider_user()
    {
        return $this->hasMany(\Plugins\SocialLogins\Model\ProviderUser::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function user_login()
    {
        return $this->hasMany(UserLogin::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function last_login_ip()
    {
        return $this->belongsTo(Ip::class, 'last_login_ip_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function register_ip()
    {
        return $this->belongsTo(Ip::class, 'register_ip_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user_profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function user_cash_withdrawal()
    {
        return $this->hasMany(UserCashWithdrawal::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function money_transfer_account()
    {
        return $this->hasMany(MoneyTransferAccount::class);
    }

    /**
     * Get all of the users attachment.
     */
    public function attachments()
    {
        return $this->morphOne(Attachment::class, 'attachmentable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vehicle()
    {
        return $this->hasMany(\Plugins\Vehicles\Model\Vehicle::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function vehicle_company()
    {
        return $this->hasOne(\Plugins\Vehicles\Model\VehicleCompany::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\morphMany
     */
    public function message()
    {
        return $this->morphMany(\App\Message::class, 'messageable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function vehicle_feedback()
    {
        return $this->morphMany(\Plugins\VehicleFeedbacks\Model\VehicleFeedback::class, 'feedbackable');
    }

    /**
     * @return array
     */
    public function scopeGetValidationRule()
    {
        return [
            'username' => 'sometimes|required|min:3|unique:users',
            'email' => 'sometimes|required|email|unique:users',
            'password' => 'sometimes|required|min:6|max:20',
            'confirm_password' => 'sometimes|required|min:6|max:20|same:password',
            'is_agree_terms_conditions' => 'sometimes|required',
            'is_active' => 'sometimes|boolean',
            'is_email_confirmed' => 'sometimes|boolean',
            'role_id' => 'sometimes|required|integer'
        ];
    }

    /**
     * @return array
     */
    public function scopeGetEditValidationRule()
    {
        return [
            'username' => 'sometimes|required|min:3',
            'email' => 'required|email',
        ];
    }

    /**
     * @return array
     */
    public function scopeGetForgotPasswordValidationRule()
    {
        return [
            'email' => 'required|email'
        ];
    }

    /**
     * @return array
     */
    public function scopeGetValidationMessage()
    {
        return [
            'email.required' => 'Required',
            'email.email' => 'Enter valid e-mail address!',
            'email.unique' => 'E-mail address already exists!',
            'username.required' => 'Required',
            'username.min' => 'username - minimum length is 3!',
            'username.unique' => 'Username already exists!',
            'password.required' => 'Required',
            'password.min' => 'password - minimum length is 6',
            'password.max' => 'password - maximum length is 20',
            'confirm_password.required' => 'Required',
            'confirm_password.min' => 'confirm_password - Minimum length is 6',
            'confirm_password.max' => 'confirm_password - Maximum length is 20',
            'confirm_password.same' => 'Password Mismatch',
            'is_agree_terms_conditions.required' => 'Required',
            'is_active.boolean' => 'Enter 1 for activate or 0 for inacivate',
            'is_email_confirmed.boolean' => 'Enter 1 for email verified or 0 for not verified',
        ];
    }
}
