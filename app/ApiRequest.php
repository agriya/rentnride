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

class ApiRequest extends Model
{
    /**
     * @var string
     */
    protected $table = "api_requests";

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ip()
    {
        return $this->belongsTo(Ip::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    /**
     * @param         $query
     * @param Request $request
     * @return mixed
     */
    public function scopeFilterByRequest($query, Request $request)
    {
        if($request->input('sort', 'id') == 'User.username') {
            $query->orderBy('username', $request->input('sortby', 'desc'));
        }elseif($request->input('sort', 'id') == 'Ip.ip'){
                $query->orderBy('ip', $request->input('sortby', 'desc'));
        }else{
            $query->orderBy($request->input('sort', 'id'), $request->input('sortby', 'desc'));
        }
        if ($request->has('q')) {
            $query->where('path', 'LIKE', '%' . $request->input('q') . '%')
                ->orWhere('method', 'LIKE', '%' . $request->input('q') . '%')
                ->orWhere('http_response_code', 'LIKE', '%' . $request->input('q') . '%');
            $query->orWhereHas('Ip', function ($q) use ($request) {
                $q->Where('ip', 'like', '%' . $request->input('q') . '%');
            });
            $query->orWhereHas('User', function ($q) use ($request) {
                $q->Where('username', 'like', '%' . $request->input('q') . '%');
            });
        }
        if ($request->has('filter')) {
            $query->where('method', '=', $request->input('filter'));
        }
        return $query;
    }

}
