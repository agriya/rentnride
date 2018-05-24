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
use App\User;
use JWTAuth;
use Validator;
use App\Transformers\UserTransformer;
use App\Transformers\UserSimpleTransformer;
use Illuminate\Support\Facades\Hash;
use App\Services\UserService;
use App\Services\IpService;
use DB;

/**
 * Users resource representation.
 * @Resource("Admin/AdminUsers")
 */
class AdminUsersController extends Controller
{
    /**
     * @var UserService
     */
    protected $user_service;
    /**
     * @var IpService
     */
    protected $IpService;

    /**
     * AdminUsersController constructor.
     */
    public function __construct(UserService $user_Service)
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
        // Check the logged user role.
        $this->middleware('role');
        $this->UserService = $user_Service;
        $this->setIpService();
    }

    public function setIpService()
    {
        $this->IpService = new IpService();
    }

    /**
     * Show all users
     * Get a JSON representation of all the users.
     *
     * @Get("/users?filter={filter}&sort={sort}&sortby={sortby}&type={type}&field={field}&q={q}")
     * @Parameters({
     *      @Parameter("filter", type="integer", required=false, description="Filter the users list by type.", default=null),
     *      @Parameter("sort", type="string", required=false, description="Sort the users list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort users by Ascending / Descending Order.", default=null),
     *      @Parameter("type", type="string", required=false, description="Display users By Listing Type.", default=null),
     *      @Parameter("field", type="string", required=false, description="Give Whatever Fields Needed by Comma Seperator.", default=null),
     *      @Parameter("q", type="string", required=false, description="Search users.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1)
     * })
     */
    public function index(Request $request)
    {
        if($request->has('limit') && $request->input('limit') == 'all'){
            $users_count = User::where(['is_active' => true, 'is_email_confirmed' => true, 'is_agree_terms_conditions' => true ])->count();
            $users = User::where(['is_active' => true, 'is_email_confirmed' => true, 'is_agree_terms_conditions' => true ])->select('id', 'username')->paginate($users_count);
            return $this->response->paginator($users, new UserSimpleTransformer);
        } else if($request->has('limit') && $request->input('limit') != 'all'){
            $users_count = $request->input('limit');
            $users = User::select(DB::raw('users.*'))
                ->leftJoin(DB::raw('(select id,ip from ips) as last_login_ip'), 'last_login_ip.id', '=', 'users.last_login_ip_id')
                ->leftJoin(DB::raw('(select id,ip from ips) as register_ip'), 'register_ip.id', '=', 'users.register_ip_id')
				->filterByRequest($request)->paginate($users_count);
            return $this->response->paginator($users, (new UserTransformer)->setDefaultIncludes(['last_login_ip', 'register_ip']));
        } else{
            $users = User::select(DB::raw('users.*'))
                ->leftJoin(DB::raw('(select id,ip from ips) as last_login_ip'), 'last_login_ip.id', '=', 'users.last_login_ip_id')
                ->leftJoin(DB::raw('(select id,ip from ips) as register_ip'), 'register_ip.id', '=', 'users.register_ip_id')
                ->filterByRequest($request)
                ->paginate(config('constants.ConstPageLimit'));
            return $this->response->paginator($users, (new UserTransformer)->setDefaultIncludes(['last_login_ip', 'register_ip']));
        }
    }

    /**
     * Store a new user.
     * Store a new city with a `role_id`, `username`, `email`, `password`, `is_active` and `is_email_confirmed`.
     * @Post("/users")
     * @Transaction({
     *      @Request({"role_id": 1, "name": "AHSAN", "email": "guest@gmail.com", "password": "XXXXXX", "is_active": 1, "is_email_confirmed": 1 }),
     *      @Response(200, body={"success": "Record has been added."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {"name": {}}, "status_code": 422})
     * })
     */
    public function store(Request $request)
    {
        $user_data = $request->only('username', 'email', 'password', 'role_id', 'is_active', 'is_email_confirmed');
        $validator = Validator::make($user_data, User::GetValidationRule(), User::GetEditValidationRule(), User::GetValidationMessage());
        if ($validator->passes()) {
            if ($request->has('password')) {
                $user_data['password'] = Hash::make($request->password);
            }
            $user_data['is_agree_terms_conditions'] = true;
            $user_data['register_ip_id'] = $this->IpService->getIpId($request->ip());
            $user = User::create($user_data);
            if ($user) {
				$this->UserService->sendAdminAddUserMail($user->username, $request->password, $user->email);
                return response()->json(['Success' => 'User has been added'], 200);
            } else {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('User could not be updated. Please, try again.');
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('User could not be updated. Please, try again.', $validator->errors());
        }
    }

    /**
     * Edit the specified user.
     * Edit the user with a `id`.
     * @Get("/users/{id}/edit")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"id": 1, "role_id": 1, "name": "AHSAN", "email": "guest@gmail.com", "password": "XXXXXX", "is_active": 1, "is_email_confirmed": 1}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function edit($id)
    {
        $user = User::find($id);
        if (!$user) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($user, new UserTransformer);
    }

    /**
     * Show the specified user.
     * Show the user with a `id`.
     * @Get("/users/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"id": 1, "role_id": 1, "name": "AHSAN", "email": "guest@gmail.com", "password": "XXXXXX", "is_active": 1, "is_email_confirmed": 1}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function show($id)
    {
        $user = User::with('last_login_ip', 'register_ip', 'attachments')->find($id);
        if (!$user) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($user, (new UserTransformer)->setDefaultIncludes(['last_login_ip', 'register_ip', 'attachmentable']));
    }

    /**
     * Update User
     * Update user with a `id`.
     * @Put("/users?id=1")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"success": "Record has been updated."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {}, "status_code": 422})
     * })
     */
    public function update(Request $request, $id)
    {
        $user_data = $request->only('username', 'email', 'role_id', 'is_active', 'is_email_confirmed');
        $validator = Validator::make($user_data, array_merge(User::GetValidationRule(), User::GetEditValidationRule()), User::GetValidationMessage());
        $user = false;
        if ($request->has('id')) {
            $user = User::find($id);
            $user = ($request->id != $id) ? false : $user;
            $user_is_active = $user->is_active;
        }
        $check_user = User::where('email', $request->email)
            ->where('id', '!=', $id)
            ->first();
        if ($check_user) {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('User could not be updated. Email already exists.');
        }
        if ($validator->passes() && $user) {
            try {
                $user->update($user_data);
                if ($user_is_active !== $request->is_active && $request->is_active === 1) {
                    // send mail for active
                    $this->UserService->sendStatusMail($user->username, $user->email, 'Admin User Active');
                } else if ($user_is_active !== $request->is_active && $request->is_active === 0) {
                    // send mail for deactive
                    $this->UserService->sendStatusMail($user->username, $user->email, 'Admin User Deactivate');
                }
                if($user->user_login_count === 0){
                    $this->UserService->sendWelcomeMail($user->id, $user->email, $user->username);
                }
                return response()->json(['Success' => 'User has been updated'], 200);
            } catch (\Exception $e) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('User could not be updated. Please, try again.');
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('User could not be updated. Please, try again.', $validator->errors());
        }
    }

    /**
     * Delete the specified user.
     * Delete the user with a `id`.
     * @Delete("/users/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"success": "Record Deleted."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return $this->response->errorNotFound("Invalid Request");
        } else {
            if ($user->delete()) {
                $this->UserService->sendStatusMail($user->username, $user->email, 'Admin User Delete');
                return response()->json(['Success' => 'User deleted'], 200);
            }
        }
    }

    /**
     * Change Password.
     * @Put("/users/{id}/change_password")
     * @Transaction({
     *      @Request({"id": 1, "New Password": "XXXXXX", "Confirm Password": "XXXXXX"}),
     *      @Response(200, body={"success": "Password Changed Successfully."}),
     *      @Response(422, body={"message": "Could not change password.","errors": {},"status_code": 422}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404}),
     * })
     */
    public function changePassword(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return $this->response->errorNotFound("Invalid Request");
        }
        $user_data = $request->only('password', 'confirm_password');
        $validator = Validator::make($user_data, User::GetValidationRule());
        if ($validator->passes()) {
            $user->password = Hash::make($request->password);
            if ($user->save()) {
                return response()->json(['Success' => 'Password Changed Successfully.'], 200);
            }
        } else {
            throw new \Dingo\Api\Exception\UpdateResourceFailedException('Could not change password.', $validator->errors());
        }
    }

    /**
     * Deactivate the user.
     * @Put("/users/{id}/deactive")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"success": "Record has been deactivated!."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404}),
     * })
     */
    public function deactive(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return $this->response->errorNotFound("Invalid Request");
        } else {
            $user_data['is_active'] = false;
            if ($user->update($user_data)) {
                return response()->json(['Success' => 'Record has been deactivated!'], 200);
            }
        }
    }

    /**
     * Activate the user.
     * @Put("/users/{id}/active")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"success": "Record has been activated!."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404}),
     * })
     */
    public function active(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return $this->response->errorNotFound("Invalid Request");
        } else {
            $user_data['is_active'] = true;
            if ($user->update($user_data)) {
                return response()->json(['Success' => 'Record has been activated!'], 200);
            }
        }
    }
}
