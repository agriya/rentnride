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
    $api->group(['prefix' => 'admin', 'namespace' => 'Plugins\SocialLogins\Controllers\Admin', 'middleware' => 'apitracking'], function () use ($api) {
        // providers admin side
        $api->get('providers', 'AdminProvidersController@index');
        $api->get('providers/{id}/edit', 'AdminProvidersController@edit');
        $api->get('providers/{id}', 'AdminProvidersController@edit');
        $api->put('providers/{id}', 'AdminProvidersController@update');
        $api->delete('providers/{id}', 'AdminProvidersController@destroy');
    });
    $api->group(['namespace' => 'Plugins\SocialLogins\Controllers', 'middleware' => 'apitracking'], function () use ($api) {
        //Providers user side
        $api->get('/providers', 'ProvidersController@index');

        $api->get('/auth/{provider}', 'SocialLoginsController@getAuthProviders');

        $api->post('/auth/facebook', 'SocialLoginsController@facebook');
        $api->post('/auth/google', 'SocialLoginsController@google');
        $api->post('/auth/linkedin', 'SocialLoginsController@linkedin');
        $api->post('/auth/twitter', 'SocialLoginsController@twitter');
        $api->post('/auth/github', 'SocialLoginsController@github');
        $api->get('/provider_users', 'SocialLoginsController@getProviderUsers');
        $api->post('/auth/unlink', 'SocialLoginsController@unlink');
        $api->post('/update_profile', 'SocialLoginsController@update_profile');
        $api->post('/social_login', 'SocialLoginsController@socialLoginWithEmail');
    });
});
