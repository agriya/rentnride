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
 
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
$api = $this->app->make('Dingo\Api\Routing\Router');
$api->version(['v1'], function ($api) {

    //Admin Booking
    $api->group(['prefix' => 'admin', 'namespace' => 'Plugins\Vehicles\Controllers\Admin', 'middleware' => 'apitracking'], function () use ($api) {
        $api->get('vehicle_makes', 'AdminVehicleMakesController@index');
        $api->post('vehicle_makes', 'AdminVehicleMakesController@store');
        $api->get('vehicle_makes/{id}/edit', 'AdminVehicleMakesController@edit');
        $api->put('vehicle_makes/{id}', 'AdminVehicleMakesController@update');
        $api->get('vehicle_makes/{id}', 'AdminVehicleMakesController@show');
        $api->delete('vehicle_makes/{id}', 'AdminVehicleMakesController@destroy');

        $api->get('vehicle_types', 'AdminVehicleTypesController@index');
        $api->post('vehicle_types', 'AdminVehicleTypesController@store');
        $api->get('vehicle_types/{id}/edit', 'AdminVehicleTypesController@edit');
        $api->put('vehicle_types/{id}', 'AdminVehicleTypesController@update');
        $api->get('vehicle_types/{id}', 'AdminVehicleTypesController@show');
        $api->delete('vehicle_types/{id}', 'AdminVehicleTypesController@destroy');

        $api->get('vehicle_type_prices', 'AdminVehicleTypePricesController@index');
        $api->post('vehicle_type_prices', 'AdminVehicleTypePricesController@store');
        $api->get('vehicle_type_prices/{id}/edit', 'AdminVehicleTypePricesController@edit');
        $api->put('vehicle_type_prices/{id}', 'AdminVehicleTypePricesController@update');
        $api->get('vehicle_type_prices/{id}', 'AdminVehicleTypePricesController@show');
        $api->delete('vehicle_type_prices/{id}', 'AdminVehicleTypePricesController@destroy');

        $api->get('vehicle_special_prices', 'AdminVehicleSpecialPricesController@index');
        $api->post('vehicle_special_prices', 'AdminVehicleSpecialPricesController@store');
        $api->get('vehicle_special_prices/{id}/edit', 'AdminVehicleSpecialPricesController@edit');
        $api->put('vehicle_special_prices/{id}', 'AdminVehicleSpecialPricesController@update');
        $api->get('vehicle_special_prices/{id}', 'AdminVehicleSpecialPricesController@show');
        $api->delete('vehicle_special_prices/{id}', 'AdminVehicleSpecialPricesController@destroy');

        $api->get('vehicle_companies', 'AdminVehicleCompaniesController@index');
        $api->post('vehicle_companies', 'AdminVehicleCompaniesController@store');
        $api->get('vehicle_companies/{id}/edit', 'AdminVehicleCompaniesController@edit');
        $api->put('vehicle_companies/{id}', 'AdminVehicleCompaniesController@update');
        $api->get('vehicle_companies/{id}', 'AdminVehicleCompaniesController@show');
        $api->delete('vehicle_companies/{id}', 'AdminVehicleCompaniesController@destroy');
        $api->put('vehicle_companies/{id}/active', 'AdminVehicleCompaniesController@active');
        $api->put('vehicle_companies/{id}/reject', 'AdminVehicleCompaniesController@reject');
        $api->put('vehicle_companies/{id}/deactive', 'AdminVehicleCompaniesController@deactive');

        $api->get('vehicle_models', 'AdminVehicleModelsController@index');
        $api->post('vehicle_models', 'AdminVehicleModelsController@store');
        $api->get('vehicle_models/{id}/edit', 'AdminVehicleModelsController@edit');
        $api->put('vehicle_models/{id}', 'AdminVehicleModelsController@update');
        $api->get('vehicle_models/{id}', 'AdminVehicleModelsController@show');
        $api->delete('vehicle_models/{id}', 'AdminVehicleModelsController@destroy');

        $api->get('counter_locations', 'AdminCounterLocationsController@index');
        $api->post('counter_locations', 'AdminCounterLocationsController@store');
        $api->get('counter_locations/{id}/edit', 'AdminCounterLocationsController@edit');
        $api->put('counter_locations/{id}', 'AdminCounterLocationsController@update');
        $api->get('counter_locations/{id}', 'AdminCounterLocationsController@show');
        $api->delete('counter_locations/{id}', 'AdminCounterLocationsController@destroy');

        $api->get('vehicles', 'AdminVehiclesController@index');
        $api->post('vehicles', 'AdminVehiclesController@store');
        $api->get('vehicles/{id}/edit', 'AdminVehiclesController@edit');
        $api->post('vehicles/{id}', 'AdminVehiclesController@update');
        $api->get('vehicles/{id}', 'AdminVehiclesController@show');
        $api->delete('vehicles/{id}', 'AdminVehiclesController@destroy');
        $api->get('vehicle/add', 'AdminVehiclesController@getVehicleRelatedDetail');
        $api->put('vehicles/{id}/deactive', 'AdminVehiclesController@deactive');
        $api->put('vehicles/{id}/active', 'AdminVehiclesController@active');

        $api->get('fuel_types', 'AdminFuelTypesController@index');
        $api->post('fuel_types', 'AdminFuelTypesController@store');
        $api->get('fuel_types/{id}/edit', 'AdminFuelTypesController@edit');
        $api->put('fuel_types/{id}', 'AdminFuelTypesController@update');
        $api->get('fuel_types/{id}', 'AdminFuelTypesController@show');
        $api->delete('fuel_types/{id}', 'AdminFuelTypesController@destroy');

        $api->get('unavailable_vehicles', 'AdminUnavailableVehiclesController@index');
        $api->post('unavailable_vehicles', 'AdminUnavailableVehiclesController@store');
        $api->get('unavailable_vehicles/{id}/edit', 'AdminUnavailableVehiclesController@edit');
        $api->put('unavailable_vehicles/{id}', 'AdminUnavailableVehiclesController@update');
        $api->delete('unavailable_vehicles/{id}', 'AdminUnavailableVehiclesController@destroy');
        $api->get('unavailable_vehicles/{id}', 'AdminUnavailableVehiclesController@show');

    });
    $api->group(['namespace' => 'Plugins\Vehicles\Controllers', 'middleware' => 'apitracking'], function () use ($api) {
        $api->get('vehicles/filters', 'VehiclesController@getVehicleRelatedFilters');
        $api->get('vehicles/me', 'VehiclesController@index');
        $api->post('vehicles/search', 'VehiclesController@search');
        $api->post('vehicles', 'VehiclesController@store');
        $api->get('vehicles/{id}/edit', 'VehiclesController@edit');
        $api->post('vehicles/paynow', 'VehiclesController@payNow');
        $api->post('vehicles/{id}', 'VehiclesController@update');
        $api->get('vehicles/{id}', 'VehiclesController@show');
        $api->delete('vehicles/{id}', 'VehiclesController@destroy');
        $api->get('vehicle/add', 'VehiclesController@getVehicleRelatedDetail');


        $api->get('vehicle_type_prices', 'VehicleTypePricesController@index');
        $api->get('vehicle_type_prices/{id}/edit', 'VehicleTypePricesController@edit');
        $api->get('vehicle_type_prices/{id}', 'VehicleTypePricesController@show');

        $api->get('vehicle_special_prices', 'VehicleSpecialPricesController@index');
        $api->get('vehicle_special_prices/{id}/edit', 'VehicleSpecialPricesController@edit');
        $api->get('vehicle_special_prices/{id}', 'VehicleSpecialPricesController@show');

        $api->get('vehicle_companies', 'VehicleCompaniesController@index');
        $api->post('vehicle_companies', 'VehicleCompaniesController@store');
        $api->get('vehicle_companies/edit', 'VehicleCompaniesController@edit');
        $api->get('vehicle_companies/show', 'VehicleCompaniesController@show');
        $api->delete('vehicle_companies/{id}', 'VehicleCompaniesController@destroy');

        $api->get('vehicle_makes', 'VehicleMakesController@index');

        $api->get('vehicle_models', 'VehicleModelsController@index');

        $api->get('vehicle_types', 'VehicleTypesController@index');
        $api->get('vehicle_types/{id}', 'VehicleTypesController@show');

        $api->get('counter_locations', 'CounterLocationsController@index');

        $api->get('fuel_types', 'FuelTypesController@index');

        $api->get('unavailable_vehicles', 'UnavailableVehiclesController@index');
        $api->post('unavailable_vehicles', 'UnavailableVehiclesController@store');
        $api->get('unavailable_vehicles/{id}/edit', 'UnavailableVehiclesController@edit');
        $api->put('unavailable_vehicles/{id}', 'UnavailableVehiclesController@update');
        $api->delete('unavailable_vehicles/{id}', 'UnavailableVehiclesController@destroy');
    });
});
