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
 
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Schema;
use DB;
use Cache;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap the application services.
     * @return void
     */
    public function boot()
    {
        if (Schema::hasTable('settings')) {
            $setting = Cache::rememberForever('settings_data', function () {
                return DB::table('settings')->lists('value', 'name');
            });
            foreach ($setting as $key => $value) {
                config()->set($key, $value);
            }
        }
    }
}
