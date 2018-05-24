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
 
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\City;
use JWTAuth;
use Validator;
use App\Transformers\CityTransformer;
use App\State;
use App\Country;
use DB;

/**
 * Cities resource representation.
 * @Resource("Admin/AdminCities")
 */
class AdminCitiesController extends Controller
{
    /**
     * AdminCitiesController constructor.
     */
    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
        // Check the logged user role.
        $this->middleware('role');
    }

    /**
     * Show all cities.
     * Get a JSON representation of all the cities.
     *
     * @Get("/cities?filter={filter}&sort={sort}&sortby={sortby}&q={q}")
     * @Parameters({
     *      @Parameter("filter", type="integer", required=false, description="Filter the cities list by type.", default=null),
     *      @Parameter("sort", type="string", required=false, description="Sort the cities list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort cities by Ascending / Descending Order.", default=null),
     *      @Parameter("q", type="string", required=false, description="Search Cities.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1)
     * })
     */
    public function index(Request $request)
    {
        $cities = City::select(DB::raw('cities.*'))
            ->leftJoin(DB::raw('(select id,name as state_name from states) as states'), 'states.id', '=', 'cities.state_id')
            ->leftJoin(DB::raw('(select id,name as country_name from countries) as countries'), 'countries.id', '=', 'cities.country_id')
            ->filterByRequest($request)
            ->paginate(config('constants.ConstPageLimit'));
        return $this->response->paginator($cities, (new CityTransformer)->setDefaultIncludes(['State', 'Country']));
    }

    /**
     * Store a new city.
     * Store a new city with a `name`, `state_id`, `country_id`, `latitude` and `longitude`.
     * @Post("/cities")
     * @Transaction({
     *      @Request({"name": "chennai", "state_id": 1, "country_id": 1, "latitude": 10.10, "longitude": 12.12}),
     *      @Response(200, body={"success": "Record has been added."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {"name": {}}, "status_code": 422})
     * })
     */
    public function store(Request $request)
    {
        $city_data = $request->only('name', 'state_id', 'country_id', 'latitude', 'longitude');
        $validator = Validator::make($city_data, City::GetValidationRule(), City::GetValidationMessage());
        $state = State::find($city_data['state_id']);
        if (!$state) {
            return $this->response->errorNotFound("Invalid State Id");
        }
        $country = Country::find($city_data['country_id']);
        if (!$country) {
            return $this->response->errorNotFound("Invalid Country Id");
        }
        if ($validator->passes()) {
            $city_data['is_active'] = true;
            $city = City::create($city_data);
            if ($city) {
                return response()->json(['Success' => 'City has been added'], 200);
            } else {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('City could not be updated. Please, try again.');
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('City could not be updated. Please, try again.', $validator->errors());
        }
    }

    /**
     * Edit the specified city.
     * Edit the city with a `id`.
     * @Get("/cities/{id}/edit")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"id": 1, "name": "chennai", "state_id": 1, "country_id": 1, "latitude": 10.10, "longitude": 12.12, "is_active": 1, "State": {}, "Country": {} }),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function edit($id)
    {
        $city = City::with('State', 'Country')->find($id);
        if (!$city) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($city, (new CityTransformer)->setDefaultIncludes(['State', 'Country']));
    }

    /**
     * Update the specified city.
     * Update the city with a `id`.
     * @Put("/cities/{id}")
     * @Transaction({
     *      @Request({"id": 1, "name": "chennai", "state_id": 1, "country_id": 1, "latitude": 10.10, "longitude": 12.12, "is_active": 1}),
     *      @Response(200, body={"success": "Record has been updated."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {"name": {}}, "status_code": 422})
     * })
     */
    public function update(Request $request, $id)
    {
        $city_data = $request->only('name', 'state_id', 'country_id', 'latitude', 'longitude', 'is_active');
        $validator = Validator::make($city_data, City::GetValidationRule(), City::GetValidationMessage());
        $city = false;
        if ($request->has('id')) {
            $city = City::find($id);
            $city = ($request->id != $id) ? false : $city;
        }
        if ($validator->passes() && $city) {
            try {
                $city->update($city_data);
                return response()->json(['Success' => 'City has been updated'], 200);
            } catch (\Exception $e) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('City could not be updated. Please, try again.');
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('City could not be updated. Please, try again.', $validator->errors());
        }
    }

    /**
     * Delete the specified city.
     * Delete the city with a `id`.
     * @Delete("/cities/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"success": "Record Deleted."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function destroy($id)
    {
        $city = City::find($id);
        if (!$city) {
            return $this->response->errorNotFound("Invalid Request");
        } else {
            $city->delete();
        }
        return response()->json(['Success' => 'City deleted'], 200);
    }
	/**
     * Deactivate the city.
     * @Put("/cities/{id}/deactive")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"success": "Record has been deactivated!."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404}),
     * })
     */
    public function deactive(Request $request, $id)
    {
        $city = City::find($id);
        if (!$city) {
            return $this->response->errorNotFound("Invalid Request");
        } else {
            $city_data['is_active'] = false;
            if ($city->update($city_data)) {
                return response()->json(['Success' => 'Record has been deactivated!'], 200);
            }
        }
    }

    /**
     * Activate the city.
     * @Put("/cities/{id}/active")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"success": "Record has been activated!."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404}),
     * })
     */
    public function active(Request $request, $id)
    {
        $city = City::find($id);
        if (!$city) {
            return $this->response->errorNotFound("Invalid Request");
        } else {
            $city_data['is_active'] = true;
            if ($city->update($city_data)) {
                return response()->json(['Success' => 'Record has been activated!'], 200);
            }
        }
    }
}
