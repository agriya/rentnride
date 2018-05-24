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

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;


use App\Http\Controllers\Controller;
use App\Currency;

use JWTAuth;
use Validator;
use App\Transformers\CurrencyTransformer;

/**
 * Currencies resource representation.
 * @Resource("Admin/AdminCurrencies")
 */
class AdminCurrenciesController extends Controller
{
    /**
     * AdminCurrenciesController constructor.
     */
    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
        // Check the logged user role.
        $this->middleware('role');
    }

    /**
     * Show all currencies
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
        $currency_count = config('constants.ConstPageLimit');
        if ($request->has('type') && $request->type == 'list') {
            $currency_count = Currency::count();
        }
        $currencies = Currency::filterByRequest($request)->paginate($currency_count);
        return $this->response->paginator($currencies, new CurrencyTransformer);
    }

    /**
     * Store a new currency.
     * Store a new currency with a `name`, `code`, `symbol`, `prefix`, `suffix`, `decimals` ,`dec_point`, `thousands_sep`, `is_prefix_display_on_left` and `is_use_graphic_symbol`.
     * @Post("/currencies")
     * @Transaction({
     *      @Request({"name": "Dollar", "code": "USD", "symbol": "$", "prefix" : "$", "suffix":"", "decimals":"0.00", "dec_point":"2", "thousands_sep":"4", "is_prefix_display_on_left":1, "is_use_graphic_symbol":1 }),
     *      @Response(200, body={"success": "Record has been added."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {"name": {}}, "status_code": 422})
     * })
     */
    public function store(Request $request)
    {
        $currency_data = $request->only('name', 'code', 'symbol', 'prefix', 'suffix', 'decimals', 'dec_point', 'thousands_sep', 'is_prefix_display_on_left', 'is_use_graphic_symbol');
        $validator = Validator::make($currency_data, Currency::GetValidationRule(), Currency::GetValidationMessage());
        if ($validator->passes()) {
            $currency_data['is_active'] = true;
            $currency = Currency::create($currency_data);
            if ($currency) {
                return response()->json(['Success' => 'Currency has been added'], 200);
            } else {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('Currency could not be updated. Please, try again.');
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Currency could not be updated. Please, try again.', $validator->errors());
        }
    }

    /**
     * Edit the specified currency.
     * Edit the currency with a `id`.
     * @Get("/currencies/{id}/edit")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"id": 1, "name": "Dollar", "code": "USD", "symbol": "$", "prefix" : "$", "suffix":"", "decimals":"0.00", "dec_point":"2", "thousands_sep":"4", "is_prefix_display_on_left":1, "is_use_graphic_symbol":1, "is_active": 1}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function edit($id)
    {
        $currency = Currency::find($id);
        if (!$currency) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($currency, new CurrencyTransformer);
    }

    /**
     * Show the specified currency.
     * show the currency with a `id`.
     * @Get("/currencies/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"id": 1, "name": "Dollar", "code": "USD", "symbol": "$", "prefix" : "$", "suffix":"USD", "decimals":"2", "dec_point":".", "thousands_sep":",", "is_prefix_display_on_left":1, "is_use_graphic_symbol":1, "is_active": 1}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function show($id)
    {
        $currency = Currency::find($id);
        if (!$currency) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($currency, new CurrencyTransformer);
    }

    /**
     * Update the specified currency
     * Update the currency with a `id`.
     * @Put("/currencies/{id}")
     * @Transaction({
     *      @Request({"id": 1, "name": "Dollar", "code": "USD", "symbol": "$", "prefix" : "$", "suffix":"", "decimals":"0.00", "dec_point":"2", "thousands_sep":"4", "is_prefix_display_on_left":1, "is_use_graphic_symbol":1, "is_active": 1}),
     *      @Response(200, body={"success": "Record has been updated."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {"name": {}}, "status_code": 422})
     * })
     */
    public function update(Request $request, $id)
    {
        $currency_data = $request->only('name', 'code', 'symbol', 'prefix', 'suffix', 'decimals', 'dec_point', 'thousands_sep', 'is_prefix_display_on_left', 'is_use_graphic_symbol', 'is_active');
        $validator = Validator::make($currency_data, Currency::GetValidationRule(), Currency::GetValidationMessage());
        $currency = false;
        if ($request->has('id')) {
            $currency = Currency::find($id);
            $currency = ($request->id != $id) ? false : $currency;
        }
        if ($validator->passes() && $currency) {
            try {
                $currency->update($currency_data);
                return response()->json(['Success' => 'Currency has been updated'], 200);
            } catch (\Exception $e) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('Currency could not be updated. Please, try again.');
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Currency could not be updated. Please, try again.', $validator->errors());
        }

    }

    /**
     * Delete the specified currency
     * Delete the currency with a `id`.
     * @Delete("/currencies/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"success": "Record Deleted."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function destroy($id)
    {
        $currency = Currency::find($id);
        if (!$currency) {
            return $this->response->errorNotFound("Invalid Request");
        } else {
            $currency->delete();
        }
        return response()->json(['Success' => 'Currency deleted'], 200);
    }
}
