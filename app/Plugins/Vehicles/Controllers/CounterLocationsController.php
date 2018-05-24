<?php
/**
 * Plugin
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
 
namespace Plugins\Vehicles\Controllers;


use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Plugins\Vehicles\Model\CounterLocation;
use JWTAuth;
use Validator;
use Plugins\Vehicles\Transformers\CounterLocationTransformer;


/**
 * Class CounterLocationsController
 * @package Plugins\Vehicles\Controllers
 */
class CounterLocationsController extends Controller
{

    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth', ['except' => ['index']]);
    }

    /**
     * Show all counter locations.
     * Get a JSON representation of all the counter locations.
     *
     * @Get("/counter_locations?filter={filter}&sort={sort}&sortby={sortby}&q={q}")
     * @Parameters({
     *      @Parameter("filter", type="integer", required=false, description="Filter the counter_locations list by status.", default=null),
     *      @Parameter("sort", type="string", required=false, description="Sort the counter_locations list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort counter_locations by Ascending / Descending Order.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1)
     * })
     */
    public function index(Request $request)
    {
        $counter_location_count = config('constants.ConstPageLimit');
        if ($request->has('type') && $request->type == 'list') {
            $counter_location_count = CounterLocation::count();
        }
        $counter_locations = CounterLocation::filterByRequest($request)->paginate($counter_location_count);
        return $this->response->paginator($counter_locations, new CounterLocationTransformer);
    }

}
