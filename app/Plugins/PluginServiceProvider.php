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
 
namespace Plugins;

use Illuminate\Support\ServiceProvider;
use File;

class PluginServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $enabled_plugins = enabled_plugins();
        foreach ($enabled_plugins as $plugins) {
            foreach (glob(base_path("app/Plugins/$plugins/Providers/*.php")) as $files) {
                $filename = File::name($files);
                $file_path = 'Plugins' . '\\' . $plugins . '\\Providers' . '\\' . $filename;
                $this->app->register($file_path);
            }
        }
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

    }
}
