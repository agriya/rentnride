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

class EmailTemplate extends Model
{
    protected $table = "email_templates";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'subject', 'body_content', 'from_name', 'reply_to'
    ];


    /**
     * @param         $query
     * @param Request $request
     * @return mixed
     */
    public function scopeFilterByRequest($query, Request $request)
    {
        $query->orderBy($request->input('sort', 'id'), $request->input('sortby', 'desc'));
        if ($request->has('q')) {
            $query->where('name', 'like', '%' . $request->input('q') . '%');
            $query->orWhere('body_content', 'like', '%' . $request->input('q') . '%');
            $query->orWhere('subject', 'like', '%' . $request->input('q') . '%');
            $query->orWhere('from_name', 'like', '%' . $request->input('q') . '%');
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
            'subject' => 'required',
            'body_content' => 'required',
            'from_name' => 'required'
        ];
    }

    public function scopeGetValidationMessage()
    {
        return [
            'name.required' => 'Required',
            'subject.required' => 'Required',
            'body_content.required' => 'Required',
            'from_name.required' => 'Required',
        ];
    }

}
