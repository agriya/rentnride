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
 
namespace Plugins\VehicleSurcharges\Providers;

use Illuminate\Support\ServiceProvider;

class VehicleSurchargeServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__ . '/../routes.php';
        $this->app->make('Plugins\VehicleSurcharges\Controllers\VehicleSurchargesController');
        $this->app->make('Plugins\VehicleSurcharges\Controllers\Admin\AdminVehicleSurchargesController');
        $this->app->make('Plugins\VehicleSurcharges\Controllers\VehicleTypeSurchargesController');
        $this->app->make('Plugins\VehicleSurcharges\Controllers\Admin\AdminVehicleTypeSurchargesController');
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
