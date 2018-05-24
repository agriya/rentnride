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
    $api->group(['prefix' => 'admin', 'namespace' => 'Plugins\VehicleExtraAccessories\Controllers\Admin', 'middleware' => 'apitracking'], function () use ($api) {
        // ExtraAccessories admin side        
        $api->get('vehicle_extra_accessories', 'AdminVehicleExtraAccessoriesController@index');
        $api->post('vehicle_extra_accessories', 'AdminVehicleExtraAccessoriesController@store');
        $api->put('vehicle_extra_accessories/{id}', 'AdminVehicleExtraAccessoriesController@update');
        $api->get('vehicle_extra_accessories/{id}/edit', 'AdminVehicleExtraAccessoriesController@edit');
        $api->get('vehicle_extra_accessories/{id}', 'AdminVehicleExtraAccessoriesController@show');
        $api->delete('vehicle_extra_accessories/{id}', 'AdminVehicleExtraAccessoriesController@destroy');

        // Vehicle Type ExtraAccessories admin side
        $api->get('vehicle_type_extra_accessories', 'AdminVehicleTypeExtraAccessoriesController@index');
        $api->post('vehicle_type_extra_accessories', 'AdminVehicleTypeExtraAccessoriesController@store');
        $api->put('vehicle_type_extra_accessories/{id}', 'AdminVehicleTypeExtraAccessoriesController@update');
        $api->get('vehicle_type_extra_accessories/{id}/edit', 'AdminVehicleTypeExtraAccessoriesController@edit');
        $api->get('vehicle_type_extra_accessories/{id}', 'AdminVehicleTypeExtraAccessoriesController@show');
        $api->delete('vehicle_type_extra_accessories/{id}', 'AdminVehicleTypeExtraAccessoriesController@destroy');

    });
    $api->group(['namespace' => 'Plugins\VehicleExtraAccessories\Controllers', 'middleware' => 'apitracking'], function () use ($api) {
        //extra_accessories user side
        $api->get('vehicle_extra_accessories', 'VehicleExtraAccessoriesController@index');
        $api->get('vehicle_type_extra_accessories', 'VehicleTypeExtraAccessoriesController@index');
    });
});
