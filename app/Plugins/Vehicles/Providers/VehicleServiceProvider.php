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
 
namespace Plugins\Vehicles\Providers;

use Illuminate\Support\ServiceProvider;

class VehicleServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__ . '/../routes.php';
        $this->app->make('Plugins\Vehicles\Controllers\Admin\AdminVehicleMakesController');
        $this->app->make('Plugins\Vehicles\Controllers\Admin\AdminVehicleTypesController');
        $this->app->make('Plugins\Vehicles\Controllers\Admin\AdminVehicleCompaniesController');
        $this->app->make('Plugins\Vehicles\Controllers\Admin\AdminVehicleModelsController');
        $this->app->make('Plugins\Vehicles\Controllers\Admin\AdminCounterLocationsController');
        $this->app->make('Plugins\Vehicles\Controllers\Admin\AdminVehiclesController');
        $this->app->make('Plugins\Vehicles\Controllers\Admin\AdminVehicleSpecialPricesController');
        $this->app->make('Plugins\Vehicles\Controllers\Admin\AdminVehicleTypePricesController');
        $this->app->make('Plugins\Vehicles\Controllers\Admin\AdminFuelTypesController');
        $this->app->make('Plugins\Vehicles\Controllers\Admin\AdminUnavailableVehiclesController');
        $this->app->make('Plugins\Vehicles\Controllers\VehiclesController');
        $this->app->make('Plugins\Vehicles\Controllers\VehicleSpecialPricesController');
        $this->app->make('Plugins\Vehicles\Controllers\VehicleTypePricesController');
        $this->app->make('Plugins\Vehicles\Controllers\VehicleCompaniesController');
        $this->app->make('Plugins\Vehicles\Controllers\VehicleMakesController');
        $this->app->make('Plugins\Vehicles\Controllers\VehicleModelsController');
        $this->app->make('Plugins\Vehicles\Controllers\VehicleTypesController');
        $this->app->make('Plugins\Vehicles\Controllers\CounterLocationsController');
        $this->app->make('Plugins\Vehicles\Controllers\FuelTypesController');
        $this->app->make('Plugins\Vehicles\Controllers\UnavailableVehiclesController');
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
