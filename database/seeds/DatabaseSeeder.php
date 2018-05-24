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

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        //disable foreign key check for this connection before running seeders
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('setting_categories')->truncate();
        DB::table('settings')->truncate();
        DB::table('roles')->truncate();
        DB::table('withdrawal_statuses')->truncate();
        DB::table('countries')->truncate();
        DB::table('cities')->truncate();
        DB::table('states')->truncate();
        DB::table('currencies')->truncate();
        DB::table('users')->truncate();
        DB::table('user_profiles')->truncate();
        DB::table('attachments')->truncate();
        DB::table('languages')->truncate();
        DB::table('pages')->truncate();
        DB::table('providers')->truncate();
        DB::table('email_templates')->truncate();
        DB::table('ips')->truncate();
        DB::table('item_user_statuses')->truncate();
        DB::table('transaction_types')->truncate();
        DB::table('dispute_closed_types')->truncate();
        DB::table('dispute_types')->truncate();
        DB::table('dispute_statuses')->truncate();
        DB::table('counter_locations')->truncate();
        DB::table('discount_types')->truncate();
        DB::table('duration_types')->truncate();
        DB::table('fuel_options')->truncate();
        DB::table('fuel_types')->truncate();
        DB::table('insurances')->truncate();
        DB::table('surcharges')->truncate();
        DB::table('taxes')->truncate();
        DB::table('vehicle_makes')->truncate();
        DB::table('vehicle_models')->truncate();
        DB::table('vehicle_types')->truncate();
        DB::table('extra_accessories')->truncate();
        DB::table('api_requests')->truncate();
        DB::table('cancellation_types')->truncate();
        DB::table('contacts')->truncate();
        DB::table('coupons')->truncate();
        DB::table('currency_conversions')->truncate();
        DB::table('currency_conversion_histories')->truncate();
        DB::table('feedbacks')->truncate();
        DB::table('item_users')->truncate();
        DB::table('item_user_disputes')->truncate();
        DB::table('messages')->truncate();
        DB::table('message_contents')->truncate();
        DB::table('money_transfer_accounts')->truncate();
        DB::table('paypal_transaction_logs')->truncate();
        DB::table('provider_users')->truncate();
        DB::table('sudopay_ipn_logs')->truncate();
        DB::table('sudopay_transaction_logs')->truncate();
        DB::table('transactions')->truncate();
        DB::table('user_add_wallet_amounts')->truncate();
        DB::table('user_cash_withdrawals')->truncate();
        DB::table('user_logins')->truncate();
        DB::table('vehicle_companies')->truncate();
        DB::table('wallet_transaction_logs')->truncate();
        DB::table('booker_details')->truncate();

        $this->call('SettingCategoryTableSeeder');
        $this->call('WithdrawalStatusesTableSeeder');
        $this->call('RolesTableSeeder');
        $this->call('CountriesTableSeeder');
        $this->call('CurrenciesTableSeeder');
        $this->call('IpsTableSeeder');
        $this->call('ProvidersTableSeeder');
        $this->call('UsersTableSeeder');
        $this->call('EmailTemplatesTableSeeder');
        $this->call('LanguagesTableSeeder');
        $this->call('ItemUserStatusesTableSeeder');
        $this->call('TransactionTypesTableSeeder');
        $this->call('DiscountTypeSeeder');
        $this->call('DisputeStatusesTableSeeder');
        $this->call('DisputeTypesTableSeeder');
        $this->call('CounterLocationsTableSeeder');
        $this->call('DurationTypesTableSeeder');
        $this->call('ExtraAccessoriesTableSeeder');
        $this->call('FuelOptionsTableSeeder');
        $this->call('FuelTypesTableSeeder');
        $this->call('InsurancesTableSeeder');
        $this->call('SurchargesTableSeeder');
        $this->call('TaxesTableSeeder');
        $this->call('VehicleMakesTableSeeder');
        $this->call('VehicleTypesTableSeeder');
        $this->call('AttachmentTableSeeder');
        //$this->call('VehiclesTableSeeder');
        //$this->call('VehicleCompaniesTableSeeder');
    }
}
