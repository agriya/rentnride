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
use Plugins\Vehicles\Model\VehicleMake;
use Plugins\Vehicles\Model\VehicleModel;
use Illuminate\Support\Facades\Hash;

class VehicleMakesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bmw = VehicleMake::create([
            'id' => 1,
            'name' => 'BMW',
			'slug' => 'bmw',
			'is_active' => '1'
        ]);
        $toyota = VehicleMake::create([
            'id' => 2,
            'name' => 'TOYOTA',
			'slug' => 'toyota',
			'is_active' => '1'
        ]);
		$hyundai = VehicleMake::create([
            'id' => 3,
            'name' => 'HYUNDAI',
			'slug' => 'hyundai',
			'is_active' => '1'
        ]);
		$honda = VehicleMake::create([
            'id' => 4,
            'name' => 'HONDA',
			'slug' => 'honda',
			'is_active' => '1'
        ]);
		$maruti = VehicleMake::create([
            'id' => 5,
            'name' => 'MARUTI',
			'slug' => 'maruti',
			'is_active' => '1'
        ]);
		VehicleModel::create([
            'id' => 1,
            'name' => 'X1',
			'slug' => 'x1',
			'vehicle_make_id' => $bmw->id,
			'is_active' => 1
        ]);
		VehicleModel::create([
            'id' => 2,
            'name' => 'X3',
			'slug' => 'x3',
			'vehicle_make_id' => $bmw->id,
			'is_active' => 1
        ]);
		VehicleModel::create([
            'id' => 3,
            'name' => 'X5',
			'slug' => 'x5',
			'vehicle_make_id' => $bmw->id,
			'is_active' => 1
        ]);
		VehicleModel::create([
            'id' => 4,
            'name' => 'Camry',
			'slug' => 'camry',
			'vehicle_make_id' => $toyota->id,
			'is_active' => 1
        ]);
		VehicleModel::create([
            'id' => 5,
            'name' => 'Corolla Altis',
			'slug' => 'corolla-altis',
			'vehicle_make_id' => $toyota->id,
			'is_active' => 1
        ]);
		VehicleModel::create([
            'id' => 6,
            'name' => 'Etios',
			'slug' => 'etios',
			'vehicle_make_id' => $toyota->id,
			'is_active' => 1
        ]);
		VehicleModel::create([
            'id' => 7,
            'name' => 'Innova',
			'slug' => 'innova',
			'vehicle_make_id' => $toyota->id,
			'is_active' => 1
        ]);
		VehicleModel::create([
            'id' => 8,
            'name' => 'Elantra',
			'slug' => 'elantra',
			'vehicle_make_id' => $hyundai->id,
			'is_active' => 1
        ]);
		VehicleModel::create([
            'id' => 9,
            'name' => 'Eon',
			'slug' => 'eon',
			'vehicle_make_id' => $hyundai->id,
			'is_active' => 1
        ]);
		VehicleModel::create([
            'id' => 10,
            'name' => 'i20',
			'slug' => 'i20',
			'vehicle_make_id' => $hyundai->id,
			'is_active' => 1
        ]);
		VehicleModel::create([
            'id' => 11,
            'name' => 'Verna',
			'slug' => 'verna',
			'vehicle_make_id' => $hyundai->id,
			'is_active' => 1
        ]);
		VehicleModel::create([
            'id' => 12,
            'name' => 'Amaze',
			'slug' => 'amaze',
			'vehicle_make_id' => $honda->id,
			'is_active' => 1
        ]);
		VehicleModel::create([
            'id' => 13,
            'name' => 'Brio',
			'slug' => 'brio',
			'vehicle_make_id' => $honda->id,
			'is_active' => 1
        ]);
		VehicleModel::create([
            'id' => 14,
            'name' => 'City',
			'slug' => 'city',
			'vehicle_make_id' => $honda->id,
			'is_active' => 1
        ]);
		VehicleModel::create([
            'id' => 15,
            'name' => 'Jazz',
			'slug' => 'jazz',
			'vehicle_make_id' => $honda->id,
			'is_active' => 1
        ]);
		VehicleModel::create([
            'id' => 16,
            'name' => 'CR-V',
			'slug' => 'cr-v',
			'vehicle_make_id' => $honda->id,
			'is_active' => 1
        ]);
		VehicleModel::create([
            'id' => 17,
            'name' => 'Baleno',
			'slug' => 'baleno',
			'vehicle_make_id' => $maruti->id,
			'is_active' => 1
        ]);
		VehicleModel::create([
            'id' => 18,
            'name' => 'Celerio',
			'slug' => 'celerio',
			'vehicle_make_id' => $maruti->id,
			'is_active' => 1
        ]);
		VehicleModel::create([
            'id' => 19,
            'name' => 'Swift',
			'slug' => 'swift',
			'vehicle_make_id' => $maruti->id,
			'is_active' => 1
        ]);
		VehicleModel::create([
            'id' => 20,
            'name' => 'Swift Dezire',
			'slug' => 'swift-dezire',
			'vehicle_make_id' => $maruti->id,
			'is_active' => 1
        ]);
    }
}