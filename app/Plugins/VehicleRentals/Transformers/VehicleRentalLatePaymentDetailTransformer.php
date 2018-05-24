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
use Plugins\VehicleRentals\Model\VehicleRentalLatePaymentDetail;

/**
 * Class VehicleRentalLatePaymentDetailTransformer
 * @package App\Transformers
 */
class VehicleRentalLatePaymentDetailTransformer extends Fractal\TransformerAbstract
{
    /**
     * @param VehicleRentalLatePaymentDetail $vehicle_rental_late_payment_detail
     * @return array
     */
    public function transform(VehicleRentalLatePaymentDetail $vehicle_rental_late_payment_detail)
    {
        $output = array_only($vehicle_rental_late_payment_detail->toArray(), ['id']);
        return $output;
    }
}

?>