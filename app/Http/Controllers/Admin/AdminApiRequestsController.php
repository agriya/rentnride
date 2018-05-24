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

use App\ApiRequest;

use JWTAuth;
use Validator;
use App\Transformers\ApiRequestTransformer;
use DB;

/**
 * ApiRequests resource representation.
 * @Resource("Admin/AdminApiRequests")
 */
class AdminApiRequestsController extends Controller
{
    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
        // Check the logged user role.
        $this->middleware('role');
    }

    /**
     * Show all api requests.
     * Get a JSON representation of all the api requests.
     *
     * @Get("/api_requests?sort={sort}&sortby={sortby}")
     * @Parameters({
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1),
     *      @Parameter("sort", type="string", required=false, description="Sort the api requests list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort api requests by Ascending / Descending Order.", default=null),
     * })
     */
    public function index(Request $request)
    {
        $api_requests = ApiRequest::select(DB::raw('api_requests.*'))
            ->leftJoin(DB::raw('(select id,username from users) as users'), 'users.id', '=', 'api_requests.user_id')
            ->leftJoin(DB::raw('(select id,ip from ips) as ips'), 'ips.id', '=', 'api_requests.ip_id')
            ->filterByRequest($request)
            ->paginate(config('constants.ConstPageLimit'));
        return $this->response->paginator($api_requests, (new ApiRequestTransformer)->setDefaultIncludes(['User', 'Ip']));
    }

    /**
     * Show the specified api requests.
     * Show the api requests with a `id`.
     * @Get("/api_requests/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"id": 1, "created_at": "2016-05-11 16:37:50", "path": "http://localhost/bookorrent/public/api/admin/api_requests", "method": "GET", "http_response_code": "200", "user_id": 1, "ip_id": 2, "User": {}, "Ip": {}}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function show($id)
    {
        $api_request = ApiRequest::with('User', 'Ip')->find($id);
        if (!$api_request) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($api_request, (new ApiRequestTransformer)->setDefaultIncludes(['User', 'Ip']));
    }

    /**
     * Delete the specified api requests.
     * Delete the api requests with a `id`.
     * @Delete("/api_requests/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"success": "Record Deleted."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function destroy($id)
    {
        $api_request = ApiRequest::find($id);
        if (!$api_request) {
            return $this->response->errorNotFound("Invalid Request");
        } else {
            $api_request->delete();
        }
        return response()->json(['Success' => 'Api Request deleted'], 200);
    }
}