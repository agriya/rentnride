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
use Plugins\VehicleInsurances\Model\VehicleInsurance;
use Illuminate\Support\Facades\Hash;

class InsurancesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VehicleInsurance::create([
            'id' => 1,
            'name' => 'Liability coverage/Liability Insurance Supplement (LIS)',
            'short_description' => 'Liability coverage/Liability Insurance Supplement (LIS)',
            'description' => 'i) Liability Insurance Supplement (LIS) provides coverage for you and other authorized operators of your rental vehicle for third party claims. 
ii) Liability protection up to a limit of $1,000,000 per accident.
iii) LIS is third party liability coverage only,except where permitted by law or pertaining to Uninsured / Underinsured Motorist Coverage.',
            'is_active' => 1
        ]);
        VehicleInsurance::create([
            'id' => 2,
            'name' => 'Partial Damage Waiver (PDW)',
            'short_description' => 'Partial Damage Waiver (PDW)',
            'description' => 'A partial / certain amount will be waived off. After that limit Booker has to pay for the damage.',
            'is_active' => 1
        ]);
        VehicleInsurance::create([
            'id' => 3,
            'name' => ' Loss Damage Waiver (LDW)',
            'short_description' => ' Loss Damage Waiver (LDW)',
            'description' => 'i) The cost of LDW may vary depending on location or car type and is charged per each full or partial day of rental.
ii) In the event of any loss or damage to the car regardless of fault, your financial responsibility will in no event exceed the fair market value, plus actual charges for towing, storage, impound fees, and an administrative fee.',
            'is_active' => 1
        ]);
        VehicleInsurance::create([
            'id' => 4,
            'name' => 'Loss Damage Waiver (including Theft Protection) with minimum excess',
            'short_description' => 'Loss Damage Waiver (including Theft Protection) with minimum excess',
            'description' => 'i) Loss or damage for any cause other than theft is limited to USD 15,500.00.
ii) Loss or damage related to theft is limited to USD 2,000.00, unless the theft results from your fault.',
            'is_active' => 1
        ]);
        VehicleInsurance::create([
            'id' => 5,
            'name' => 'Glass Damage Waiver (GDW)',
            'short_description' => 'Glass Damage Waiver (GDW)',
            'description' => 'i) GDW is protection only for the windshield of the rental car. 
ii) This policy covers nearly all windows in the vehicle, including the windshield, side windows, rear-window and mirror glass.',
            'is_active' => 1
        ]);
        VehicleInsurance::create([
            'id' => 6,
            'name' => 'Personal Accident Insurance (PAI)',
            'short_description' => 'Personal Accident Insurance (PAI)',
            'description' => 'i) Personal Accident Insurance (PAI), offered in combination with PEC, allows you to elect accidental death and accidental medical expense coverage for yourself and your passengers during the rental period of the vehicle. 
ii) Total benefits for any one accident are limited to USD 225,000.00.
iii) Your passengers are also covered, but only for incidents occurring while they occupy the rental car.',
            'is_active' => 1
        ]);
        VehicleInsurance::create([
            'id' => 7,
            'name' => 'Liability Coverage/Supplemental Liability Insurance Loss Damage Waiver',
            'short_description' => 'Liability Coverage/Supplemental Liability Insurance Loss Damage Waiver',
            'description' => 'If you are involved in an accident with your rental car, you are insured against bodily injury and property damage to a third party up to a certain monetary value.',
            'is_active' => 1
        ]);
		VehicleInsurance::create([
            'id' => 8,
            'name' => 'Protection Package (PP)',
            'short_description' => 'Protection Package (PP)',
            'description' => 'The Protection Package includes the following products: 
Loss Damage Waiver(LDW)
Third Party Liability (SLI) 
Personal Accident Insurance (PAI) 
Roadside Assistance (BC) 
Navigation System (NG)
	CRS Code Loss Damage Waiver (including Theft Protection) with minimum excess Personal Accident Insurance $/Day.',
            'is_active' => 1
        ]);
		VehicleInsurance::create([
            'id' => 9,
            'name' => 'Uninsured Motorist Protection (UMP)',
            'short_description' => 'Uninsured Motorist Protection (UMP)',
            'description' => 'i) UMP provides up to USD 1,000,000.00 of Uninsured / Under-insured protection for bodily injury sustained while driving the rental vehicle. 
ii) UMP is only available when first accepting Liability Insurance Supplement (LIS). 
iii) UMP is currently not available to Gold customers who book using their Gold Plus Rewards number and profile.',
            'is_active' => 1
        ]);
    }
}