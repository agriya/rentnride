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
 
namespace App\Transformers;

use League\Fractal;
use App\ApiRequest;

/**
 * Class ApiRequestTransformer
 * @package App\Transformers
 */
class ApiRequestTransformer extends Fractal\TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'User', 'Ip'
    ];

    /**
     * @param ApiRequest $api_request
     * @return array
     */
    public function transform(ApiRequest $api_request)
    {
        $output = array_only($api_request->toArray(), ['id', 'created_at', 'user_id', 'ip_id', 'path', 'method', 'http_response_code']);
        return $output;
    }

    /**
     * @param ApiRequest $api_request
     * @return Fractal\Resource\Item
     */
    public function includeUser(ApiRequest $api_request)
    {
        if ($api_request->user) {
            return $this->item($api_request->user, new UserTransformer());
        } else {
            return null;
        }

    }

    /**
     * @param ApiRequest $api_request
     * @return Fractal\Resource\Item
     */
    public function includeIp(ApiRequest $api_request)
    {
        if ($api_request->ip) {
            return $this->item($api_request->ip, new IpTransformer());
        } else {
            return null;
        }

    }
}