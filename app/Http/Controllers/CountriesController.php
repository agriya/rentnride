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

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Country;
use App\Transformers\CountryTransformer;

/**
 * Countries resource representation.
 * @Resource("Countries")
 */
class CountriesController extends Controller
{
    /**
     * CountriesController constructor.
     */
    public function __construct()
    {
        // Apply the jwt.auth middleware to all methods in this controller
        // except for the authenticate method. We don't want to prevent
        // the user from retrieving their token if they don't already have it
        //  $this->middleware('jwt.auth');
    }

    /**
     * Show all countries
     * Get a JSON representation of all the countries.
     * @Get("/countries?sort={sort}")
     * @Parameters({
     *      @Parameter("sort", type="string", required=false, description="Sort the countries list by sort ley.", default=null),
     * })
     */
    public function index(Request $request)
    {
        $countries = Country::filterByRequest($request)->get();
        return $this->response->collection($countries, new CountryTransformer);
    }

}
