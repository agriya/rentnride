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
use App\Attachment;

class UserProfile extends Model
{
    /**
     * @var string
     */
    protected $table = "user_profiles";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'first_name', 'last_name', 'about_me', 'attachment_id', 'website', 'facebook_profile_link', 'twitter_profile_link', 'google_plus_profile_link', 'linkedin_profile_link', 'youtube_profile_link'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    /**
     * @param         $query
     * @param Request $request
     * @return mixed
     */
    public function scopeFilterByRequest($query, Request $request)
    {
        $query->orderBy($request->input('sort', 'id'), $request->input('sortby', 'desc'));
        return $query;
    }

    public function scopeGetValidationRule()
    {
        return [
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:1'
        ];
    }

    public function scopeGetValidationMessage()
    {
        return [
            'first_name.required' => 'Required',
            'first_name.min' => 'first_name - minimum length is 3',
            'last_name.required' => 'Required',
            'last_name.min' => 'last_name - minimum length is 1',
        ];
    }
}
