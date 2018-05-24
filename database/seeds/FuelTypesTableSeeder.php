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
use Plugins\Vehicles\Model\FuelType;
use Illuminate\Support\Facades\Hash;

class FuelTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FuelType::create([
            'id' => 1,
            'name' => 'Petrol'
        ]);
        FuelType::create([
            'id' => 2,
            'name' => 'Diesel'
        ]);
		FuelType::create([
            'id' => 3,
            'name' => 'CNG'
        ]);
		FuelType::create([
            'id' => 4,
            'name' => 'LPG'
        ]);
		FuelType::create([
            'id' => 5,
            'name' => 'Electric'
        ]);
    }
}