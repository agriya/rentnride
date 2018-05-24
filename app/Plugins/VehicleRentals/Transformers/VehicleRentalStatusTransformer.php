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
use Plugins\VehicleRentals\Model\VehicleRentalStatus;

/**
 * Class VehicleRentalStatusTransformer
 * @package App\Transformers
 */
class VehicleRentalStatusTransformer extends Fractal\TransformerAbstract
{
    /**
     * @param VehicleRentalStatus $vehicle_rental_status
     * @return array
     */
    public function transform(VehicleRentalStatus $vehicle_rental_status)
    {
        $output = array_only($vehicle_rental_status->toArray(), ['id', 'name', 'description']);
        if(!isPluginEnabled('VehicleFeedbacks')) {
            if ($output['name'] == 'Waiting for Review') {
                $output['name'] = 'Waiting for Next Update';
            }
        }

        return $output;
    }
}

?>