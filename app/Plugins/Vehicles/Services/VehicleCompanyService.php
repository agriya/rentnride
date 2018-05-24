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
 
namespace Plugins\Vehicles\Services;

use Plugins\Vehicles\Model\VehicleCompany;

class VehicleCompanyService
{
    /**
     * VehicleCompanyService constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getVehicleCompanyList()
    {
        $vehicle_company_list = VehicleCompany::where('is_active', true)->lists('id', 'name');
        foreach($vehicle_company_list as $value=>$key) {
            $vehicle_company_list[$value] = (integer)$key;
        }
        return $vehicle_company_list;
    }
}
