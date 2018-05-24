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
    $api->group(['prefix' => 'admin', 'namespace' => 'Plugins\VehicleSurcharges\Controllers\Admin', 'middleware' => 'apitracking'], function () use ($api) {
        // Surcharges admin side        
        $api->get('vehicle_surcharges', 'AdminVehicleSurchargesController@index');
        $api->post('vehicle_surcharges', 'AdminVehicleSurchargesController@store');
        $api->put('vehicle_surcharges/{id}', 'AdminVehicleSurchargesController@update');
        $api->get('vehicle_surcharges/{id}/edit', 'AdminVehicleSurchargesController@edit');
        $api->get('vehicle_surcharges/{id}', 'AdminVehicleSurchargesController@show');
        $api->delete('vehicle_surcharges/{id}', 'AdminVehicleSurchargesController@destroy');

        // Vehicle Type Surcharges admin side
        $api->get('vehicle_type_surcharges', 'AdminVehicleTypeSurchargesController@index');
        $api->post('vehicle_type_surcharges', 'AdminVehicleTypeSurchargesController@store');
        $api->put('vehicle_type_surcharges/{id}', 'AdminVehicleTypeSurchargesController@update');
        $api->get('vehicle_type_surcharges/{id}/edit', 'AdminVehicleTypeSurchargesController@edit');
        $api->get('vehicle_type_surcharges/{id}', 'AdminVehicleTypeSurchargesController@show');
        $api->delete('vehicle_type_surcharges/{id}', 'AdminVehicleTypeSurchargesController@destroy');

    });
    $api->group(['namespace' => 'Plugins\VehicleSurcharges\Controllers', 'middleware' => 'apitracking'], function () use ($api) {
        //surcharges user side
        $api->get('vehicle_surcharges', 'VehicleSurchargesController@index');
        $api->get('vehicle_type_surcharges', 'VehicleTypeSurchargesController@index');
    });
});
