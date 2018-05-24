<?php
/**
 * Plugin
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
 
namespace Plugins\VehicleTaxes\Providers;

use Illuminate\Support\ServiceProvider;

class VehicleTaxServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__ . '/../routes.php';
        $this->app->make('Plugins\VehicleTaxes\Controllers\VehicleTaxesController');
        $this->app->make('Plugins\VehicleTaxes\Controllers\Admin\AdminVehicleTaxesController');
        $this->app->make('Plugins\VehicleTaxes\Controllers\VehicleTypeTaxesController');
        $this->app->make('Plugins\VehicleTaxes\Controllers\Admin\AdminVehicleTypeTaxesController');
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
