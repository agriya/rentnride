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
 
namespace Plugins\Withdrawals\Transformers;

use League\Fractal;
use Plugins\Withdrawals\Model\WithdrawalStatus;

class WithdrawalStatusTransformer extends Fractal\TransformerAbstract
{
    public function transform(WithdrawalStatus $withdrawal_status)
    {
        $output = array_only($withdrawal_status->toArray(), ['id', 'name']);
        return $output;
    }
}

?>