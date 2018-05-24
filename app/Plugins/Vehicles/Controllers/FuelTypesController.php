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
use Plugins\Vehicles\Model\FuelType;
use JWTAuth;
use Validator;
use Plugins\Vehicles\Transformers\FuelTypeTransformer;

/**
 * Class FuelTypesController
 * @package Plugins\Vehicles\Controllers
 */
class FuelTypesController extends Controller
{

    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
    }

    /**
     * Show all fuel types.
     * Get a JSON representation of all the fuel types.
     *
     * @Get("/fuel_types?filter={filter}&sort={sort}&sortby={sortby}&q={q}")
     * @Parameters({
     *      @Parameter("filter", type="integer", required=false, description="Filter the fuel types list by status.", default=null),
     *      @Parameter("sort", type="string", required=false, description="Sort the fuel types list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort fuel types by Ascending / Descending Order.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1)
     * })
     */
    public function index(Request $request)
    {
        $fuel_type_count = config('constants.ConstPageLimit');
        if ($request->has('type') && $request->type == 'list') {
            $fuel_type_count = FuelType::count();
        }
        $fuel_types = FuelType::filterByRequest($request)->paginate($fuel_type_count);
        return $this->response->paginator($fuel_types, new FuelTypeTransformer);
    }

}
