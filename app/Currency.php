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

class Currency extends Model
{
    protected $table = "currencies";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'code', 'symbol', 'prefix', 'suffix', 'decimals', 'dec_point', 'thousands_sep', 'is_prefix_display_on_left', 'is_use_graphic_symbol', 'is_active'
    ];

    /**
     * @param         $query
     * @param Request $request
     * @return mixed
     */
    public function scopeFilterByRequest($query, Request $request)
    {
        $query->orderBy($request->input('sort', 'id'), $request->input('sortby', 'desc'));
        if ($request->has('filter')) {
            $filter = false;
            if ($request->input('filter') == 'active') {
                $filter = true;
            }
            $query->where('is_active', '=', $filter);
        }
        if ($request->has('q')) {
            $query->where('name', 'LIKE', '%' . $request->input('q') . '%')
                ->orWhere('code', 'LIKE', '%' . $request->input('q') . '%');
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
            'code' => 'required|min:2',
            'symbol' => 'required',
            'decimals' => 'required|integer',
            'dec_point' => 'required|max:1',
            'thousands_sep' => 'required|max:1',
            'is_prefix_display_on_left' => 'required|integer|max:1',
            'is_use_graphic_symbol' => 'required|integer|max:1'
        ];
    }

    public function scopeGetValidationMessage()
    {
        return [
            'name.required' => 'Required',
            'name.min' => 'Name - minimum length is 2',
            'code.required' => 'Required',
            'code.min' => 'Code - minimum length is 2',
            'symbol.required' => 'Required',
            'decimals.required' => 'Required',
            'decimals.integer' => 'Decimals must be a number',
            'dec_point.required' => 'Required',
            'dec_point.max' => 'dec_point - maximum length is 1',
            'thousands_sep.required' => 'Required',
            'thousands_sep.max' => 'thousand_sep - maximum length is 1',
            'is_prefix_display_on_left.required' => 'Required',
            'is_prefix_display_on_left.integer' => 'is_prefix_display_on_left must be a number',
            'is_prefix_display_on_left.max' => 'is_prefix_display_on_left - maximum length is 1',
            'is_use_graphic_symbol.required' => 'Required',
            'is_use_graphic_symbol.integer' => 'is_use_graphic_symbol must be a number',
            'is_use_graphic_symbol.max' => 'is_use_graphic_symbol - maximum length is 1',
        ];
    }
}
