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
 
namespace Plugins\VehicleRentals\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
class VehicleRentalServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__ . '/../routes.php';
        $this->app->make('Plugins\VehicleRentals\Controllers\Admin\AdminVehicleRentalsController');
        $this->app->make('Plugins\VehicleRentals\Controllers\Admin\AdminVehicleRentalStatusesController');
        $this->app->make('Plugins\VehicleRentals\Controllers\VehicleRentalsController');
        $this->app->make('Plugins\VehicleRentals\Controllers\VehicleRentalStatusesController');
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $enabledIncludes = array();
        $enabledIncludes['MorphVehicle'] = \Plugins\Vehicles\Model\Vehicle::class;
        if (isPluginEnabled('VehicleInsurances')) {
            $enabledIncludes['MorphInsurance'] = \Plugins\VehicleInsurances\Model\VehicleTypeInsurance::class;
        }
        if (isPluginEnabled('VehicleFuelOptions')) {
            $enabledIncludes['MorphFuelOption'] = \Plugins\VehicleFuelOptions\Model\VehicleTypeFuelOption::class;
        }
        if (isPluginEnabled('VehicleExtraAccessories')) {
            $enabledIncludes['MorphExtraAccessory'] = \Plugins\VehicleExtraAccessories\Model\VehicleTypeExtraAccessory::class;
        }
        if (isPluginEnabled('VehicleTaxes')) {
            $enabledIncludes['MorphTax'] = \Plugins\VehicleTaxes\Model\VehicleTypeTax::class;
        }
        if (isPluginEnabled('VehicleSurcharges')) {
            $enabledIncludes['MorphSurcharge'] = \Plugins\VehicleSurcharges\Model\VehicleTypeSurcharge::class;
        }
        if (!empty($enabledIncludes)) {
            Relation::morphMap($enabledIncludes);
        }
    }
}
