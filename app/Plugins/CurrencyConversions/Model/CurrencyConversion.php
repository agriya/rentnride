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
 
namespace Plugins\CurrencyConversions\Model;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use App\Currency;

class CurrencyConversion extends Model
{
    /**
     * @var string
     */
    protected $table = "currency_conversions";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'currency_id', 'converted_currency_id', 'rate'
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function converted_currency()
    {
        return $this->belongsTo(Currency::class, 'converted_currency_id');
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
            $query->orWhereHas('Currency', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('q') . '%');
            });
            $query->orWhereHas('ConvertedCurrency', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('q') . '%');
            });
        }
        if ($request->has('from_currency')) {
            $query->where('currency_id', '=', $request->input('from_currency'));
        }
        if ($request->has('to_currency')) {
            $query->where('converted_currency_id', '=', $request->input('to_currency'));
        }
        return $query;
    }

    /**
     * @return array
     */
    public function scopeGetValidationRule()
    {
        return [
            'currency_id' => 'required|integer|exists:currencies,id',
            'converted_currency_id' => 'required|integer|exists:currencies,id',
            'rate' => 'required|numeric'
        ];
    }

    public function scopeGetValidationMessage()
    {
        return [
            'currency_id.required' => 'Required',
            'currency_id.integer' => 'Currency id must be a number!',
            'currency_id.exists' => 'Invalid currency id',
            'converted_currency_id.required' => 'Required',
            'converted_currency_id.integer' => 'Converted currency id must be a number!',
            'converted_currency_id.exists' => 'Invalid converted currency id',
            'rate.required' => 'Required',
            'rate.numeric' => 'Rate must be a number!'
        ];
    }
}
