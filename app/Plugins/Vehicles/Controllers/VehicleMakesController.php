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
use Plugins\Vehicles\Model\VehicleMake;
use JWTAuth;
use Validator;
use Plugins\Vehicles\Transformers\VehicleMakeTransformer;

/**
 * Class VehicleMakesController
 * @package Plugins\Vehicles\Controllers
 */
class VehicleMakesController extends Controller
{

    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
    }

    /**
     * Show all vehicle makes.
     * Get a JSON representation of all the vehicle makes.
     *
     * @Get("/vehicle_makes?filter={filter}&sort={sort}&sortby={sortby}&q={q}")
     * @Parameters({
     *      @Parameter("filter", type="integer", required=false, description="Filter the vehicle makes list by status.", default=null),
     *      @Parameter("sort", type="string", required=false, description="Sort the vehicle makes list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort vehicle makes by Ascending / Descending Order.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1)
     * })
     */
    public function index(Request $request)
    {
        $vehicle_make_count = config('constants.ConstPageLimit');
        if ($request->has('type') && $request->type == 'list') {
            $vehicle_make_count = VehicleMake::count();
        }
        $vehicle_makes = VehicleMake::filterByRequest($request)->paginate($vehicle_make_count);
        return $this->response->paginator($vehicle_makes, new VehicleMakeTransformer);
    }

}
