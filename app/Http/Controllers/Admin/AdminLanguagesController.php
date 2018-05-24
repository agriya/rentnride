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

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;


use App\Http\Controllers\Controller;
use App\Language;

use JWTAuth;
use Validator;
use App\Transformers\LanguageTransformer;

/**
 * Languages resource representation.
 * @Resource("Admin/AdminLanguages")
 */
class AdminLanguagesController extends Controller
{
    /**
     * AdminLanguagesController constructor.
     */
    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
        // Check the logged user role.
        $this->middleware('role');
    }

    /**
     * Show all languages
     * Get a JSON representation of all the languages.
     *
     * @Get("/languages?filter={filter}&sort={sort}&sortby={sortby}&q={q}")
     * @Parameters({
     *      @Parameter("filter", type="integer", required=false, description="Filter the languages list by type.", default=null),
     *      @Parameter("sort", type="string", required=false, description="Sort the languages list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort languages by Ascending / Descending Order.", default=null),
     *      @Parameter("q", type="string", required=false, description="Search languages.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1)
     * })
     */
    public function index(Request $request)
    {
        $languages = Language::filterByRequest($request)->paginate(config('constants.ConstPageLimit'));
        return $this->response->paginator($languages, new LanguageTransformer);
    }

    /**
     * Store a new language.
     * Store a new language with a `name`, iso2, and `iso3`.
     * @Post("/languages")
     * @Transaction({
     *      @Request({"name": "English", "iso2": "EN", "iso3": "ENG"}),
     *      @Response(200, body={"success": "Record has been added."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {"name": {}}, "status_code": 422})
     * })
     */
    public function store(Request $request)
    {
        $language_data = $request->only('name', 'iso2', 'iso3');
        $validator = Validator::make($language_data, Language::GetValidationRule(), Language::GetValidationMessage());
        if ($validator->passes()) {
            $language_data['is_active'] = true;
            $language = Language::create($language_data);
            if ($language) {
                return response()->json(['Success' => 'Language has been added'], 200);
            } else {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('Language could not be updated. Please, try again.');
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Language could not be updated. Please, try again.', $validator->errors());
        }
    }

    /**
     * Edit the specified language.
     * Edit the language with a `id`.
     * @Get("/languages/{id}/edit")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"id": 1, "name": "English", "iso2": "EN", "iso3": "ENG", "is_active": 1}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function edit($id)
    {
        $language = Language::find($id);
        if (!$language) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($language, new LanguageTransformer);
    }

    /**
     * Update the specified language.
     * Update the language with a `id`.
     * @Put("/languages/{id}")
     * @Transaction({
     *      @Request({"id": 1, "name": "English", "iso2": "EN", "iso3": "ENG", "is_active": 1}),
     *      @Response(200, body={"success": "Record has been updated."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {"name": {}}, "status_code": 422})
     * })
     */
    public function update(Request $request, $id)
    {
        $language_data = $request->only('name', 'iso2', 'iso3', 'is_active');
        $validator = Validator::make($language_data, Language::GetValidationRule(), Language::GetValidationMessage());
        $language = false;
        if ($request->has('id')) {
            $language = Language::find($id);
            $language = ($request->id != $id) ? false : $language;
        }
        if ($validator->passes() && $language) {
            try {
                $language->update($language_data);
                return response()->json(['Success' => 'Language has been updated'], 200);
            } catch (\Exception $e) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('Language could not be updated. Please, try again.');
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Language could not be updated. Please, try again.', $validator->errors());
        }
    }

    /**
     * Delete the specified language.
     * Delete the language with a `id`.
     * @Delete("/languages/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"success": "Record Deleted."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function destroy($id)
    {
        $language = Language::find($id);
        if (!$language) {
            return $this->response->errorNotFound("Invalid Request");
        } else {
            $language->delete();
        }
        return response()->json(['Success' => 'Language deleted'], 200);
    }
}
