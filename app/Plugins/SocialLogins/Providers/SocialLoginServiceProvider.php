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
 
namespace Plugins\SocialLogins\Providers;

use Illuminate\Support\ServiceProvider;
use Schema;
use Plugins\SocialLogins\Model\Provider;

class SocialLoginServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__ . '/../routes.php';
        $this->app->make('Plugins\SocialLogins\Controllers\SocialLoginsController');
        $this->app->make('Plugins\SocialLogins\Controllers\ProvidersController');
        $this->app->make('Plugins\SocialLogins\Controllers\Admin\AdminProvidersController');
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if (Schema::hasTable('providers')) {
            $providers = Provider::where('is_active', 1)->get();
            foreach ($providers as $value) {
                config()->set($value->name, $value);
            }
        }
    }
}
