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

class CurrencyConversionHistory extends Model
{
    /**
     * @var string
     */
    protected $table = "currency_conversion_histories";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'currency_conversion_id', 'rate_before_change', 'rate'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currency_conversion()
    {
        return $this->belongsTo(CurrencyConversion::class);
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
            $query->orWhereHas('currency_conversion.Currency', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('q') . '%');
            });
            $query->orWhereHas('currency_conversion.ConvertedCurrency', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('q') . '%');
            });
        }
        if ($request->has('start_date')) {
            $start_date = date("Y-m-d H:i:s", strtotime($request->input('start_date')));
            $query->where('created_at', '>=', $start_date);
        }
        if ($request->has('end_date')) {
            $end_date = date("Y-m-d H:i:s", strtotime($request->input('end_date')));
            $query->where('created_at', '<=', $end_date);
        }
        if ($request->has('from_currency')) {
            $query->orWhereHas('currency_conversion', function ($q) use ($request) {
                $q->where('currency_id', '=', $request->input('from_currency'));
            });
        }
        if ($request->has('to_currency')) {
            $query->orWhereHas('currency_conversion', function ($q) use ($request) {
                $q->where('converted_currency_id', '=', $request->input('to_currency'));
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
            'currency_conversion_id' => 'required|integer|exists:currency_conversions,id',
            'rate_before_change' => 'required|numeric',
            'rate' => 'required|numeric'
        ];
    }

    public function scopeGetValidationMessage()
    {
        return [
            'currency_conversion_id.required' => 'Required',
            'currency_conversion_id.integer' => 'Currency conversion id must be a number',
            'currency_conversion_id.exists' => 'Invalid currency conversion id',
            'rate_before_change.required' => 'Required',
            'rate_before_change.numeric' => 'rate_before_change must be a number',
            'rate.required' => 'Required',
            'rate.numeric' => 'Rate must be a number!',
        ];
    }
}
