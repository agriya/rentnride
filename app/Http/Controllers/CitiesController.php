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
use App\City;
use JWTAuth;
use App\Transformers\CityTransformer;

/**
 * Cities resource representation.
 * @Resource("Cities")
 */
class CitiesController extends Controller
{
    /**
     * Show all cities
     * Get a JSON representation of all the cities.
	 *
     * @Get("/cities?sort={sort}")
     * @Parameters({
     *      @Parameter("sort", type="string", required=false, description="Sort the cities list by sort ley.", default=null),
     * })
     */
    public function index(Request $request)
    {
        $cities = City::filterByRequest($request)->get();
        return $this->response->collection($cities, new CityTransformer);
    }
}
