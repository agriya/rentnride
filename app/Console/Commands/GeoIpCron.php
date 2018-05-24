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
use League\Flysystem\Exception;
use Log;

class GeoIpCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ip:cron';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron command to update the ip table';
    /**
     * @var
     */
    protected $countryService;
    /**
     * @var
     */
    protected $cityService;
    /**
     * @var
     */
    protected $ipService;


    /**
     *  Execute the console command.
     */
    public function handle()
    {

        $ips = \App\Ip::where('is_checked', false)->take(5)->get();
        $client = new \GuzzleHttp\Client();
        try {
            foreach ($ips as $ip) {
                $res = $client->request('GET', 'geoip.nekudo.com/api/'.$ip->ip);
                $code = $res->getStatusCode();
                if($code == 200) {
                    $body = $res->getBody();
                    $content = $body->getContents();
                    $response = json_decode($content);
                    if (isset($response->country) && isset($response->country->code)) {
                        $countryService = new \App\Services\CountryService();
                        $country_id = $countryService->getCountryId($response->country->code, $response->country->name);
                        $ip_arr['country_id'] = $country_id;
                        if (isset($response->city)) {
                            $cityService = new \App\Services\CityService();
                            $city_id = $cityService->getCityId($response->city, $country_id);
                            $ip_arr['city_id'] = $city_id;
                        }
                    }
                    if (isset($response->location)) {
                        $ip_arr['latitude'] = $response->location->latitude;
                        $ip_arr['longitude'] = $response->location->longitude;
                    }
                    $ip_arr['is_checked'] = true;
                    $ipService = new \App\Services\IpService();
                    $ipService->updateIpById($ip->id, $ip_arr);
                } else {
                    Log::info("error");
                }
            }
        } catch(Exception $e) {
           Log::info("message", array($e->getMessage()));
        }
    }
}