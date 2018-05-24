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
namespace Plugins\CurrencyConversions\Transformers;

use League\Fractal;
use Plugins\CurrencyConversions\Model\CurrencyConversion;
use App\Transformers\CurrencyTransformer;

/**
 * Class CurrencyConversionTransformer
 * @package App\Transformers
 */
class CurrencyConversionTransformer extends Fractal\TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'Currency', 'ConvertedCurrency'
    ];

    /**
     * @param CurrencyConversion $currency_conversion
     * @return array
     */
    public function transform(CurrencyConversion $currency_conversion_history)
    {
        $output = array_only($currency_conversion_history->toArray(), ['id', 'updated_at', 'currency_id', 'converted_currency_id', 'rate']);
        return $output;
    }

	/**
     * @param CurrencyConversion $currency_conversion
     * @return Fractal\Resource\Item
     */
    public function includeCurrency(CurrencyConversion $currency_conversion)
    {
        if ($currency_conversion->currency) {
            return $this->item($currency_conversion->currency, new CurrencyTransformer());
        } else {
            return null;
        }

    }

	/**
     * @param CurrencyConversion $currency_conversion
     * @return Fractal\Resource\Item
     */
    public function includeConvertedCurrency(CurrencyConversion $currency_conversion)
    {
        if ($currency_conversion->converted_currency) {
            return $this->item($currency_conversion->converted_currency, new CurrencyTransformer());
        } else {
            return null;
        }

    }

}