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
use Plugins\Vehicles\Model\Vehicle;

/**
 * Class VehicleCompany
 * @package Plugins\Vehicles\Model
 */
class UnavailableVehicle extends Model
{
    /**
     * @var string
     */
    protected $table = "unavailable_vehicles";
	
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'item_user_id', 'vehicle_id', 'start_date', 'end_date', 'is_dummy'
    ];

    /**
     * @return mixed
     */
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function scopeFilterByMyVehicle($query, $user_id = null)
    {
        if (!is_null($user_id)) {
            $query->WhereHas('vehicle', function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            });
        }
    }

    public function scopeFilterByRequest($query, Request $request)
    {
        $query->orderBy($request->input('sort', 'id'), $request->input('sortby', 'desc'));
        // maintance record only
        $query->where('is_dummy', 2);
        // query to filter pickup counter location id
        if ($request->has('vehicle_id')) {
            $query->where('vehicle_id', $request->vehicle_id);
        }
        if ($request->has('q')) {
            $query->whereHas('vehicle', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('q') . '%');
            });
        }
        if($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('start_date', array($request->start_date, $request->end_date));
            $query->orWhereBetween('end_date', array($request->start_date, $request->end_date));
        }else if($request->has('start_date')){
            $query->where('start_date','>=', $request->start_date);
        }else if($request->has('end_date')){
            $query->where('end_date', '<=', $request->end_date);
        }

        return $query;
    }

    /**
     * @return array
     */
    public function scopeGetValidationRule()
    {
        return [
            'vehicle_id' => 'sometimes|required|integer|exists:vehicles,id',
            'start_date' => 'required|date|date_format:Y-m-d H:i:s',
            'end_date' => 'required|date|date_format:Y-m-d H:i:s',
        ];
    }

    /**
     * @return array
     */
    public function scopeGetValidationMessage()
    {
        return [
            'vehicle_id.required' => 'Required',
            'vehicle_id.integer' => 'vehicle_id must be a number',
            'vehicle_id.exists' => 'Invalid vehicle id',
            'start_date.required' => 'Required',
            'start_date.date' => 'Start date should be a date!',
            'start_date.date_format' => 'Date should be in Y-m-d H:i:s format',
            'end_date.required' => 'Required',
            'end_date.date' => 'End date should be a date!',
            'end_date.date_format' => 'Date should be in Y-m-d H:i:s format'
        ];
    }
}
