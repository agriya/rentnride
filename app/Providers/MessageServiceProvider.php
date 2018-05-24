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
use Illuminate\Database\Eloquent\Relations\Relation;

class MessageServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $enabledIncludes = array();
        $enabledIncludes['MorphUser'] = \App\User::class;

        if (isPluginEnabled('VehicleRentals')) {
            $enabledIncludes['MorphVehicleRental'] = \Plugins\VehicleRentals\Model\VehicleRental::class;
        }
        if (isPluginEnabled('VehicleDisputes')) {
            $enabledIncludes['MorphVehicleRentalDispute'] = \Plugins\VehicleDisputes\Model\VehicleDispute::class;
        }
        if (!empty($enabledIncludes)) {
            Relation::morphMap($enabledIncludes);
        }
    }
}
