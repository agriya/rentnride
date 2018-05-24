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
 
namespace Plugins\Pages\Transformers;

use League\Fractal;
use Plugins\Pages\Model\Page;

/**
 * Class PageTransformer
 * @package Pages\Transformers
 */
class PageLanguageTransformer extends Fractal\TransformerAbstract
{

    /**
     * @param Page $page
     * @return array
     */
    public function transform(Page $page)
    {
        $output = array_only($page->toArray(), ['id', 'title', 'slug']);
        return $output;
    }
}
