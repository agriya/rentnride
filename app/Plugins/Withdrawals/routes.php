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
    $api->group(['prefix' => 'admin', 'namespace' => 'Plugins\Withdrawals\Controllers\Admin', 'middleware' => 'apitracking'], function () use ($api) {
        $api->delete('user_cash_withdrawals/{id}', 'AdminUserCashWithdrawalsController@destroy');
        $api->get('user_cash_withdrawals', 'AdminUserCashWithdrawalsController@index');
        $api->get('user_cash_withdrawals/{id}/edit', 'AdminUserCashWithdrawalsController@edit');
        $api->get('user_cash_withdrawals/{id}', 'AdminUserCashWithdrawalsController@edit');
        $api->put('user_cash_withdrawals/{id}', 'AdminUserCashWithdrawalsController@update');
        // withdrawal status
        $api->get('withdrawal_statuses', 'AdminWithdrawalStatusesController@index');
    });
    $api->group(['namespace' => 'Plugins\Withdrawals\Controllers', 'middleware' => 'apitracking'], function () use ($api) {
        // Money transfer accounts
        $api->delete('/money_transfer_accounts/{id}', 'MoneyTransferAccountsController@destroy');
        $api->get('/money_transfer_accounts', 'MoneyTransferAccountsController@index');
        $api->post('/money_transfer_accounts', 'MoneyTransferAccountsController@store');
        // user cash withdrawals
        $api->get('/user_cash_withdrawals', 'UserCashWithdrawalsController@index');
        $api->post('/user_cash_withdrawals', 'UserCashWithdrawalsController@store');
        // withdrawal status
        $api->get('/withdrawal_statuses', 'WithdrawalStatusesController@index');
    });
});
