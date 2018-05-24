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
 
namespace Plugins\VehicleRentals\Model;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

use JWTAuth;
use Plugins\VehicleFeedbacks\Model\VehicleFeedback;
use Plugins\Vehicles\Model\UnavailableVehicle;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\User;
use Plugins\Vehicles\Model\CounterLocation;
use Illuminate\Support\Facades\Auth;
use DB;
use Carbon;
use Plugins\Vehicles\Services\VehicleService;

/**
 * Class VehicleRental
 * @package App
 */
class VehicleRental extends Model
{
    /**
     * @var string
     */
    protected $table = "item_users";
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'user_id', 'item_booking_start_date', 'item_booking_end_date', 'pickup_counter_location_id', 'drop_counter_location_id', 'coupon_code', 'item_user_status_id', 'status_updated_at', 'is_payment_cleared', 'booking_amount', 'deposit_amount', 'surcharge_amount', 'extra_accessory_amount', 'tax_amount', 'insurance_amount', 'fuel_option_amount', 'drop_location_differ_charges', 'additional_fee', 'total_amount', 'payment_gateway_id', 'coupon_id', 'coupon_discount_amount', 'special_discount_amount', 'type_discount_amount', 'admin_commission_amount', 'host_service_amount', 'late_fee', 'claim_request_amount', 'quantity', 'is_dispute', 'paid_deposit_amount', 'paid_manual_amount', 'booker_amount'
    ];

    /**
     * Get all of the owning likeable models.
     */
    public function item_userable()
    {
        return $this->morphTo();
    }

    /**
     * @return mixed
     */
    public function sudopay_transaction_logs()
    {
        return $this->morphOne(\Plugins\Sudopays\Model\SudopayTransactionLog::class, 'sudopay_transaction_logable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item_user_status()
    {
        return $this->belongsTo(VehicleRentalStatus::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vehicle_coupon()
    {
        return $this->belongsTo(\Plugins\VehicleCoupons\Model\VehicleCoupon::class);
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
    public function message()
    {
        return $this->morphMany(\App\Message::class, 'messageable');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function item_user_dispute()
    {
        return $this->morphOne(\Plugins\VehicleDisputes\Model\VehicleDispute::class, 'item_user_disputable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function paypal_transaction_log()
    {
        return $this->morphOne(\Plugins\Paypal\Model\PaypalTransactionLog::class, 'paypal_transaction_logable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function wallet_transaction_log()
    {
        return $this->morphOne(\App\WalletTransactionLog::class, 'wallet_transaction_logable');
    }

    /**
     * @return mixed
     */
    public function pickup_counter_location()
    {
        return $this->belongsTo(CounterLocation::class, 'pickup_counter_location_id');
    }

    /**
     * @return mixed
     */
    public function drop_counter_location()
    {
        return $this->belongsTo(CounterLocation::class, 'drop_counter_location_id');
    }

// todo: vehicle_rental_additional_chargable need to fix relation
    public function vehicle_rental_additional_chargable()
    {
        return $this->hasMany(VehicleRentalAdditionalCharge::class, 'item_user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function late_payment_detail()
    {
        return $this->hasOne(VehicleRentalLatePaymentDetail::class, 'item_user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function booker_detail()
    {
        return $this->hasOne(VehicleRentalBookerDetail::class, 'item_user_id');
    }

    public function vehicle_feedback()
    {
        return $this->hasMany(VehicleFeedback::class, 'item_user_id');
    }

    public function unavailable_vehicle()
    {
        return $this->hasOne(UnavailableVehicle::class, 'item_user_id');
    }

    /**
     * @param $query
     * @param Request $request
     * @param string $status
     * @return mixed
     */
    public function scopeFilterByRequest($query, Request $request, $status = '')
    {
        $query->orderBy($request->input('sort', 'id'), $request->input('sortby', 'desc'));
        if ($request->has('q')) {
            // vehicle search
            // polymoric relation q search unable to do it.
            // user search
            $query->WhereHas('user', function ($q) use ($request) {
                $q->where('username', 'like', '%' . $request->input('q') . '%');
            });
            $query->orWhereHas('item_user_status', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('q') . '%');
            });
            $query->orWhere('item_userable.name', 'LIKE', '%' . $request->input('q') . '%');
        }
        if ($request->has('start_date')) {
            $query->where('item_booking_start_date', '>=', $request->input('start_date'));
        }
        if ($request->has('end_date')) {
            $query->where('item_booking_end_date', '<=', $request->input('end_date'));
        }
        if ($request->input('filter') === 'payment_pending') {
            $query->where('item_user_status_id', '=', config('constants.ConstItemUserStatus.PaymentPending'));
        } else if ($request->input('filter') === 'waiting_for_acceptance') {
            $query->where('item_user_status_id', '=', config('constants.ConstItemUserStatus.WaitingForAcceptance'));
        } else if ($request->input('filter') === 'rejected') {
            $query->where('item_user_status_id', '=', config('constants.ConstItemUserStatus.Rejected'));
        } else if ($request->input('filter') === 'cancelled') {
            $query->where('item_user_status_id', '=', config('constants.ConstItemUserStatus.Cancelled'));
        } else if ($request->input('filter') === 'expired') {
            $query->where('item_user_status_id', '=', config('constants.ConstItemUserStatus.Expired'));
        } else if ($request->input('filter') === 'confirmed') {
            $query->where('item_user_status_id', '=', config('constants.ConstItemUserStatus.Confirmed'));
        } else if ($request->input('filter') === 'waiting_for_review') {
            $query->where('item_user_status_id', '=', config('constants.ConstItemUserStatus.WaitingForReview'));
        } else if ($request->input('filter') === 'completed') {
            $query->where('item_user_status_id', '=', config('constants.ConstItemUserStatus.Completed'));
        }
        if (!empty($status)) {
            $query->whereIn('item_user_status_id', $status);
        }
        if (empty($status) && $request->has('item_user_status_id')) {
            $query->where('item_user_status_id', '=', $request->item_user_status_id);
        }
        // filter records for particular vehicle
        if ($request->has('vehicle_id')) {
            $query->where('item_userable_id', '=', $request->vehicle_id);
        }

        // filter records for calendar page
        if ($request->has('cal_start_date') && $request->has('cal_end_date')) {
            $request->cal_start_date = date("Y-m-d H:i:s", strtotime($request->cal_start_date));
            $request->cal_end_date = date("Y-m-d H:i:s", strtotime($request->cal_end_date));
            $query->where(function ($query) use ($request) {
                $query->whereBetween('item_booking_start_date', [$request->cal_start_date, $request->cal_end_date])
                    ->orWhereBetween('item_booking_end_date', [$request->cal_start_date, $request->cal_end_date])
                    ->orwhere(function ($query) use ($request) {
                        $query->where('item_booking_start_date', '>', $request->cal_start_date)
                            ->where('item_booking_end_date', '<', $request->cal_start_date);
                    })->orwhere(function ($query) use ($request) {
                        $query->where('item_booking_start_date', '<', $request->cal_end_date)
                            ->where('item_booking_end_date', '>', $request->cal_end_date);
                    });
            });
        }
        return $query;
    }

    public function scopeFilterByVehicleRental($query, $is_user_check = true)
    {
        $user = Auth::user();
        $query->where('item_userable_type', 'MorphVehicle');
        if ($is_user_check) {
			$query->where('user_id', $user->id);
        }
        return $query;
    }

    public function scopeFilterByBooking($query, $request) {
        if($request->has('type') && $request->type == 'booking' && $request->has('vehicle_id')) {
            $query->where('item_userable_id', $request->vehicle_id)->where('item_userable_type', 'MorphVehicle');
        }
        return $query;
    }

    public function scopeFilterByStatus($query, $id, $status_id)
    {
        $current_date = Carbon::now()->toDateTimeString();
        $query->where('id', '=', $id);
        $query->where('item_user_status_id', '=', $status_id);
        if ($status_id == config('constants.ConstItemUserStatus.Confirmed')) {
            $query->where('item_booking_start_date', '<=', $current_date);
        }
        return $query;
    }

    /**
     * @return array
     */
    public function scopeGetValidationRule()
    {
        return [
            'vehicle_id' => 'sometimes|required|integer',
            'item_booking_start_date' => 'required|date|date_format:Y-m-d H:i:s',
            'item_booking_end_date' => 'required|date|date_format:Y-m-d H:i:s',
            'pickup_counter_location_id' => 'required|integer',
            'drop_counter_location_id' => 'required|integer',
            'quantity' => 'sometimes|integer',
            'coupon_code' => 'sometimes|required'
        ];
    }

    public function scopeGetValidationMessage()
    {
        return [
            'vehicle_id.required' => 'Required',
            'vehicle_id.integer' => 'Vehicle id must be a number!',
            'item_booking_start_date.required' => 'Required',
            'item_booking_start_date.date' => 'Start date should be a date!',
            'item_booking_start_date.date_format' => 'Date should be in Y-m-d H:i:s format',
            'item_booking_end_date.required' => 'Required',
            'item_booking_end_date.date' => 'Start date should be a date!',
            'item_booking_end_date.date_format' => 'Date should be in Y-m-d H:i:s format',
            'pickup_counter_location_id.required' => 'Required',
            'pickup_counter_location_id.integer' => 'pickup location must be a number!',
            'drop_counter_location_id.required' => 'Required',
            'drop_counter_location_id.integer' => 'drop location must be a number!',
            'coupon_code.required' => 'Required',
            'quantity.required' => 'Required',
            'quantity.integer' => 'Quantity must be a number',
        ];
    }

}
