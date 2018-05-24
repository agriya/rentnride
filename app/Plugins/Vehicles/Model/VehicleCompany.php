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
 
namespace Plugins\Vehicles\Model;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use App\User;

/**
 * Class VehicleCompany
 * @package Plugins\Vehicles\Model
 */
class VehicleCompany extends Model
{
    /**
     * @var string
     */
    protected $table = "vehicle_companies";
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'user_id', 'name', 'address', 'latitude', 'longitude', 'fax', 'phone', 'mobile', 'email', 'is_active', 'vehicle_count'
    ];

    /**
     * @return mixed
     */
    public function vehicle()
    {
        return $this->hasMany(Vehicle::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param $query
     * @param Request $request
     * @param string $status
     * @return mixed
     */
    public function scopeFilterByRequest($query, Request $request, $status = '')
    {
		
		if ($request->has('sort') && $request->sort == 'status') {
			$query->orderBy('is_active', $request->input('sortby', 'desc'));
		} else {
            $query->orderBy($request->input('sort', 'id'), $request->input('sortby', 'desc'));
        }
        if ($request->has('filter')) {
            $filter = 0;
            if ($request->input('filter') == 'active') {
                $filter = 1;
            }
            if ($request->input('filter') == 'rejected') {
                $filter = 2;
            }
            $query->where('is_active', '=', $filter);
        }
        if($request->has('is_active')) {
            $query->where('is_active', $request->input('is_active'));
        }
        if($request->has('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }
        if ($request->has('q')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('username', 'LIKE', '%' . $request->input('q') . '%');
            });
            $query->orwhere('name', 'LIKE', '%' . $request->input('q') . '%')
                ->orWhere('address', 'LIKE', '%' . $request->input('q') . '%')
                ->orWhere('mobile', 'LIKE', '%' . $request->input('q') . '%')
                ->orWhere('email', 'LIKE', '%' . $request->input('q') . '%');
        }
        return $query;
    }

    /**
     * @return array
     */
    public function scopeGetValidationRule()
    {
        return [
            'user_id' => 'required|unique:vehicle_companies',
            'name' => 'required|min:2',
            'address' => 'required|min:5',
            'mobile' => 'required',
            'email' => 'required|email'
        ];
    }

    public function scopeGetValidationMessage()
    {
        return [
            'user_id.required' => 'Required',
            'user_id.unique' => 'User - already added company',
            'name.required' => 'Required',
            'name.min' => 'Name - minimum length is 2!',
            'address.required' => 'Required',
            'address.min' => 'address - minimum length is 5!',
            'mobile.required' => 'Required',
            'email.required' => 'Required',
            'email.email' => 'Enter valid e-mail address!',
        ];
    }

}
