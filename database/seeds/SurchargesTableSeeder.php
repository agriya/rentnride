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
use Plugins\VehicleSurcharges\Model\VehicleSurcharge;
use Illuminate\Support\Facades\Hash;

class SurchargesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VehicleSurcharge::create([
            'id' => 1,
            'name' => 'Excluded Locations Additional Fees',
            'short_description' => 'Excluded Locations Additional Fees',
            'description' => 'Excluded locations additional fees will be collected as $xx for each mile / km.',
            'is_active' => 1
        ]);
        VehicleSurcharge::create([
            'id' => 2,
            'name' => 'Delivery & Collection',
            'short_description' => 'Delivery & Collection',
            'description' => 'If this Service is purchased host will delicer and collect the vehicle form booker',
            'is_active' => 1
        ]);
        VehicleSurcharge::create([
            'id' => 3,
            'name' => 'ACFR',
            'short_description' => 'Airport Concession Fee Recovery / Hotel and Airport Concession Fee Recovery / Fixed Based Operator & Airport Concession Fee Recovery (ACFR)',
            'description' => 'If this service is purchased, some concession will be given for Airport locations',
            'is_active' => 1
        ]);
        VehicleSurcharge::create([
            'id' => 4,
            'name' => 'Concession / Commission Fee Recovery (CFR) / Concession Recovery Surcharge Fee',
            'short_description' => 'Concession / Commission Fee Recovery (CFR) / Concession Recovery Surcharge Fee',
            'description' => 'If this service is purchased, some concession will be given for Commission',
            'is_active' => 1
        ]);
        VehicleSurcharge::create([
            'id' => 5,
            'name' => 'Hospitality Fee',
            'short_description' => 'Hospitality Fee',
            'description' => 'For maitain a good hospitality this surachrge will be applied',
            'is_active' => 1
        ]);
        VehicleSurcharge::create([
            'id' => 6,
            'name' => 'Tourism Surcharge',
            'short_description' => 'Tourism Surcharge',
            'description' => 'For Tourism packages this surchargre is applied',
            'is_active' => 1
        ]);
        VehicleSurcharge::create([
            'id' => 7,
            'name' => 'Domestic Security Fee',
            'short_description' => 'Domestic Security Fee',
            'description' => 'A domestic security fee of $xx per day applies to rentals.',
            'is_active' => 1
        ]);
		VehicleSurcharge::create([
            'id' => 8,
            'name' => 'Customer Transportation Fee / Transportation Fee / Transportation Facility Fee',
            'short_description' => 'Customer Transportation Fee / Transportation Fee / Transportation Facility Fee',
            'description' => 'The city requires that all car rental companies collect this fee. The money collected is used to pay for airport services. This fee is mandated by the airport',
            'is_active' => 1
        ]);
		VehicleSurcharge::create([
            'id' => 9,
            'name' => 'Energy Surcharge',
            'short_description' => 'Energy Surcharge',
            'description' => 'The costs of energy needed to support our business operations have escalated considerably. To offset the increasing costs of utilities, bus fuel, oil and grease, etc.,',
            'is_active' => 1
        ]);
		VehicleSurcharge::create([
            'id' => 10,
            'name' => 'Garage Recoupment Surcharge',
            'short_description' => 'Garage Recoupment Surcharge',
            'description' => 'This fee is to reimburse for certain service facilities charges at the rental location.',
            'is_active' => 1
        ]);
		VehicleSurcharge::create([
            'id' => 11,
            'name' => 'Rental Contract Fee',
            'short_description' => 'Rental Contract Fee',
            'description' => 'The Airport requires that all vehicle rental companies collect this fee. The money collected is used to pay for vehicle rental facilities.',
            'is_active' => 1
        ]);
		VehicleSurcharge::create([
            'id' => 12,
            'name' => 'Property Tax, Title/License Reimbursement / Vehicle Licensing Cost Recovery / Vehicle Licensing Fee / Recovery Surcharge / Vehicle Licensing and Business Licensing Fee',
            'short_description' => 'This fee is recovery of the proportionate amount of vehicle registration',
            'description' => 'This fee is recovery of the proportionate amount of vehicle registration, licensing and related fees applicable to a rental.',
            'is_active' => 1
        ]);
		VehicleSurcharge::create([
            'id' => 13,
            'name' => 'Operation and Maintenance Recovery Fee (O & M fee)',
            'short_description' => 'This fee is imposed to recover amounts it pays toward the operation and maintenance of a consolidated car rental facility at the airport, above the costs paid through the transportation charge. This is not government mandated.',
            'description' => '',
            'is_active' => 1
        ]);
		VehicleSurcharge::create([
            'id' => 14,
            'name' => 'CA Tourism Fee',
            'short_description' => 'CA Tourism Fee',
            'description' => 'Car rental companies are required by law to pay monthly assessments to the California Travel and Tourism Commission on revenue generated at either airport or hotel rental locations. This fee has been calculated to recover such assessment on an applicable rental basis.',
            'is_active' => 1
        ]);
		VehicleSurcharge::create([
            'id' => 15,
            'name' => 'An Alteration Fee',
            'short_description' => 'An Alteration Fee',
            'description' => 'A prepaid booking can be changed up to 48 hours before the start of the rental (depending on availability). An alteration fee of $28.00 (exclusive of sales tax) applies.',
            'is_active' => 1
        ]);
		VehicleSurcharge::create([
            'id' => 16,
            'name' => 'After Hours Service',
            'short_description' => ' After Hours Service',
            'description' => 'Extra payment may be applied if rental end date exceeded.',
            'is_active' => 1
        ]);
		VehicleSurcharge::create([
            'id' => 17,
            'name' => 'The Age Differential Charge (car based)',
            'short_description' => 'The Age Differential Charge (car based)',
            'description' => 'The minimum age is 18 without an additional Age Differential Charge, and below 18 with an additional Age Differential Charge.',
            'is_active' => 1
        ]);
		VehicleSurcharge::create([
            'id' => 18,
            'name' => 'Authorization Amount',
            'short_description' => 'Authorization Amount',
            'description' => 'Authorization amount up to $xxx plus the estimated charges on a customers card, given certain conditions that will be outlined at time of rental.',
            'is_active' => 1
        ]);
		VehicleSurcharge::create([
            'id' => 19,
            'name' => 'Credit Check Fee',
            'short_description' => 'Credit Check Fee',
            'description' => 'There is a US$15 nonrefundable processing fee which offsets the cost to have a modified credit check performed on the applicant.',
            'is_active' => 1
        ]);
		VehicleSurcharge::create([
            'id' => 20,
            'name' => 'Frequent Flyer surcharge',
            'short_description' => 'Frequent Flyer surcharge',
            'description' => 'Frequent Flyer surcharge equivalent to up to $ 1.00 per day.',
            'is_active' => 1
        ]);
		VehicleSurcharge::create([
            'id' => 21,
            'name' => 'Smoking Fee',
            'short_description' => 'Smoking Fee',
            'description' => 'Smoking is prohibited in all vehicles. A maximum charge of $ 175.00 can apply when smoking has occurred during the rental.',
            'is_active' => 1
        ]);
		VehicleSurcharge::create([
            'id' => 22,
            'name' => 'Premium Emergency Roadside Assistance',
            'short_description' => 'Premium Emergency Roadside Assistance',
            'description' => ' It is an optional service which, if accepted, reduces your financial liability for services required to remedy non-mechanical problems of the vehicle and/or problems resulting from an accident or collision.',
            'is_active' => 1
        ]);
    }
}