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
 
namespace Plugins\VehicleInsurances\Providers;

use Illuminate\Support\ServiceProvider;

class VehicleInsuranceServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__ . '/../routes.php';
        $this->app->make('Plugins\VehicleInsurances\Controllers\VehicleInsurancesController');
        $this->app->make('Plugins\VehicleInsurances\Controllers\Admin\AdminVehicleInsurancesController');
        $this->app->make('Plugins\VehicleInsurances\Controllers\VehicleTypeInsurancesController');
        $this->app->make('Plugins\VehicleInsurances\Controllers\Admin\AdminVehicleTypeInsurancesController');
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
