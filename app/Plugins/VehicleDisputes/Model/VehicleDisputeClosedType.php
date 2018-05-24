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

class VehicleDisputeClosedType extends Model
{
    /**
     * @var string
     */
    protected $table = "dispute_closed_types";
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'name', 'dispute_type_id', 'is_booker', 'resolved_type', 'reason'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dispute_type()
    {
        return $this->belongsTo(\Plugins\VehicleDisputes\Model\VehicleDisputeType::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dispute()
    {
        return $this->hasMany(\Plugins\VehicleDisputes\Model\VehicleDispute::class);
    }

    /**
     * @param $query
     * @param Request $request
     * @return mixed
     */
    public function scopeFilterByRequest($query, Request $request)
    {
        $query->orderBy($request->input('sort', 'id'), $request->input('sortby', 'desc'));
		if($request->has('q')){
            $query->where('dispute_closed_types.name', 'like', '%'.$request->q.'%');
            $query->orWhere('dispute_closed_types.resolved_type', 'like', '%'.$request->q.'%');
            $query->orWhere('dispute_closed_types.reason', 'like', '%'.$request->q.'%');
        }
		if ($request->input('filter') === 'booker') {
            $query->where('is_booker', '=', 1);
        } else if ($request->input('filter') === 'host') {
            $query->where('is_booker', '=', 0);
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
            'dispute_type_id' => 'required|integer',
            'is_booker' => 'required|boolean',
            'resolved_type' => 'required|min:5',
            'reason' => 'required|min:5'
        ];
    }

    /**
     * @return array
     */
    public function scopeGetValidationMessage()
    {
        return [
            'name.required' => 'Required',
            'name.min' => 'Name must be min 5 characters!',
            'dispute_type_id.required' => 'Required',
            'dispute_type_id.integer' => 'Enter only number values for dispute type id !',
            'is_booker.required' => 'Required',
            'is_booker.boolean' => 'Accepted input for Is Booker are 1, 0 !',
            'resolved_type.required' => 'Required',
            'resolved_type.min' => 'Name must be min 5 characters!',
            'reason.required' => 'Required',
            'reason.min' => 'Name must be min 5 characters!'
        ];
    }
}
