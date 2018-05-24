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
 
namespace Plugins\VehicleFeedbacks\Model;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

use App\User;
use App\Ip;

class VehicleFeedback extends Model
{
    /**
     * @var string
     */
    protected $table = "feedbacks";

    protected $fillable = [
        'user_id', 'to_user_id', 'is_host', 'feedback', 'ip_id', 'rating', 'item_user_id'
    ];

    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ip()
    {
        return $this->belongsTo(Ip::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function feedbackable()
    {
        return $this->morphTo();
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function vehicle_rental(){
        return $this->belongsTo(\Plugins\VehicleRentals\Model\VehicleRental::class, 'item_user_id');
    }

    public function to_user(){
        return $this->belongsTo(User::class, 'to_user_id');
    }

    /**
     * @param $query
     * @param Request $request
     * @return mixed
     */
    public function scopeFilterByRequest($query, Request $request)
    {
        $query->orderBy($request->input('sort', 'id'), $request->input('sortby', 'desc'));
        if ($request->has('q')) {
				$query->where('feedback', 'like', '%' . $request->input('q') . '%');
				$query->orWhere('user.username', 'like', '%' . $request->input('q') . '%');
				$query->orWhere('to_user.username', 'like', '%' . $request->input('q') . '%');
				$query->orWhere('feedbackable.name', 'like', '%' . $request->input('q') . '%');
        }
        if ($request->input('filter') === 'feedback_to_host') {
				$query->where('is_host', '=', false);
        } else if ($request->input('filter') === 'feedback_to_booker') {
				$query->where('is_host', '=', true);
        }
        if($request->has('user_id')){
            $query->where('feedbackable_type', 'MorphUser');
            $query->where('feedbackable_id', $request->user_id);
        }
        if($request->has('to_user_id')){
            $query->where('to_user_id', $request->to_user_id);
        }
        if($request->has('vehicle_id')){
            $query->where('feedbackable_type', 'MorphVehicle');
            $query->where('feedbackable_id', $request->vehicle_id);
        }
        if($request->has('type') && $request->type == 'vehicle') {
            $query->where('feedbackable_type', 'MorphVehicle');
        }
        return $query;
    }

    /**
     * @return array
     */
    public function scopeGetValidationRule()
    {
        return [
            'feedback' => 'sometimes|required|string'
        ];
    }

    public function scopeGetValidationMessage()
    {
        return [
            'feedback.required' => 'Required',
            'feedback.string' => 'Feedback must be a string',
        ];
    }
}
