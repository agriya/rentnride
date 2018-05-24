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
use Plugins\VehicleExtraAccessories\Model\VehicleExtraAccessory;
use Illuminate\Support\Facades\Hash;

class ExtraAccessoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VehicleExtraAccessory::create([
            'id' => 1,
            'name' => 'Invoicing by mail',
            'short_description' => 'Invoicing by mail',
            'description' => 'Invoice will be send to your mail with rental details.',
            'is_active' => 1
        ]);
        VehicleExtraAccessory::create([
            'id' => 2,
            'name' => 'Skierized Equipment',
            'short_description' => 'Skierized Equipment',
            'description' => 'Skierized vehicles include a ski rack (holding up to 4 pairs of skis), ice scraper and all season tires, if a city offers tires other than all season, it will be listed in the cities ski information. ',
            'is_active' => 1
        ]);
        VehicleExtraAccessory::create([
            'id' => 3,
            'name' => 'GPS Built-In',
            'short_description' => 'GPS Built-In',
            'description' => 'i) Deposit required to built GPS.
ii) Deposit amount will be refunded on return of vehicle.
iii) BN 350.00 (Per Day BN10.00 Maximum per rental: BN300.00. Liability for Loss or damage of the GPS Unit: BN 350.00)
',
            'is_active' => 1
        ]);
        VehicleExtraAccessory::create([
            'id' => 4,
            'name' => 'Satellite Radio',
            'short_description' => 'Satellite Radio',
            'description' => 'i) Whether you are traveling for business or pleasure rental cars equipped with Satellite Radio will make your drive come alive. 
ii) You can enjoy over 150 channels, including commercial-free music plus the best sports, news, talk, comedy, entertainment, and a collection of multi-language programming. ',
            'is_active' => 1
        ]);
        VehicleExtraAccessory::create([
            'id' => 5,
            'name' => 'Child seats',
            'short_description' => 'Child seats',
            'description' => 'It is important that your child has the safest journey possible, we offers a range of child safety seats that are suitable for babies and children.',
            'is_active' => 1
        ]);
        VehicleExtraAccessory::create([
            'id' => 6,
            'name' => 'Services for physically challenged',
            'short_description' => 'Services for physically challenged',
            'description' => 'i) A full range of special services for physically challenged renters are available to both the customer and any member of the traveling party.
ii) Hand Controls / Spinner Knob / Visually Impaired Renters / Scooter / Wheelchair Storage.',
            'is_active' => 1
        ]);
        VehicleExtraAccessory::create([
            'id' => 7,
            'name' => 'Additional driver',
            'short_description' => 'Additional driver',
            'description' => 'i) you can share the driving responsibilities with other friends and family members in your top quality rental car. 
ii) Opting for additional driver insurance will allow for more flexibility in your travel experience',
            'is_active' => 1
        ]);
    }
}