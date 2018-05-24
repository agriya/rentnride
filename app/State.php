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

/**
 * Class State
 * @package App
 */
class State extends Model
{
    /**
     * @var string
     */
    protected $table = "states";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'country_id', 'is_active'
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function city()
    {
        return $this->hasMany(City::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }


    /**
     * @param         $query
     * @param Request $request
     * @return mixed
     */
    public function scopeFilterByRequest($query, Request $request)
    {
        if ($request->input('sort', 'id') == 'Country.name') {
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
            $query->orWhereHas('Country', function ($q) use ($request) {
                $q->Where('name', 'like', '%' . $request->input('q') . '%');
            });
        }
        if ($request->has('name')) {
            $query->where('name', 'LIKE', '%' . $request->input('name') . '%');
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
            'country_id' => 'required|integer|exists:countries,id'
        ];
    }

    public function scopeGetValidationMessage()
    {
        return [
            'name.required' => 'Required',
            'name.min' => 'name - minimum length is 2',
            'country_id.required' => 'Required',
            'country_id.integer' => 'Country id must be a number!',
            'country_id.exists' => 'Invalid country id'
        ];
    }
}
