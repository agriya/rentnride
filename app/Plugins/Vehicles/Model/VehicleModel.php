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
 * Class VehicleModel
 * @package Plugins\Vehicles\Model
 */
class VehicleModel extends Model
{
    /**
     * @var string
     */
    protected $table = "vehicle_models";
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'vehicle_make_id', 'is_active', 'vehicle_count'
    ];

    /**
     * @return mixed
     */
    public function vehicle_make()
    {
        return $this->belongsTo(VehicleMake::class);
    }

    /**
     * @return mixed
     */
    public function vehicle()
    {
        return $this->hasMany(Vehicle::class);
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
        if($request->has('vehicle_make_id')) {
            $query->where('vehicle_make_id', $request->input('vehicle_make_id'));
        }
        if ($request->has('q')) {
            $query->whereHas('vehicle_make', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->input('q') . '%');
            });
            $query->orwhere('vehicle_models.name', 'LIKE', '%' . $request->input('q') . '%');
        }
        return $query;
    }

    /**
     * @return array
     */
    public function scopeGetValidationRule()
    {
        return [
            'name' => 'required|min:2',
            'vehicle_make_id' => 'required|integer|exists:vehicle_makes,id',
            'is_active' => 'required|boolean'
        ];
    }

    /**
     * @return array
     */
    public function scopeGetValidationMessage()
    {
        return [
            'name.required' => 'Required',
            'name.min' => 'Name - minimum length is 2!',
            'vehicle_make_id.required' => 'Required',
            'vehicle_make_id.integer' => 'vehicle_make_id must be a number',
            'vehicle_make_id.exists' => 'Invalid vehicle make id',
            'is_active.required' => 'Required',
            'is_active.boolean' => 'is_active must be a boolean',
        ];
    }
}
