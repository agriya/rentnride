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
use App\State;

/**
 * Class StateTransformer
 * @package App\Transformers
 */
class StateTransformer extends Fractal\TransformerAbstract
{
    /**
     * List of resources possible to include
     * @var array
     */
    protected $availableIncludes = [
        'Country'
    ];

    /**
     * @param State $state
     * @return array
     */
    public function transform(State $state)
    {
        $output = array_only($state->toArray(), ['id', 'name', 'country_id', 'is_active']);
        $output['is_active'] = ($output['is_active'] == 1) ? true : false;
        return $output;
    }

    /**
     * @param State $state
     * @return Fractal\Resource\Item
     */
    public function includeCountry(State $state)
    {
        if ($state->country) {
            return $this->item($state->country, new CountryTransformer());
        } else {
            return null;
        }

    }
}
