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
 
namespace app\Transformers;

use League\Fractal;
use App\Currency;

/**
 * Class CurrencyTransformer
 * @package app\Transformers
 */
class CurrencyTransformer extends Fractal\TransformerAbstract
{
    /**
     * @param Currency $currency
     * @return array
     */
    public function transform(Currency $currency)
    {
        $output = array_only($currency->toArray(), ['id', 'created_at', 'name', 'code', 'symbol', 'prefix', 'suffix', 'decimals', 'dec_point', 'thousands_sep', 'is_prefix_display_on_left', 'is_use_graphic_symbol', 'is_active']);
        $output['is_active'] = ($output['is_active'] == 1) ? true : false;
        return $output;
    }
}