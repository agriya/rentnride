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

namespace Plugins\SocialLogins\Controllers;

use Illuminate\Http\Request;


use App\Http\Controllers\Controller;
use JWTAuth;

use Validator;
use App\User;

use GuzzleHttp;
use GuzzleHttp\Subscriber\Oauth\Oauth1;

use App\Services\UserService;
use Plugins\SocialLogins\Services\SocialLoginService;
use App\Services\IpService;
use App\Services\UserLoginService;
use Plugins\SocialLogins\Model\ProviderUser;
use Plugins\SocialLogins\Transformers\ProviderUserTransformer;
use Log;

/**
 * Users resource representation.
 * @Resource("users")
 */
class SocialLoginsController extends Controller
{
    /**
     * @var SocialLoginService
     */
    protected $SocialLoginService;
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
     * SocialLoginsController constructor.
     */
    public function __construct(SocialLoginService $service, UserService $userService)
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth', ['only' => ['unlink', 'getProviderUsers']]);
        $this->setIpService();
        $this->setUserLoginService();
        $this->SocialLoginService = $service;
        $this->UserService = $userService;
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
     * Check user connected with social
     * @get("/provider_users")
     * @Transaction({
     *      @Response(200, body={"user_id": 1, "provider_id": 1, "is_connected": 1, "profile_picture_url": "/profile/1"}),
     *      @Response(200, body={})
     * })
     */
    public function getProviderUsers()
    {
        $user = $this->auth->user();
        $provider_user = ProviderUser::where('user_id', $user->id)->get();
        if (!$provider_user) {
            return $this->response->array($provider_user->toArray());
        }
        return $this->response->Collection($provider_user, (new ProviderUserTransformer)->setDefaultIncludes(['user']));
    }

    /**
     * unlink provider user
     * @post("/auth/unlink/{provider}")
     * @Transaction({
     *      @Request({"provider": "Facebook"}),
     *      @Response(200, body={"user_id": 1, "provider_id": 1, "is_connected": 1, "profile_picture_url": "/profile/1"}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function unlink(Request $request)
    {
        $provider_id = config(ucfirst($request->provider))['id'];
        $user = $this->auth->user();
        $provider = ProviderUser::where(['user_id' => $user->id, 'provider_id' => $provider_id])->get();
        if (!$provider) {
            return $this->response->errorNotFound("Invalid Request");
        }
        ProviderUser::where(['user_id' => $user->id, 'provider_id' => $provider_id])->update(['is_connected' => false]);
        $get_user = User::where('id', $user->id)->first();
        $get_user->update(['user_avatar_source_id' => config('constants.ConstSocialLogin.User')]);
        $getProvider = ProviderUser::where(['user_id' => $user->id, 'provider_id' => $provider_id])->first();
        return $this->response->item($getProvider, new ProviderUserTransformer);
    }

    /**
     * update profile image
     * @post("/update_profile")
     * @Transaction({
     *      @Request({"source_id": "1"}),
     *      @Response(200, body={"Success": "Profile image updated successfully."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function update_profile(Request $request)
    {
        $user = $this->auth->user();
        $get_user = User::where('id', $user->id)->first();
        if (!$get_user) {
            return $this->response->errorNotFound("Invalid Request");
        }
        $update_user = $get_user->update(['user_avatar_source_id' => $request->source_id]);
        if ($update_user) {
            return response()->json(['Success' => 'Profile image updated successfully.'], 200);
        }
    }

    /**
     * Login with Facebook.
     * @post("/auth/facebook")
     * @Transaction({
     *      @Request({"client_id": "xxxxxxxxxx", "secret": "xxxxxxxxxx"}),
     *      @Response(200, body={"success": "Login successfully."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */

