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
 * Class VehicleTypePrice
 * @package Plugins\Vehicles\Model
 */
class VehicleTypePrice extends Model
{
    /**
     * @var string
     */
    protected $table = "vehicle_type_prices";
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'vehicle_type_id', 'minimum_no_of_day', 'maximum_no_of_day', 'discount_percentage', 'is_active'
    ];

    /**
     * @return mixed
     */
    public function vehicle_type()
    {
        return $this->belongsTo(VehicleType::class);
    }

    /**
     * @param $query
     * @param Request $request
     * @return mixed
     */
    public function scopeFilterByRequest($query, Request $request)
    {
        $query->orderBy($request->input('sort', 'id'), $request->input('sortby', 'desc'));
        if ($request->has('filter')) {
            $filter = false;
            if ($request->input('filter') == 'active') {
                $filter = true;
            }
            $query->where('is_active', '=', $filter);
        }
        if($request->has('vehicle_type_id')) {
            $query->where('vehicle_type_id', $request->input('vehicle_type_id'));
        }
        if ($request->has('q')) {
            $query->whereHas('vehicle_type', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('q') . '%');
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
            'vehicle_type_id' => 'required|integer|exists:vehicle_types,id',
			'minimum_no_of_day' => 'required|integer',
			'maximum_no_of_day' => 'required|integer',
			'discount_percentage' => 'required|numeric',
			'is_active' => 'required|boolean'
        ];
    }

    /**
     * @return array
     */
    public function scopeGetValidationMessage()
    {
        return [
            'vehicle_type_id.required' => 'Required',
            'vehicle_type_id.integer' => 'Vehicle Type Id must be integer',
            'vehicle_type_id.exists' => 'Invalid vehicle type id',
            'minimum_no_of_day.required' => 'Required',
            'minimum_no_of_day.integer' => 'Minimum number of days must be integer',
            'maximum_no_of_day.required' => 'Required',            
            'maximum_no_of_day.integer' => 'Maximum number of day must be integer',
            'discount_percentage.required' => 'Required',
            'discount_percentage.numeric' => 'Discount percentage must be numeric',
            'is_active.required' => 'Required',
            'is_active.boolean' => 'Possible values to be entered is 0 or 1'
        ];
    }

}
