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

    $api->group(['prefix' => 'admin', 'namespace' => 'Plugins\Sudopays\Controllers\Admin', 'middleware' => 'apitracking'], function () use ($api) {
        $api->get('sudopay_transaction_logs', 'AdminSudopayTransactionLogsController@index');
        $api->get('sudopay_transaction_logs/{id}', 'AdminSudopayTransactionLogsController@show');
        $api->get('sudopay_ipn_logs', 'AdminSudopayIpnLogsController@index');
        $api->get('sudopay_ipn_logs/{id}', 'AdminSudopayIpnLogsController@show');
        $api->get('sudopay/synchronize', 'AdminSudopaysController@synchronize');
    });
    $api->group(['namespace' => 'Plugins\Sudopays\Controllers', 'middleware' => 'apitracking'], function () use ($api) {
        $api->post('sudopay/process_payment/{id}', 'SudopaysController@processPayment');
        $api->get('sudopay/cancel_payment/{id}', 'SudopaysController@cancelPayment');
        $api->get('sudopay/success_payment/{id}', 'SudopaysController@successPayment');
    });
});
