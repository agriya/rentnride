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
 
namespace Plugins\VehicleInsurances\Model;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use App\DiscountType;
use App\DurationType;

class VehicleTypeInsurance extends Model
{
    /**
     * @var string
     */
    protected $table = "vehicle_type_insurances";

    protected $fillable = [
        'vehicle_type_id', 'insurance_id', 'rate', 'discount_type_id', 'duration_type_id', 'max_allowed_amount', 'is_active'
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vehicle_insurance()
    {
        return $this->belongsTo(VehicleInsurance::class, 'insurance_id');
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
            $query->WhereHas('vehicle_insurance', function ($q) use ($request) {
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

        if ($request->has('filter')) {
            $filter = false;
            if ($request->filter == 'active') {
                $filter = true;
            }
            $query->where('is_active', '=', $filter);
        }
        if($request->has('insurance_id')){
            $query->where('insurance_id', '=', $request->insurance_id);
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
            'insurance_id' => 'required|integer|exists:insurances,id',
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
            'insurance_id.required' => 'Required',
            'insurance_id.integer' => 'Insurance Id must be integer',
            'insurance_id.exists' => 'Invalid insurance id',
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
