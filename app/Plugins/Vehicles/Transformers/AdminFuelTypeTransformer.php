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
 
namespace Plugins\Vehicles\Transformers;

use League\Fractal;
use Plugins\Vehicles\Model\FuelType;


/**
 * Class AdminFuelTypeTransformer
 * @package Plugins\Vehicles\Transformers
 */
class AdminFuelTypeTransformer extends Fractal\TransformerAbstract
{

    /**
     * @param FuelType $fuel_type
     * @return array
     */
    public function transform(FuelType $fuel_type)
    {
        $output = array_only($fuel_type->toArray(), ['id', 'name']);
        return $output;
    }

}
