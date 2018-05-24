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

class StatusUpdateCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'status_update:cron';

    /**
     * @var
     */
    protected $VehicleRentalService;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron command to update daily status updates';

    /**
     * StatusUpdateCron constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *  Execute the console command.
     */
    public function handle()
    {
        if (isPluginEnabled('VehicleRentals')) {
            $VehicleRentalService = new \Plugins\VehicleRentals\Services\VehicleRentalService;
            $VehicleRentalService->autoUpdateStatus();
        }
    }
}