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
use App\UserProfile;

/**
 * Class UserProfileTransformer
 * @package App\Transformers
 */
class UserProfileTransformer extends Fractal\TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'User'
    ];

    /**
     * @param UserProfile $user_profile
     * @return array
     */
    public function transform(UserProfile $user_profile)
    {
        $output = array_only($user_profile->toArray(), ['id', 'user_id', 'first_name', 'last_name', 'about_me', 'website', 'facebook_profile_link', 'twitter_profile_link', 'google_plus_profile_link', 'linkedin_profile_link', 'youtube_profile_link']);
        return $output;
    }


    /**
     * @param UserProfile $user_profile
     * @return Fractal\Resource\Item
     */
    public function includeUser(UserProfile $user_profile)
    {
        if ($user_profile->user) {
            return $this->item($user_profile->user, (new UserTransformer())->setDefaultIncludes(['attachmentable']));
        } else {
            return null;
        }

    }


}