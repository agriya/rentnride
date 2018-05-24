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
 
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Plugins\SocialLogins\Model\Provider;
use Plugins\SocialLogins\Model\ProviderUser;

class ProvidersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Provider::create(array(
            'id' => 1,
            'name' => 'Facebook',
            'secret_key' => ($_ENV['DB_DATABASE'] == 'smysql_bookorrent') ? '273238c75664b68914efa64d6a49399c' : '19dff7bada02624c89af8d6d94977cb8',
            'api_key' => ($_ENV['DB_DATABASE'] == 'smysql_bookorrent') ? '491567277716372' : '2003061299919443',
            'icon_class' => 'fa-facebook',
            'button_class' => 'btn-facebook',
            'is_active' => true,
            'display_order' => 1
        ));

        Provider::create(array(
            'id' => 2,
            'name' => 'Twitter',
            'secret_key' => '3WY4tkA6eEtTNPdU6lvZuIBc4Rqp2kOis9TMJd8lvelAL3g1gu',
            'api_key' => 'G9vRaWhm11QcDMJ6TrovMcFdP',
            'icon_class' => 'fa-twitter',
            'button_class' => 'btn-twitter',
            'is_active' => true,
            'display_order' => 2
        ));

        Provider::create(array(
            'id' => 3,
            'name' => 'Google',
            'secret_key' => 'Y4Rtr7apXX5N0rXE6Ifa5FPJ',
            'api_key' => '95821807561-2jm168ubd9rccv3en94lu7fn6b1a0hc6.apps.googleusercontent.com',
            'icon_class' => 'fa-google',
            'button_class' => 'btn-google',
            'is_active' => true,
            'display_order' => 3
        ));
        Provider::create(array(
            'id' => 4,
            'name' => 'Github',
            'secret_key' => ($_ENV['DB_DATABASE'] == 'smysql_bookorrent') ? 'f54bdf0ef3b5da8478978ae3f3176be638549d29' : 'ef3761332d0b2c970513712c1fe6b3da371b76b6',
            'api_key' => ($_ENV['DB_DATABASE'] == 'smysql_bookorrent') ? '4f898e5bbeb66c3288b1' : '8beb15c45bc7d1d71510',
            'icon_class' => 'fa-github',
            'button_class' => 'btn-github',
            'is_active' => true,
            'display_order' => 4
        ));

    }
}