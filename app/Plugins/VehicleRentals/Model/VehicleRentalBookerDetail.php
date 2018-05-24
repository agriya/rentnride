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
 * Class VehicleRentalBookerDetail
 * @package Plugins\VehicleRentals\Model
 */
class VehicleRentalBookerDetail extends Model
{
    /**
     * @var string
     */
    protected $table = "booker_details";
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'item_user_id', 'email', 'first_name', 'last_name', 'mobile', 'address'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vehicle_rental()
    {
        return $this->belongsTo(VehicleRental::class);
    }

    /**
     * @return array
     */
    public function scopeGetValidationRule()
    {
        return [
            'item_user_id' => 'required|integer',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'mobile' => 'required',
            'address' => 'required'
        ];
    }

    public function scopeGetValidationMessage()
    {
        return [
            'item_user_id.required' => 'Required',
            'item_user_id.integer' => 'Vehicle id must be a number!',
            'first_name.required' => 'Required',
            'last_name.required' => 'Required',
            'email.required' => 'Required',
            'email.email' => 'Given email is not valid!',
            'mobile.required' => 'Required',
            'address.required' => 'Required'
        ];
    }

}
