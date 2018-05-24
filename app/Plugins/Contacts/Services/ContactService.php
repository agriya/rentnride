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
 
namespace Plugins\Contacts\Services;

use App\Services\MailService;

class ContactService
{

    /**
     * @var MailService
     */
    protected $mail_service;

    /**
     * ContactService constructor.
     */

    public function __construct()
    {
        $this->setMailService();
    }

    public function setMailService()
    {
        $this->MailService = new MailService();
    }

    /**
     * Send Contact Mail
     * @param $request
     */
    public function sendContactMail($request)
    {
        // send mail to admin
        $template = $this->MailService->getTemplate('Contact Us');
        $emailFindReplace = array(
            '##FIRST_NAME##' => $request->first_name,
            '##LAST_NAME##' => $request->last_name,
            '##FROM_URL##' => '<a href="' . url('/#/contactus') . '">Contact Us</a>',
            '##IP##' => $request->ip(),
            '##MESSAGE##' => $request->message,
            '##SUBJECT##' => $request->subject,
            '##TELEPHONE##' => $request->telephone,
        );
        $this->MailService->sendMail($template, $emailFindReplace, config('site.reply_to_email'), 'Admin');

        // send auto reply to user
        $template = $this->MailService->getTemplate('Contact Us Auto Reply');
        $emailFindReplace = array(
            '##USERNAME##' => $request->first_name . ' ' . $request->last_name,
            '##SUBJECT##' => $request->subject,
            '##MESSAGE##' => $request->message,
            '##CONTACT_URL##' => '<a href="' . url('/#/contactus') . '">Contact Us</a>'
        );
        $this->MailService->sendMail($template, $emailFindReplace, $request->email, $request->first_name);
    }
}
