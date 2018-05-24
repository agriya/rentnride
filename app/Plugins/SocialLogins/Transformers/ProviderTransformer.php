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
use Plugins\SocialLogins\Model\Provider;

/**
 * Class ProviderTransformer
 * @package app\Transformers
 */
class ProviderTransformer extends Fractal\TransformerAbstract
{
    /**
     * @param Provider $provider
     * @return array
     */
    public function transform(Provider $provider)
    {
        $output = array_only($provider->toArray(), ['id', 'name', 'secret_key', 'api_key', 'icon_class', 'button_class', 'display_order', 'is_active']);
        $output['is_active'] = ($output['is_active'] == 1) ? true : false;
        return $output;
    }
}