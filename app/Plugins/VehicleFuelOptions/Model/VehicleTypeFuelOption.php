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
 
namespace Plugins\VehicleFuelOptions\Model;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use App\DiscountType;
use App\DurationType;

class VehicleTypeFuelOption extends Model
{
    /**
     * @var string
     */
    protected $table = "vehicle_type_fuel_options";

    protected $fillable = [
        'vehicle_type_id', 'fuel_option_id', 'rate', 'discount_type_id', 'duration_type_id', 'max_allowed_amount', 'is_active'
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vehicle_fuel_option()
    {
        return $this->belongsTo(VehicleFuelOption::class, 'fuel_option_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vehicle_type()
    {
        return $this->belongsTo(\Plugins\Vehicles\Model\VehicleType::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function discount_type()
    {
        return $this->belongsTo(DiscountType::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function duration_type()
    {
        return $this->belongsTo(DurationType::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function vehicle_rental_additional_charges()
    {
        return $this->morphMany(\Plugins\VehicleRentals\Model\VehicleRentalAdditionalCharge::class, 'item_user_additional_chargable');
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
            $query->WhereHas('vehicle_fuel_option', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('q') . '%');
            });
            $query->orWhereHas('vehicle_type', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('q') . '%');
            });
            $query->orWhereHas('discount_type', function ($q) use ($request) {
                $q->where('type', 'like', '%' . $request->input('q') . '%');
            });
            $query->orWhereHas('duration_type', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('q') . '%');
            });
        }
        if ($request->has('fuel_option_id')) {
            $query->where('fuel_option_id', '=', $request->fuel_option_id);
        }
        if ($request->has('filter')) {
            $filter = false;
            if ($request->filter == 'active') {
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
            'vehicle_type_id' => 'required|integer|exists:vehicle_types,id',
            'fuel_option_id' => 'required|integer|exists:fuel_options,id',
            'rate' => 'required|numeric',
            'discount_type_id' => 'required|integer|exists:discount_types,id',
            'duration_type_id' => 'required|integer|exists:duration_types,id',
            'max_allowed_amount' => 'required|numeric'
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
            'fuel_option_id.required' => 'Required',
            'fuel_option_id.integer' => 'Fuel option Id must be integer',
            'fuel_option_id.exists' => 'Invalid fuel option id',
            'rate.required' => 'Required',
            'rate.numeric' => 'Rate must be numeric',
            'discount_type_id.required' => 'Required',
            'discount_type_id.integer' => 'Discount Type Id must be integer',
            'discount_type_id.exists' => 'Invalid discount type id',
            'duration_type_id.required' => 'Required',
            'duration_type_id.integer' => 'Duration Type Id must be integer',
            'discount_type_id.exists' => 'Invalid duration type id',
            'max_allowed_amount.required' => 'Required',
            'max_allowed_amount.numeric' => 'Max allowed amount must be numeric'
        ];
    }

}
