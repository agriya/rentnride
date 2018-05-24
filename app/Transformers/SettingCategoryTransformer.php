<?php
/**
 * Rent & Ride
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
 

namespace app\Transformers;

use League\Fractal;
use App\SettingCategory;

/**
 * Class SettingCategoryTransformer
 * @package app\Transformers
 */
class SettingCategoryTransformer extends Fractal\TransformerAbstract
{
    /**
     * @param SettingCategory $setting_category
     * @return array
     */
    public function transform(SettingCategory $setting_category)
    {
        $output = array_only($setting_category->toArray(), ['id', 'name', 'description', 'display_order']);
        return $output;
    }
}