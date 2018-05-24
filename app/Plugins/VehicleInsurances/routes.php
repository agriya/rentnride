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
    $api->group(['prefix' => 'admin', 'namespace' => 'Plugins\VehicleInsurances\Controllers\Admin', 'middleware' => 'apitracking'], function () use ($api) {
        // Insurances admin side        
        $api->get('vehicle_insurances', 'AdminVehicleInsurancesController@index');
        $api->post('vehicle_insurances', 'AdminVehicleInsurancesController@store');
        $api->put('vehicle_insurances/{id}', 'AdminVehicleInsurancesController@update');
        $api->get('vehicle_insurances/{id}/edit', 'AdminVehicleInsurancesController@edit');
        $api->get('vehicle_insurances/{id}', 'AdminVehicleInsurancesController@show');
        $api->delete('vehicle_insurances/{id}', 'AdminVehicleInsurancesController@destroy');

        // Vehicle Type Insurances admin side
        $api->get('vehicle_type_insurances', 'AdminVehicleTypeInsurancesController@index');
        $api->post('vehicle_type_insurances', 'AdminVehicleTypeInsurancesController@store');
        $api->put('vehicle_type_insurances/{id}', 'AdminVehicleTypeInsurancesController@update');
        $api->get('vehicle_type_insurances/{id}/edit', 'AdminVehicleTypeInsurancesController@edit');
        $api->get('vehicle_type_insurances/{id}', 'AdminVehicleTypeInsurancesController@show');
        $api->delete('vehicle_type_insurances/{id}', 'AdminVehicleTypeInsurancesController@destroy');

    });
    $api->group(['namespace' => 'Plugins\VehicleInsurances\Controllers', 'middleware' => 'apitracking'], function () use ($api) {
        //insurances user side
        $api->get('vehicle_insurances', 'VehicleInsurancesController@index');
        $api->get('vehicle_type_insurances', 'VehicleTypeInsurancesController@index');
    });
});
