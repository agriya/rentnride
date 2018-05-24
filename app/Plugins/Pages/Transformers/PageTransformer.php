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
use App\Transformers\LanguageTransformer;

/**
 * Class PageTransformer
 * @package Pages\Transformers
 */
class PageTransformer extends Fractal\TransformerAbstract
{
    /**
     * List of resources possible to include
     * @var array
     */
    protected $availableIncludes = [
        'Language'
    ];

    /**
     * @param Page $page
     * @return array
     */
    public function transform(Page $page)
    {
        $output = array_only($page->toArray(), ['id', 'title', 'language_id', 'slug', 'page_content', 'is_active']);
        return $output;
    }

    /**
     * @param Page $page
     * @return Fractal\Resource\Item
     */
    public function includeLanguage(Page $page)
    {
        if ($page->language) {
            return $this->item($page->language, new LanguageTransformer());
        } else {
            return null;
        }

    }

}
