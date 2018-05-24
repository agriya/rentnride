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
 
namespace Plugins\Paypal\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class PaypalServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     * @return void
     */
    public function register()
    {
        include __DIR__ . '/../routes.php';
        $this->app->make('Plugins\Paypal\Controllers\PaypalController');
        $this->app->make('Plugins\Paypal\Controllers\Admin\AdminPaypalTransactionLogsController');
    }

    /**
     * Bootstrap the application services.
     * @return void
     */
    public function boot()
    {
        $enabledIncludes = array();
        $enabledIncludes['MorphWallet'] = \App\Wallet::class;
        Relation::morphMap($enabledIncludes);
    }
}
