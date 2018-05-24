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
use Plugins\Vehicles\Model\VehicleCompany;
use Illuminate\Support\Facades\Hash;

class VehicleCompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VehicleCompany::create([
            'user_id' => '2',
            'name' => 'Agriya travels',
            'slug' => 'agriya-travels',
            'address' => 'New no 61, Plot no 39, Second Street, Kamdar Nagar, Mahalingapuram, Nungambakkam, Kamdar Nagar, Nungambakkam, Chennai, Tamil Nadu 600034, India',
            'latitude' => '13.055106',
            'longitude' => '80.23626300000001',
            'fax' => '04456856856',
            'phone' => '2564856856',
            'mobile' => '865865865956',
            'email' => 'agriya@gmail.com',
            'vehicle_count' => '2',
            'is_active' => '1'
        ]);
        VehicleCompany::create([
            'user_id' => '1',
            'name' => 'Ahsan travels',
            'slug' => 'ahsan-travels',
            'address' => 'Mahalingapuram, Nungambakkam, Chennai, Tamil Nadu 600034, India',
            'latitude' => '13.0572703',
            'longitude' => '80.23422260000007',
            'fax' => '0546568566585',
            'phone' => '85685458658',
            'mobile' => '85685568556',
            'email' => 'ahsan@gmail.com',
            'vehicle_count' => '2',
            'is_active' => '1'
        ]);
    }
}