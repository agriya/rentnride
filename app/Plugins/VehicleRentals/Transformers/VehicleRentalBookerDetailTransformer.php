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
use Plugins\VehicleRentals\Model\VehicleRentalBookerDetail;

/**
 * Class VehicleRentalBookerDetailTransformer
 * @package App\Transformers
 */
class VehicleRentalBookerDetailTransformer extends Fractal\TransformerAbstract
{
    /**
     * @param VehicleRentalBookerDetail $vehicle_rental_booker_detail
     * @return array
     */
    public function transform(VehicleRentalBookerDetail $vehicle_rental_booker_detail)
    {
        $output = array_only($vehicle_rental_booker_detail->toArray(), ['id', 'email', 'first_name', 'last_name', 'mobile', 'address']);
        return $output;
    }
}

?>