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
use Plugins\VehicleDisputes\Model\VehicleDisputeType;
use Plugins\VehicleDisputes\Model\VehicleDisputeClosedType;
use App\Eloquent;
use Illuminate\Support\Facades\Hash;

class DisputeTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        $specification = VehicleDisputeType::create([
            'name' => 'Doesn\'t match the specification as mentioned by the property owner',
            'is_booker' => 1
        ]);
		$feedback = VehicleDisputeType::create([
            'name' => 'Booker given poor feedback'
        ]);
		$security = VehicleDisputeType::create([
            'name' => 'Claim the security damage from booker'
        ]);
		
		VehicleDisputeClosedType::create([
            'dispute_type_id' => $specification->id,
            'name' => 'Favor Booker',
            'is_booker' => 1,
            'reason' => 'Property doesn\'t match with the one mentioned in property specification',
            'resolved_type' => 'refunded'
        ]);
		
		VehicleDisputeClosedType::create([
            'dispute_type_id' => $specification->id,
            'name' => 'Favor Host',
            'is_booker' => 0,
            'reason' => 'Property matches with the one mentioned in property specification',
            'resolved_type' => 'resolve without any change'
        ]);
		
		VehicleDisputeClosedType::create([
            'dispute_type_id' => $specification->id,
            'name' => 'Favor Booker',
            'is_booker' => 1,
            'reason' => 'Failure to respond in time limit',
            'resolved_type' => 'refunded'
        ]);
		
		VehicleDisputeClosedType::create([
            'dispute_type_id' => $feedback->id,
            'name' => 'Favor Booker',
            'is_booker' => 1,
            'reason' => 'Property doesn\'t matches the quality and requirement/specification, so no changes in feedback / rating',
            'resolved_type' => 'resolve without any change'
        ]);
		
		VehicleDisputeClosedType::create([
            'dispute_type_id' => $feedback->id,
            'name' => 'Favor Host',
            'is_booker' => 1,
            'reason' => 'Property matches the quality and requirement/specification, so host rating changed to positive',
            'resolved_type' => 'Update host rating'
        ]);
		
		VehicleDisputeClosedType::create([
            'dispute_type_id' => $feedback->id,
            'name' => 'Favor Host',
            'is_booker' => 1,
            'reason' => 'Failure to respond in time limit',
            'resolved_type' => 'Update host rating'
        ]);
		
		VehicleDisputeClosedType::create([
            'dispute_type_id' => $security->id,
            'name' => 'Favor Booker',
            'is_booker' => 1,
            'reason' => 'Claiming reason doesn\'t match with existing conversation',
            'resolved_type' => 'Deposit amount refunded'
        ]);
		
		VehicleDisputeClosedType::create([
            'dispute_type_id' => $security->id,
            'name' => 'Favor Host',
            'is_booker' => 0,
            'reason' => 'Claiming reason matches with existing conversation',
            'resolved_type' => 'Deposit amount to host'
        ]);
		
		VehicleDisputeClosedType::create([
            'dispute_type_id' => $security->id,
            'name' => 'Favor Host',
            'is_booker' => 0,
            'reason' => 'Failure to respond in time limit',
            'resolved_type' => 'Deposit amount to host'
        ]);
    }
}