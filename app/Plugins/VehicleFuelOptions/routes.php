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
    $api->group(['prefix' => 'admin', 'namespace' => 'Plugins\VehicleFuelOptions\Controllers\Admin', 'middleware' => 'apitracking'], function () use ($api) {
        // FuelOptions admin side        
        $api->get('vehicle_fuel_options', 'AdminVehicleFuelOptionsController@index');
        $api->post('vehicle_fuel_options', 'AdminVehicleFuelOptionsController@store');
        $api->put('vehicle_fuel_options/{id}', 'AdminVehicleFuelOptionsController@update');
        $api->get('vehicle_fuel_options/{id}/edit', 'AdminVehicleFuelOptionsController@edit');
        $api->get('vehicle_fuel_options/{id}', 'AdminVehicleFuelOptionsController@show');
        $api->delete('vehicle_fuel_options/{id}', 'AdminVehicleFuelOptionsController@destroy');

        // Vehicle Type FuelOptions admin side
        $api->get('vehicle_type_fuel_options', 'AdminVehicleTypeFuelOptionsController@index');
        $api->post('vehicle_type_fuel_options', 'AdminVehicleTypeFuelOptionsController@store');
        $api->put('vehicle_type_fuel_options/{id}', 'AdminVehicleTypeFuelOptionsController@update');
        $api->get('vehicle_type_fuel_options/{id}/edit', 'AdminVehicleTypeFuelOptionsController@edit');
        $api->get('vehicle_type_fuel_options/{id}', 'AdminVehicleTypeFuelOptionsController@show');
        $api->delete('vehicle_type_fuel_options/{id}', 'AdminVehicleTypeFuelOptionsController@destroy');

    });
    $api->group(['namespace' => 'Plugins\VehicleFuelOptions\Controllers', 'middleware' => 'apitracking'], function () use ($api) {
        //fuel_options user side
        $api->get('vehicle_fuel_options', 'VehicleFuelOptionsController@index');
        $api->get('vehicle_type_fuel_options', 'VehicleTypeFuelOptionsController@index');
    });
});
