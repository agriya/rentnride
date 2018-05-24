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
 * Class Setting
 * @package App
 */
class Setting extends Model
{
    /**
     * @var string
     */
    protected $table = "settings";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'value'
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function setting_category()
    {
        return $this->belongsTo(SettingCategory::class);
    }


    /**
     * @return array
     */
    public function scopeGetValidationRule()
    {
        return [
            'value' => 'required'
        ];
    }

    public function scopeGetValidationMessage()
    {
        return [
            'value.required' => 'Required',
        ];
    }

    /**
     * @param         $query
     * @param Request $request
     * @return mixed
     */
    public function scopeFilterByRequest($query, Request $request)
    {
        $query->orderBy($request->input('sort', 'display_order'), $request->input('sortby', 'ASC'));
        if ($request->has('q')) {
            $query->where('name', 'LIKE', '%' . $request->input('q') . '%');
        }
        if ($request->has('setting_category_id')) {
            $query->where('setting_category_id', '=', $request->input('setting_category_id'));
        }
        return $query;
    }

}
