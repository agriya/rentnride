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
use Plugins\SocialLogins\Model\Provider;

use JWTAuth;
use Validator;
use Plugins\SocialLogins\Transformers\ProviderTransformer;

/**
 * Providers resource representation.
 * @Resource("/Providers")
 */
class ProvidersController extends Controller
{
    /**
     * Show all providers
     * Get a JSON representation of all the providers.
     * @Get("/providers?filter={filter}&sort={sort}&sortby={sortby}&q={q}")
     * @Parameters({
     *      @Parameter("filter", type="integer", required=false, description="Filter the providers list by type.", default=null),
     *      @Parameter("sort", type="string", required=false, description="Sort the providers list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort providers by Ascending / Descending Order.", default=null),
     *      @Parameter("q", type="string", required=false, description="Search providers.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1)
     * })
     */
    public function index(Request $request)
    {
        $providers = Provider::filterByRequest($request)->get();
        return $this->response->Collection($providers, new ProviderTransformer);
    }


}
