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
use Plugins\Vehicles\Model\VehicleType;
use Illuminate\Support\Facades\Hash;

class VehicleTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VehicleType::create([
            'name' => 'Luxury',
            'slug' => 'luxury',
            'minimum_hour_price' => '50',
            'maximum_hour_price' => '200',
            'minimum_day_price' => '1000',
            'maximum_day_price' => '5000',
            'drop_location_differ_unit_price' => '100',
            'drop_location_differ_additional_fee' => '100',
            'deposit_amount' => '1000',
            'is_active' => '1'
        ]);
        VehicleType::create([
            'name' => 'SUV',
            'slug' => 'suv',
            'minimum_hour_price' => '40',
            'maximum_hour_price' => '150',
            'minimum_day_price' => '500',
            'maximum_day_price' => '1500',
            'drop_location_differ_unit_price' => '50',
            'drop_location_differ_additional_fee' => '100',
            'deposit_amount' => '500',
            'is_active' => '1'
        ]);
        VehicleType::create([
            'name' => 'Sedan',
            'slug' => 'sedan',
            'minimum_hour_price' => '50',
            'maximum_hour_price' => '150',
            'minimum_day_price' => '750',
            'maximum_day_price' => '2000',
            'drop_location_differ_unit_price' => '75',
            'drop_location_differ_additional_fee' => '80',
            'deposit_amount' => '700',
            'is_active' => '1'
        ]);
        VehicleType::create([
            'name' => 'Mini',
            'slug' => 'mini',
            'minimum_hour_price' => '30',
            'maximum_hour_price' => '100',
            'minimum_day_price' => '300',
            'maximum_day_price' => '1200',
            'drop_location_differ_unit_price' => '50',
            'drop_location_differ_additional_fee' => '50',
            'deposit_amount' => '100',
            'is_active' => '1'
        ]);
    }
}