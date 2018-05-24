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
use App\User;

/**
 * Class Vehicle
 * @package Plugins\Vehicles\Model
 */
class Vehicle extends Model
{
    /**
     * @var string
     */
    protected $table = "vehicles";
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'user_id', 'vehicle_company_id', 'vehicle_make_id', 'vehicle_model_id', 'vehicle_type_id', 'driven_kilometer', 'vehicle_no', 'no_of_seats', 'no_of_doors', 'no_of_gears', 'is_manual_transmission', 'no_small_bags', 'no_large_bags', 'is_ac', 'minimum_age_of_driver', 'mileage', 'is_km', 'is_airbag', 'no_of_airbags', 'is_abs', 'per_hour_amount', 'per_day_amount', 'fuel_type_id', 'vehicle_rental_count', 'feedback_count', 'is_active', 'feedback_rating'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all of the vehicle's bookings .
     */
    public function vehicle_rentals()
    {
        return $this->morphMany(\Plugins\VehicleRentals\Model\VehicleRental::class, 'item_userable');
    }

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
    public function vehicle_model()
    {
        return $this->belongsTo(VehicleModel::class);
    }


    /**
     * @return mixed
     */
    public function vehicle_type()
    {
        return $this->belongsTo(VehicleType::class);
    }

    /**
     * @return mixed
     */
    public function vehicle_company()
    {
        return $this->belongsTo(VehicleCompany::class);
    }

    /**
     * @return mixed
     */
    public function fuel_type()
    {
        return $this->belongsTo(FuelType::class, 'fuel_type_id');
    }

    /**
     * @return mixed
     */
    public function unavailable_vehicle()
    {
        return $this->hasMany(UnavailableVehicle::class);
    }

