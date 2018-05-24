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

class Ip extends Model
{
    /**
     * @var string
     */
    protected $table = "ips";

    protected $fillable = ['city_id', 'state_id', 'country_id', 'host', 'ip', 'latitude', 'longitude', 'user_agent', 'timezone', 'is_checked'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function state()
    {
        return $this->belongsTo(State::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function userloginUserLoginIp()
    {
        return $this->hasOne(UserLogin::class, 'user_login_ip_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userLastLoginIp()
    {
        return $this->hasOne(User::class, 'last_login_ip_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userRegiserIp()
    {
        return $this->hasOne(User::class, 'register_ip_id');
    }

    /**
     * @param         $query
     * @param Request $request
     * @return mixed
     */
    public function scopeFilterByRequest($query, Request $request)
    {
        if ($request->input('sort', 'id') == 'State.name') {
            $query->orderBy('state_name', $request->input('sortby', 'desc'));
        } elseif ($request->input('sort', 'id') == 'Country.name') {
            $query->orderBy('country_name', $request->input('sortby', 'desc'));
        } elseif ($request->input('sort', 'id') == 'City.name') {
            $query->orderBy('city_name', $request->input('sortby', 'desc'));
        } else {
            $query->orderBy($request->input('sort', 'id'), $request->input('sortby', 'desc'));
        }

        if ($request->has('q')) {
            $query->whereHas('City', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('q') . '%');
            });
            $query->orwhereHas('State', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('q') . '%');
            });
            $query->orwhereHas('Country', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('q') . '%');
            });
        }
        return $query;
    }

}
