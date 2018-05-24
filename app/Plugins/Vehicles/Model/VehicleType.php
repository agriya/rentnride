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

use App\DurationType;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

/**
 * Class VehicleType
 * @package Plugins\Vehicles\Model
 */
class VehicleType extends Model
{
    /**
     * @var string
     */
    protected $table = "vehicle_types";
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'minimum_hour_price', 'maximum_hour_price', 'minimum_day_price', 'maximum_day_price', 'drop_location_differ_unit_price', 'drop_location_differ_additional_fee', 'deposit_amount', 'is_active', 'vehicle_count', 'late_checkout_addtional_fee', 'duration_type_id'
    ];

    /**
     * @return mixed
     */
    public function vehicle()
    {
        return $this->hasMany(Vehicle::class);
    }

    /**
     * @return mixed
     */
    public function vehicle_type_price()
    {
        return $this->hasMany(VehicleTypePrice::class);
    }

    /**
     * @return mixed
     */
    public function vehicle_special_price()
    {
        return $this->hasMany(VehicleSpecialPrice::class);
    }

    /**
     * @return mixed
     */
    public function vehicle_type_surcharge()
    {
        return $this->hasMany(\Plugins\VehicleSurcharges\Model\VehicleTypeSurcharge::class);
    }

    /**
     * @return mixed
     */
    public function vehicle_type_tax()
    {
        return $this->hasMany(\Plugins\VehicleTaxes\Model\VehicleTypeTax::class);
    }

    /**
     * @return mixed
     */
    public function vehicle_type_extra_accessory()
    {
        return $this->hasMany(\Plugins\VehicleExtraAccessories\Model\VehicleTypeExtraAccessory::class);
    }

    /**
     * @return mixed
     */
    public function vehicle_type_insurance()
    {
        return $this->hasMany(\Plugins\VehicleInsurances\Model\VehicleTypeInsurance::class);
    }

    /**
     * @return mixed
     */
    public function vehicle_type_fuel_option()
    {
        return $this->hasMany(\Plugins\VehicleFuelOptions\Model\VehicleTypeFuelOption::class);
    }

    /**
     * @return mixed
     */
    public function duration_type()
    {
        return $this->belongsTo(DurationType::class);
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
        if ($request->has('q')) {
            $query->where('name', 'LIKE', '%' . $request->input('q') . '%');
        }
        if ($request->has('type') && $request->type == 'vehicle_count') {
            $query->where('vehicle_count', '>', 0);
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
            'minimum_hour_price' => 'numeric',
            'maximum_hour_price' => 'numeric',
            'minimum_day_price' => 'numeric',
            'maximum_day_price' => 'numeric',
            'drop_location_differ_unit_price' => 'numeric',
            'drop_location_differ_additional_fee' => 'numeric',
            'deposit_amount' => 'numeric',
            'is_active' => 'required|boolean',
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
            'minimum_hour_price.numeric' => 'minimum hour price must be a number',
            'maximum_hour_price.numeric' => 'maximum_hour_price must be a number',
            'minimum_day_price.numeric' => 'minimum_day_price must be a number',
            'maximum_day_price.numeric' => 'maximum_day_price must be a number',
            'drop_location_differ_unit_price.numeric' => 'drop_location_differ_unit_price must be a number',
            'drop_location_differ_additional_fee.numeric' => 'drop_location_differ_additional_fee must be a number',
            'deposit_amount.numeric' => 'deposit_amount must be a number',
            'is_active.required' => 'Required',
            'is_active.boolean' => 'boolean',
        ];
    }
}
