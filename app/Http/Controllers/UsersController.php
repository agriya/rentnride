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

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use JWTAuth;
use Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\User;
use Illuminate\Support\Facades\Hash;

use App\Transformers\UserTransformer;
use App\Transformers\UserAuthTransformer;
use App\Transformers\UploadAttachmentTransformer;

use App\Services\UserService;
use App\Services\IpService;
use App\Services\UserLoginService;
use App\Attachment;

/**
 * Users resource representation.
 * @Resource("Users")
 */
class UsersController extends Controller
{
    /**
     * @var UserService
     */
    protected $UserService;
    /**
     * @var IpService
     */
    protected $IpService;
    /**
     * @var UserLoginService
     */
    protected $UserLoginService;

    /**
     * UsersController constructor.
     */
    public function __construct(UserService $user_Service)
    {
        $this->middleware('jwt.auth', ['only' => ['change_password', 'getAuth', 'getStats']]);
        $this->UserService = $user_Service;
        $this->setIpService();
        $this->setUserLoginService();
    }

    public function setIpService()
    {
        $this->IpService = new IpService();
    }

    public function setUserLoginService()
    {
        $this->UserLoginService = new UserLoginService();
    }

    /**
     * Show the specified user.
     * Show the user with a `id`.
     * @Get("/users/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"id": 1, "role_id": 1, "username": "admin", "email": "guest@gmail.com", "available_wallet_amount": 0, "is_active": 1, "is_email_confirmed": 1, "register_ip_id": 1, "last_login_ip_id": 3}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function show(Request $request)
    {
        $enabledIncludes = array('user_profile', 'attachments');
        (isPluginEnabled('SocialLogins')) ? $enabledIncludes[] = 'provider_user' : '';
        if ($request->has('username')) {
            $user = User::with($enabledIncludes)->where("username", $request->username)->first();
        } else {
            $auth_user = $this->auth->user();
            $user = User::with($enabledIncludes)->find($auth_user->id);
        }
        if (!$user) {
            return $this->response->errorNotFound("Invalid Request");
        }
        if ($request->has('username')) {
            return $this->response->item($user, (new UserTransformer)->setDefaultIncludes(['user_profile', 'providerUser', 'attachmentable']));
        } else {
            return $this->response->item($user, (new UserAuthTransformer)->setDefaultIncludes(['user_profile', 'providerUser', 'attachmentable']));
        }
    }

    /**
     * Store a new user.
     * Store a new user with a `username`, `email`, `password`, `confirm_password` and `is_agree_terms_conditions`.
     * @Post("/users/register")
     * @Transaction({
     *      @Request({"name": "XXXXXX", "email": "XXXXX@gmail.com", "password": "XXXXXX", "confirm_password": "XXXXXX", "is_agree_terms_conditions": 1}),
     *      @Response(200, body={"success": "Record has been added."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {"name": {}}, "status_code": 422})
     * })
     */
    public function register(Request $request)
    {
        $user_data = $request->only('username', 'email', 'password', 'confirm_password', 'is_agree_terms_conditions');
        $validator = Validator::make($user_data, User::GetValidationRule(), User::GetValidationMessage());
        if ($validator->passes()) {
            if ($request->has('password')) {
                $user_data['password'] = Hash::make($request->password);
            }
            $user_data['is_active'] = 1;
            $user_data['role_id'] = config('constants.ConstUserTypes.User');
            $user_data['register_ip_id'] = $this->IpService->getIpId($request->ip());
            $user_data['is_email_confirmed'] = 1;
            $user_data['user_avatar_source_id'] = config('constants.ConstSocialLogin.User');
            if (config('user.is_email_verification_for_register')) {
                $user_data['is_email_confirmed'] = 0;
                $user_data['activate_hash'] = rand(1,100);
            }
            if (config('user.is_admin_activate_after_register')) {
                $user_data['is_active'] = 0;
            }
            //Captcha verification
            $captcha = $request['g-recaptcha-response'];
            if ($this->UserService->captchaCheck($captcha) == false) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('Captcha Verification failed.');
            }
            $user = User::create($user_data);
            if ($user) {
                return $this->UserService->emailConditions($user, 'register');
            } else {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('Could not create new user.');
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Could not create new user.', $validator->errors());
        }
    }

