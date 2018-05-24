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
    $api->group(['prefix' => 'admin', 'namespace' => 'Plugins\VehicleCoupons\Controllers\Admin', 'middleware' => 'apitracking'], function () use ($api) {
        // coupons admin side        
        $api->get('vehicle_coupons', 'AdminVehicleCouponsController@index');
        $api->post('vehicle_coupons', 'AdminVehicleCouponsController@store');
        $api->put('vehicle_coupons/{id}', 'AdminVehicleCouponsController@update');
        $api->get('vehicle_coupons/{id}/edit', 'AdminVehicleCouponsController@edit');
        $api->get('vehicle_coupons/{id}', 'AdminVehicleCouponsController@show');
        $api->delete('vehicle_coupons/{id}', 'AdminVehicleCouponsController@destroy');

    });
    $api->group(['namespace' => 'Plugins\VehicleCoupons\Controllers', 'middleware' => 'apitracking'], function () use ($api) {
        //coupons user side
        $api->post('vehicle_coupons/{item_user_id}', 'VehicleCouponsController@applyCoupon');
    });
});
