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
use Plugins\Vehicles\Model\CounterLocation;
use Illuminate\Support\Facades\Hash;

class CounterLocationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CounterLocation::create([
            'address' => 'Chennai International Airport, GST Rd, Meenambakkam, Chennai, Tamil Nadu 600027, India',
            'latitude' => '12.994112',
            'longitude' => '80.170867',
            'fax' => '04485965865',
            'phone' => '04458659856',
            'mobile' => '5468648548',
            'email' => 'counterlocation1@gmail.com'
        ]);
        CounterLocation::create([
            'address' => 'Madurai International Airport, Madurai, Tamil Nadu 625022, India',
            'latitude' => '9.838590',
            'longitude' => '78.089522',
            'fax' => '045258658659',
            'phone' => '045258658659',
            'mobile' => '5468648552',
            'email' => 'counterlocation2@gmail.com'
        ]);
        CounterLocation::create([
            'address' => 'Mumbai International Airport, Andheri Kurla Road, Opp. H K Studio, Safed Pul, Sakinaka, Safed Pul, Sakinaka, Mumbai, Maharashtra 400072, India',
            'latitude' => '19.097002',
            'longitude' => '0228525658356',
            'fax' => '0228525658356',
            'phone' => '0228525658356',
            'mobile' => '5468648548',
            'email' => 'counterlocation3@gmail.com'
        ]);
        CounterLocation::create([
            'address' => 'Bangalore International Airport, Devanahalli, Bengaluru, Karnataka 560300, India',
            'latitude' => '13.198633',
            'longitude' => '77.706593',
            'fax' => '0802568526886',
            'phone' => '0802568526886',
            'mobile' => '5468648549',
            'email' => 'counterlocation4@gmail.com'
        ]);
        CounterLocation::create([
            'address' => 'Hyderabad International Airport, Shamshabad, Hyderabad, Telangana 500409, India',
            'latitude' => '17.240263',
            'longitude' => '78.429385',
            'fax' => '056585685568',
            'phone' => '056585685568',
            'mobile' => '5468648550',
            'email' => 'counterlocation5@gmail.com'
        ]);
        CounterLocation::create([
            'address' => 'Indira Gandhi International Airport, New Delhi, New Delhi 110037, India',
            'latitude' => '28.556162',
            'longitude' => '77.099958',
            'fax' => '011565568655',
            'phone' => '011565568655',
            'mobile' => '5468648553',
            'email' => 'counterlocation6@gmail.com'
        ]);
        CounterLocation::create([
            'address' => 'Netaji Subhas Chandra Bose International Airport, Dum Dum, Kolkata, West Bengal 700052, India',
            'latitude' => '22.642664',
            'longitude' => '88.439122',
            'fax' => '05235865866',
            'phone' => '05235865866',
            'mobile' => '5468648555',
            'email' => 'counterlocation7@gmail.com'
        ]);
    }
}