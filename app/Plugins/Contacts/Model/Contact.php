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
 
namespace Plugins\Contacts\Model;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Ip;

class Contact extends Model
{
    /**
     * @var string
     */
    protected $table = "contacts";

    protected $fillable = [
        'first_name', 'last_name', 'subject', 'message', 'telephone', 'email', 'user_id', 'ip_id'
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ip()
    {
        return $this->belongsTo(Ip::class);
    }

    /**
     * @param         $query
     * @param Request $request
     * @return mixed
     */
    public function scopeFilterByRequest($query, Request $request)
    {	
        if($request->has('sort') && $request->sort == 'user_type'){			
			$query->orderBy('user_id', $request->input('sortby', 'desc'));
		} else if($request->has('sort') && $request->sort == 'ip.ip'){			
			$query->orderBy('ip_id', $request->input('sortby', 'desc'));
		} else {
			$query->orderBy($request->input('sort', 'id'), $request->input('sortby', 'asc'));
		}
		
        if ($request->has('q')) {
            $query->where('first_name', 'LIKE', '%' . $request->input('q') . '%')
                ->orWhere('last_name', 'LIKE', '%' . $request->input('q') . '%')
                ->orWhere('email', 'LIKE', '%' . $request->input('q') . '%')
                ->orWhere('subject', 'LIKE', '%' . $request->input('q') . '%')
                ->orWhere('message', 'LIKE', '%' . $request->input('q') . '%');
        }
        return $query;
    }

    /**
     * @return array
     */
    public function scopeGetValidationRule()
    {
        return [
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
            'user_id' => 'integer',
            'email' => 'required|email',
            'subject' => 'required|min:5',
            'message' => 'required|min:10',
            'telephone' => 'required|min:10'
        ];
    }

    /**
     * @return array
     */
    public function scopeGetValidationMessage()
    {
        return [
            'first_name.required' => 'Required',
            'first_name.min' => 'first_name - Minimum length is 2!',
            'last_name.required' => 'Required',
            'last_name.min' => 'last_name - Minimum length is 2!',
            'user_id.integer' => 'User id must be a number',
            'email.required' => 'Required',
            'email.email' => 'Enter valid e-mail address!',
            'subject.required' => 'Required',
            'subject.min' => 'subject - Minimum length is 5',
            'message.required' => 'Required',
            'message.min' => 'message -Minimum length is 10',
            'telephone.required' => 'Required',
            'telephone.min' => 'telephone - Minimum length is 10'
        ];
    }

}
