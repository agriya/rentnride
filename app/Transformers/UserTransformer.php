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
use App\User;
use App\Services\AttachmentService;
use App\Attachment;

class UserTransformer extends Fractal\TransformerAbstract
{
    /**
     * List of resources possible to include
     * @var array
     */
    protected $availableIncludes = [
        'RegisterIp', 'LastLoginIp', 'UserProfile', 'Attachmentable', 'ProviderUser', 'Vehicle'
    ];

    /**
     * @param User $user
     * @return array
     */
    public function transform(User $user)
    {
        $output = array_only($user->toArray(), ['id', 'created_at', 'username', 'email', 'is_active', 'is_email_confirmed', 'role_id', 'password', 'register_ip_id', 'last_login_ip_id', 'available_wallet_amount', 'user_login_count', 'vehicle_rental_order_count', 'vehicle_rental_count', 'user_avatar_source_id']);
        $output['is_active'] = ($output['is_active'] == 1) ? true : false;
        $output['is_email_confirmed'] = ($output['is_email_confirmed'] == 1) ? true : false;
        return $output;
    }

    /**
     * @param User $user
     * @return Fractal\Resource\Item
     */
    public function includeRegisterIp(User $user)
    {
        if ($user->register_ip) {
            return $this->item($user->register_ip, new IpTransformer());
        } else {
            return null;
        }

    }

    /**
     * @param User $user
     * @return Fractal\Resource\Item|null
     */
    public function includeLastLoginIp(User $user)
    {
        if ($user->last_login_ip) {
            return $this->item($user->last_login_ip, new IpTransformer());
        } else {
            return null;
        }

    }

    /**
     * @param User $user
     * @return Fractal\Resource\Item|null
     */
    public function includeUserProfile(User $user)
    {
        if ($user->user_profile) {
            return $this->item($user->user_profile, new UserProfileTransformer());
        } else {
            return null;
        }

    }

    /**
     * @param User $user
     * @return mixed
     */
    public function includeAttachmentable(User $user)
    {
        if ($user->attachments) {
            return $this->item($user->attachments, new AttachmentTransformer());
        } else {
            $user->attachments = Attachment::where('id', '=', config('constants.ConstAttachment.UserAvatar'))->first();
            $user->attachments->attachmentable_id = $user->id;
            return $this->item($user->attachments, new AttachmentTransformer());
        }
    }

    /**
     * @param User $user
     * @return Fractal\Resource\Item|null
     */
    public function includeVehicle(User $user)
    {
        if ($user->Vehicle) {
            return $this->Collection($user->Vehicle, new VehicleTransformer());
        } else {
            return null;
        }

    }

    /**
     * @param User $user
     * @return array|Fractal\Resource\Item
     */
    public function includeProviderUser(User $user)
    {
        if ($user->provider_user) {
            return $this->Collection($user->provider_user, new  \Plugins\SocialLogins\Transformers\ProviderUserTransformer);
        } else {
            return null;
        }

    }
}
