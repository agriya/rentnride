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

use App\UserLogin;

use JWTAuth;
use Validator;
use App\Transformers\UserLoginTransformer;
use App\Services\UserLoginService;
use DB;

/**
 * UserLogins resource representation.
 * @Resource("Admin/AdminUserLogins")
 */
class AdminUserLoginsController extends Controller
{
    /**
     * @var
     */
    protected $UserLoginService;

    /**
     * AdminUserLoginsController constructor.
     */
    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
        // Check the logged user role.
        $this->middleware('role');
        $this->setUserLoginService();
    }

    public function setUserLoginService()
    {
        $this->UserLoginService = new UserLoginService();
    }

    /**
     * Show all user logins.
     * Get a JSON representation of all the user logins.
     *
     * @Get("/user_logins?sort={sort}&sortby={sortby}")
     * @Parameters({
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1),
     *      @Parameter("sort", type="string", required=false, description="Sort the user logins list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort user logins by Ascending / Descending Order.", default=null),
     * })
     */
    public function index(Request $request)
    {
        $user_logins = UserLogin::select(DB::raw('user_logins.*'))
            ->leftJoin(DB::raw('(select id,username from users) as user'), 'user.id', '=', 'user_logins.user_id')
            ->leftJoin(DB::raw('(select id,ip from ips) as user_login_ip'), 'user_login_ip.id', '=', 'user_logins.user_login_ip_id')
            ->filterByRequest($request)
            ->paginate(config('constants.ConstPageLimit'));
        return $this->response->paginator($user_logins, (new UserLoginTransformer)->setDefaultIncludes(['user_login_ip', 'user', 'role']));
    }

    /**
     * Show the specified user login.
     * Show the user login with a `id`.
     * @Get("/user_login/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"id": 1, "created_at": "2016-04-15 11:07:42", "user_id": 1, "user_login_ip_id": 4, "role_id": 1, "user_agent": "XXX", "User": {}, "Role": {}}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function show($id)
    {
        $user_login = UserLogin::with('user_login_ip', 'user', 'role')->find($id);
        if (!$user_login) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($user_login, (new UserLoginTransformer)->setDefaultIncludes(['user_login_ip', 'user', 'role']));
    }

    /**
     * Delete the specified user logins.
     * Delete the user login with a `id`.
     * @Delete("/user_login/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"success": "Record Deleted."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function destroy($id)
    {
        $user_login = UserLogin::find($id);
        if (!$user_login) {
            return $this->response->errorNotFound("Invalid Request");
        } else {
            $user_login->delete();
            // decrement user login count by 1 in users
            $this->UserLoginService->decreaseUserLoginCount($user_login->user_id);
        }
        return response()->json(['Success' => 'UserLogin deleted'], 200);
    }
}