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
 
namespace Plugins\VehicleFuelOptions\Providers;

use Illuminate\Support\ServiceProvider;

class VehicleFuelOptionServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__ . '/../routes.php';
        $this->app->make('Plugins\VehicleFuelOptions\Controllers\VehicleFuelOptionsController');
        $this->app->make('Plugins\VehicleFuelOptions\Controllers\Admin\AdminVehicleFuelOptionsController');
        $this->app->make('Plugins\VehicleFuelOptions\Controllers\VehicleTypeFuelOptionsController');
        $this->app->make('Plugins\VehicleFuelOptions\Controllers\Admin\AdminVehicleTypeFuelOptionsController');
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
