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
 
namespace Plugins\VehicleDisputes\Model;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class VehicleDisputeStatus extends Model
{
    /**
     * @var string
     */
    protected $table = "dispute_statuses";

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function item_user_dispute()
    {
        return $this->hasMany(VehicleDispute::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function message()
    {
        return $this->hasMany(\App\Message::class);
    }

}
