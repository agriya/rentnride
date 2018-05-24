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
 
namespace App\Transformers;

use League\Fractal;
use App\DiscountType;

class DiscountTypeTransformer extends Fractal\TransformerAbstract
{
    public function transform(DiscountType $discount_type)
    {
        $output = array_only($discount_type->toArray(), ['id', 'type']);
        return $output;
    }
}
