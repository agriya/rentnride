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
 
namespace Plugins\VehicleExtraAccessories\Model;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class VehicleExtraAccessory extends Model
{
    /**
     * @var string
     */
    protected $table = "extra_accessories";

    protected $fillable = [
        'name', 'short_description', 'description', 'is_active', 'slug'
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vehicle_type_extra_accessory()
    {
        return $this->hasMany(VehicleTypeExtraAccessory::class);
    }
    /**
     * @param         $query
     * @param Request $request
     */
    public function scopeFilterByActiveRecord($query, Request $request)
    {
        $query->where('is_active', '=', 1);
    }
    /**
     * @param         $query
     * @param Request $request
     * @return mixed
     */
    public function scopeFilterByRequest($query, Request $request)
    {
        $query->orderBy($request->input('sort', 'id'), $request->input('sortby', 'asc'));
        if ($request->has('q')) {
            $query->where('name', 'LIKE', '%' . $request->input('q') . '%')
                ->orWhere('short_description', 'LIKE', '%' . $request->input('q') . '%')
                ->orWhere('description', 'LIKE', '%' . $request->input('q') . '%');
        }
        if ($request->has('filter')) {
            $filter = false;
            if ($request->input('filter') == 'active') {
                $filter = true;
            }
            $query->where('is_active', '=', $filter);
        }
        return $query;
    }

    /**
     * @return array
     */
    public function scopeGetValidationRule()
    {
        return [
            'name' => 'required|min:5',
            'short_description' => 'required|min:10',
            'description' => 'required|min:15',
            'is_active' => 'sometimes|required|boolean'
        ];
    }

    /**
     * @return array
     */
    public function scopeGetValidationMessage()
    {
        return [
            'name.required' => 'Required',
            'name.min' => 'name - Minimum length is 5',
            'short_description.required' => 'Required',
            'short_description.min' => 'Short Description - Minimum length is 10',
            'description.required' => 'Required',
            'description.min' => 'Description - Minimum length is 15',
            'is_active.required' => 'Required',
            'is_active.boolean' => 'Possible values to be entered is 0 or 1'
        ];
    }

}
