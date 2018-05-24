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
 
namespace Plugins\Sudopays\Services;

use Plugins\Sudopays\Model\SudopayIpnLog;
use App\Services\IpService;
class SudopayIpnService
{
    /**
     * @var
     */
    protected $ipService;

    /**
     * SudopayIpnService constructor.
     */
    public function __construct()
    {
        $this->setIpService();
    }

    /**
     * Ipservice object created
     */
    public function setIpService() {
        $this->ipService = new IpService();
    }

    /**
     * @param $request
     * @param $ip
     * @return static
     */
    public function log($request, $ip)
    {
        $log = array();
        $log['ip'] = $this->ipService->getIpId($ip);
        $log['post_variable'] = serialize($request);
        return SudopayIpnLog::create($log);
    }
}

?>
