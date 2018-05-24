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
 
namespace app\Transformers;

use League\Fractal;
use App\Country;

/**
 * Class CountryTransformer
 * @package app\Transformers
 */
class CountryTransformer extends Fractal\TransformerAbstract
{
    /**
     * @param Country $country
     * @return array
     */
    public function transform(Country $country)
    {
        $output = array_only($country->toArray(), ['id', 'name', 'iso2', 'iso3', 'is_active']);
        $output['is_active'] = ($output['is_active'] == 1) ? true : false;
        return $output;
    }
}