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
    $api->group(['prefix' => 'admin', 'namespace' => 'Plugins\Pages\Controllers\Admin', 'middleware' => 'apitracking'], function () use ($api) {
        // pages admin side
        $api->get('pages', 'AdminPagesController@index');
        $api->get('pages/{id}/edit', 'AdminPagesController@edit');
        $api->get('pages/{id}', 'AdminPagesController@show');
        $api->post('pages', 'AdminPagesController@store');
        $api->put('pages/{id}', 'AdminPagesController@update');
        $api->delete('pages/{id}', 'AdminPagesController@destroy');
    });
    //Pages user side
    $api->group(['namespace' => 'Plugins\Pages\Controllers', 'middleware' => 'apitracking'], function () use ($api) {
        $api->get('/languages/{iso2}/pages', 'PagesController@getPages');
        $api->get('/page/{slug}/{iso2}', 'PagesController@show');
    });
});
