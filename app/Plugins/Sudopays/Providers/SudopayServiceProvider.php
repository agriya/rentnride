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
 
namespace Plugins\Sudopays\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class SudopayServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     * @return void
     */
    public function register()
    {
        include __DIR__ . '/../routes.php';
        $this->app->make('Plugins\Sudopays\Controllers\Admin\AdminSudopayTransactionLogsController');
        $this->app->make('Plugins\Sudopays\Controllers\Admin\AdminSudopayIpnLogsController');
        $this->app->make('Plugins\Sudopays\Controllers\Admin\AdminSudopaysController');
    }

    /**
     * Bootstrap the application services.
     * @return void
     */
    public function boot()
    {
        $enabledIncludes = array();
        $enabledIncludes['MorphWallet'] = \App\Wallet::class;
        if (!empty($enabledIncludes)) {
            Relation::morphMap($enabledIncludes);
        }
    }
}
