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

namespace Plugins\Pages\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Plugins\Pages\Model\Page;
use App\Language;

use JWTAuth;
use Validator;
use Plugins\Pages\Transformers\PageLanguageTransformer;
use Plugins\Pages\Transformers\PageTransformer;


/**
 * Pages resource representation.
 * @Resource("/Pages")
 */
class PagesController extends Controller
{
    /**
     * Show all pages based on languages
     * Get a JSON representation of all the pages.
     *
     * @Get("/languages/{en}/pages")
     * @Transaction({
     *      @Request({"iso2": "en"}),
     *      @Response(200, body={"id": 1, "title": "Static page", "slug": "static-page"}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function getPages($iso2)
    {
        if (empty($iso2)) {
            $iso2 = config('site.dafault_language');
        }
        $language = Language::where('iso2', '=', $iso2)->first();
        $page = Page::where('language_id', '=', $language['id'])->get();
        if ($page) {
            return $this->response->collection($page, new PageLanguageTransformer);
        }
    }

    /**
     * Show the specified page.
     * Show the page with a `id`.
     * @Get("/pages/{slug}/{iso2}")
     * @Transaction({
     *      @Request({"slug": "term-and-conditions", "iso2": "en"}),
     *      @Response(200, body={"id": 1, "is_active": 1, "language_id": "1", "slug": "term-and-conditions", "page_content": "XXX", "title":"term and condition"}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function show($slug, $iso2)
    {
        $language = Language::where('iso2', '=', $iso2)->first();
        if (!$language) {
            return $this->response->errorNotFound("Invalid Request");
        }
        $page = Page::where('slug', '=', $slug)
            ->where('language_id', '=', $language->id)
            ->first();
        if (!$page) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($page, new PageTransformer);
    }

}
