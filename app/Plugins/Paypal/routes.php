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

    $api->group(['prefix' => 'admin', 'namespace' => 'Plugins\Paypal\Controllers\Admin', 'middleware' => 'apitracking'], function () use ($api) {
        $api->get('paypal_transaction_logs', 'AdminPaypalTransactionLogsController@index');
        $api->get('paypal_transaction_logs/{id}', 'AdminPaypalTransactionLogsController@show');
    });

    // Paypal
    $api->get('paypal/process_payment', 'Plugins\Paypal\Controllers\PaypalController@processPayment');
    $api->get('paypal/cancel', 'Plugins\Paypal\Controllers\PaypalController@getCancel');
});
