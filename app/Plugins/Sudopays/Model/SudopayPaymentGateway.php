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

class SudopayPaymentGateway extends Model
{
    /**
     * @var string
     */
    protected $table = "sudopay_payment_gateways";
    /**
     * @var array
     */
    protected $fillable = [
        'sudopay_gateway_name', 'sudopay_gateway_details', 'is_marketplace_supported', 'sudopay_gateway_id', 'sudopay_payment_group_id', 'form_fields_credit_card', 'form_fields_manual', 'form_fields_buyer', 'thumb_url', 'supported_features_actions', 'supported_features_card_types', 'supported_features_countries', 'supported_features_credit_card_types', 'supported_features_currencies', 'supported_features_languages', 'supported_features_services', 'connect_instruction', 'name'
    ];
}
