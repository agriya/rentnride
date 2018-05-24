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
 
namespace Plugins\VehicleInsurances\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Plugins\VehicleInsurances\Model\VehicleTypeInsurance;
use JWTAuth;
use Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Plugins\VehicleInsurances\Transformers\VehicleTypeInsuranceTransformer;

/**
 * VehicleInsurances resource representation.
 * @Resource("VehicleInsurances")
 */
class VehicleTypeInsurancesController extends Controller
{
    /**
     * VehicleInsurancesController constructor.
     */
    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
    }

    /**
     * Show all insurances
     * Get a JSON representation of all the insurances.
     *
     * @Get("/vehicle_insurances?sort={sort}&sortby={sortby}&page={page}&q={q}")
     * @Parameters({
     *      @Parameter("sort", type="string", required=false, description="Sort the insurances list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort insurances by Ascending / Descending Order.", default=null),
     *      @Parameter("q", type="string", required=false, description="Search VehicleInsurances.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1)
     * })
     */
    public function index(Request $request)
    {
        $enabled_includes = array('vehicle_insurance', 'discount_type', 'duration_type', 'vehicle_type');
        $vehicle_type_insurances = VehicleTypeInsurance::with($enabled_includes)->filterByRequest($request)->paginate(config('constants.ConstPageLimit'));
        return $this->response->paginator($vehicle_type_insurances, (new VehicleTypeInsuranceTransformer)->setDefaultIncludes($enabled_includes));
    }
}
