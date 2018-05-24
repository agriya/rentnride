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

namespace Plugins\Contacts\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Plugins\Contacts\Model\Contact;
use JWTAuth;
use Validator;
use Plugins\Contacts\Transformers\ContactTransformer;

/**
 * Contacts resource representation.
 * @Resource("Admin/AdminContacts")
 */
class AdminContactsController extends Controller
{
    /**
     * AdminContactsController constructor.
     */
    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
        // Check the logged user role.
        $this->middleware('role');
    }

    /**
     * Show all contacts
     * Get a JSON representation of all the contacts.
     *
     * @Get("/contacts?sort={sort}&sortby={sortby}&page={page}&q={q}")
     * @Parameters({
     *      @Parameter("sort", type="string", required=false, description="Sort the contacts list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort contacts by Ascending / Descending Order.", default=null),
     *      @Parameter("q", type="string", required=false, description="Search Contacts.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1)
     * })
     */
    public function index(Request $request)
    {
        $contacts = Contact::with('user', 'ip')->filterByRequest($request)->paginate(config('constants.ConstPageLimit'));
        return $this->response->paginator($contacts, (new ContactTransformer)->setDefaultIncludes(['user', 'ip']));
    }

    /**
     * Edit the specified contact.
     * Edit the contact with a `id`.
     * @Get("/contacts/{id}/edit")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body= {"id": 1, "user_id": 1, "first_name": "XXXXXX", "last_name": "XXXXXX", "email": "guest@gmail.com", "subject": "XXXXXX", "message": "XXXXXX", "telephone": "XXXXXX", "user_type": "Guest", "User": {}, "Ip": {}}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function edit($id)
    {
        $contact = Contact::with('user', 'ip')->find($id);
        if (!$contact) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($contact, (new ContactTransformer)->setDefaultIncludes(['user', 'ip']));
    }

    /**
     * Delete the specified contact.
     * Delete the contact with a `id`.
     * @Delete("/contacs/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"success": "Record Deleted."}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function destroy($id)
    {
        $contact = Contact::find($id);
        if (!$contact) {
            return $this->response->errorNotFound("Invalid Request");
        } else {
            $contact->delete();
        }
        return response()->json(['Success' => 'Contact deleted'], 200);
    }
}
