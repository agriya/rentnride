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
use DB;


/**
 * Class VehicleRentalAdditionalCharge
 * @package Plugins\VehicleRentals\Model
 */
class VehicleRentalAdditionalCharge extends Model
{
    /**
     * @var string
     */
    protected $table = "item_user_additional_charges";
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'item_user_id', 'additional_chargable_type', 'additional_chargable_id', 'amount'
    ];

    /**
     * Get all of the owning likeable models.
     */
    public function vehicle_rental_additional_chargable()
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vehicle_rental()
    {
        return $this->belongsTo(VehicleRental::class, 'item_user_id');
    }
}
