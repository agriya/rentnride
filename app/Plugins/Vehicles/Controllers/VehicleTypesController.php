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
use Plugins\Vehicles\Model\VehicleType;
use JWTAuth;
use Validator;
use Plugins\Vehicles\Transformers\VehicleTypeTransformer;

/**
 * Class VehicleTypesController
 * @package Plugins\Vehicles\Controllers
 */
class VehicleTypesController extends Controller
{

    /**
     * Show all vehicle types.
     * Get a JSON representation of all the vehicle types.
     *
     * @Get("/vehicle_types?filter={filter}&sort={sort}&sortby={sortby}&q={q}")
     * @Parameters({
     *      @Parameter("filter", type="integer", required=false, description="Filter the vehicle types list by status.", default=null),
     *      @Parameter("sort", type="string", required=false, description="Sort the vehicle types list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort vehicle types by Ascending / Descending Order.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1)
     * })
     */
    public function index(Request $request)
    {
        $vehicle_type_count = config('constants.ConstPageLimit');
        if ($request->has('type') && $request->type == 'list') {
            $vehicle_type_count = VehicleType::count();
        }
        $vehicle_types = VehicleType::filterByRequest($request)->paginate($vehicle_type_count);
        return $this->response->paginator($vehicle_types, new VehicleTypeTransformer);
    }

    /**
     * Show the specified vehicle.
     * Show the vehicle_types with a `id`.
     * @Get("/vehicle_types/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"id": 1}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function show($id)
    {
        $enabled_includes = array();
        (isPluginEnabled('VehicleExtraAccessories')) ? $enabled_includes[] = 'vehicle_type_extra_accessory' : '';
        (isPluginEnabled('VehicleInsurances')) ? $enabled_includes[] = 'vehicle_type_insurance' : '';
        (isPluginEnabled('VehicleFuelOptions')) ? $enabled_includes[] = 'vehicle_type_fuel_option' : '';
        $vehicle_type = VehicleType::with($enabled_includes)->find($id);
        if (!$vehicle_type) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($vehicle_type, (new VehicleTypeTransformer)->setDefaultIncludes($enabled_includes));
    }

}
