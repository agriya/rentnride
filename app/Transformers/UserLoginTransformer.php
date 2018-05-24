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
 

namespace App\Transformers;

use League\Fractal;
use App\UserLogin;

/**
 * Class UserLoginTransformer
 * @package App\Transformers
 */
class UserLoginTransformer extends Fractal\TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'UserLoginIp', 'User', 'Role'
    ];

    /**
     * @param UserLogin $user_login
     * @return array
     */
    public function transform(UserLogin $user_login)
    {
        $output = array_only($user_login->toArray(), ['id', 'created_at', 'user_id', 'user_login_ip_id', 'role_id', 'user_agent']);
        return $output;
    }

    /**
     * @param UserLogin $user_login
     * @return Fractal\Resource\Item
     */
    public function includeUserLoginIp(UserLogin $user_login)
    {
        if ($user_login->user_login_ip) {
            return $this->item($user_login->user_login_ip, new IpTransformer());
        } else {
            return null;
        }

    }

    /**
     * @param UserLogin $user_login
     * @return Fractal\Resource\Item
     */
    public function includeUser(UserLogin $user_login)
    {
        if ($user_login->user) {
            return $this->item($user_login->user, new UserSimpleTransformer());
        } else {
            return null;
        }

    }

    /**
     * @param UserLogin $user_login
     * @return Fractal\Resource\Item
     */
    public function includeRole(UserLogin $user_login)
    {
        if ($user_login->role) {
            return $this->item($user_login->role, new RoleTransformer());
        } else {
            return null;
        }

    }
}