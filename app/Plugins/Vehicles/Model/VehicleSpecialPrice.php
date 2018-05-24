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
 * Class VehicleSpecialPrice
 * @package Plugins\Vehicles\Model
 */
class VehicleSpecialPrice extends Model
{
    /**
     * @var string
     */
    protected $table = "vehicle_special_prices";
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'start_date', 'end_date', 'vehicle_type_id', 'discount_percentage', 'is_active'
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
        if ($request->has('vehicle_type_id')) {
            $query->where('vehicle_type_id', $request->input('vehicle_type_id'));
        }
        if ($request->has('start_date')) {
            $query->where('start_date', '>=', $request->input('start_date'));
        }
        if ($request->has('end_date')) {
            $query->where('end_date', '<=', $request->input('end_date'));
        }
        return $query;
    }

    /**
     * @return array
     */
    public function scopeGetValidationRule()
    {
        return [
            'start_date' => 'required|date|date_format:Y-m-d H:i:s',
            'end_date' => 'required|date|date_format:Y-m-d H:i:s',
            'vehicle_type_id' => 'required|integer|exists:vehicle_types,id',
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
            'start_date.required' => 'Required',
            'start_date.date' => 'Start date should be a date!',
            'start_date.date_format' => 'Date should be in Y-m-d H:i:s format',
            'end_date.required' => 'Required',
            'end_date.date' => 'End date should be a date!',
            'end_date.date_format' => 'Date should be in Y-m-d H:i:s format',
            'vehicle_type_id.required' => 'Required',
            'vehicle_type_id.integer' => 'Vehicle type id must be a number!',
            'vehicle_type_id.exists' => 'Invalid vehicle type id',
            'discount_percentage.required' => 'Required',
            'discount_percentage.numeric' => 'Discount percentage must be numeric',
            'is_active.required' => 'Required',
            'is_active.boolean' => 'Possible values to be entered is 0 or 1'
        ];
    }

}
