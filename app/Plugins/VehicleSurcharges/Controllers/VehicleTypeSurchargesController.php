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
 
namespace Plugins\VehicleSurcharges\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Plugins\VehicleSurcharges\Model\VehicleTypeSurcharge;
use JWTAuth;
use Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Plugins\VehicleSurcharges\Transformers\VehicleTypeSurchargeTransformer;

/**
 * VehicleSurcharges resource representation.
 * @Resource("VehicleSurcharges")
 */
class VehicleTypeSurchargesController extends Controller
{
    /**
     * VehicleSurchargesController constructor.
     */
    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
    }

    /**
     * Show all surcharges
     * Get a JSON representation of all the surcharges.
     *
     * @Get("/surcharges?sort={sort}&sortby={sortby}&page={page}&q={q}")
     * @Parameters({
     *      @Parameter("sort", type="string", required=false, description="Sort the surcharges list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort surcharges by Ascending / Descending Order.", default=null),
     *      @Parameter("q", type="string", required=false, description="Search VehicleSurcharges.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1)
     * })
     */
    public function index(Request $request)
    {
        $enabled_includes = array('vehicle_surcharge', 'discount_type', 'duration_type', 'vehicle_type');
        $vehicle_type_surcharges = VehicleTypeSurcharge::with($enabled_includes)->filterByRequest($request)->paginate(config('constants.ConstPageLimit'));
        return $this->response->paginator($vehicle_type_surcharges, (new VehicleTypeSurchargeTransformer)->setDefaultIncludes($enabled_includes));
    }
}
