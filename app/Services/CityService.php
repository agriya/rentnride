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

use App\City;

class CityService
{
    public function getCityId($name, $country_id)
    {
        $city = City::where(['country_id' => $country_id, 'name' => $name])->first();
        if ($city) {
            return $city->id;
        } else {
            $city_obj = array();
            $city_obj['country_id'] = $country_id;
            $city_obj['name'] = $name;
            $city_obj['is_active'] = true;
            $city = City::create($city_obj);
            return $city->id;
        }
    }
}
