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
 
namespace Plugins\Sudopays\Model;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class SudopayPaymentGroup extends Model
{
    /**
     * @var string
     */
    protected $table = "sudopay_payment_groups";
    /**
     * @var array
     */
    protected $fillable = [
        'sudopay_group_id', 'name', 'thumb_url'
    ];
}
