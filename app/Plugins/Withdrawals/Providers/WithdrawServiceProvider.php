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
 
namespace Plugins\Withdrawals\Providers;

use Illuminate\Support\ServiceProvider;

class WithdrawServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__ . '/../routes.php';
        $this->app->make('Plugins\Withdrawals\Controllers\MoneyTransferAccountsController');
        $this->app->make('Plugins\Withdrawals\Controllers\UserCashWithdrawalsController');
        $this->app->make('Plugins\Withdrawals\Controllers\Admin\AdminUserCashWithdrawalsController');
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
