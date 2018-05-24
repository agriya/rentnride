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
 
namespace Plugins\VehicleFuelOptions\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Plugins\VehicleFuelOptions\Model\VehicleFuelOption;
use JWTAuth;
use Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Plugins\VehicleFuelOptions\Transformers\VehicleFuelOptionTransformer;

/**
 * VehicleFuelOptions resource representation.
 * @Resource("VehicleFuelOptions")
 */
class VehicleFuelOptionsController extends Controller
{
    /**
     * VehicleFuelOptionsController constructor.
     */
    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
    }

    /**
     * Show all fuel_options
     * Get a JSON representation of all the fuel_options.
     *
     * @Get("/vehicle_fuel_options?sort={sort}&sortby={sortby}&page={page}&q={q}")
     * @Parameters({
     *      @Parameter("sort", type="string", required=false, description="Sort the fuel_options list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort fuel_options by Ascending / Descending Order.", default=null),
     *      @Parameter("q", type="string", required=false, description="Search VehicleFuelOptions.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1)
     * })
     */
    public function index(Request $request)
    {
        $fuel_options = VehicleFuelOption::filterByRequest($request)->paginate(config('constants.ConstPageLimit'));
        return $this->response->paginator($fuel_options, (new VehicleFuelOptionTransformer));
    }
}
