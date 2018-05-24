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
    $api->group(['prefix' => 'admin', 'namespace' => 'Plugins\VehicleTaxes\Controllers\Admin', 'middleware' => 'apitracking'], function () use ($api) {
        // Taxes admin side        
        $api->get('vehicle_taxes', 'AdminVehicleTaxesController@index');
        $api->post('vehicle_taxes', 'AdminVehicleTaxesController@store');
        $api->put('vehicle_taxes/{id}', 'AdminVehicleTaxesController@update');
        $api->get('vehicle_taxes/{id}/edit', 'AdminVehicleTaxesController@edit');
        $api->get('vehicle_taxes/{id}', 'AdminVehicleTaxesController@show');
        $api->delete('vehicle_taxes/{id}', 'AdminVehicleTaxesController@destroy');

        // Vehicle Type Taxes admin side
        $api->get('vehicle_type_taxes', 'AdminVehicleTypeTaxesController@index');
        $api->post('vehicle_type_taxes', 'AdminVehicleTypeTaxesController@store');
        $api->put('vehicle_type_taxes/{id}', 'AdminVehicleTypeTaxesController@update');
        $api->get('vehicle_type_taxes/{id}/edit', 'AdminVehicleTypeTaxesController@edit');
        $api->get('vehicle_type_taxes/{id}', 'AdminVehicleTypeTaxesController@show');
        $api->delete('vehicle_type_taxes/{id}', 'AdminVehicleTypeTaxesController@destroy');

    });
    $api->group(['namespace' => 'Plugins\VehicleTaxes\Controllers', 'middleware' => 'apitracking'], function () use ($api) {
        //taxes user side
        $api->get('vehicle_taxes', 'VehicleTaxesController@index');
        $api->get('vehicle_type_taxes', 'VehicleTypeTaxesController@index');
    });
});
