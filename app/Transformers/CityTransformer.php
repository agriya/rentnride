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
use App\City;

/**
 * Class CityTransformer
 * @package App\Transformers
 */
class CityTransformer extends Fractal\TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'State', 'Country'
    ];

    /**
     * @param City $city
     * @return array
     */
    public function transform(City $city)
    {
        $output = array_only($city->toArray(), ['id', 'name', 'state_id', 'country_id', 'is_active', 'latitude', 'longitude']);
        $output['is_active'] = ($output['is_active'] == 1) ? true : false;
        return $output;
    }

    /**
     * @param City $city
     * @return Fractal\Resource\Item
     */
    public function includeState(City $city)
    {
        if ($city->state) {
            return $this->item($city->state, new StateTransformer());
        } else {
            return null;
        }

    }

    /**
     * @param City $city
     * @return Fractal\Resource\Item
     */
    public function includeCountry(City $city)
    {
        if ($city->country) {
            return $this->item($city->country, new CountryTransformer());
        } else {
            return null;
        }

    }
}