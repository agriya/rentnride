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

use App\EmailTemplate;

use JWTAuth;
use Validator;
use App\Transformers\EmailTemplateTransformer;

/**
 * EmailTemplates resource representation.
 * @Resource("Admin/AdminEmailTemplates")
 */
class AdminEmailTemplatesController extends Controller
{
    /**
     * AdminEmailTemplatesController constructor.
     */
    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
        // Check the logged user role.
        $this->middleware('role');
    }

    /**
     * Show all email templates.
     * Get a JSON representation of all the EmailTemplates.
     *
     * @Get("/email_templates?sort={sort}&sortby={sortby}&page={page}")
     * @Parameters({
     *      @Parameter("sort", type="string", required=false, description="Sort the email templates list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort email templates by Ascending / Descending Order.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1)
     * })
     */
    public function index(Request $request)
    {
        $email_templates = EmailTemplate::filterByRequest($request)->paginate(config('constants.ConstPageLimit'));
        return $this->response->paginator($email_templates, new EmailTemplateTransformer);
    }

    /**
     * Edit the specified email template.
     * Edit the email template with a `id`.
     * @Get("/email_templates/{id}/edit")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"id": 1, "name": "XXXXXX", "subject": "XXXXXX", "body_content": "XXXXXX", "from_name": "XXXXXX", "info": "XXXXXX", "reply_to": "XXXXXX"}),
     *      @Response(404, body={"message": "Invalid Request", "errors": {"name":{}}, "status_code": 404})
     * })
     */
    public function edit($id)
    {
        $email_template = EmailTemplate::find($id);
        if (!$email_template) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($email_template, new EmailTemplateTransformer);
    }

    /**
     * Update the specified email template.
     * Update the email template with a `id`.
     * @Put("/email_templates/{id}")
     * @Transaction({
     *      @Request({"id": 1, "name": "XXXXXX", "subject": "XXXXXX", "body_content": "XXXXXX", "from_name": "XXXXXX", "info": "XXXXXX", "reply_to": "XXXXXX"}),
     *      @Response(200, body={"success": "Record has been updated."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {"name":{}}, "status_code": 422})
     * })
     */
    public function update(Request $request, $id)
    {
        $email_template_data = $request->only('name', 'subject', 'body_content', 'from_name', 'reply_to');
        $validator = Validator::make($email_template_data, EmailTemplate::GetValidationRule(), EmailTemplate::GetValidationMessage());
        $email_template = false;
        if ($request->has('id')) {
            $email_template = EmailTemplate::find($id);
            $email_template = ($request->id != $id) ? false : $email_template;
        }
        if ($validator->passes() && $email_template) {
            try {
                $email_template->update($email_template_data);
                return response()->json(['Success' => 'EmailTemplate has been updated'], 200);
            } catch (\Exception $e) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('EmailTemplate could not be updated. Please, try again.');
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('EmailTemplate could not be updated. Please, try again.', $validator->errors());
        }

    }
}