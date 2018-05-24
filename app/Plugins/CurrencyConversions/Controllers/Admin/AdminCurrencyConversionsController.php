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

namespace Plugins\CurrencyConversions\Controllers\Admin;

use Illuminate\Http\Request;


use App\Http\Controllers\Controller;

use Plugins\CurrencyConversions\Model\CurrencyConversion;

use JWTAuth;
use Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Plugins\CurrencyConversions\Transformers\CurrencyConversionTransformer;
use DB;
/**
 * CurrencyConversions resource representation.
 * @Resource("Admin/AdminCurrencyConversions")
 */
class AdminCurrencyConversionsController extends Controller
{
    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
        // Check the logged user role.
        $this->middleware('role');
    }

    /**
     * Show all api requests.
     * Get a JSON representation of all the api requests.
     *
     * @Get("/currency_conversions?sort={sort}&sortby={sortby}")
     * @Parameters({
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1),
     *      @Parameter("sort", type="string", required=false, description="Sort the currency conversions list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort currency conversions by Ascending / Descending Order.", default=null),
     * })
     */
    public function index(Request $request)
    {
        $currency_conversions = CurrencyConversion::with('currency', 'converted_currency')
            ->select(DB::raw('currency_conversions.*'))
            ->leftJoin(DB::raw('(select id,name from currencies) as currency'), 'currency.id', '=', 'currency_conversions.currency_id')
            ->leftJoin(DB::raw('(select id,name from currencies) as converted_currency'), 'converted_currency.id', '=', 'currency_conversions.converted_currency_id')
            ->filterByRequest($request)->paginate(config('constants.ConstPageLimit'));
        return $this->response->paginator($currency_conversions, (new CurrencyConversionTransformer)->setDefaultIncludes(['currency', 'converted_currency']));
    }
}