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

class UserLogin extends Model
{
    /**
     * @var string
     */
    protected $table = "user_logins";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'user_login_ip_id', 'role_id', 'user_agent'
    ];

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
    public function user_login_ip()
    {
        return $this->belongsTo(Ip::class, 'user_login_ip_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * @param         $query
     * @param Request $request
     * @return mixed
     */
    public function scopeFilterByRequest($query, Request $request)
    {
        if ($request->input('sort', 'id') == 'User.name') {
            $query->orderBy('name', $request->input('sortby', 'desc'));
        } elseif ($request->input('sort', 'id') == 'user_login_ip.ip') {
            $query->orderBy('ip', $request->input('sortby', 'desc'));
        } else {
            $query->orderBy($request->input('sort', 'id'), $request->input('sortby', 'desc'));
        }
        if ($request->has('q')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('username', 'like', '%' . $request->input('q') . '%');
            });
        }
		if($request->has('user_id') && !empty($request->user_id)) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('id', '=', $request->user_id);
            });
        }
        return $query;
    }

}
