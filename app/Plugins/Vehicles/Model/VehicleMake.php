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
 * Class VehicleMake
 * @package Plugins\Vehicles\Model
 */
class VehicleMake extends Model
{
    /**
     * @var string
     */
    protected $table = "vehicle_makes";
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'is_active', 'vehicle_count', 'vehicle_model_count'
    ];

    /**
     * @return mixed
     */
    public function vehicle_model()
    {
        return $this->hasMany(VehicleModel::class);
    }

    /**
     * @return mixed
     */
    public function vehicle()
    {
        return $this->hasMany(Vehicle::class);
    }

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
        if ($request->has('q')) {
            $query->where('name', 'LIKE', '%' . $request->input('q') . '%');
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
            'is_active' => 'required|boolean'
        ];
    }

    public function scopeGetValidationMessage()
    {
        return [
            'name.required' => 'Required',
            'name.min' => 'Name - minimum length is 2!',
            'is_active.required' => 'Required',
            'is_active.boolean' => 'is_active must be a boolean'
        ];
    }

}
