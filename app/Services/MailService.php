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
 
namespace App\Services;

use Illuminate\Support\Facades\Mail;
use App\EmailTemplate;
use League\Flysystem\Exception;

class MailService
{
    public function __construct()
    {

    }

    /**
     * Send Mail to users
     * @param $template
     * @param $replaceContent
     * @param $to_email
     * @param $to_username
     */
    public function sendMail($template, $replaceContent, $to_email, $to_username)
    {
        $default_content = array(
            '##SITE_NAME##' => config('site.name'),
            '##SITE_URL##' => '<a href="' . url('/') . '">' . url('/') . '</a>',
            '##FROM_EMAIL##' => config('site.from_email'),
            '##CONTACT_MAIL##' => config('site.contact_email'),
            '##CONTACT_URL##' => '<a href="' . url('/#/contactus') . '">Contact Us</a>',
			'##USERNAME##' => $to_username
        );
        $emailFindReplace = array_merge($default_content, $replaceContent);
        $content = strtr($template['body_content'], $emailFindReplace);
        $subject = strtr($template['subject'], $emailFindReplace);
        try {
            Mail::send('emails.mailContent', ['body' => $content], function ($m) use ($to_email, $to_username, $subject) {
                $m->from(config('site.from_email'), config('site.name'));
                $m->to($to_email, $to_username)->subject($subject);
            });
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * get Template content for sending mail
     * @param $name
     * @return array
     */
    public function getTemplate($name)
    {
        $email_template = EmailTemplate::where('name', $name)->get();
        $template_array = [];
        foreach ($email_template as $template) {
            $template_array['from'] = $template['from_name'];
            $template_array['body_content'] = $template['body_content'];
            $template_array['subject'] = $template['subject'];
        }
        return $template_array;
    }
}
