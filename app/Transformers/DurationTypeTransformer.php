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
use App\DurationType;

class DurationTypeTransformer extends Fractal\TransformerAbstract
{
    public function transform(DurationType $duration_type)
    {
        $output = array_only($duration_type->toArray(), ['id', 'name']);
        return $output;
    }
}
