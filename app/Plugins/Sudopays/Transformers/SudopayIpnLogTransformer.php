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
 
namespace Plugins\Sudopays\Transformers;

use League\Fractal;
use Plugins\Sudopays\Model\SudopayIpnLog;

/**
 * Class SudopayIpnLogTransformer
 * @package Plugins\Sudopays\Transformers
 *
 */
class SudopayIpnLogTransformer extends Fractal\TransformerAbstract
{

    /**
     * @param SudopayIpnLog $sudopay_ipn_log
     * @return array
     */
    public function transform(SudopayIpnLog $sudopay_ipn_log)
    {
        $output = array_only($sudopay_ipn_log->toArray(), ['id', 'created_at', 'ip', 'post_variable']);
        return $output;
    }
}
