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
 
return array(
    // set your sudopay credential
    'sudopay_merchant_id' => config('sudopay.sudopay_merchant_id'),
    'sudopay_website_id' => config('sudopay.sudopay_website_id'),
    'sudopay_api_key' => config('sudopay.sudopay_api_key'),
    'sudopay_secret_string' => config('sudopay.sudopay_secret_string'),
    'is_live_mode' => config('sudopay.is_live_mode'),

);