    public function facebook(Request $request)
    {
        $fb_detail = config('Facebook');
        $client = new GuzzleHttp\Client();
        $params = [
            'code' => $request->input('code'),
            'client_id' => $request->input('clientId'),
            'redirect_uri' => $request->input('redirectUri'),
            'client_secret' => $fb_detail['secret_key']
        ];
        // Step 1. Exchange authorization code for access token.
        $accessTokenResponse = $client->request('GET', 'https://graph.facebook.com/v2.5/oauth/access_token', [
            'query' => $params
        ]);
        $accessToken = json_decode($accessTokenResponse->getBody(), true);
        // Step 2. Retrieve profile information about the current user.
        $fields = 'id,email,first_name,last_name,link,name';
        $profileResponse = $client->request('GET', 'https://graph.facebook.com/v2.5/me', [
            'query' => [
                'access_token' => $accessToken['access_token'],
                'fields' => $fields
            ]
        ]);
        $profile = json_decode($profileResponse->getBody(), true);

        // Step 3a. If user is already signed in then link accounts.
        if ($request->header('Authorization')) {
            $provider_user = ProviderUser::where('foreign_id', '=', $profile['id'])->first();
            $auth_user = $this->auth->user();
            if ($provider_user) {
                if ($auth_user->id != $provider_user->user_id) {
                    return response()->json(['message' => 'There is already a Facebook account registered by other user'], 409);
                }
                $provider_user->update(['is_connected' => true]);
                return $this->response->item($provider_user, new ProviderUserTransformer);
            } else {
                $provider = new ProviderUser;
                $provider->user_id = $auth_user->id;
                $provider->provider_id = $fb_detail['id'];
                $provider->foreign_id = $profile['id'];
                $provider->access_token = $accessToken['access_token'];
                $provider->profile_picture_url = 'http://graph.facebook.com/' . $profile['id'] . '/picture';
                $provider->is_connected = true;
                $provider->save();
                return $this->response->item($provider, new ProviderUserTransformer);
            }

        } // Step 3b. Create a new user account or return an existing one.
        else {
            $provider_user = ProviderUser::where('foreign_id', '=', $profile['id'])->first();
            if ($provider_user) {
                $user = User::find($provider_user->user_id);
                $request->email = $user->email;
                $ip_id = $this->IpService->getIpId($request->ip());
                $role = $this->UserLoginService->saveUserLogin($request, $ip_id);
                $token = $userToken = $this->SocialLoginService->createToken($user);
                return response()->json(compact('userToken', 'token', 'role'));
            } else {
                $user = new User;
                $user->email = $profile['email'];
                $user->username = $this->SocialLoginService->generateUserName($profile['name']);
                $user->role_id = config('constants.ConstUserTypes.User');
                $user->is_email_confirmed = 1;
                $user->register_ip_id = $this->IpService->getIpId($request->ip());
                $user->last_login_ip_id = $this->IpService->getIpId($request->ip());
                $user->user_avatar_source_id = config('constants.ConstSocialLogin.Facebook');
                if (!config('user.is_admin_activate_after_register')) {
                    $user->is_active = 1;
                }
                if ($user->save()) {
                    //Save provider user
                    $provider = [
                        'user_id' => $user->id,
                        'provider_id' => $fb_detail['id'],
                        'foreign_id' => $profile['id'],
                        'access_token' => $accessToken['access_token'],
                        'profile_picture_url' => 'http://graph.facebook.com/' . $profile['id'] . '/picture',
                        'is_connected' => true
                    ];
                    ProviderUser::create($provider);
                    $request->email = $profile['email'];

                    $ip_id = $this->IpService->getIpId($request->ip());
                    $this->UserService->emailConditions($user, 'register');
                    $role = $this->UserLoginService->saveUserLogin($request, $ip_id);
                    $token = $userToken = $this->SocialLoginService->createToken($user);
                    return response()->json(compact('userToken', 'token', 'role'));
                }
            }
        }
    }


