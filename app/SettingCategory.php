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

class SettingCategory extends Model
{
    protected $table = "setting_categories";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'display_order'
    ];


    /**
     * @param         $query
     * @param Request $request
     * @return mixed
     */
    public function scopeFilterByRequest($query, Request $request, $notInCategories = array())
    {
        $query->orderBy($request->input('sort', 'name'), $request->input('sortby', 'ASC'));
        if(!empty($notInCategories)) {
            $query->whereNotIn('id', $notInCategories);
        }
        return $query;
    }

    /**
     * @return array
     */
    public function scopeGetValidationRule()
    {
        return [
            'name' => 'required',
            'description' => 'required',
            'display_order' => 'required'
        ];
    }

    /**
     * @return array
     */
    public function scopeGetValidationMessage()
    {
        return [
            'name.required' => 'Required',
            'description.required' => 'Required',
            'display_order.required' => 'Required',
        ];
    }
}
