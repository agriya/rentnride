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
 
namespace Plugins\VehicleDisputes\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;
use Plugins\VehicleDisputes\Model\VehicleDisputeClosedType;
use Plugins\VehicleDisputes\Transformers\VehicleDisputeClosedTypeTransformer;

/**
 * Class VehicleDisputeClosedTypesController
 * @package Plugins\VehicleDisputes\Controllers\Admin
 */
class VehicleDisputeClosedTypesController extends Controller
{
    /**
     * VehicleDisputeClosedTypesController constructor.
     */
    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
    }

    /**
     * Show all VehicleDisputeClosedTypes
     * Get a JSON representation of all the VehicleDisputeClosedTypes.
     *
     * @Get("/vehicle_dispute_closed_types?sort={sort}&sortby={sortby}&page={page}")
     * @Parameters({
     *      @Parameter("sort", type="string", required=false, description="Sort the disputes list by sort key.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort disputes by Ascending / Descending Order.", default=null)
     * })
     */
    public function index(Request $request)
    {
        $vehicle_dispute_closed_types = VehicleDisputeClosedType::with('dispute_type')->filterByRequest($request)->paginate(config('constants.ConstPageLimit'));
        return $this->response->paginator($vehicle_dispute_closed_types, (new VehicleDisputeClosedTypeTransformer)->setDefaultIncludes('dispute_type'));
    }

}