    /**
     * Login with Google.
     * @post("/auth/google")
     * @Transaction({
     *      @Request({"client_id": "xxxxxx", "secret": "xxxxxxxx"}),
     *      @Response(200, body={"success": "Login successfully."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function google(Request $request)
    {
        $google_detail = config('Google');
        $client = new GuzzleHttp\Client();
        $params = [
            'code' => $request->input('code'),
            'client_id' => $request->input('clientId'),
            'client_secret' => $google_detail['secret_key'],
            'redirect_uri' => $request->input('redirectUri'),
            'grant_type' => 'authorization_code',
        ];
        // Step 1. Exchange authorization code for access token.
        $accessTokenResponse = $client->request('POST', 'https://accounts.google.com/o/oauth2/token', [
            'form_params' => $params
        ]);
        $accessToken = json_decode($accessTokenResponse->getBody(), true);
        // Step 2. Retrieve profile information about the current user.
        $profileResponse = $client->request('GET', 'https://www.googleapis.com/plus/v1/people/me/openIdConnect', [
            'headers' => array('Authorization' => 'Bearer ' . $accessToken['access_token'])
        ]);
        $profile = json_decode($profileResponse->getBody(), true);
        // Step 3a. If user is already signed in then link accounts.
        if ($request->header('Authorization')) {
            // check provider user already exists
            $provider_user = ProviderUser::where('foreign_id', '=', $profile['sub'])->first();
            $auth_user = $this->auth->user();
            if ($provider_user) {
                if ($auth_user->id != $provider_user->user_id) {
                    return response()->json(['message' => 'There is already a Google account registered by other user'], 409);
                }
                $provider_user->update(['is_connected' => true]);
                return $this->response->item($provider_user, new ProviderUserTransformer);
            } else {
                $provider = new ProviderUser;
                $provider->user_id = $auth_user->id;
                $provider->provider_id = $google_detail['id'];
                $provider->foreign_id = $profile['sub'];
                $provider->access_token = $accessToken['access_token'];
                $provider->profile_picture_url = $profile['picture'];
                $provider->is_connected = true;
                $provider->save();
                return $this->response->item($provider, new ProviderUserTransformer);
            }
        } // Step 3b. Create a new user account or return an existing one.
        else {
            $isAlreadyExistingUser = User::where('email', '=', $profile['email'])->first();
            if ($isAlreadyExistingUser) {
                $request->email = $profile['email'];
                $ip_id = $this->IpService->getIpId($request->ip());
                $role = $this->UserLoginService->saveUserLogin($request, $ip_id);
                $token = $userToken = $this->SocialLoginService->createToken($isAlreadyExistingUser);
                return response()->json(compact('userToken', 'token', 'role'));
            } else {
                $user = new User;
                $user->email = $profile['email'];
                $user->username = $this->SocialLoginService->generateUserName($profile['name']);
                $user->role_id = config('constants.ConstUserTypes.User');
                $user->is_email_confirmed = 1;
                $user->register_ip_id = $this->IpService->getIpId($request->ip());
                $user->last_login_ip_id = $this->IpService->getIpId($request->ip());
                $user->user_avatar_source_id = config('constants.ConstSocialLogin.Google');
                if (!config('user.is_admin_activate_after_register')) {
                    $user->is_active = 1;
                }
                if ($user->save()) {
                    //Save provider user
                    $provider = [
                        'user_id' => $user->id,
                        'provider_id' => $google_detail['id'],
                        'foreign_id' => $profile['sub'],
                        'access_token' => $accessToken['access_token'],
                        'profile_picture_url' => $profile['picture'],
                        'is_connected' => true
                    ];
                    ProviderUser::create($provider);
                    $request->email = $profile['email'];
                    $ip_id = $this->IpService->getIpId($request->ip());
                    $this->UserService->emailConditions($user, 'register');
                    $role = $this->UserLoginService->saveUserLogin($request, $ip_id);
                    $token = $userToken = $this->SocialLoginService->createToken($user);
                    return response()->json(compact('userToken', 'token', 'role'));
                }
            }
        }
    }

    /**
     * Login with Twitter.
     * @post("/auth/twitter")
     * @Transaction({
     *      @Request({"client_id": "xxxxxxxxxxxx", "secret": "xxxxxxxxxxxx"}),
     *      @Response(200, body={"success": "Login successfully."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function twitter(Request $request)
    {
        $twtr_detail = config('Twitter');
        $provider_id = $twtr_detail->id;
        $stack = GuzzleHttp\HandlerStack::create();
        // Part 1 of 2: Initial request from Satellizer.
        if (!$request->input('oauth_token') || !$request->input('oauth_verifier')) {
            $stack = GuzzleHttp\HandlerStack::create();
            $requestTokenOauth = new Oauth1([
                'consumer_key' => $twtr_detail->api_key,
                'consumer_secret' => $twtr_detail->secret_key,
                'callback' => $request->input('redirectUri'),
                'token' => '',
                'token_secret' => ''
            ]);
            $stack->push($requestTokenOauth);
            $client = new GuzzleHttp\Client([
                'handler' => $stack
            ]);
            // Step 1. Obtain request token for the authorization popup.
            $requestTokenResponse = $client->request('POST', 'https://api.twitter.com/oauth/request_token', [
                'auth' => 'oauth'
            ]);
            $oauthToken = array();
            parse_str($requestTokenResponse->getBody(), $oauthToken);
            // Step 2. Send OAuth token back to open the authorization screen.
            return response()->json($oauthToken);
        } // Part 2 of 2: Second request after Authorize app is clicked.
        else {
            $accessTokenOauth = new Oauth1([
                'consumer_key' => $twtr_detail->api_key,
                'consumer_secret' => $twtr_detail->secret_key,
                'token' => $request->input('oauth_token'),
                'verifier' => $request->input('oauth_verifier'),
                'token_secret' => ''
            ]);
            $stack->push($accessTokenOauth);
            $client = new GuzzleHttp\Client([
                'handler' => $stack
            ]);
            // Step 3. Exchange oauth token and oauth verifier for access token.
            $accessTokenResponse = $client->request('POST', 'https://api.twitter.com/oauth/access_token', [
                'auth' => 'oauth'
            ]);
            $accessToken = array();
            parse_str($accessTokenResponse->getBody(), $accessToken);
            $profileOauth = new Oauth1([
                'consumer_key' => $twtr_detail->api_key,
                'consumer_secret' => $twtr_detail->secret_key,
                'oauth_token' => $accessToken['oauth_token'],
                'token_secret' => ''
            ]);
            $stack->push($profileOauth);
            $client = new GuzzleHttp\Client([
                'handler' => $stack
            ]);
            // Step 4. Retrieve profile information about the current user.
            $profileResponse = $client->request('GET', 'https://api.twitter.com/1.1/users/show.json?screen_name=' . $accessToken['screen_name'], [
                'auth' => 'oauth'
            ]);
            $profile = json_decode($profileResponse->getBody(), true);
            // Step 5a. Link user accounts.
            if ($request->header('Authorization')) {
                // check provider user already exists
                $provider_user = ProviderUser::where('foreign_id', '=', $profile['id'])->first();
                $auth_user = $this->auth->user();
                if ($provider_user) {
                    if ($auth_user->id != $provider_user->user_id) {
                        return response()->json(['message' => 'There is already a Twitter account registered by other user'], 409);
                    }
                    $provider_user->update(['is_connected' => true]);
                    return $this->response->item($provider_user, new ProviderUserTransformer);
                } else {
                    $provider = new ProviderUser;
                    $provider->user_id = $auth_user->id;
                    $provider->provider_id = $twtr_detail['id'];
                    $provider->foreign_id = $profile['id'];
                    $provider->access_token = $accessToken['oauth_token'];
                    $provider->profile_picture_url = $profile['profile_image_url'];
                    $provider->is_connected = true;
                    $provider->save();
                    return $this->response->item($provider, new ProviderUserTransformer);
                }
            } // Step 5b. Create a new user account or return an existing one.
            else {
                // check provider user already exists
                $provider_user = ProviderUser::where('foreign_id', '=', $profile['id'])->first();
                if ($provider_user) {
                    // check user already exists for provider
                    $user = User::where('id', '=', $provider_user->user_id)->first();
                    if ($user) {
                        $user = User::find($provider_user->user_id);
                        $request->email = $user->email;
                        $ip_id = $this->IpService->getIpId($request->ip());
                        $role = $this->UserLoginService->saveUserLogin($request, $ip_id);
                        $token = $userToken = $this->SocialLoginService->createToken($user);
                        return response()->json(compact('userToken', 'token', 'role'));
                    }
                } else {
                    if (empty($profile['email'])) {
                        $profile['provider_id'] = config('constants.ConstSocialLogin.Twitter');
                        $profile['access_token'] = $accessToken['oauth_token'];
                        $profile['provider'] = 'twitter';
                        $response['thrid_party_profile'] = $profile;
                        return response()->json($response);
                    }
                }
            }
        }
    }

    /**
     * Login with Github.
     * @post("/auth/github")
     * @Transaction({
     *      @Request({"client_id": 95821807561, "code": "F1xOUd6dOy0WJ9qH", "redirectUri": "../auth/github"}),
     *      @Response(200, body={"success": "Login successfully."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function github(Request $request)
    {
        $github_detail = config('Github');
        $client = new GuzzleHttp\Client();

        $params = [
            'code' => $request->input('code'),
            'client_id' => $request->input('clientId'),
            'client_secret' => $github_detail['secret_key'],
            'redirect_uri' => $request->input('redirectUri')
        ];

        // Step 1. Exchange authorization code for access token.
        $accessTokenResponse = $client->request('GET', 'https://github.com/login/oauth/access_token', [
            'query' => $params
        ]);

        $accessToken = array();
        parse_str($accessTokenResponse->getBody(), $accessToken);

        // Step 2. Retrieve profile information about the current user.
        $profileResponse = $client->request('GET', 'https://api.github.com/user', [
            'headers' => ['User-Agent' => 'Satellizer'],
            'query' => $accessToken
        ]);
        $profile = json_decode($profileResponse->getBody(), true);
        //  exit;
        // Step 3a. If user is already signed in then link accounts.
        if ($request->header('Authorization')) {
            $provider_user = ProviderUser::where('foreign_id', '=', $profile['id'])->first();
            $auth_user = $this->auth->user();
            if ($provider_user) {
                if ($auth_user->id != $provider_user->user_id) {
                    return response()->json(['message' => 'There is already a account registered by other user'], 409);
                }
                $provider_user->update(['is_connected' => true]);
                return $this->response->item($provider_user, new ProviderUserTransformer);
            } else {
                $provider = new ProviderUser;
                $provider->user_id = $auth_user->id;
                $provider->provider_id = $github_detail['id'];
                $provider->foreign_id = $profile['id'];
                $provider->access_token = $accessToken['access_token'];
                $provider->profile_picture_url = $profile['avatar_url'];
                $provider->is_connected = true;
                $provider->save();
                return $this->response->item($provider, new ProviderUserTransformer);
            }
        } // Step 3b. Create a new user account or return an existing one.
        else {
            $provider_user = ProviderUser::where('foreign_id', '=', $profile['id'])->first();
            if ($provider_user) {
                $user = User::find($provider_user->user_id);
                $request->email = $user->email;
                $ip_id = $this->IpService->getIpId($request->ip());
                $role = $this->UserLoginService->saveUserLogin($request, $ip_id);
                $token = $userToken = $this->SocialLoginService->createToken($user);
                return response()->json(compact('userToken', 'token', 'role'));
            } else {
                if (empty($profile['email'])) {
                    $response['thrid_party_login_no_email'] = 1;
                    $profile['provider_id'] = config('constants.ConstSocialLogin.Github');
                    $profile['access_token'] = $accessToken['access_token'];
                    $profile['provider'] = 'github';
                    $response['thrid_party_profile'] = $profile;
                    return response()->json($response);
                } else {
                    $user = new User;
                    $user->email = $profile['email'];
                    $user->username = $this->SocialLoginService->generateUserName($profile['login']);
                    $user->role_id = config('constants.ConstUserTypes.User');
                    $user->is_email_confirmed = 1;
                    $user->user_avatar_source_id = config('constants.ConstSocialLogin.Github');
                    $user->register_ip_id = $this->IpService->getIpId($request->ip());
                    $user->last_login_ip_id = $this->IpService->getIpId($request->ip());
                    $user->user_avatar_source_id = config('constants.ConstSocialLogin.Github');
                    $user->is_active = (config('user.is_admin_activate_after_register')) ? 0 : 1;
                    if ($user->save()) {
                        //Save provider user
                        $provider = [
                            'user_id' => $user->id,
                            'provider_id' => $github_detail['id'],
                            'foreign_id' => $profile['id'],
                            'access_token' => $accessToken['access_token'],
                            'profile_picture_url' => $profile['avatar_url'],
                            'is_connected' => true
                        ];
                        ProviderUser::create($provider);
                        $request->email = $profile['email'];
                        $ip_id = $this->IpService->getIpId($request->ip());
                        $this->UserService->emailConditions($user, 'register');
                        $role = $this->UserLoginService->saveUserLogin($request, $ip_id);
                        $token = $userToken = $this->SocialLoginService->createToken($user);
                        return response()->json(compact('userToken', 'token', 'role'));
                    }
                }
            }
        }
    }

    /**
     * Social register email must be needed. So we get it from user and register the user.
     * @post("/social_login")
     * @Transaction({
     *      @Request({"email": 95821807561, "thrid_party_profile": ""}),
     *      @Response(200, body={"userToken": "F1xOUd6dOy0WJ9qH"}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404}),
     *      @Response(409, body={"message": "There is already a provider account that belongs to you", "status_code": 404})
     * })
     */
    public function socialLoginWithEmail(Request $request)
    {
        $email = $request->input('email');
        $profile = $request->input('thrid_party_profile');
        $user = User::where('email', '=', $email)->first();
        if ($user) {
            return response()->json(['message' => 'There is already a ' . $profile['provider'] . 'account that belongs to you'], 409);
        } else {
            switch ($profile['provider_id']) {
                case 2: // Twitter
                    $user = new User;
                    $user->email = $email;
                    $user->username = $this->SocialLoginService->generateUserName($profile['name']);
                    $user->role_id = config('constants.ConstUserTypes.User');
                    $user->is_email_confirmed = 1;
                    $user->register_ip_id = $this->IpService->getIpId($request->ip());
                    $user->last_login_ip_id = $this->IpService->getIpId($request->ip());
                    if (!config('user.is_admin_activate_after_register')) {
                        $user->is_active = 1;
                    }
                    if ($user->save()) {
                        //Save provider user
                        $provider = [
                            'user_id' => $user->id,
                            'provider_id' => $profile['provider_id'],
                            'foreign_id' => $profile['id'],
                            'access_token' => $profile['access_token'],
                            'profile_picture_url' => $profile['profile_image_url'],
                            'is_connected' => true
                        ];
                        ProviderUser::create($provider);
                        $request->email = $email;
                        $ip_id = $this->IpService->getIpId($request->ip());
                        $this->UserService->emailConditions($user, 'register');
                        $role = $this->UserLoginService->saveUserLogin($request, $ip_id);
                        $token = $userToken = $this->SocialLoginService->createToken($user);
                        return response()->json(compact('userToken', 'token', 'role'));

                    }
                case 4: // github
                    $user = new User;
                    $user->email = $email;
                    $user->username = $this->SocialLoginService->generateUserName($profile['login']);
                    $user->role_id = config('constants.ConstUserTypes.User');
                    $user->is_email_confirmed = 1;
                    $user->register_ip_id = $this->IpService->getIpId($request->ip());
                    $user->last_login_ip_id = $this->IpService->getIpId($request->ip());
                    $user->user_avatar_source_id = config('constants.ConstSocialLogin.Github');
                    if (!config('user.is_admin_activate_after_register')) {
                        $user->is_active = 1;
                    }
                    if ($user->save()) {
                        //Save provider user
                        $provider = [
                            'user_id' => $user->id,
                            'provider_id' => $profile['provider_id'],
                            'foreign_id' => $profile['id'],
                            'access_token' => $profile['access_token'],
                            'profile_picture_url' => $profile['avatar_url'],
                            'is_connected' => true
                        ];
                        ProviderUser::create($provider);
                        $request->email = $email;
                        $ip_id = $this->IpService->getIpId($request->ip());
                        $this->UserService->emailConditions($user, 'register');

                        $role = $this->UserLoginService->saveUserLogin($request, $ip_id);
                        $token = $userToken = $this->SocialLoginService->createToken($user);
                        return response()->json(compact('userToken', 'token', 'role'));
                    }
                    break;
            }
        }


    }

    public function getAuthProviders() {
        return "";
    }

}