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
use App\DiscountType;
use App\Eloquent;
use Illuminate\Support\Facades\Hash;

class DiscountTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        DiscountType::create([
            'id' => 1,
            'type' => 'percentage',            
        ]);
        DiscountType::create([
            'id' => 2,
            'type' => 'amount',            
        ]);
    }
}