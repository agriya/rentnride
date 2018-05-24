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
 
namespace Plugins\VehicleExtraAccessories\Providers;

use Illuminate\Support\ServiceProvider;

class VehicleExtraAccessoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__ . '/../routes.php';
        $this->app->make('Plugins\VehicleExtraAccessories\Controllers\VehicleExtraAccessoriesController');
        $this->app->make('Plugins\VehicleExtraAccessories\Controllers\Admin\AdminVehicleExtraAccessoriesController');
        $this->app->make('Plugins\VehicleExtraAccessories\Controllers\VehicleTypeExtraAccessoriesController');
        $this->app->make('Plugins\VehicleExtraAccessories\Controllers\Admin\AdminVehicleTypeExtraAccessoriesController');
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
