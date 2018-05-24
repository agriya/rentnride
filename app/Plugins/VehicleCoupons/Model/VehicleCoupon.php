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
 
namespace Plugins\VehicleCoupons\Model;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class VehicleCoupon extends Model
{
    /**
     * @var string
     */
    protected $table = "coupons";

    protected $fillable = [
        'model_type', 'name', 'description', 'discount', 'discount_type_id', 'no_of_quantity', 'no_of_quantity_used', 'validity_start_date', 'validity_end_date', 'maximum_discount_amount', 'is_active'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function couponable()
    {
        return $this->morphTo();
    }

    /**
     * @param         $query
     * @param Request $request
     * @return mixed
     */
    public function scopeFilterByRequest($query, Request $request)
    {
        if ($request->input('sort', 'id') == 'item.name') {
            $query->orderBy('name', $request->input('sortby', 'desc'));
        } else {
            $query->orderBy($request->input('sort', 'id'), $request->input('sortby', 'desc'));
        }
        if ($request->has('filter')) {
            $filter = false;
            if ($request->input('filter') == 'active') {
                $filter = true;
            }
            $query->where('is_active', '=', $filter);
        }
        if ($request->has('q')) {
            /*$query->whereHas('couponable', function ($query) use ($request) {
                $query->Where('name', 'LIKE', '%' . $request->input('q') . '%');
            });*/
            $query->Where('couponable.name', 'LIKE', '%' . $request->input('q') . '%');
            $query->orWhere('coupons.name', 'LIKE', '%' . $request->input('q') . '%');
        }
        if ($request->has('start_date')) {
            $start_date = date("Y-m-d H:i:s", strtotime($request->input('start_date')));
            $query->where('validity_start_date', '>=', $start_date);
        }
        if ($request->has('end_date')) {
            $end_date = date("Y-m-d H:i:s", strtotime($request->input('end_date')));
            $query->where('validity_end_date', '<=', $end_date);
        }
        return $query;
    }

    public function scopeFilterByVehicleRental($query)
    {
        $query->where('model_type', config('constants.ConstBookingTypes.Booking'));
        return $query;
    }

    /**
     * @return array
     */
    public function scopeGetValidationRule()
    {
        return [
            'name' => 'sometimes|required|unique:coupons|min:4',
            'description' => 'required',
            'discount' => 'required|numeric',
            'discount_type_id' => 'required',
            'no_of_quantity' => 'required|integer',
            'maximum_discount_amount' => 'required|numeric',
            'is_active' => 'required|boolean',
            'validity_start_date' => 'date|date_format:Y-m-d',
            'validity_end_date' => 'date|date_format:Y-m-d'
        ];
    }

    public function scopeGetValidationMessage()
    {
        return [
            'name.required' => 'Required',
            'name.unique' => 'Name already exists!',
            'name.min' => 'name - Minimum length is 4!',
            'description.required' => 'Required',
            'discount.required' => 'Required',
            'discount.numeric' => 'Discount must be a number',
            'discount_type_id.required' => 'Required',
            'no_of_quantity.required' => 'Required',
            'no_of_quantity.integer' => 'No of quantity must be a number',
            'maximum_discount_amount.required' => 'Required',
            'maximum_discount_amount.numeric' => 'Maximum discount amount must be a number',
            'is_active.required' => 'Required',
            'is_active.boolean' => 'is_active must be a boolean',
            'validity_start_date.date' => 'Start date should be a date!',
            'item_booking_start_date.date_format' => 'Date should be in yyyy-mm-dd format',
            'validity_end_date.date' => 'End date should be a date!',
            'validity_end_date.date_format' => 'Date should be in yyyy-mm-dd format',
        ];
    }
}
