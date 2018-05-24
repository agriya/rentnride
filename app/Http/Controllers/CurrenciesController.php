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

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Currency;
use App\Transformers\CurrencyTransformer;

/**
 * Currencies resource representation.
 * @Resource("Currencies")
 */
class CurrenciesController extends Controller
{
    /**
     * Show all currencies.
     * Get a JSON representation of all the currencies.
     *
     * @Get("/currencies?filter={filter}&sort={sort}&sortby={sortby}&type={type}&field={field}&q={q}")
     * @Parameters({
     *      @Parameter("filter", type="integer", required=false, description="Filter the currencies list by type.", default=null),
     *      @Parameter("sort", type="string", required=false, description="Sort the currencies list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort currencies by Ascending / Descending Order.", default=null),
     *      @Parameter("q", type="string", required=false, description="Search currencies.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1)
     * })
     */
    public function index(Request $request)
    {
        $currencies = Currency::filterByRequest($request)->get();
        return $this->response->collection($currencies, new CurrencyTransformer);
    }
}
