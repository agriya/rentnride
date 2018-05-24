<?php
/**
 * APP
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
 
namespace App;

use Illuminate\Database\Eloquent\Model;

class DiscountType extends Model
{
    /**
     * @var string
     */
    protected $table = "discount_types";

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function vehicle_type_surcharge()
    {
        return $this->hasMany(\Plugins\VehicleSurcharges\Model\VehicleTypeSurcharge::class);
    }

}
