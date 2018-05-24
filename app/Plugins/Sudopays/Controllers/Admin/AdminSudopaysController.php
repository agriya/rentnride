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
 
namespace Plugins\Sudopays\Controllers\Admin;

use Illuminate\Http\Request;


use DB;
use App\Http\Controllers\Controller;
use Plugins\Sudopays\Model\Sudopay;
use JWTAuth;
use Validator;

use Plugins\Sudopays\Services\SudopayAPIService;
use Plugins\Sudopays\Model\SudopayPaymentGateway;
use Plugins\Sudopays\Model\SudopayPaymentGroup;

/**
 * Money Transfer Accounts resource representation.
 * @Resource("Sudopays")
 */
class AdminSudopaysController extends Controller
{

    /**
     * @var
     */
    protected $sudopayApiService;

    /**
     * SudopaysController constructor.
     */
    public function __construct()
    {
        // Check the logged user authentication.
        $this->middleware('jwt.auth');
        // Check the logged user role.
        $this->middleware('role');
        $this->setSudopayAPIService();
    }

    public function setSudopayAPIService()
    {
        $this->SudopayAPIService = new SudopayAPIService();
    }

    /**
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response|void
     */
    public function synchronize()
    {
        try {
            $this->SudopayAPIService->_doDeleteCache(array('supported_actions' => 'auth,capture'));
            $gateway_response = $this->SudopayAPIService->callGateways(array('supported_actions' => 'auth,capture'));
            if (!$gateway_response) {
                return $this->response->errorNotFound("Invalid Request");
            } else {
                $enabled_gateways = array();
                DB::table('sudopay_payment_groups')->delete();
                DB::table('sudopay_payment_gateways')->delete();
                if (empty($gateway_response['error']['message'])) {
                    $i = 0;
                    foreach ($gateway_response['gateways'] as $key => $gateway_group) {
                        $group_data = array();
                        $group_data['sudopay_group_id'] = $gateway_group['id'];
                        $group_data['name'] = $gateway_group['name'];
                        $group_data['thumb_url'] = $gateway_group['thumb_url'];
                        $sudopay_group = SudopayPaymentGroup::create($group_data);
                        $sudopay_group_id = $sudopay_group->id;
                        foreach ($gateway_group['gateways'] as $keyval => $gateway) {
                            $supported_actions = $gateway['supported_features'][0]['actions'];
                            $is_marketplace_supported = 0;
                            if (in_array('Marketplace-Auth', $supported_actions)) {
                                $is_marketplace_supported = 1;
                            }
                            $enabled_gateways[$i]['sudopay_gateway_name'] = $gateway['display_name'];
                            $enabled_gateways[$i]['is_marketplace_supported'] = $is_marketplace_supported;
                            $enabled_gateways[$i]['sudopay_gateway_id'] = $gateway['id'];
                            $enabled_gateways[$i]['sudopay_payment_group_id'] = $sudopay_group_id;
                            $enabled_gateways[$i]['sudopay_gateway_details'] = serialize($gateway);
                            $enabled_gateways[$i]['thumb_url'] = $gateway['thumb_url'];
                            $enabled_gateways[$i]['name'] = $gateway['name'];
                            SudopayPaymentGateway::create($enabled_gateways[$i]);
                            $enabled_gateways[$i]['supported_features']['actions'] = $gateway['supported_features'][0]['actions'];
                            $enabled_gateways[$i]['supported_features']['currencies'] = $gateway['supported_features'][0]['currencies'];
                            $i++;
                        }
                    }
                }
                return response()->json(['Success' => 'ZazPay synchronized successfully'], 200);
            }
        } catch (\Exception $e) {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('ZazPay could not be synchronized. Please, try again.');
        }
    }

}