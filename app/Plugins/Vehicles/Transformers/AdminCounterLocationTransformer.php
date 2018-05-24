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
use Plugins\Vehicles\Model\CounterLocation;


/**
 * Class AdminCounterLocationTransformer
 * @package Plugins\Vehicles\Transformers
 */
class AdminCounterLocationTransformer extends Fractal\TransformerAbstract
{

    /**
     * @param CounterLocation $counter_location
     * @return array
     */
    public function transform(CounterLocation $counter_location)
    {
        $output = array_only($counter_location->toArray(), ['id', 'created_at', 'address', 'latitude', 'longitude', 'phone', 'mobile', 'fax', 'email']);
        return $output;
    }


}
