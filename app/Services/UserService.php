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
 
namespace App\Services;

use App\User;
use App\UserLogin;
use Carbon;
use ReCaptcha\ReCaptcha;

class UserService extends MailService
{
    /**
     * @var
     */
    protected $tokenService;
    /**
     * @var IpService
     */
    protected $ip_service;

    /**
     * UserService constructor.
     */
    public function __construct(TokenService $tokenService)
    {
        $this->setIpService();
        $this->tokenService = $tokenService;
    }

    public function setIpService()
    {
        $this->IpService = new IpService();
    }

    /**
     * To send email based on the settings
     * @param $user
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function emailConditions($user, $type)
    {
        if (config('user.is_admin_mail_after_register') && $type != 'activate') {
            $this->sendUserJoinAdminMail($user);
        }
		if (!config('user.is_email_verification_for_register') && config('user.is_admin_activate_after_register')) {
            $this->sendWelcomeMail($user->id, $user->email, $user->username);
            return response()->json(['Success' => 'You have successfully registered with our site. After administrator approval you can login to site.'], 200);
        } else if (config('user.is_email_verification_for_register') && ($user->is_email_confirmed == 0)) {
            $this->sendActivationMail($user->id, $user->email, $user->username, $this->getActivateHash($user->id, $user->activate_hash));
            return response()->json(['Success' => 'You have successfully registered with our site and your activation link has been sent to your mail inbox.'], 200);
        } else if (config('user.is_admin_activate_after_register') && ($user->is_active == 0) && ($type == 'activate') ) {
            return response()->json(['Success' => 'You have successfully activated your account with our site. After administrator approval you can login to site.'], 200);
        } else if (!config('user.is_admin_activate_after_register') && config('user.is_auto_login_after_register') && ($type == 'activate')) {
            return response()->json(['Success' => 'Account Activated Successfully.', 'token' => $this->tokenService->createToken($user)], 200);
        }  else if (!config('user.is_admin_activate_after_register') && !config('user.is_auto_login_after_register') && ($type == 'activate')) {
            return response()->json(['Success' => 'Account Activated Successfully.'], 200);
        } else if (!config('user.is_email_verification_for_register') && !config('user.is_admin_activate_after_register') && config('user.is_welcome_mail_after_register') && !config('user.is_auto_login_after_register')) {
            $this->sendWelcomeMail($user->id, $user->email, $user->username);
            return response()->json(['Success' => 'You have successfully registered with our site.'], 200);
        } else if (!config('user.is_email_verification_for_register') && !config('user.is_admin_activate_after_register') && config('user.is_auto_login_after_register') && config('user.is_welcome_mail_after_register')) {
            $this->sendWelcomeMail($user->id, $user->email, $user->username);
            return response()->json(['Success' => 'You have successfully registered with our site.', 'token' => $this->tokenService->createToken($user)], 200);
        } else if (config('user.is_auto_login_after_register') && config('user.is_welcome_mail_after_register')) {
            $this->sendWelcomeMail($user->id, $user->email, $user->username);
            return response()->json(['Success' => 'You have successfully registered with our site.', 'token' => $this->tokenService->createToken($user)], 200);
        }
    }

    /**
     * Send welcome mail to users
     * @param $user_id
     * @param $to_email
     * @param $to_username
     */
    public function sendWelcomeMail($user_id, $to_email, $to_username)
    {
        $template = $this->getTemplate('Welcome Email');
        $emailFindReplace = array(
            '##USERNAME##' => $to_username,
            '##CONTACT_MAIL##' => config('site.contact_email'),
            '##FROM_EMAIL##' => ($template['from'] == '##FROM_EMAIL##') ? config('site.from_email') : $template['from'],
        );
        $this->sendMail($template, $emailFindReplace, $to_email, $to_username);
    }

    /**
     * Send Activation mail to users
     * @param $user_id
     * @param $to_email
     * @param $to_username
     * @param $hash
     */

    public function sendActivationMail($user_id, $to_email, $to_username, $hash)
    {
        $template = $this->getTemplate('Activation Request');
        $activation_link = '/#/users/' . $user_id . '/activate/' . $hash;
        $emailFindReplace = array(
            '##USERNAME##' => $to_username,
            '##ACTIVATION_URL##' => url($activation_link),
            '##FROM_EMAIL##' => ($template['from'] == '##FROM_EMAIL##') ? config('site.from_email') : $template['from'],
        );
        $this->sendMail($template, $emailFindReplace, $to_email, $to_username);
    }

    /**
     * get last registered record for admin dashboard
     * @param $request
     * @return User created_at
     */
    public function getLastRegistered()
    {
        $user_details = User::select('created_at')->orderBy('created_at', 'desc')->first();
        return $user_details->created_at->diffForHumans();
    }

    /**
     * get registered in count for admin dashboard
     * @param $request
     * @return User count
     */
    public function getRegisterCount($request)
    {
        $check_date = $this->getDateFilter($request);
        $check_date = Carbon::parse($check_date)->format('Y-m-d');
        $user_count = User::where('created_at', '>=', $check_date)
            ->where('role_id', '>', config('constants.ConstUserTypes.Admin'))
            ->count();
        return $user_count;
    }

