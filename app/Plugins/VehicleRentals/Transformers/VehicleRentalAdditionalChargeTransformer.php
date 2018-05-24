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
 
namespace Plugins\VehicleRentals\Transformers;

use League\Fractal;
use Plugins\VehicleRentals\Model\VehicleRentalAdditionalCharge;
use App\Transformers\UserSimpleTransformer;

/**
 * Class VehicleRentalAdditionalChargeTransformer
 * @package App\Transformers
 */
class VehicleRentalAdditionalChargeTransformer extends Fractal\TransformerAbstract
{
    /**
     * List of resources possible to include
     * @var array
     */
    protected $availableIncludes = [

    ];

    /**
     * @param VehicleRental $vehicle_rental
     * @return array
     */
    public function transform(VehicleRentalAdditionalCharge $vehicle_rental_additional_chargable)
    {
        $output = array_only($vehicle_rental_additional_chargable->toArray(), ['id', 'item_user_id', 'item_user_additional_chargable_type', 'item_user_additional_chargable_id']);
        return $output;
    }
}
