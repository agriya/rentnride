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

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Language;
use App\Transformers\LanguageTransformer;

/**
 * Contacts resource representation.
 * @Resource("Languages")
 */
class LanguagesController extends Controller
{
    /**
     * Show all languages
     * Get a JSON representation of all the languages.
     * @Get("/languages?sort={sort}")
     * @Parameters({
     *      @Parameter("sort", type="string", required=false, description="Sort the language list by sort ley.", default=null),
     * })
     */
    public function index(Request $request)
    {
        $languages = Language::filterByRequest($request)->get();
        return $this->response->collection($languages, new LanguageTransformer);
    }
}