    /**
     * @return mixed
     */
    public function counter_location()
    {
        return $this->belongsToMany(CounterLocation::class)
            ->withPivot('is_pickup', 'is_drop')
            ->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function vehicle_coupons()
    {
        return $this->morphMany(\Plugins\VehicleCoupons\Model\VehicleCoupon::class, 'couponable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function attachments()
    {
        return $this->morphOne(\App\Attachment::class, 'attachmentable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function paypal_transaction_log()
    {
        return $this->morphOne(\Plugins\Paypal\Model\PaypalTransactionLog::class, 'paypal_transaction_logable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function wallet_transaction_log()
    {
        return $this->morphOne(\App\WalletTransactionLog::class, 'wallet_transaction_logable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function sudopay_transaction_logs()
    {
        return $this->morphOne(\Plugins\Sudopays\Model\SudopayTransactionLog::class, 'sudopay_transaction_logable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function transactions()
    {
        return $this->morphOne(\App\Transaction::class, 'transactionable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function vehicle_feedback()
    {
        return $this->morphMany(\Plugins\VehicleFeedbacks\Model\VehicleFeedback::class, 'feedbackable');
    }

    /**
     * @param         $query
     * @param Request $request
     * @param null    $user_id
     */
    public function scopeFilterByMyVehicle($query, Request $request, $user_id = null)
    {
        if (!is_null($user_id)) {
            $query->where('user_id', '=', $user_id);
        }
    }

    /**
     * @param         $query
     * @param Request $request
     */
    public function scopeFilterActiveVehicle($query, Request $request)
    {
        $query->where('is_active', 1);
        $query->where('is_paid', 1);
    }

    /**
     * @param         $query
     * @param Request $request
     * @return mixed
     */
    public function scopeFilterByRequest($query, Request $request)
    {
        $field = '';
        // query to filter pickup counter location id
        if ($request->has('pickup_location_id')) {
            $query->WhereHas('counter_location', function ($query) use ($request) {
                $query->where('counter_location_id', $request->pickup_location_id)
                    ->where('is_pickup', '=', 1);
            });

        }
        // query to filter drop counter location id
        if ($request->has('drop_location_id')) {
            $query->WhereHas('counter_location', function ($query) use ($request) {
                $query->where('counter_location_id', $request->drop_location_id)
                    ->where('is_drop', '=', 1);
            });
        }
        // query to filter vehicle company id
        if ($request->has('vehicle_company_id')) {
            $query->where('vehicle_company_id', $request->vehicle_company_id);
        }
        // query to filter vehicle model id
        if ($request->has('vehicle_model_id')) {
            $query->where('vehicle_model_id', $request->vehicle_model_id);
        }
        // query to filter vehicle make id
        if ($request->has('vehicle_make_id')) {
            $query->where('vehicle_make_id', $request->vehicle_make_id);
        }
        // query to filter vehicle type id
        if ($request->has('vehicle_type_id')) {
            $query->where('vehicle_type_id', $request->vehicle_type_id);
        }

        // query to remove already booked vehicles
        if ($request->has('start_date') && $request->has('end_date')) {
            $data = UnavailableVehicle::where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                    ->orwhere(function ($query) use ($request) {
                        $query->where('start_date', '>', $request->start_date)
                            ->where('end_date', '<', $request->start_date);
                    })->orwhere(function ($query) use ($request) {
                        $query->where('start_date', '<', $request->end_date)
                            ->where('end_date', '>', $request->end_date);
                    });
            })
                ->where('is_dummy', '!=', 1)
                ->groupBy('vehicle_id')
                ->lists('vehicle_id');
            $query->whereHas('unavailable_vehicle', function ($query) use ($data) {
                $query->whereNotIN('vehicle_id', $data->toArray());
            });
            $hourdiff = round((strtotime($request->end_date) - strtotime($request->start_date)) / 3600, 1);
            $field = 'per_hour_amount';
            if ($hourdiff >= 24) {
                $field = 'per_day_amount';
            }
        }
        //Price filter if no start and end date
        if ($request->has('price_type') && $request->price_type == 'day') {
            $field = 'per_day_amount';
        }
        if ($request->has('price_type') && $request->price_type == 'hour') {
            $field = 'per_hour_amount';
        }
        // query to filter price min value
        if ($request->has('price_min') && !empty($field)) {
            $query->where($field, '>=', $request->price_min);
        }
        // query to filter price max value
        if ($request->has('price_max') && !empty($field)) {
            $query->where($field, '<=', $request->price_max);
        }
        // sorting conditions
        if ($request->has('sort') && $request->sort == 'price') {
            if($request->has('sort_by_price') && $request->sort_by_price == 'day') {
                $field = 'per_day_amount';
            }
            if($request->has('sort_by_price') && $request->sort_by_price == 'hour') {
                $field = 'per_hour_amount';
            }
            $query->orderBy($field, $request->input('sortby', 'desc'));
        } else if ($request->has('sort') && $request->sort == 'rating') {
            $query->orderBy('feedback_rating', $request->input('sortby', 'desc'));
        } else {
            $query->orderBy($request->input('sort', 'id'), $request->input('sortby', 'desc'));
        }
        // query to filter Vehicle type id
        if ($request->has('mileage_min')) {
            $query->where('mileage', '>=', $request->mileage_min);
        }
        // query to filter price max value
        if ($request->has('mileage_max')) {
            $query->where('mileage', '<=', $request->mileage_max);
        }
        // query to filter Vehicle type id
        if ($request->has('seat_min')) {
            $query->where('no_of_seats', '>=', $request->seat_min);
        }
        // query to filter price max value
        if ($request->has('seat_max')) {
            $query->where('no_of_seats', '<=', $request->seat_max);
        }
        // query to filter Vehicle type id
        if ($request->has('vehicle_type') && !empty($request->vehicle_type)) {
            $query->whereIn('vehicle_type_id', $request->vehicle_type);
        }
        // query to filter fuel type id
        if ($request->has('fuel_type') && !empty($request->fuel_type)) {
            $query->whereIn('fuel_type_id', $request->fuel_type);
        }

        if ($request->has('ac') && $request->ac == 1 && $request->has('non_ac') && $request->non_ac == 0) {
            $query->where('is_ac', '=', 1);
        } else if ($request->has('ac') && $request->ac == 0 && $request->has('non_ac') && $request->non_ac == 1) {
            $query->where('is_ac', '=', 0);
        } else if (!$request->has('ac') && $request->has('non_ac') && $request->non_ac == 1) {
            $query->where('is_ac', '=', 0);
        } else if ($request->has('ac') && $request->ac == 1 && !$request->has('non_ac')) {
            $query->where('is_ac', '=', 1);
        }

        // query to filter transmission
        if ($request->has('manual_transmission') && $request->manual_transmission == 1 && $request->has('auto_transmission') && $request->auto_transmission == 0) {
            $query->where('is_manual_transmission', '=', 1);
        } else if ($request->has('manual_transmission') && $request->manual_transmission == 0 && $request->has('auto_transmission') && $request->auto_transmission == 1) {
            $query->where('is_manual_transmission', '=', 0);
        } else if (!$request->has('manual_transmission') && $request->has('auto_transmission') && $request->auto_transmission == 1) {
            $query->where('is_manual_transmission', '=', 0);
        } else if ($request->has('manual_transmission') && $request->manual_transmission == 1 && !$request->has('auto_transmission')) {
            $query->where('is_manual_transmission', '=', 1);
        }

        // query to filter Airbag type
        if ($request->has('airbag') && $request->airbag == 1) {
            $query->where('is_airbag', '=', 1);
        }

        if ($request->has('filter')) {
            $filter = false;
            if ($request->filter == 'active') {
                $filter = true;
            }
            $query->where('is_active', '=', $filter);
        }
        if ($request->has('q')) {
            //Search vehicle tables
            $query->where('vehicles.name', 'LIKE', '%' . $request->input('q') . '%');
            $query->orWhere('vehicles.per_day_amount', 'LIKE', '%' . $request->input('q') . '%');
            $query->orWhere('vehicles.per_hour_amount', 'LIKE', '%' . $request->input('q') . '%');
            $query->orWhere('vehicles.feedback_count', 'LIKE', '%' . $request->input('q') . '%');
            //Search vehicle relation tables
            $query->orWhereHas('vehicle_company', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('q') . '%');
            });
            $query->orWhereHas('vehicle_make', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('q') . '%');
            });
            $query->orWhereHas('vehicle_model', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('q') . '%');
            });
            $query->orWhereHas('vehicle_type', function ($q) use ($request) {
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
            'vehicle_company_id' => 'sometimes|required|integer|exists:vehicle_companies,id',
            'vehicle_make_id' => 'required|integer|exists:vehicle_makes,id',
            'vehicle_model_id' => 'required|integer|exists:vehicle_models,id',
            'vehicle_type_id' => 'required|integer|exists:vehicle_types,id',
            'vehicle_no' => 'required',
            'per_hour_amount' => 'numeric',
            'per_day_amount' => 'required|numeric',
            'is_active' => 'sometimes|required|boolean',
        ];
    }

    public function scopeGetValidationMessage()
    {
        return [
            'vehicle_company_id.required' => 'Required',
            'vehicle_company_id.integer' => 'vehicle_company_id - should be a number!',
            'vehicle_company_id.exists' => 'Invalid vehicle company id',
            'vehicle_make_id.required' => 'Required',
            'vehicle_make_id.integer' => 'vehicle_make_id - should be a number!',
            'vehicle_make_id.exists' => 'Invalid vehicle make id',
            'vehicle_model_id.required' => 'Required',
            'vehicle_model_id.integer' => 'vehicle_model_id - should be a number!',
            'vehicle_model_id.exists' => 'Invalid vehicle model id',
            'vehicle_type_id.required' => 'Required',
            'vehicle_type_id.integer' => 'vehicle_type_id - should be a number!',
            'vehicle_type_id.exists' => 'Invalid vehicle type id',
            'vehicle_no.required' => 'Required',
            'per_hour_amount.numeric' => 'Per hour amount must be a number',
            'per_day_amount.required' => 'Required',
            'per_day_amount.numeric' => 'Per day amount must be a number',
            'is_paid.required' => 'Required',
            'is_paid.boolean' => 'is_paid must be a boolean',
            'is_active.required' => 'Required',
            'is_active.boolean' => 'is_active - should be a boolean!',
        ];
    }
}
