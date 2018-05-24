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
    $api->group(['prefix' => 'admin', 'namespace' => 'Plugins\VehicleFeedbacks\Controllers\Admin', 'middleware' => 'apitracking'], function () use ($api) {
        // Vehicle feedbacks admin side
        $api->get('vehicle_feedbacks', 'AdminVehicleFeedbacksController@index');
        $api->put('vehicle_feedbacks/{id}', 'AdminVehicleFeedbacksController@update');
        $api->get('vehicle_feedbacks/{id}/edit', 'AdminVehicleFeedbacksController@edit');
        $api->get('vehicle_feedbacks/{id}', 'AdminVehicleFeedbacksController@show');
        $api->delete('vehicle_feedbacks/{id}', 'AdminVehicleFeedbacksController@destroy');

    });
    $api->group(['namespace' => 'Plugins\VehicleFeedbacks\Controllers', 'middleware' => 'apitracking'], function () use ($api) {
        //Vehicle feedbacks user side
        $api->get('vehicle_feedbacks', 'VehicleFeedbacksController@index');
        $api->get('vehicle_feedbacks/{id}', 'VehicleFeedbacksController@show');
        $api->post('booker/review', 'VehicleFeedbacksController@BookerReview');
        $api->post('host/review', 'VehicleFeedbacksController@HostReview');
    });
});
