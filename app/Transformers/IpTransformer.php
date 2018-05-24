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
use App\Ip;

/**
 * Class IpTransformer
 * @package App\Transformers
 */
class IpTransformer extends Fractal\TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'City', 'State', 'Country'
    ];

    /**
     * @param Ip $ip
     * @return array
     */
    public function transform(Ip $ip)
    {
        $output = array_only($ip->toArray(), ['id', 'ip', 'city_id', 'state_id', 'country_id', 'latitude', 'longitude']);
        return $output;
    }

    /**
     * @param Ip $ip
     * @return Fractal\Resource\Item
     */
    public function includeCity(Ip $ip)
    {
        if ($ip->city) {
            return $this->item($ip->city, new CityTransformer());
        } else {
            return null;
        }
    }

    /**
     * @param Ip $ip
     * @return Fractal\Resource\Item
     */
    public function includeState(Ip $ip)
    {
        if ($ip->state) {
            return $this->item($ip->state, new StateTransformer());
        } else {
            return null;
        }

    }

    /**
     * @param Ip $ip
     * @return Fractal\Resource\Item
     */
    public function includeCountry(Ip $ip)
    {
        if ($ip->country) {
            return $this->item($ip->country, new CountryTransformer());
        } else {
            return null;
        }

    }
}