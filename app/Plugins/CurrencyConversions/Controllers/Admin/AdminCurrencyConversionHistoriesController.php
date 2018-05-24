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

use Plugins\CurrencyConversions\Model\CurrencyConversionHistory;

use JWTAuth;
use Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Plugins\CurrencyConversions\Transformers\CurrencyConversionHistoryTransformer;
use DB;

/**
 * CurrencyConversionHistories resource representation.
 * @Resource("Admin/AdminCurrencyConversionHistories")
 */
class AdminCurrencyConversionHistoriesController extends Controller
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
     * @Get("/currency_conversion_histories?sort={sort}&sortby={sortby}")
     * @Parameters({
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1),
     *      @Parameter("sort", type="string", required=false, description="Sort the currency conversion histories list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort currency conversion histories by Ascending / Descending Order.", default=null),
     * })
     */
    public function index(Request $request)
    {
        $currency_conversion_histories = CurrencyConversionHistory::with('currency_conversion')
            ->select(DB::raw('currency_conversion_histories.*'))
            ->leftJoin(DB::raw('(select id, currency_id, converted_currency_id from currency_conversions) as currency_conversion'), 'currency_conversion.id', '=', 'currency_conversion_histories.currency_conversion_id')
            ->leftJoin(DB::raw('(select id,name from currencies) as currency'), 'currency.id', '=', 'currency_conversion.currency_id')
            ->leftJoin(DB::raw('(select id,name from currencies) as converted_currency'), 'converted_currency.id', '=', 'currency_conversion.converted_currency_id')
            ->filterByRequest($request)->paginate(config('constants.ConstPageLimit'));

        return $this->response->paginator($currency_conversion_histories, (new CurrencyConversionHistoryTransformer)->setDefaultIncludes(['currency_conversion']));
    }
}