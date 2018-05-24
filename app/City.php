<?php
/**
 * APP
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
 
namespace App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    /**
     * @var string
     */
    protected $table = "cities";

    protected $fillable = [
        'name', 'state_id', 'country_id', 'latitude', 'longitude', 'is_active'
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function state()
    {
        return $this->belongsTo(State::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ip()
    {
        return $this->hasMany(Ip::class);
    }

    /**
     * @param         $query
     * @param Request $request
     * @return mixed
     */
    public function scopeFilterByRequest($query, Request $request)
    {
        if ($request->input('sort', 'id') == 'State.name') {
            $query->orderBy('state_name', $request->input('sortby', 'desc'));
        } elseif ($request->input('sort', 'id') == 'Country.name') {
            $query->orderBy('country_name', $request->input('sortby', 'desc'));
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
            $query->where('name', 'LIKE', '%' . $request->input('q') . '%');
            $query->orWhereHas('State', function ($q) use ($request) {
                $q->Where('name', 'like', '%' . $request->input('q') . '%');
            });
            $query->orWhereHas('Country', function ($q) use ($request) {
                $q->Where('name', 'like', '%' . $request->input('q') . '%');
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
            'name' => 'required|min:2',
            'state_id' => 'required|integer|exists:states,id',
            'country_id' => 'required|integer|exists:countries,id'
        ];
    }

    public function scopeGetValidationMessage()
    {
        return [
            'name.required' => 'Required',
            'name.min' => 'Name - minimum length is 2!',
            'state_id.required' => 'Required',
            'state_id.integer' => 'State id must be a number!',
            'state_id.exists' => 'Invalid state id',
            'country_id.required' => 'Required',
            'country_id.integer' => 'Country id must be a number!',
            'country_id.exists' => 'Invalid country id',
        ];
    }

}
