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
use App\Currency;

class CurrenciesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Currency::create([
            'name' => 'Euros',
            'code' => 'EUR',
            'symbol' => '€',
            'prefix' => '€',
            'suffix' => 'EUR',
            'decimals' => '2',
            'dec_point' => '.',
            'thousands_sep' => ',',
            'is_prefix_display_on_left' => 1,
            'is_use_graphic_symbol' => 1,
            'is_active' => 1,
        ]);

        Currency::create([
            'name' => 'U.S. Dollars',
            'code' => 'USD',
            'symbol' => '$',
            'prefix' => '$',
            'suffix' => 'USD',
            'decimals' => '2',
            'dec_point' => '.',
            'thousands_sep' => ',',
            'is_prefix_display_on_left' => 1,
            'is_use_graphic_symbol' => 1,
            'is_active' => 1,
        ]);

        Currency::create([
            'name' => 'Australian Dollars',
            'code' => 'AUD',
            'symbol' => '$',
            'prefix' => '$',
            'suffix' => 'AUD',
            'decimals' => '2',
            'dec_point' => '.',
            'thousands_sep' => ',',
            'is_prefix_display_on_left' => 1,
            'is_use_graphic_symbol' => 0,
            'is_active' => 1,
        ]);

        Currency::create([
            'name' => 'British Pounds',
            'code' => 'GBP',
            'symbol' => '£',
            'prefix' => '£',
            'suffix' => 'GBP',
            'decimals' => '2',
            'dec_point' => '.',
            'thousands_sep' => ',',
            'is_prefix_display_on_left' => 1,
            'is_use_graphic_symbol' => 0,
            'is_active' => 1,
        ]);

        Currency::create([
            'name' => 'Canadian Dollars',
            'code' => 'CAD',
            'symbol' => '$',
            'prefix' => '$',
            'suffix' => 'CAD',
            'decimals' => '2',
            'dec_point' => '.',
            'thousands_sep' => ',',
            'is_prefix_display_on_left' => 1,
            'is_use_graphic_symbol' => 0,
            'is_active' => 1,
        ]);

        Currency::create([
            'name' => 'Danish Kroner',
            'code' => 'DKK',
            'symbol' => 'kr',
            'prefix' => 'kr',
            'suffix' => 'DKK',
            'decimals' => '2',
            'dec_point' => '.',
            'thousands_sep' => ',',
            'is_prefix_display_on_left' => 1,
            'is_use_graphic_symbol' => 0,
            'is_active' => 1,
        ]);

        Currency::create([
            'name' => 'Hong Kong Dollars',
            'code' => 'HKD',
            'symbol' => 'HK$',
            'prefix' => 'HK$',
            'suffix' => 'HKD',
            'decimals' => '2',
            'dec_point' => '.',
            'thousands_sep' => ',',
            'is_prefix_display_on_left' => 1,
            'is_use_graphic_symbol' => 0,
            'is_active' => 1,
        ]);

        Currency::create([
            'name' => 'Israeli New Shekels',
            'code' => 'ILS',
            'symbol' => '₪',
            'prefix' => '₪',
            'suffix' => 'ILS',
            'decimals' => '2',
            'dec_point' => '.',
            'thousands_sep' => ',',
            'is_prefix_display_on_left' => 1,
            'is_use_graphic_symbol' => 0,
            'is_active' => 1,
        ]);

        Currency::create([
            'name' => 'Japanese Yen',
            'code' => 'JPY',
            'symbol' => '¥',
            'prefix' => '¥',
            'suffix' => 'JPY',
            'decimals' => '2',
            'dec_point' => '.',
            'thousands_sep' => ',',
            'is_prefix_display_on_left' => 1,
            'is_use_graphic_symbol' => 0,
            'is_active' => 1,
        ]);

        Currency::create([
            'name' => 'Mexican Pesos',
            'code' => 'MXN',
            'symbol' => '$',
            'prefix' => '$',
            'suffix' => 'MXN',
            'decimals' => '2',
            'dec_point' => '.',
            'thousands_sep' => ',',
            'is_prefix_display_on_left' => 1,
            'is_use_graphic_symbol' => 0,
            'is_active' => 1,
        ]);

        Currency::create([
            'name' => 'New Zealand Dollars',
            'code' => 'NZD',
            'symbol' => '$',
            'prefix' => '$',
            'suffix' => 'NZD',
            'decimals' => '2',
            'dec_point' => '.',
            'thousands_sep' => ',',
            'is_prefix_display_on_left' => 1,
            'is_use_graphic_symbol' => 0,
            'is_active' => 1,
        ]);

        Currency::create([
            'name' => 'Norwegian Kroner',
            'code' => 'NOK',
            'symbol' => 'kr',
            'prefix' => 'kr',
            'suffix' => 'NOK',
            'decimals' => '2',
            'dec_point' => '.',
            'thousands_sep' => ',',
            'is_prefix_display_on_left' => 1,
            'is_use_graphic_symbol' => 0,
            'is_active' => 1,
        ]);

        Currency::create([
            'name' => 'Philippine Pesos',
            'code' => 'PHP',
            'symbol' => 'php',
            'prefix' => 'php',
            'suffix' => 'PHP',
            'decimals' => '2',
            'dec_point' => '.',
            'thousands_sep' => ',',
            'is_prefix_display_on_left' => 1,
            'is_use_graphic_symbol' => 0,
            'is_active' => 1,
        ]);

        Currency::create([
            'name' => 'Polish Zlotych',
            'code' => 'PLN',
            'symbol' => 'zł‚',
            'prefix' => 'zł‚',
            'suffix' => 'PLN',
            'decimals' => '2',
            'dec_point' => '.',
            'thousands_sep' => ',',
            'is_prefix_display_on_left' => 1,
            'is_use_graphic_symbol' => 0,
            'is_active' => 1,
        ]);

        Currency::create([
            'name' => 'Singapore Dollars',
            'code' => 'SGD',
            'symbol' => '$',
            'prefix' => '$',
            'suffix' => 'SGD',
            'decimals' => '2',
            'dec_point' => '.',
            'thousands_sep' => ',',
            'is_prefix_display_on_left' => 1,
            'is_use_graphic_symbol' => 0,
            'is_active' => 1,
        ]);

        Currency::create([
            'name' => 'Swedish Kronor',
            'code' => 'SEK',
            'symbol' => 'kr',
            'prefix' => 'kr',
            'suffix' => 'SEK',
            'decimals' => '2',
            'dec_point' => '.',
            'thousands_sep' => ',',
            'is_prefix_display_on_left' => 1,
            'is_use_graphic_symbol' => 0,
            'is_active' => 1,
        ]);

        Currency::create([
            'name' => 'Swiss Francs',
            'code' => 'CHF',
            'symbol' => 'CHF',
            'prefix' => 'CHF',
            'suffix' => 'CHF',
            'decimals' => '2',
            'dec_point' => '.',
            'thousands_sep' => ',',
            'is_prefix_display_on_left' => 1,
            'is_use_graphic_symbol' => 0,
            'is_active' => 1,
        ]);

        Currency::create([
            'name' => 'Thai Baht',
            'code' => 'THB',
            'symbol' => 'Bt',
            'prefix' => 'Bt',
            'suffix' => 'THB',
            'decimals' => '2',
            'dec_point' => '.',
            'thousands_sep' => ',',
            'is_prefix_display_on_left' => 1,
            'is_use_graphic_symbol' => 0,
            'is_active' => 1,
        ]);

        Currency::create([
            'name' => 'Indian Rupee',
            'code' => 'INR',
            'symbol' => '₹',
            'prefix' => '₹',
            'suffix' => 'INR',
            'decimals' => '2',
            'dec_point' => '.',
            'thousands_sep' => ',',
            'is_prefix_display_on_left' => 1,
            'is_use_graphic_symbol' => 1,
            'is_active' => 1,
        ]);

        Currency::create([
            'name' => 'South African Rand',
            'code' => 'ZAR',
            'symbol' => 'ZAR',
            'prefix' => '',
            'suffix' => '',
            'decimals' => '',
            'dec_point' => '',
            'thousands_sep' => '',
            'is_prefix_display_on_left' => 1,
            'is_use_graphic_symbol' => 0,
            'is_active' => 1,
        ]);

        Currency::create([
            'name' => 'BRL-CAD',
            'code' => 'BRL',
            'symbol' => 'R$',
            'prefix' => '',
            'suffix' => '',
            'decimals' => '',
            'dec_point' => '',
            'thousands_sep' => '',
            'is_prefix_display_on_left' => 1,
            'is_use_graphic_symbol' => 1,
            'is_active' => 1,
        ]);

        Currency::create([
            'name' => 'TÃ¼rk LirasÄ±',
            'code' => 'TRY',
            'symbol' => 'TL',
            'prefix' => '',
            'suffix' => '',
            'decimals' => '',
            'dec_point' => '',
            'thousands_sep' => '',
            'is_prefix_display_on_left' => 1,
            'is_use_graphic_symbol' => 0,
            'is_active' => 1,
        ]);

        Currency::create([
            'name' => 'Chilean Peso',
            'code' => 'CLP',
            'symbol' => '$',
            'prefix' => '$',
            'suffix' => 'CLP',
            'decimals' => '1',
            'dec_point' => ',',
            'thousands_sep' => '.',
            'is_prefix_display_on_left' => 1,
            'is_use_graphic_symbol' => 1,
            'is_active' => 1,
        ]);

        Currency::create([
            'name' => 'Romanian Leu',
            'code' => 'RON',
            'symbol' => 'Leu',
            'prefix' => '',
            'suffix' => '',
            'decimals' => '',
            'dec_point' => '',
            'thousands_sep' => '',
            'is_prefix_display_on_left' => 1,
            'is_use_graphic_symbol' => 0,
            'is_active' => 1,
        ]);

        Currency::create([
            'name' => 'Egyptian Pound',
            'code' => 'EGP',
            'symbol' => 'EGP',
            'prefix' => '',
            'suffix' => '',
            'decimals' => '2',
            'dec_point' => '.',
            'thousands_sep' => ',',
            'is_prefix_display_on_left' => 1,
            'is_use_graphic_symbol' => 0,
            'is_active' => 1,
        ]);

    }
}