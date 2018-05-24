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
    $api->group(['prefix' => 'admin', 'namespace' => 'Plugins\VehicleDisputes\Controllers\Admin', 'middleware' => 'apitracking'], function () use ($api) {
        // disputes admin side        
        $api->get('vehicle_disputes', 'AdminVehicleDisputesController@index');
        $api->post('vehicle_disputes/resolve', 'AdminVehicleDisputesController@resolve');
        // vehicle dispute types
        $api->get('vehicle_dispute_types', 'AdminVehicleDisputeTypesController@index');
        $api->get('vehicle_dispute_types/{id}/edit', 'AdminVehicleDisputeTypesController@edit');
        $api->get('vehicle_dispute_types/{id}', 'AdminVehicleDisputeTypesController@edit');
        $api->put('vehicle_dispute_types/{id}', 'AdminVehicleDisputeTypesController@update');
        // vehicle dispute closed types
        $api->get('vehicle_dispute_closed_types', 'AdminVehicleDisputeClosedTypesController@index');
        $api->get('vehicle_dispute_closed_types/{id}/edit', 'AdminVehicleDisputeClosedTypesController@edit');
        $api->get('vehicle_dispute_closed_types/{id}', 'AdminVehicleDisputeClosedTypesController@edit');
        $api->put('vehicle_dispute_closed_types/{id}', 'AdminVehicleDisputeClosedTypesController@update');
    });
    $api->group(['namespace' => 'Plugins\VehicleDisputes\Controllers', 'middleware' => 'apitracking'], function () use ($api) {
        //disputes user side
        $api->get('vehicle_disputes', 'VehicleDisputesController@index');
        $api->post('vehicle_disputes/add', 'VehicleDisputesController@store');
        $api->get('vehicle_disputes/{id}', 'VehicleDisputesController@getPossibleDisputeTypes');

        $api->get('vehicle_dispute_types', 'VehicleDisputeTypesController@index');

        $api->get('vehicle_dispute_closed_types', 'VehicleDisputeClosedTypesController@index');
    });
});
