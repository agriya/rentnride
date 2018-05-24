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
 
namespace Plugins\VehicleDisputes\Model;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

use JWTAuth;
use Illuminate\Support\Facades\Auth;

/**
 * Class VehicleDispute
 * @package App
 */
class VehicleDispute extends Model
{
    /**
     * @var string
     */
    protected $table = "item_user_disputes";
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'user_id', 'item_user_disputable_id', 'item_user_disputable_type', 'dispute_type_id', 'dispute_status_id', 'last_replied_user_id', 'dispute_closed_type_id', 'is_favor_booker', 'is_booker', 'last_replied_date', 'resolved_date', 'dispute_conversation_count', 'reason', 'model_type'
    ];

    /**
     * Get all of the owning likeable models.
     */
    public function item_user_disputable()
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dispute_type()
    {
        return $this->belongsTo(\Plugins\VehicleDisputes\Model\VehicleDisputeType::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dispute_status()
    {
        return $this->belongsTo(\Plugins\VehicleDisputes\Model\VehicleDisputeStatus::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dispute_closed_type()
    {
        return $this->belongsTo(\Plugins\VehicleDisputes\Model\VehicleDisputeClosedType::class);
    }

    /**
     * @param         $query
     * @param Request $request
     * @return mixed
     */
    public function scopeFilterByRequest($query, Request $request)
    {
        $query->orderBy($request->input('sort', 'id'), $request->input('sortby', 'desc'));
        if ($request->has('q')) {
            $query->where('reason', 'like', '%' . $request->input('q') . '%');
            $query->orWhereHas('user', function ($q) use ($request) {
                $q->where('username', 'like', '%' . $request->input('q') . '%');
            });
            $query->orWhereHas('dispute_type', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('q') . '%');
            });
            $query->orWhereHas('dispute_status', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('q') . '%');
            });
        }
        if ($request->input('filter') === 'Open') {
            $query->where('dispute_status_id', '=', config('constants.ConstDisputeStatuses.Open'));
        } else if ($request->input('filter') === 'Under Discussion') {
            $query->where('dispute_status_id', '=', config('constants.ConstDisputeStatuses.UnderDiscussion'));
        } else if ($request->input('filter') === 'Waiting Administrator Decision') {
            $query->where('dispute_status_id', '=', config('constants.ConstDisputeStatuses.WaitingAdministratorDecision'));
        } else if ($request->input('filter') === 'Closed') {
            $query->where('dispute_status_id', '=', config('constants.ConstDisputeStatuses.Closed'));
        }

        // if not admin user can view their own disputes only
        $user = Auth::user();
        if ($user->role_id != config('constants.ConstUserTypes.Admin')) {
            $query->where('user_id', $user->id);
        }

        //$query->where('item_user_disputable_type', 'MorphVehicleRental');

        return $query;
    }

    /**
     * @param $query
     * @return mixed
     */

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
            'item_user_id' => 'required|integer',
            'dispute_type_id' => 'sometimes|required|integer',
            'dispute_closed_type_id' => 'sometimes|required|integer'
        ];
    }

    /**
     * @return array
     */
    public function scopeGetValidationMessage()
    {
        return [
            'item_user_id.required' => 'Required',
            'item_user_id.integer' => 'Item id must be a number!',
            'dispute_type_id.required' => 'Required',
            'dispute_type_id.integer' => 'Dispute Type Id must be a number!',
            'dispute_closed_type_id.required' => 'Required',
            'dispute_closed_type_id.integer' => 'Dispute Type Id must be a number!'
        ];
    }

}
