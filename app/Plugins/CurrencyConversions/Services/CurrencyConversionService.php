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
 
namespace Plugins\CurrencyConversions\Services;

use App\Currency;
use Plugins\CurrencyConversions\Model\CurrencyConversion;
use Plugins\CurrencyConversions\Model\CurrencyConversionHistory;
use Validator;

class CurrencyConversionService
{
    /**
     * updates daily currency rates in currency_conversion & currency_conversion_history table through cron
     */
    public function currencyconversion()
    {
        $currency_conversion_data = [];
        $currency_conversion_history_data = [];
        $httpAdapter = new \Ivory\HttpAdapter\FileGetContentsHttpAdapter();
        $chainProvider = new \Swap\Provider\ChainProvider([
            new \Swap\Provider\YahooFinanceProvider($httpAdapter),
            new \Swap\Provider\GoogleFinanceProvider($httpAdapter)
        ]);
        $swap = new \Swap\Swap($chainProvider);
        $currency_list = Currency::select('id', 'code')->get();
        $active_currencies = Currency::select('id', 'code')->where('is_active', '=', 1)->get();
        foreach ($currency_list as $currency) {
            foreach ($active_currencies as $active_currency) {
                $currency_conversion_data['currency_id'] = $currency->id;
                $currency_conversion_data['converted_currency_id'] = $active_currency->id;
                $rate = $swap->quote($currency->code . '/' . $active_currency->code);
                $currency_conversion_data['rate'] = (double)$rate->getValue();
                $currency_conversion_list = CurrencyConversion::where('currency_id', '=', $currency->id)
                    ->where('converted_currency_id', '=', $active_currency->id)->first();
                if ($currency_conversion_list) {
                    $currency_conversion_data['id'] = $currency_conversion_list->id;
                    $currency_conversion_history_data['rate_before_change'] = $currency_conversion_list->rate;
                    $currency_conversion_history_data['currency_conversion_id'] = $currency_conversion_list->id;
                    $validator = Validator::make($currency_conversion_data, CurrencyConversion::GetValidationRule());
                    if ($validator->passes() && $currency_conversion_list) {
                        $currency_conversion_list->update($currency_conversion_data);
                    }
                } else {
                    $currency_conversion_history_data['rate_before_change'] = 0.00;
                    $validator = Validator::make($currency_conversion_data, CurrencyConversion::GetValidationRule());
                    if ($validator->passes()) {
                        $currency_conversion = CurrencyConversion::create($currency_conversion_data);
                        $currency_conversion_history_data['currency_conversion_id'] = $currency_conversion->id;
                    }
                }
                $currency_conversion_history_data['rate'] = (double)$rate->getValue();
                $validator = Validator::make($currency_conversion_history_data, CurrencyConversionHistory::GetValidationRule());
                if ($validator->passes()) {
                    CurrencyConversionHistory::create($currency_conversion_history_data);
                }
            }
        }
    }

    public function getCurrencyconvertion() {
        $currency = Currency::where('code', config('site.currency_code'))->first();
        $currency_conversion = CurrencyConversion::with(['converted_currency', 'currency'])->where('currency_id', $currency->id)->get();
        $currency_response = array();
        foreach($currency_conversion as $currency) {
            $currency_response[] = $currency->converted_currency;
            $currency->converted_currency['rate'] = $currency->rate;
        }
        $response['currency_conversion'] = $currency_response;
        return $response;
    }
}
