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

/**
 * Class CounterLocation
 * @package Plugins\Vehicles\Model
 */
class CounterLocation extends Model
{
    /**
     * @var string
     */
    protected $table = "counter_locations";
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'address', 'latitude', 'longitude', 'fax', 'phone', 'mobile', 'email'
    ];

    /**
     * @return mixed
     */
    public function vehicle()
    {
        return $this->belongsToMany(Vehicle::class);
    }

    /**
     * @param $query
     * @param Request $request
     * @return mixed
     */
    public function scopeFilterByRequest($query, Request $request)
    {
        $query->orderBy($request->input('sort', 'id'), $request->input('sortby', 'desc'));
        if ($request->has('q')) {
            $query->where('address', 'LIKE', '%' . $request->input('q') . '%')
                ->orWhere('mobile', 'LIKE', '%' . $request->input('q') . '%')
                ->orWhere('email', 'LIKE', '%' . $request->input('q') . '%');
        }
        if ($request->has('vehicle_id')) {
            $query->whereHas('vehicle', function ($q) use ($request) {
                $q->where('vehicle_id', $request->input('vehicle_id'));
            });
        }
        return $query;
    }

    /**
     * @return array
     */
    public function scopeGetValidationRule()
    {
        return [
            'address' => 'required|min:5|unique:counter_locations',
            'mobile' => 'required',
            'email' => 'required|email'
        ];
    }

    /**
     * @return array
     */
    public function scopeGetValidationMessage()
    {
        return [
            'address.required' => 'Required',
            'address.min' => 'address - minimum length is 5!',
            'address.unique' => 'address - already exists!',
            'mobile.required' => 'Required',
            'email.required' => 'Required',
            'email.email' => 'Enter valid e-mail address!'
        ];
    }
}