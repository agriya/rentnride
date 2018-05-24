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
use Plugins\VehicleTaxes\Model\VehicleTax;
use Illuminate\Support\Facades\Hash;

class TaxesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VehicleTax::create([
            'id' => 1,
            'name' => 'Motor Vehicle Rental Tax',
            'short_description' => 'Motor Vehicle Rental Tax',
            'description' => 'This is for using the motor vehicle for rental',
            'is_active' => 1
        ]);
        VehicleTax::create([
            'id' => 2,
            'name' => 'Sales Tax',
            'short_description' => 'Sales Tax (6.0%)',
            'description' => 'Sales Tax',
            'is_active' => 1
        ]);
        VehicleTax::create([
            'id' => 3,
            'name' => 'Tax for Accessories',
            'short_description' => ' A tax of 6%-18% applies to all accessory charges',
            'description' => 'A tax of 6%-18% applies to all accessory charges.',
            'is_active' => 1
        ]);
        VehicleTax::create([
            'id' => 4,
            'name' => 'Business Tax',
            'short_description' => 'Business Tax',
            'description' => 'When renting a business tax of USD 0.3% per rental agreement applies (Business Tax =G5)',
            'is_active' => 1
        ]);
        VehicleTax::create([
            'id' => 5,
            'name' => 'Motor Vehicle Licensing Tax',
            'short_description' => 'Motor Vehicle Licensing Tax',
            'description' => 'The all rental car companies collect a USD 2.75 Motor Vehicle Licensing Tax. This charge is not included in your approximate rental costs.',
            'is_active' => 1
        ]);
        VehicleTax::create([
            'id' => 6,
            'name' => 'Motor Vehicle Lessor Tax',
            'short_description' => 'Motor Vehicle Lessor Tax',
            'description' => 'The all car rental company collect a USD 6.00 Motor Vehicle Lessor Tax to finance various city projects. This charge is not included in your approximate rental costs.',
            'is_active' => 1
        ]);
    }
}