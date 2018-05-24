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
 
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\User;
use App\UserProfile;
use App\Attachment;
use App\Eloquent;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        $admin_user = User::create([
            'id' => 1,
            'role_id' => 1,
            'username' => 'admin',
            'email' => 'productdemo.admin@gmail.com',
            'password' => Hash::make('agriya'),
            'is_agree_terms_conditions' => 1,
            'is_email_confirmed' => 1,
            'is_active' => 1,
            'register_ip_id' => 1,
            'last_login_ip_id' => 1
        ]);
        $host_user = User::create([
            'id' => 2,
            'role_id' => 2,
            'username' => 'host',
            'email' => 'host@gmail.com',
            'password' => Hash::make('agriya'),
            'is_agree_terms_conditions' => 1,
            'is_email_confirmed' => 1,
            'is_active' => 1,
            'register_ip_id' => 1,
            'last_login_ip_id' => 1
        ]);
        $booker_user = User::create([
            'id' => 3,
            'role_id' => 2,
            'username' => 'booker',
            'email' => 'booker@gmail.com',
            'password' => Hash::make('agriya'),
            'is_agree_terms_conditions' => 1,
            'is_email_confirmed' => 1,
            'is_active' => 1,
            'register_ip_id' => 1,
            'last_login_ip_id' => 1
        ]);
        UserProfile::create([
            'user_id' => $admin_user->id,
            'first_name' => 'admin',
            'last_name' => 'admin',
            'about_me' => 'I am the site admin and will manange all the pages in the site'
        ]);
        UserProfile::create([
            'user_id' => $host_user->id,
            'first_name' => 'alex',
            'last_name' => 'paul',
            'about_me' => 'I am the host and will host the vehicles'
        ]);
        UserProfile::create([
            'user_id' => $booker_user->id,
            'first_name' => 'john',
            'last_name' => 'hastings',
            'about_me' => 'I am the booker and will book the vehicles'
        ]);	
    }
}