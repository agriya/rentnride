<?php
/**
 * Rent & Ride
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
 
namespace App\Http\Middleware;

use Cache;
use Closure;
use Illuminate\Support\Facades\Auth;
use App\ApiRequest;
use App\Services\IpService;
use Log;

class Tracking
{
    /**
     * @var IpService
     */
    protected $IpService;
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Authenticate constructor.
     * @param Auth $auth
     */
    public function __construct()
    {
        $this->setIpService();
    }

    public function setIpService()
    {
        $this->IpService = new IpService();
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        //@todo: ip address need remove and  update dynamic domain name
        if (!Cache::has('site_url_for_shell')) {
            Cache::forever('site_url_for_shell', $request->root());
        }
        $response = $next($request);
        $api_request = new ApiRequest();
        if (Auth::user()) {
            $user = Auth::user()->toArray();
            $api_request->user_id = $user['id'];
        }
        $api_request->path = $request->fullUrl();
        $api_request->method = $request->method();
        $api_request->http_response_code = $response->status();
        $api_request->ip_id = $this->IpService->getIpId($request->ip());
        $api_request->save();
        return $response;
    }
}