<?php
/**
 * APP - Http
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

$api = $app->make('Dingo\Api\Routing\Router');

$value = config('app.timezone');
date_default_timezone_set($value);

$app->get('/', function () use ($app) {
    return $app->version();
});

$app->get('robots.txt', function () use ($app) {
    return config('site.robots');
});

$app->get('clear_cache', function () use ($app) {
    Cache::forget('settings_data');
    Cache::forget('site_url_for_shell');
    return response()->json(['Success' => 'setting cache cleared'], 200);
});

$api->version(['v1'], function ($api) use ($app) {
    $api->group(['prefix' => 'admin', 'namespace' => 'App\Http\Controllers\Admin', 'middleware' => 'apitracking'], function () use ($api) {

        //Api Requests
        $api->get('api_requests', 'AdminApiRequestsController@index');
        $api->get('api_requests/{id}', 'AdminApiRequestsController@show');
        $api->delete('api_requests/{id}', 'AdminApiRequestsController@destroy');

        // cities admin side
        $api->delete('cities/{id}', 'AdminCitiesController@destroy');
        $api->get('cities', 'AdminCitiesController@index');
        $api->get('cities/{id}/edit', 'AdminCitiesController@edit');
        $api->get('cities/{id}', 'AdminCitiesController@edit');
        $api->post('cities', 'AdminCitiesController@store');
        $api->put('cities/{id}', 'AdminCitiesController@update');				
        $api->put('cities/{id}/deactive', 'AdminCitiesController@deactive');
        $api->put('cities/{id}/active', 'AdminCitiesController@active');

        // countries admin side
        $api->delete('countries/{id}', 'AdminCountriesController@destroy');
        $api->get('countries', 'AdminCountriesController@index');
        $api->get('countries/{id}/edit', 'AdminCountriesController@edit');
        $api->get('countries/{id}', 'AdminCountriesController@edit');
        $api->post('countries', 'AdminCountriesController@store');
        $api->put('countries/{id}', 'AdminCountriesController@update');		
        $api->put('countries/{id}/deactive', 'AdminCountriesController@deactive');
        $api->put('countries/{id}/active', 'AdminCountriesController@active');

        // currencies
        $api->delete('currencies/{id}', 'AdminCurrenciesController@destroy');
        $api->get('currencies', 'AdminCurrenciesController@index');
        $api->get('currencies/{id}/edit', 'AdminCurrenciesController@edit');
        $api->get('currencies/{id}', 'AdminCurrenciesController@show');
        $api->post('currencies', 'AdminCurrenciesController@store');
        $api->put('currencies/{id}', 'AdminCurrenciesController@update');

        $api->get('discount_types', 'AdminDiscountTypesController@index');
        $api->get('duration_types', 'AdminDurationTypesController@index');

        // admin dashboards
        $api->get('stats', 'AdminDashboardsController@stats');

        // email templates admin side
        $api->get('email_templates', 'AdminEmailTemplatesController@index');
        $api->get('email_templates/{id}/edit', 'AdminEmailTemplatesController@edit');
        $api->get('email_templates/{id}', 'AdminEmailTemplatesController@edit');
        $api->put('email_templates/{id}', 'AdminEmailTemplatesController@update');

        // ips
        $api->delete('ips/{id}', 'AdminIpsController@destroy');
        $api->get('ips', 'AdminIpsController@index');
        $api->get('ips/{id}', 'AdminIpsController@show');

        // languages admin side
        $api->get('languages', 'AdminLanguagesController@index');
        $api->get('languages/{id}/edit', 'AdminLanguagesController@edit');
        $api->get('languages/{id}', 'AdminLanguagesController@edit');
        $api->post('languages', 'AdminLanguagesController@store');
        $api->put('languages/{id}', 'AdminLanguagesController@update');
        $api->delete('languages/{id}', 'AdminLanguagesController@destroy');

        //Messages admin side
        $api->get('messages', 'AdminMessagesController@index');
        //$api->get('item_messages/{item_id}', 'AdminMessagesController@itemActivities');
        //$api->get('item_user_messages/{item_user_id}', 'AdminMessagesController@bookerActivities');
        //$api->get('user_messages/{user_id}', 'AdminMessagesController@userActivities');
        $api->get('messages/{id}', 'AdminMessagesController@show');
        $api->delete('messages/{id}', 'AdminMessagesController@destroy');

        // roles
        $api->get('roles', 'AdminRolesController@index');

        // Settings
        $api->get('settings', 'AdminSettingsController@index');
        $api->get('settings/{id}/edit', 'AdminSettingsController@edit');
        $api->get('settings/{id}', 'AdminSettingsController@edit');
        $api->get('settings/{name}/show', 'AdminSettingsController@show');
        $api->get('setting_categories/{id}/settings', 'AdminSettingsController@settings');
        $api->put('settings/{id}', 'AdminSettingsController@update');
        $api->get('plugins', 'AdminSettingsController@getPlugin');
        $api->put('plugins', 'AdminSettingsController@updatePlugin');

        // Setting Categories
        $api->get('setting_categories', 'AdminSettingCategoriesController@index');
        $api->get('setting_categories/{id}', 'AdminSettingCategoriesController@show');

        // states
        $api->delete('states/{id}', 'AdminStatesController@destroy');
        $api->get('states', 'AdminStatesController@index');
        $api->get('states/{id}/edit', 'AdminStatesController@edit');
        $api->get('states/{id}', 'AdminStatesController@edit');
        $api->post('states', 'AdminStatesController@store');
        $api->put('states/{id}', 'AdminStatesController@update');
        $api->put('states/{id}/deactive', 'AdminStatesController@deactive');
        $api->put('states/{id}/active', 'AdminStatesController@active');

        //Transactions admin side
        $api->get('transactions', 'AdminTransactionsController@index');

        //Transactions types admin side
        $api->get('transaction_types', 'AdminTransactionTypesController@index');
        $api->get('transaction_types/{id}', 'AdminTransactionTypesController@show');
        $api->get('transaction_types/{id}/edit', 'AdminTransactionTypesController@edit');
        $api->put('transaction_types/{id}', 'AdminTransactionTypesController@update');

        // users
        $api->delete('users/{id}', 'AdminUsersController@destroy');
        $api->get('users', 'AdminUsersController@index');
        $api->get('users/{id}', 'AdminUsersController@show');
        $api->get('users/{id}/edit', 'AdminUsersController@edit');
        $api->post('users', 'AdminUsersController@store');
        $api->put('users/{id}', 'AdminUsersController@update');
        $api->put('users/{id}/change_password', 'AdminUsersController@changePassword');
        $api->put('users/{id}/deactive', 'AdminUsersController@deactive');
        $api->put('users/{id}/active', 'AdminUsersController@active');
        // user_logins
        $api->delete('user_logins/{id}', 'AdminUserLoginsController@destroy');
        $api->get('user_logins', 'AdminUserLoginsController@index');
        $api->get('user_logins/{id}', 'AdminUserLoginsController@show');
        // wallet transaction log
        $api->get('wallet_transaction_logs', 'AdminWalletTransactionLogsController@index');
        $api->get('wallet_transaction_logs/{id}', 'AdminWalletTransactionLogsController@show');
    });
    // , 'middleware' => 'apitracking'
    $api->group(['namespace' => 'App\Http\Controllers', 'middleware' => 'apitracking'], function () use ($api) {
        //cities user side
        $api->get('/cities', 'CitiesController@index');
        //Settings user side
        $api->get('/settings', 'SettingsController@index');
        //countries user side
        $api->get('/countries', 'CountriesController@index');
        //currencies user side
        $api->get('/currencies', 'CurrenciesController@index');
        //discount types
        $api->get('discount_types', 'DiscountTypesController@index');
        // duration types
        $api->get('duration_types', 'DurationTypesController@index');

        //languages user side
        $api->get('/languages', 'LanguagesController@index');

        //Messages user side
        $api->get('messages', 'MessagesController@inbox');
        $api->get('sentMessages', 'MessagesController@sentMessage');
        $api->get('starMessages', 'MessagesController@starMessage');
        $api->get('item_messages/{item_id}', 'MessagesController@itemActivities');
        $api->get('item_user_messages/{item_user_id}', 'MessagesController@bookerActivities');
        $api->post('messages/{user_id}/user', 'MessagesController@store');
        $api->get('messages/{id}', 'MessagesController@show');
        $api->post('messages/{id}/reply', 'MessagesController@reply');
        $api->post('private_notes', 'MessagesController@privateNote');
        $api->put('messages/{id}', 'MessagesController@update');

        //states user side
        $api->get('/states', 'StatesController@index');

        //Transactions user side
        $api->get('/transactions', 'TransactionsController@index');
        // Transaction Types user side
        $api->get('/transaction_types', 'TransactionTypesController@index');

        // users
        $api->post('/users/login', 'UsersController@authenticate');
        $api->get('/users/auth', 'UsersController@getAuth');
        $api->post('/users/register', 'UsersController@register');
        $api->put('/users/{user_id}/activate/{hash}', 'UsersController@activate');
        $api->get('/user', 'UsersController@show');
        $api->put('/users/{user_id}/change_password', 'UsersController@changePassword');
        $api->put('/users/forgot_password', 'UsersController@forgotPassword');
        $api->get('users/{id}/attachment', 'UsersController@getUserUploadAttachment');
        $api->get('users/stats', 'UsersController@getStats');
        // user profiles
        $api->get('/user_profiles/', 'UserProfilesController@edit');
        $api->post('/user_profiles/', 'UserProfilesController@update');

        $api->get('/img/{size}/{model}/{filename}', 'ImagesController@create');
        $api->get('/assets/js/plugins.js', 'AssetsController@createJsTplFiles');

        //get_gateways
        $api->get('/get_gateways', 'PaymentGatewaysController@getGateways');

        //wallet
        $api->post('/wallets', 'WalletsController@addToWallet');
    });
});
