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
 
namespace Plugins\SocialLogins\Services;

use App\Services\UserService;
use Tymon\JWTAuth\Providers\JWT\JWTInterface;
use App\User;

class SocialLoginService
{
    /**
     * @var JWTInterface
     */
    protected $jwt;
    /**
     * @var UserService
     */
    protected $service;

    /**
     * SocialLoginService constructor.
     * @param JWTInterface $jwt
     * @param UserService $service
     */

    public function __construct(JWTInterface $jwt, UserService $service)
    {
        $this->jwt = $jwt;
        $this->service = $service;
    }

    /**
     * Generate JSON Web Token.
     * @param $user
     * @return token
     */
    public function createToken($user)
    {
        $payload = [
            'sub' => $user->id,
            'iat' => time(),
            'exp' => time() + (2 * 7 * 24 * 60 * 60)
        ];
        return $this->jwt->encode($payload, config('constants.token_secret'));
    }

    /**
     * Generate facebook user name
     * @param $fb_user_name
     * @return username
     */

    public function generateUserName($fb_user_name)
    {
        $username = str_replace(' ', '', $fb_user_name);
        $username = strtolower(str_replace('.', '_', $username));
        // A condtion to avoid unavilability of user username in our sites
        $i = 1;
        $created_username = $username;
        while ($this->service->CheckUsernameAvailable($username) !== false) {
            $username = $created_username . $i++;
        }
        return $username;
    }


}
