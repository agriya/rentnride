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
 
namespace App\Services;

use App\Country;

class CountryService
{
    public function getCountryId($code, $name)
    {
        $country = Country::where('iso2', strtoupper($code))->first();
        if ($country) {
            return $country->id;
        } else {
            $country_obj = array();
            $country_obj['iso2'] = strtoupper($code);
            $country_obj['name'] = $name;
            $country_obj['is_active'] = true;
            $country = Country::create($country_obj);
            return $country->id;
        }
    }
}
