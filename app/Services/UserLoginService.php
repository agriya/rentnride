<?php
/**
 * Rent & Ride
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
 
namespace App\Services;

use App\UserLogin;
use App\User;
use Carbon;

class UserLoginService
{

    /**
     * Save record to user_login table after login
     * @param $request
     * @param $ip_id
     * @return void
     */
    public function saveUserLogin($request, $ip_id)
    {
        $user_details = User::where('email', '=', $request->email)->first();
        $user_details['last_login_ip_id'] = $ip_id;
        $user_details->save();
        $user_login = new UserLogin;
        $user_login->user_id = $user_details->id;
        $user_login->role_id = $user_details->role_id;
        $user_login->user_agent = $request->header('User-Agent');
        $user_login->user_login_ip_id = $ip_id;
        $user_login->save();
        // update login count in users table
        $user_details->increment('user_login_count', 1);
        return $user_details->role_id;
    }

    /**
     * get last logged record for admin dashboard
     * @param $request
     * @return UserLogin created_at
     */
    public function getLastLogin()
    {
        $user_login_details = UserLogin::select('created_at')->orderBy('created_at', 'desc')->first();
        if($user_login_details)
            return $user_login_details->created_at->diffForHumans();
        else
            return '-';
    }

    /**
     * decrease user_login_count when delete user_login record
     * @param user_id
     * @return void
     */
    public function decreaseUserLoginCount($user_id){
        // update login count in users table
        User::where('id', '=', $user_id)->decrement('user_login_count', 1);
    }
}