    /**
     * User Authentication.
     * @Post("/users/login")
     * @Transaction({
     *      @Request({"email": "guest@gmail.com", "password": "XXXXXX"}),
     *      @Response(200, body={"success": "Login successfully", "Token": "XXXXXX"}),
     *      @Response(401, body={"message": "Unauthorized", "status_code": 401})
     * })
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $credentials['is_active'] = true;
        $credentials['is_email_confirmed'] = true;
        $chk_user = User::where('email', $request->email)->first();
        if(empty($chk_user)) {
            return response()->json(['error' => 'Account does not exist.'], 404);
        }
        try {
            if (!$userToken = JWTAuth::attempt($credentials)) {
                return $this->response->errorUnauthorized();
            }
        } catch (JWTException $e) {
            // something went wrong
            throw new \Symfony\Component\HttpKernel\Exception\HttpException('Could not create token');
        }
        // insert record in user logins after successful login
        $ip_id = $this->IpService->getIpId($request->ip());
        $role = $this->UserLoginService->saveUserLogin($request, $ip_id);
        // Admin End Token varaiable should be need. so we assign two variable
        $token = $userToken;
        $message = "Admin login successfully";
        $enabled_plugins_arr = enabled_plugins();
        $enabled_plugins = array();
        foreach($enabled_plugins_arr as $key=>$value) {
            $enabled_plugins[] = $value;
        }
        // if no errors are encountered we can return a JWT
        return response()->json(compact('userToken', 'token', 'message', 'role', 'enabled_plugins'));
    }

    /**
     * Activate user.
     * @Put("/users/{id}/activate/{hash}")
     * @Transaction({
     *      @Request({"id": 1, "hash": "XXXXXXXXXXX"}),
     *      @Response(200, body={"success": "User Activated."}),
     *      @Response(404, body={"message": "Invalid Activation Request", "status_code": 404})
     * })
     */
    public function activate($hash, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return $this->response->errorNotFound("Invalid Request");
        }
        if ($user) {
            $user_valid = $this->UserService->validateHash($user->id, $hash, $user->activate_hash);
            if ($user_valid === false) {
                return $this->response->errorNotFound("Invalid Activation Request");
            }
            $user->is_email_confirmed = 1;
            $user->activate_hash = rand(1,100);
            if (!config('user.is_admin_activate_after_register')) {
                $user->is_active = 1;
            }
            if ($user->save()) {
                return $this->UserService->emailConditions($user, 'activate');
            }
        }
    }

    /**
     * Change Password.
     * @Put("/users/{id}/change_password")
     * @Transaction({
     *      @Request({"id": 1, "old_password": "XXXXXX", "password": "XXXXXX", "confirm_password": "XXXXXX"}),
     *      @Response(200, body={"success": "Password changed."}),
     *      @Response(422, body={"message": "Could not change password.","errors": {},"status_code": 422}),
     *      @Response(400, body={"error": "token_not_provided"}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404}),
     * })
     */
    public function changePassword(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return $this->response->errorNotFound("Invalid Request");
        }
        if (!$password = Hash::check($request->old_password, $user->password)) {
            $this->response->errorNotFound("Your old password is incorrect, please try again");
        }
        if ($password == Hash::check($request->password, $user->password)) {
            $this->response->errorNotFound("Your new password is same as old password");
        }
        $user_data = $request->only('password', 'confirm_password');
        $validator = Validator::make($user_data, User::GetValidationRule(), User::GetValidationMessage());
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
     * Forgot Password.
     * @Put("/users/forgot_password")
     * @Transaction({
     *      @Request({"email": "guest@gmail.com"}),
     *      @Response(200, body={"success": "We have sent an email."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404}),
     *      @Response(422, body={"message": "Could not change password.","errors": {},"status_code": 422})
     * })
     */
    public function forgotPassword(Request $request)
    {
        $user_data = $request->only('email');
        $validator = Validator::make($user_data, User::GetForgotPasswordValidationRule());
        if ($validator->passes()) {
            $user = User::where('email', $request->email)->first();
            if($user) {
                if ($user->is_email_confirmed == 0 || $user->is_active == 0) {
                    return $this->response->errorNotFound("This email address is not verified.");
                }
            }
            if (!$user) {
                return $this->response->errorNotFound("This email address is not in our registered users.");
            }
            $user_data['id'] = $user->id;
            $new_password = uniqid();
            $user_data['password'] = Hash::make($new_password);

            if ($user->update($user_data)) {
                try {
                    $this->UserService->sendForgotPasswordMail($new_password, $user->email, $user->username);
                    return response()->json(['Success' => 'We have sent an email to ' . $user->email . ' with further instructions'], 200);
                } catch (\Exception $e) {
                    throw new \Dingo\Api\Exception\StoreResourceFailedException('Password could not be updated. Please, try again.');
                }
            } else {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('Password could not be updated. Please, try again.');
            }
        } else {
            throw new \Dingo\Api\Exception\UpdateResourceFailedException('Password could not be updated.', $validator->errors());
        }

    }

    /*
     * Get Authenticated user
     * @Get("/users/auth")
     * @Transaction({
     *      @Response(200, body={"id": 1, "role_id": 1, "username": "test", "email": "guest@gmail.com", "available_wallet_amount": 10, "is_active": 1, "is_email_confirmed": 1, "register_ip_id": 192.168.1.22, "last_login_ip_id": 192.168.1.22,"user_profile :{}}),
     *      @Response(400, body={"message": "Token not provided", "status_code": 400}),
     * })
     */
    public function getAuth()
    {
        $user = $this->auth->user();
        if ($user) {
            return $this->response->item($user, (new UserAuthTransformer)->setDefaultIncludes(['user_profile', 'attachmentable', 'provider_user', 'vehicle_company']));
        }
    }

    /*
     * Get Authenticated user Upload OR default avatar thumb url
     * @Get("/users/{id}/attachment")
     * @Transaction({
     *      @Response(200, body={"thumb": {}}),
     *      @Response(400, body={"message": "Token not provided", "status_code": 400}),
     * })
     */
    public function getUserUploadAttachment()
    {
        $user = $this->auth->user();
        if ($user) {
            if ($user->attachments) {
                return $this->item($user->attachments, new UploadAttachmentTransformer());
            } else {
                $user->attachments = Attachment::where('id', '=', config('constants.ConstAttachment.UserAvatar'))->first();
                $user->attachments->attachmentable_id = $user->id;
                return $this->item($user->attachments, new UploadAttachmentTransformer());
            }
        }
    }

    /*
     * Get Authenticated user Upload OR default avatar thumb url
     * @Get("/users/{id}/attachment")
     * @Transaction({
     *      @Response(200, body={"thumb": {}}),
     *      @Response(400, body={"message": "Token not provided", "status_code": 400}),
     * })
     */
    public function getStats()
    {
        $user = $this->auth->user();
        if (isPluginEnabled('VehicleRentals') && $user) {
            $vehicle_rental_service = new \Plugins\VehicleRentals\Services\VehicleRentalService();
            $response = $vehicle_rental_service->getBookAndOrderCount($user->id);
            return response()->json(compact('response'), 200);
        }
    }

}