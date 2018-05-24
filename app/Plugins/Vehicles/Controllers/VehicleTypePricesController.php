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
use App\Http\Controllers\Controller;
use Plugins\Vehicles\Model\VehicleTypePrice;
use JWTAuth;
use Validator;
use Plugins\Vehicles\Transformers\VehicleTypePriceTransformer;

/**
 * VehiclePrices resource representation.
 * @Resource("VehiclePrices")
 */
class VehicleTypePricesController extends Controller
{
    /**
     * VehiclePricesController constructor.
     */
    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
    }

    /**
     * Show all prices
     * Get a JSON representation of all the prices.
     *
     * @Get("/vehicle_type_prices?sort={sort}&sortby={sortby}&page={page}&q={q}")
     * @Parameters({
     *      @Parameter("sort", type="string", required=false, description="Sort the prices list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort prices by Ascending / Descending Order.", default=null),
     *      @Parameter("q", type="string", required=false, description="Search VehiclePrices.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1)
     * })
     */
    public function index(Request $request)
    {
        $vehicle_type_prices = VehicleTypePrice::with('vehicle_type')->filterByRequest($request)->paginate(config('constants.ConstPageLimit'));
        return $this->response->paginator($vehicle_type_prices, (new VehicleTypePriceTransformer)->setDefaultIncludes(array('vehicle_type')));
    }

    /**
     * Edit the specified vehicle_type_price.
     * Edit the vehicle_type_price with a `id`.
     * @Get("/vehicle_type_prices/{id}/edit")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body= {"id": 1, "vehicle_type_id": 1, "price_id": 1, "discount_type_id": 1, "duration_type_id": 1, "rate": 200, "max_allowed_amount": 1000, "vehicle_type": {}, "vehicle_price": {}, "duration_type": {}, "discount_type": {}}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function edit($id)
    {
        $vehicle_type_price = VehicleTypePrice::with('vehicle_type')->find($id);
        if (!$vehicle_type_price) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($vehicle_type_price, (new VehicleTypePriceTransformer)->setDefaultIncludes(array('vehicle_type')));
    }

    /**
     * Edit the specified vehicle_type_price.
     * Edit the vehicle_type_price with a `id`.
     * @Get("/vehicle_type_prices/{id}/edit")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body= {"id": 1, "vehicle_type_id": 1, "minimum_no_of_day": 1, "maximum_no_of_day": 1, "discount_percentage": 1, "is_active": 1, "vehicle_type": {}}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function show($id)
    {
        $vehicle_type_price = VehicleTypePrice::with('vehicle_type')->find($id);
        if (!$vehicle_type_price) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($vehicle_type_price, (new VehicleTypePriceTransformer)->setDefaultIncludes(array('vehicle_type')));
    }

}