    /**
     * get last logged in count for admin dashboard
     * @param $request
     * @return UserLogin count
     */
    public function getLoginCount($request)
    {
        $check_date = $this->getDateFilter($request);
        $check_date = Carbon::parse($check_date)->format('Y-m-d');
        $user_login_details = UserLogin::where('created_at', '>=', $check_date)->count();
        return $user_login_details;
    }

    /**
     * get the total active users count
     * @return active user count.
     */
    public function getTotalUsers()
    {
        $total_user_count = User::where('role_id', '>', config('constants.ConstUserTypes.Admin'))
            ->count();
        return $total_user_count;
    }

    /**
     * Verify captcha
     */

    public function captchaCheck($captcha)
    {
        $remoteip = $_SERVER['REMOTE_ADDR'];
        $secret = config('captcha.secret_key');
        $recaptcha = new ReCaptcha($secret);
        $response = $recaptcha->verify($captcha, $remoteip);
        if ($response->isSuccess()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * get the date filter
     * @return $check_date
     */
    public function getDateFilter($request)
    {
        $check_date = Carbon::now()->subDays(7);
        if ($request->has('filter')) {
            if ($request->filter == 'lastDays') {
                $check_date = Carbon::now()->subDays(7);
            } else if ($request->filter == 'lastWeeks') {
                $check_date = Carbon::now()->subWeeks(4);
            } else if ($request->filter == 'lastMonths') {
                $check_date = Carbon::now()->subMonths(3);
            } else if ($request->filter == 'lastYears') {
                $check_date = Carbon::now()->subYears(3);
            }
        }
        return $check_date;
    }

    /**
     * Check facebook username
     * @param $name
     * @return bool
     */
    public function CheckUsernameAvailable($name)
    {
        $user = User::where('username', '=', $name)->get();
        if ($user) {
            return false;
        }
        return $name;
    }

    /**
     * Send Forgot Password Mail
     * @param $new_password
     * @param $to_email
     * @param $to_username
     */
    public function sendForgotPasswordMail($new_password, $to_email, $to_username)
    {
        $template = $this->getTemplate('Forgot Password');
        $emailFindReplace = array(
            '##USERNAME##' => $to_username,
            '##PASSWORD##' => $new_password
        );
        $this->sendMail($template, $emailFindReplace, $to_email, $to_username);
    }

    /**
     * Send mail to admin reg user join
     * @param $user
     */
    public function sendUserJoinAdminMail($user)
    {
        $ip = $this->IpService->getIp($user->register_ip_id);
        $template = $this->getTemplate('New User Join');
        $emailFindReplace = array(
            '##USERNAME##' => '<a href="' . url('/#/user/' . $user->username) . '">' . $user->username . '</a>',
            '##EMAIL##' => $user->email,
            '##SIGNUP_IP##' => $ip
        );
        $this->sendMail($template, $emailFindReplace, config('site.from_email'), 'Admin');
    }

    /**
     * Send mail to user reg active / deactive status change
     * @param $username
     * @param $email
     * @param $status
     */
    public function sendStatusMail($username, $email, $status)
    {
        $template = $this->getTemplate($status);
        $emailFindReplace = array(
            '##USERNAME##' => $username
        );
        $this->sendMail($template, $emailFindReplace, $email, $username);
    }

    /**
     * get Activate hash
     * @param $user_id
     * @return string
     */
    public function getActivateHash($user_id, $activate_hash)
    {
        return md5($user_id . '-' . config('Security.salt') . '-' . $activate_hash);
    }

    /**
     * Validate Activate Hash
     * @param $user_id
     * @param $hash
     * @return bool
     */
    public function validateHash($user_id, $hash, $activate_hash)
    {
        return (md5($user_id . '-' . config('Security.salt') . '-' . $activate_hash) === $hash);
    }

    public function checkBalanceAvailability($user_id, $amount)
    {
        $user = User::where('id', '=', $user_id)->first();
        if ($user->available_wallet_amount < $amount) {
            return false;
        }
        return true;
    }

    public function updateFeedbackDetails($user, $average_rating)
    {
        $user->increment('feedback_count', 1);
        // update average rating
        $user->feedback_rating = $average_rating;
        $user->save();
    }
	
	/**
     * Send Admin add user mail to users
     * @param $to_username
     * @param $password
     * @param $to_email
     */
    public function sendAdminAddUserMail($to_username, $password, $to_email)
    {
        $template = $this->getTemplate('Admin User Add');
        $emailFindReplace = array(
            '##USERNAME##' => $to_username,
            '##USEREMAIL##' => $to_email,
            '##PASSWORD##' => $password,
            '##FROM_EMAIL##' => ($template['from'] == '##FROM_EMAIL##') ? config('site.from_email') : $template['from'],
        );
        $this->sendMail($template, $emailFindReplace, $to_email, $to_username);
    }
}
