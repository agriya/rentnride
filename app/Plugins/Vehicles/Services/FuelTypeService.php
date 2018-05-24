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

use Plugins\Vehicles\Model\FuelType;

class FuelTypeService
{
    /**
     * FuelTypeService constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getFuelTypeList()
    {
        $feul_type_list = FuelType::lists('id', 'name');
        foreach($feul_type_list as $value=>$key) {
            $feul_type_list[$value] = (integer)$key;
        }
        return $feul_type_list;
    }
}
