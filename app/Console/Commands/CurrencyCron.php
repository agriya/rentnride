<?php
/**
 * APP - Console
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
 
namespace App\Console\Commands;

use Illuminate\Console\Command;

class CurrencyCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:cron';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron command to update daily currency rates';

    /**
     *  Execute the console command.
     */
    public function handle()
    {
        if (isPluginEnabled('CurrencyConversions')) {
            $currencyService = new \Plugins\CurrencyConversions\Services\CurrencyConversionService();
            $currencyService->currencyconversion();
        }
    }
}