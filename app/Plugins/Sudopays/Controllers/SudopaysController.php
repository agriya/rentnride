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
 
namespace Plugins\Sudopays\Controllers;

use Illuminate\Http\Request;

use DB;
use App\Http\Controllers\Controller;
use Plugins\Sudopays\Model\Sudopay;
use JWTAuth;
use Validator;

use Plugins\Sudopays\Services\SudopayAPIService;
use Plugins\Sudopays\Services\SudopayService;
use Plugins\Sudopays\Services\SudopayIpnService;

/**
 * Sudopays resource representation.
 * @Resource("Sudopays")
 */
class SudopaysController extends Controller
{
    /**
     * @var
     */
    protected $sudopayIpnService;
    /**
     * @var
     */
    protected $sudopayApiService;
    /**
     * @var
     */
    protected $sudopayService;

    /**
     * SudopaysController constructor.
     */
    public function __construct()
    {
        $this->setSudopayAPIService();
        $this->setSudopayService();
        $this->setSudopayIpnService();
    }

    /**
     * sudopayapi service object created
     */
    public function setSudopayAPIService()
    {
        $this->sudopayApiService = new SudopayAPIService();
    }

    /**
     * sudopay service object created
     */
    public function setSudopayService()
    {
        $this->sudopayService = new SudopayService();
    }

    /**
     *
     */
    public function setSudopayIpnService()
    {
        $this->sudopayIpnService = new SudopayIpnService();
    }

    /**
     * @param Request $request
     * @param         $logable_id
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function processPayment(Request $request, $logable_id)
    {
        $this->sudopayIpnService->log($request->all(), $request->ip());
        $response = $this->sudopayService->executePayment($logable_id, $request->all());
        if ($response) {
            return response()->json(['success' => "ipn successfully updated"], 200);
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('IPN could not be updated.');
        }

    }

    /**
     * @param $request
     * @param $logable_id
     */
    public function successPayment($logable_id) {
        $response = $this->sudopayService->successPayment($logable_id);
        return redirect($response['returnUrl']);
    }
	
	/**
     * @param $request
     * @param $logable_id
     */
    public function cancelPayment($logable_id) {
        $response = $this->sudopayService->cancelPayment($logable_id);
        return redirect($response['returnUrl']);
    }

}