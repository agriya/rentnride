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
use App\Setting;

use Validator;
use App\Transformers\SettingTransformer;
use App\Currency;

/**
 * Settings resource representation.
 * @Resource("/Settings")
 */
class SettingsController extends Controller
{

    /**
     * Show all settings
     * Get a JSON representation of all the settings.
     * @Get("/settings?sort={sort}&sortby={sortby}&page={page}&setting_category_id={id}")
     * @Parameters({
     *      @Parameter("sort", type="string", required=false, description="Sort the settings list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort setting by Ascending / Descending Order.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1),
     *      @Parameter("setting_category_id", type="integer", required=false, description="Sort setting by category.", default=null)
     * })
     */
    public function index(Request $request)
    {
        if ($request->input('type') == 'public_settings') {
            $settings = Setting::filterByRequest($request)->get();
            if(isPluginEnabled('CurrencyConversions')) {
                $currencyService = new \Plugins\CurrencyConversions\Services\CurrencyConversionService();
                $currency_response = $currencyService->getCurrencyconvertion();
                if(empty($currency_response['currency_conversion'])) {
                    $currency = Currency::where('code', config('site.currency_code'))->first();
                    $response['currencies'] = array($currency);
                }else {
                    $response['currencies'] = $currency_response['currency_conversion'];
                }
            } else {
                $currency = Currency::where('code', config('site.currency_code'))->first();
                $response['currencies'] = array($currency);
            }
            $response['default_currency_code'] = config('site.currency_code');
            $response['settings'] = $this->response->Collection($settings, new SettingTransformer);
            return $response;
        }
    }


}
