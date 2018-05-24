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
 
namespace Plugins\VehicleRentals\Model;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

use JWTAuth;


/**
 * Class VehicleRentalLatePaymentDetail
 * @package Plugins\VehicleRentals\Model
 */
class VehicleRentalLatePaymentDetail extends Model
{
    /**
     * @var string
     */
    protected $table = "late_payment_details";
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'item_user_id', 'booking_start_date', 'booking_end_date', 'checkin_date', 'checkout_date', 'booking_amount', 'late_checkout_fee', 'extra_time_taken'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vehicle_rental()
    {
        return $this->belongsTo(VehicleRental::class);
    }

}
