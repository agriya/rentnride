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
use Plugins\VehicleFuelOptions\Model\VehicleFuelOption;
use Illuminate\Support\Facades\Hash;

class FuelOptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VehicleFuelOption::create([
            'id' => 1,
            'name' => 'Fuel Purchase Option (FPO)',
            'short_description' => 'Fuel Purchase Option (FPO)',
            'description' => 'Prepay the fuel in the tank at competitive local prices. Return at any level. NO REFUND FOR UNUSED FUEL, except for rentals of 75 miles or less.',
            'is_active' => 1
        ]);
        VehicleFuelOption::create([
            'id' => 2,
            'name' => 'Express Fuel',
            'short_description' => 'Express Fuel',
            'description' => 'Drive a total of 75 miles or less for a service and fueling convenience fee of USD 13.99.',
            'is_active' => 1
        ]);
        VehicleFuelOption::create([
            'id' => 3,
            'name' => 'Fuel and Service Charge (FSC)',
            'short_description' => 'Fuel and Service Charge (FSC)',
            'description' => 'If you return with less fuel than at the time of rent, and have not chosen the Fuel Purchase Option (FPO) or Express Fuel, we will service and refuel at a per gallon rate.',
            'is_active' => 1
        ]);
        VehicleFuelOption::create([
            'id' => 4,
            'name' => 'You refuel',
            'short_description' => 'You refuel',
            'description' => 'Return with the tank at the same level as when rented. On trips of 75 miles or less, you will need to produce a gas receipt from a station near the return location.',
            'is_active' => 1
        ]);
    }
}