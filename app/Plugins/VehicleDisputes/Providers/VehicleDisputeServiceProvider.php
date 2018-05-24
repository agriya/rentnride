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
 
namespace Plugins\VehicleDisputes\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class VehicleDisputeServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__ . '/../routes.php';
        $this->app->make('Plugins\VehicleDisputes\Controllers\Admin\AdminVehicleDisputesController');
        $this->app->make('Plugins\VehicleDisputes\Controllers\Admin\AdminVehicleDisputeTypesController');
        $this->app->make('Plugins\VehicleDisputes\Controllers\Admin\AdminVehicleDisputeClosedTypesController');
        $this->app->make('Plugins\VehicleDisputes\Controllers\VehicleDisputesController');
        $this->app->make('Plugins\VehicleDisputes\Controllers\VehicleDisputeTypesController');
        $this->app->make('Plugins\VehicleDisputes\Controllers\VehicleDisputeClosedTypesController');
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'MorphVehicleRental' => \Plugins\VehicleRentals\Model\VehicleRental::class
        ]);
    }
}
