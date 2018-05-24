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

use App\Ip;
use App\Transformers\IpTransformer;
use DB;

/**
 * Ips resource representation.
 * @Resource("Admin/AdminIps")
 */
class AdminIpsController extends Controller
{
    /**
     * AdminIpsController constructor.
     */
    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
        // Check the logged user role.
        $this->middleware('role');
    }

    /**
     * Show all Ips
     * Get a JSON representation of all the Ips.
     *
     * @Get("/ips?sort={sort}&sortby={sortby}&page={page}&q={q}")
     * @Parameters({
     *      @Parameter("sort", type="string", required=false, description="Sort the states list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort states by Ascending / Descending Order.", default=null),
     *      @Parameter("q", type="string", required=false, description="Search Ips.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1)
     * })
     */
    public function index(Request $request)
    {
        $ips = Ip::select(DB::raw('ips.*'))
            ->leftJoin(DB::raw('(select id,name as city_name from cities) as cities'), 'cities.id', '=', 'ips.city_id')
            ->leftJoin(DB::raw('(select id,name as state_name from states) as states'), 'states.id', '=', 'ips.state_id')
            ->leftJoin(DB::raw('(select id,name as country_name from countries) as countries'), 'countries.id', '=', 'ips.country_id')
            ->filterByRequest($request)
            ->paginate(config('constants.ConstPageLimit'));
        return $this->response->paginator($ips, (new IpTransformer)->setDefaultIncludes(['City', 'State', 'Country']));
    }

    /**
     * Show the specified ip.
     * Show the ip with a `id`.
     * @Get("/ips/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body= {"id": 1, "city_id": 1, "state_id": 1, "country_id": 1, "ip": "::1", "latitude": 0, "longitude": 0, "City": {}, "State": {}, "Country": {}}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function show($id)
    {
        $ips = Ip::with('City', 'State', 'Country')->find($id);
        if (!$ips) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($ips, (new IpTransformer)->setDefaultIncludes(['City', 'State', 'Country']));
    }

    /**
     * Delete the specified ip.
     * Delete the ip with a `id`.
     * @Delete("/ips/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"success": "Record Deleted."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function destroy($id)
    {
        $ips = Ip::find($id);
        if (!$ips) {
            return $this->response->errorNotFound("Invalid Request");
        } else {
            $ips->delete();
        }
        return response()->json(['Success' => 'Ip deleted'], 200);
    }
}