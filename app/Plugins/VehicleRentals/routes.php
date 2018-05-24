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

    //Admin VehicleRental
    $api->group(['prefix' => 'admin', 'namespace' => 'Plugins\VehicleRentals\Controllers\Admin', 'middleware' => 'apitracking'], function () use ($api) {
        $api->get('vehicle_rentals', 'AdminVehicleRentalsController@index');
        $api->get('vehicle_rentals/{id}', 'AdminVehicleRentalsController@show');
        $api->put('vehicle_rentals/{id}/cancelled-by-admin', 'AdminVehicleRentalsController@CancelledByAdmin');
        $api->get('vehicle_rentals/{id}/checkin', 'AdminVehicleRentalsController@checkin');
        $api->post('vehicle_rentals/{id}/checkout', 'AdminVehicleRentalsController@checkout');
    });
    //User VehicleRental
    $api->group(['namespace' => 'Plugins\VehicleRentals\Controllers', 'middleware' => 'apitracking'], function () use ($api) {
        $api->get('vehicle_rentals', 'VehicleRentalsController@index');
        $api->post('vehicle_rentals', 'VehicleRentalsController@store');
        $api->get('vehicle_rentals/{id}/edit', 'VehicleRentalsController@edit');
        $api->put('vehicle_rentals/{id}', 'VehicleRentalsController@update');
        $api->get('vehicle_rentals/{id}', 'VehicleRentalsController@show');
        $api->post('vehicle_rentals/{id}/paynow', 'VehicleRentalsController@payNow');
        $api->get('vehicle_rentals/{id}/reject', 'VehicleRentalsController@reject');
        $api->get('vehicle_rentals/{id}/cancel', 'VehicleRentalsController@cancel');
        $api->get('vehicle_rentals/{id}/confirm', 'VehicleRentalsController@confirmed');
        $api->get('vehicle_rentals/{id}/checkin', 'VehicleRentalsController@checkin');
        $api->post('vehicle_rentals/{id}/checkout', 'VehicleRentalsController@checkout');
        $api->get('item_orders', 'VehicleRentalsController@itemOrders');
        $api->get('vehicle_rental_status', 'VehicleRentalStatusesController@index');
    });
});
