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

namespace Plugins\SocialLogins\Transformers;

use League\Fractal;
use Plugins\SocialLogins\Model\ProviderUser;
use App\Transformers\UserTransformer;

/**
 * Class ProviderTransformer
 * @package app\Transformers
 */
class ProviderUserTransformer extends Fractal\TransformerAbstract
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
     * @param ProviderUser $provider_user
     * @return array
     */
    public function transform(ProviderUser $provider_user)
    {
        $output = array_only($provider_user->toArray(), ['user_id', 'provider_id', 'is_connected', 'profile_picture_url']);
		$output['is_connected'] = (integer)$output['is_connected'];
        return $output;
    }

    /**
     * @param ProviderUser $provider_user
     * @return Fractal\Resource\Item
     */
    public function includeUser(ProviderUser $provider_user)
    {
        if ($provider_user->user) {
            return $this->item($provider_user->user, new UserTransformer());
        } else {
            return null;
        }

    }

}