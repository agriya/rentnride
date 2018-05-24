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
use Plugins\CurrencyConversions\Model\CurrencyConversionHistory;

/**
 * Class CurrencyConversionHistoryTransformer
 * @package App\Transformers
 */
class CurrencyConversionHistoryTransformer extends Fractal\TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'CurrencyConversion'
    ];

    /**
     * @param CurrencyConversionHistory $currency_conversion_histories
     * @return array
     */
    public function transform(CurrencyConversionHistory $currency_conversion_history)
    {
        $output = array_only($currency_conversion_history->toArray(), ['id', 'created_at', 'currency_conversion_id', 'rate_before_change', 'rate']);
        return $output;
    }

	/**
     * @param CurrencyConversionHistory $currency_conversion_history
     * @return Fractal\Resource\Item
     */
    public function includeCurrencyConversion(CurrencyConversionHistory $currency_conversion_history)
    {
        if ($currency_conversion_history->currency_conversion) {
            return $this->item($currency_conversion_history->currency_conversion, (new CurrencyConversionTransformer)->setDefaultIncludes(['currency', 'converted_currency']));
        } else {
            return null;
        }

    }
}