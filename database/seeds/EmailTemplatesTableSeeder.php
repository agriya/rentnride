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
 
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\EmailTemplate;

class EmailTemplatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        EmailTemplate::create([
            'name' => 'Forgot Password',
            'subject' => 'Forgot Password',
            'body_content' => '<table style="width:100%;max-width:700px;margin:auto;border:1px solid #dddddd;border-collapse:collapse;"><tbody><tr><td style="padding:15px;background-color:#f1f1f1;border-bottom:1px solid #dddddd;"><img src="http://bookorrent.servicepg.develag.com/assets/img/logo.png" alt="logo" /></td></tr><tr><td style="padding:15px;border-bottom:1px solid #dddddd;"><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Hi ##USERNAME##,</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:30px 0px 0px;">A password request has been made for your user account at ##SITE_NAME##.</p><div style="margin:20px 0;"><label style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;">New password:</label><p style="display:inline-block;font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;font-weight:600;margin:0px;">##PASSWORD##</p></div><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:0px;">If you did not request this action and feel this is in error, please contact us at ##CONTACT_MAIL##.</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:50px 0px 5px;">Thanks,</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:0px 0px 10px;">##SITE_NAME##</p></td></tr><tr><td style="padding:15px;background-color:#383838;text-align:center;"><a href="##SITE_URL##" style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;color:#ff811c;margin-left:5px;text-decoration:none;">##SITE_URL##</a></td></tr></tbody></table>',
            'filename' => '',
            'from_name' => '##FROM_EMAIL##',
            'info' => 'we will send this mail, when user submit the forgot password form.',
            'reply_to' => '##REPLY_TO_EMAIL##'
        ]);

        EmailTemplate::create([
            'name' => 'Activation Request',
            'subject' => 'Please activate your ##SITE_NAME## account',
            'body_content' => '<table style="width:100%;max-width:700px;margin:auto;border:1px solid #dddddd;border-collapse:collapse;"><tbody><tr><td style="padding:15px;background-color:#f1f1f1;border-bottom:1px solid #dddddd;"><img src="http://bookorrent.servicepg.develag.com/assets/img/logo.png" alt="logo" /></td></tr><tr><td style="padding:15px;border-bottom:1px solid #dddddd;"><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Hi ##USERNAME##,</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:30px 0px 0px;">Your account has been created. Please visit the following URL to activate your account. <a href="##ACTIVATION_URL##" style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;color:#ff811c;margin-left:5px;text-decoration:none; display:block; max-width:700px; word-wrap:break-word;">##ACTIVATION_URL##</a></p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:50px 0px 5px;">Thanks,</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:0px 0px 10px;">##SITE_NAME##</p></td></tr><tr><td style="padding:15px;background-color:#383838;text-align:center;"><a href="##SITE_URL##" style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;color:#ff811c;margin-left:5px;text-decoration:none;">##SITE_URL##</a></td></tr></tbody></table>',
            'filename' => '',
            'from_name' => '##FROM_EMAIL##',
            'info' => 'we will send this mail, when user registering an account he/she will get an activation request.',
            'reply_to' => '##REPLY_TO_EMAIL##'
        ]);

        EmailTemplate::create([
            'name' => 'Welcome Email',
            'subject' => 'Welcome to ##SITE_NAME##',
            'body_content' => '<table style="width:100%;max-width:700px;margin:auto;border:1px solid #dddddd;border-collapse:collapse;"><tbody><tr><td style="padding:15px;background-color:#f1f1f1;border-bottom:1px solid #dddddd;"><img src="http://bookorrent.servicepg.develag.com/assets/img/logo.png" alt="logo" /></td></tr><tr><td style="padding:15px;border-bottom:1px solid #dddddd;"><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Hi ##USERNAME##,</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:30px 0px 0px;">We wish to say a quick hello and thanks for registering at ##SITE_NAME##.</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:30px 0px 0px;">If you did not request this account and feel this is an error, please contact us at ##CONTACT_MAIL##</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:50px 0px 5px;">Thanks,</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:0px 0px 10px;">##SITE_NAME##</p></td></tr><tr><td style="padding:15px;background-color:#383838;text-align:center;"><a href="##SITE_URL##" style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;color:#ff811c;margin-left:5px;text-decoration:none;">##SITE_URL##</a></td></tr></tbody></table>',
            'filename' => '',
            'from_name' => '##FROM_EMAIL##',
            'info' => 'we will send this mail, when user register in this site and get activate.',
            'reply_to' => '##REPLY_TO_EMAIL##'
        ]);

        EmailTemplate::create([
            'name' => 'New User Join',
            'subject' => 'New user joined in ##SITE_NAME## account',
            'body_content' => '<table style="width:100%;max-width:700px;margin:auto;border:1px solid #dddddd;border-collapse:collapse;"><tbody><tr><td style="padding:15px;background-color:#f1f1f1;border-bottom:1px solid #dddddd;"><img src="http://bookorrent.servicepg.develag.com/assets/img/logo.png" alt="logo" /></td></tr><tr><td style="padding:15px;border-bottom:1px solid #dddddd;"><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:20px 0px 0px;">Hi, </p> <p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:20px 0px 0px;">A new user named "##USERNAME##" has joined in ##SITE_NAME## account.</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Username: ##USERNAME##</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Email: ##EMAIL##</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Signup IP: ##SIGNUP_IP##</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:50px 0px 5px;">Thanks,</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:0px 0px 10px;">##SITE_NAME##</p></td></tr> <tr><td style="padding:15px;background-color:#383838;text-align:center;"><a href="##SITE_URL##" style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;color:#ff811c;margin-left:5px;text-decoration:none;">##SITE_URL##</a></td></tr></tbody></table>',
            'filename' => '',
            'from_name' => '##FROM_EMAIL##',
            'info' => 'we will send this mail to admin, when a new user registered in the site. For this you have to enable "admin mail after register" in the settings page.',
            'reply_to' => '##REPLY_TO_EMAIL##'
        ]);

        EmailTemplate::create([
            'name' => 'Admin User Add',
            'subject' => 'Welcome to ##SITE_NAME##',
            'body_content' => '<table style="width:100%;max-width:700px;margin:auto;border:1px solid #dddddd;border-collapse:collapse;"><tbody><tr><td style="padding:15px;background-color:#f1f1f1;border-bottom:1px solid #dddddd;"><img src="http://bookorrent.servicepg.develag.com/assets/img/logo.png" alt="logo" /></td></tr><tr><td style="padding:15px;border-bottom:1px solid #dddddd;"><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Hi ##USERNAME##,</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:30px 0px 0px;">##SITE_NAME## team added you as a user in ##SITE_NAME##.</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:30px 0px 0px;">Your account details.</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Email: ##USEREMAIL##</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Password: ##PASSWORD##</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:50px 0px 5px;">Thanks,</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:0px 0px 10px;">##SITE_NAME##</p></td></tr><tr><td style="padding:15px;background-color:#383838;text-align:center;"><a href="##SITE_URL##" style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;color:#ff811c;margin-left:5px;text-decoration:none;">##SITE_URL##</a></td></tr></tbody></table>',
            'filename' => '',
            'from_name' => '##FROM_EMAIL##',
            'info' => 'we will send this mail to user, when a admin add a new user.',
            'reply_to' => '##REPLY_TO_EMAIL##'
        ]);

        EmailTemplate::create([
            'name' => 'Admin User Active',
            'subject' => 'Your ##SITE_NAME## account has been activated',
            'body_content' => '<table style="width:100%;max-width:700px;margin:auto;border:1px solid #dddddd;border-collapse:collapse;"><tbody><tr><td style="padding:15px;background-color:#f1f1f1;border-bottom:1px solid #dddddd;"><img src="http://bookorrent.servicepg.develag.com/assets/img/logo.png" alt="logo" /></td></tr><tr><td style="padding:15px;border-bottom:1px solid #dddddd;"><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Hi ##USERNAME##,</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:30px 0px 0px;">Your account has been activated.</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:50px 0px 5px;">Thanks,</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:0px 0px 10px;">##SITE_NAME##</p></td></tr><tr><td style="padding:15px;background-color:#383838;text-align:center;"><a href="##SITE_URL##" style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;color:#ff811c;margin-left:5px;text-decoration:none;">##SITE_URL##</a></td></tr></tbody></table>',
            'filename' => '',
            'from_name' => '##FROM_EMAIL##',
            'info' => 'We will send this mail to user, when user active by administrator.',
            'reply_to' => '##REPLY_TO_EMAIL##'
        ]);

        EmailTemplate::create([
            'name' => 'Admin User Deactivate',
            'subject' => 'Your ##SITE_NAME## account has been deactivated',
            'body_content' => '<table style="width:100%;max-width:700px;margin:auto;border:1px solid #dddddd;border-collapse:collapse;"><tbody><tr><td style="padding:15px;background-color:#f1f1f1;border-bottom:1px solid #dddddd;"><img src="http://bookorrent.servicepg.develag.com/assets/img/logo.png" alt="logo" /></td></tr><tr><td style="padding:15px;border-bottom:1px solid #dddddd;"><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Hi ##USERNAME##,</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:30px 0px 0px;">Your account has been deactivated.</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:50px 0px 5px;">Thanks,</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:0px 0px 10px;">##SITE_NAME##</p></td></tr><tr><td style="padding:15px;background-color:#383838;text-align:center;"><a href="##SITE_URL##" style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;color:#ff811c;margin-left:5px;text-decoration:none;">##SITE_URL##</a></td></tr></tbody></table>',
            'filename' => '',
            'from_name' => '##FROM_EMAIL##',
            'info' => 'We will send this mail to user, when user active by administrator.',
            'reply_to' => '##REPLY_TO_EMAIL##'
        ]);

        EmailTemplate::create([
            'name' => 'Admin User Delete',
            'subject' => 'Your ##SITE_NAME## account has been removed',
            'body_content' => '<table style="width:100%;max-width:700px;margin:auto;border:1px solid #dddddd;border-collapse:collapse;"><tbody><tr><td style="padding:15px;background-color:#f1f1f1;border-bottom:1px solid #dddddd;"><img src="http://bookorrent.servicepg.develag.com/assets/img/logo.png" alt="logo" /></td></tr><tr><td style="padding:15px;border-bottom:1px solid #dddddd;"><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Hi ##USERNAME##,</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:30px 0px 0px;">Your account has been removed.</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:50px 0px 5px;">Thanks,</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:0px 0px 10px;">##SITE_NAME##</p></td></tr><tr><td style="padding:15px;background-color:#383838;text-align:center;"><a href="##SITE_URL##" style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;color:#ff811c;margin-left:5px;text-decoration:none;">##SITE_URL##</a></td></tr></tbody></table>',
            'filename' => '',
            'from_name' => '##FROM_EMAIL##',
            'info' => 'We will send this mail to user, when user delete by administrator.',
            'reply_to' => '##REPLY_TO_EMAIL##'
        ]);

        EmailTemplate::create([
            'name' => 'Admin Change Password',
            'subject' => 'Password changed',
            'body_content' => '<table style="width:100%;max-width:700px;margin:auto;border:1px solid #dddddd;border-collapse:collapse;"><tbody><tr><td style="padding:15px;background-color:#f1f1f1;border-bottom:1px solid #dddddd;"><img src="http://bookorrent.servicepg.develag.com/assets/img/logo.png" alt="logo" /></td></tr><tr><td style="padding:15px;border-bottom:1px solid #dddddd;"><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Hi ##USERNAME##,</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:30px 0px 0px;">Admin reset your password for your  ##SITE_NAME## account.</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:30px 0px 0px;">Your new password: ##PASSWORD##</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:50px 0px 5px;">Thanks,</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:0px 0px 10px;">##SITE_NAME##</p></td></tr><tr><td style="padding:15px;background-color:#383838;text-align:center;"><a href="##SITE_URL##" style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;color:#ff811c;margin-left:5px;text-decoration:none;">##SITE_URL##</a></td></tr></tbody></table>',
            'filename' => '',
            'from_name' => '##FROM_EMAIL##',
            'info' => 'we will send this mail to user, when admin change user\'s password.',
            'reply_to' => '##REPLY_TO_EMAIL##'
        ]);

        EmailTemplate::create([
            'name' => 'Contact Us',
            'subject' => '[##SITE_NAME##] ##SUBJECT##',
            'body_content' => '<table style="width:100%;max-width:700px;margin:auto;border:1px solid #dddddd;border-collapse:collapse;"><tbody><tr><td style="padding:15px;background-color:#f1f1f1;border-bottom:1px solid #dddddd;"><img src="http://bookorrent.servicepg.develag.com/assets/img/logo.png" alt="logo" /></td></tr><tr><td style="padding:15px;border-bottom:1px solid #dddddd;"><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">##MESSAGE##</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:30px 0px 0px;">Telephone: ##TELEPHONE##</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">IP: ##IP##</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Whois: http://whois.sc/##IP##</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">URL: ##FROM_URL##</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:50px 0px 5px;">Thanks,</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:0px 0px 10px;">##SITE_NAME##</p></td></tr><tr><td style="padding:15px;background-color:#383838;text-align:center;"><a href="##SITE_URL##" style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;color:#ff811c;margin-left:5px;text-decoration:none;">##SITE_URL##</a></td></tr></tbody></table>',
            'filename' => '',
            'from_name' => '##FROM_EMAIL##',
            'info' => 'We will send this mail to admin, when user submit any contact form.',
            'reply_to' => '##REPLY_TO_EMAIL##'
        ]);

        EmailTemplate::create([
            'name' => 'Contact Us Auto Reply',
            'subject' => '[##SITE_NAME##] RE: ##SUBJECT##',
            'body_content' => '<table style="width:100%;max-width:700px;margin:auto;border:1px solid #dddddd;border-collapse:collapse;"><tbody><tr><td style="padding:15px;background-color:#f1f1f1;border-bottom:1px solid #dddddd;"><img src="http://bookorrent.servicepg.develag.com/assets/img/logo.png" alt="logo" /></td></tr><tr><td style="padding:15px;border-bottom:1px solid #dddddd;"><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Hi ##USERNAME##,</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:30px 0px 0px;">Thanks for contacting us. We\'ll get back to you shortly.</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:30px 0px 0px;">Please do not reply to this automated response. If you have not contacted us and if you feel this is an error, please contact us through our site ##CONTACT_URL##</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:30px 0px 0px;">------ you wrote -----</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:30px 0px 0px;">##MESSAGE##</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:50px 0px 5px;">Thanks,</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:0px 0px 10px;">##SITE_NAME##</p></td></tr><tr><td style="padding:15px;background-color:#383838;text-align:center;"><a href="##SITE_URL##" style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;color:#ff811c;margin-left:5px;text-decoration:none;">##SITE_URL##</a></td></tr></tbody></table>',
            'filename' => '',
            'from_name' => '##CONTACT_FROM_EMAIL##',
            'info' => 'we will send this mail to user, when user submit the contact us form.',
            'reply_to' => ''
        ]);

        EmailTemplate::create([
            'name' => 'New Item Activated',
            'subject' => 'New Item Activated - ##ITEM_NAME##',
            'body_content' => '<table style="width:100%;max-width:700px;margin:auto;border:1px solid #dddddd;border-collapse:collapse;"><tbody><tr><td style="padding:15px;background-color:#f1f1f1;border-bottom:1px solid #dddddd;"><img src="http://bookorrent.servicepg.develag.com/assets/img/logo.png" alt="logo" /></td></tr><tr><td style="padding:15px;border-bottom:1px solid #dddddd;"><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Dear ##USERNAME##,</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:30px 0px 0px;">Your new item has been activated.</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Item Name: ##ITEM_NAME##</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">URL: ##ITEM_URL##</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:50px 0px 5px;">Thanks,</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:0px 0px 10px;">##SITE_NAME##</p></td></tr><tr><td style="padding:15px;background-color:#383838;text-align:center;"><a href="##SITE_URL##" style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;color:#ff811c;margin-left:5px;text-decoration:none;">##SITE_URL##</a></td></tr></tbody></table>',
            'filename' => '',
            'from_name' => '##FROM_EMAIL##',
            'info' => 'Your new item has been approved and activated now',
            'reply_to' => '##REPLY_TO_EMAIL##'
        ]);

        EmailTemplate::create([
            'name' => 'Auto refund notification',
            'subject' => 'Your security deposit has refunded',
            'body_content' => '<table style="width:100%;max-width:700px;margin:auto;border:1px solid #dddddd;border-collapse:collapse;"><tbody><tr><td style="padding:15px;background-color:#f1f1f1;border-bottom:1px solid #dddddd;"><img src="http://bookorrent.servicepg.develag.com/assets/img/logo.png" alt="logo" /></td></tr><tr><td style="padding:15px;border-bottom:1px solid #dddddd;"><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Dear ##USERNAME##,</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:30px 0px 0px;">Item ##ITEM_NAME## security deposit amount ##AMOUNT## has been refunded.</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Booked item: ##ITEM_NAME##</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Vehicle Rental no: ###ORDERNO##</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:50px 0px 5px;">Thanks,</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:0px 0px 10px;">##SITE_NAME##</p></td></tr><tr><td style="padding:15px;background-color:#383838;text-align:center;"><a href="##SITE_URL##" style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;color:#ff811c;margin-left:5px;text-decoration:none;">##SITE_URL##</a></td></tr></tbody></table>',
            'filename' => '',
            'from_name' => '##FROM_EMAIL##',
            'info' => 'Internal message will be sent to the Booker mentioning the security deposit was refunded, when the booked item checkout without any due within the auto refund limit.',
            'reply_to' => '##REPLY_TO_EMAIL##'
        ]);

        EmailTemplate::create([
            'name' => 'Item User Change Status Alert',
            'subject' => '[##SITE_NAME##][##ITEM##] Vehicle Rental Status: ##PREVIOUS_STATUS## -> ##CURRENT_STATUS##',
            'body_content' => '<table style="width:100%;max-width:700px;margin:auto;border:1px solid #dddddd;border-collapse:collapse;"><tbody><tr><td style="padding:15px;background-color:#f1f1f1;border-bottom:1px solid #dddddd;"><img src="http://bookorrent.servicepg.develag.com/assets/img/logo.png" alt="logo" /></td></tr><tr><td style="padding:15px;border-bottom:1px solid #dddddd;"><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Hi,</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:30px 0px 0px;">Status was changed for booking "##ITEM_NAME##".</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:30px 0px 0px;">Status: ##PREVIOUS_STATUS## -> ##CURRENT_STATUS##</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:30px 0px 0px;">Please click the following link to view the item,</p><p><a href="##ITEM_URL##" style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;color:#ff811c;margin:30px 0px 0px;text-decoration:none; display:block; max-width:700px; word-wrap:break-word;">##ITEM_URL##</a></p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:50px 0px 5px;">Thanks,</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:0px 0px 10px;">##SITE_NAME##</p></td></tr><tr><td style="padding:15px;background-color:#383838;text-align:center;"><a href="##SITE_URL##" style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;color:#ff811c;margin-left:5px;text-decoration:none;">##SITE_URL##</a></td></tr></tbody></table>',
            'filename' => '',
            'from_name' => '##FROM_EMAIL##',
            'info' => 'we will send this when a item user status change.',
            'reply_to' => '##REPLY_TO_EMAIL##'
        ]);

        EmailTemplate::create([
            'name' => 'New VehicleRental Message To Host',
            'subject' => 'You have a new rental',
            'body_content' => '<table style="width:100%;max-width:700px;margin:auto;border:1px solid #dddddd;border-collapse:collapse;"><tbody><tr><td style="padding:15px;background-color:#f1f1f1;border-bottom:1px solid #dddddd;"><img src="http://bookorrent.servicepg.develag.com/assets/img/logo.png" alt="logo" /></td></tr><tr><td style="padding:15px;border-bottom:1px solid #dddddd;"><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Dear ##USERNAME##</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:30px 0px 0px;">You have a new rental from ##BOOKER_USERNAME##.</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:30px 0px 0px;">Rented item: ##ITEM_NAME##.</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:30px 0px 0px;">Please click the following link to accept the rental ##ACCEPT_URL##</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:30px 0px 0px;">Please click the following link to reject the rental</p> <p><a href="##REJECT_URL##" style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;color:#ff811c;text-decoration:none; display:block; max-width:700px; word-wrap:break-word;">##REJECT_URL##</a></p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:50px 0px 5px;">Thanks,</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:0px 0px 10px;">##SITE_NAME##</p></td></tr><tr><td style="padding:15px;background-color:#383838;text-align:center;"><a href="##SITE_URL##" style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;color:#ff811c;margin-left:5px;text-decoration:none;">##SITE_URL##</a></td></tr></tbody></table>',
            'filename' => '',
            'from_name' => '##FROM_EMAIL##',
            'info' => 'When new rental was made, an internal message will be sent to the owner of the item notify new rental.',
            'reply_to' => '##REPLY_TO_EMAIL##'
        ]);

        EmailTemplate::create([
            'name' => 'New VehicleRental Message To Booker',
            'subject' => 'Your rental has been made.',
            'body_content' => '<table style="width:100%;max-width:700px;margin:auto;border:1px solid #dddddd;border-collapse:collapse;"><tbody><tr><td style="padding:15px;background-color:#f1f1f1;border-bottom:1px solid #dddddd;"><img src="http://bookorrent.servicepg.develag.com/assets/img/logo.png" alt="logo" /></td></tr><tr><td style="padding:15px;border-bottom:1px solid #dddddd;"><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Dear ##USERNAME##,</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">##SITE_NAME##: Thank you. Please read this information about your rental from ##HOST_NAME##</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Your rental has been sent to the host.</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Rented item : ##ITEM_NAME##.</p><br>-----------------------------------------------------<br><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Information about your Rental</p><br>-----------------------------------------------------<br><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">rental ###ORDER_NO##</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Description: ##ITEM_DESCRIPTION##</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">From: ##FROM_DATE##</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Host: ##HOST_NAME## </p><br>-----------------------------------------------------<br><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:30px 0px 0px;">What to do if the host is not responding?<br>-----------------------------------------------------<br>If you feel that the host is taking too long to respond, you can ##CANCEL_URL## and get your funds back to your Account. We recommend allowing host at least ##ITEM_AUTO_EXPIRE_DATE## day(s) to respond.</p><br>-----------------------------------------------------
<br><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:30px 0px 0px;">What if the host rejects my rental?<br>-----------------------------------------------------<br>Host may sometimes choose to give up on an rental. This may be due to their inability to perform their work based on the information you provided or they are simply too busy or currently unavailable.<p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:30px 0px 0px;">If a host rejects your rental, your funds are returned to your ##SITE_NAME## account.<p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:0px 0px 10px;"><br>-----------------------------------------------------<br>##SITE_NAME## Customer Service<br>-----------------------------------------------------<br>The ##SITE_NAME## Customer service are here to help you. If you need any assistance with an rental, Please contact us here: ##CONTACT_URL##<br><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:50px 0px 5px;">Thanks,</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:0px 0px 10px;">##SITE_NAME##</p></td></tr><tr><td style="padding:15px;background-color:#383838;text-align:center;"><a href="##SITE_URL##" style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;color:#ff811c;margin-left:5px;text-decoration:none;">##SITE_URL##</a></td></tr></tbody></table>',
            'filename' => '',
            'from_name' => '##FROM_EMAIL##',
            'info' => 'Internal mail sent to the booker when he makes a new rental.',
            'reply_to' => '##REPLY_TO_EMAIL##'
        ]);

        EmailTemplate::create([
            'name' => 'Accepted VehicleRental Message To Host',
            'subject' => 'You have accepted the rental',
            'body_content' => '<table style="width:100%;max-width:700px;margin:auto;border:1px solid #dddddd;border-collapse:collapse;"><tbody><tr><td style="padding:15px;background-color:#f1f1f1;border-bottom:1px solid #dddddd;"><img src="http://bookorrent.servicepg.develag.com/assets/img/logo.png" alt="logo" /></td></tr><tr><td style="padding:15px;border-bottom:1px solid #dddddd;"><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Hi ##USERNAME##,</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:30px 0px 0px;">You have accepted the rental.</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Rented Item: ##ITEM_NAME##.</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Rental No#: ##ORDER_NO##</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:50px 0px 5px;">Thanks,</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:0px 0px 10px;">##SITE_NAME##</p></td></tr><tr><td style="padding:15px;background-color:#383838;text-align:center;"><a href="##SITE_URL##" style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;color:#ff811c;margin-left:5px;text-decoration:none;">##SITE_URL##</a></td></tr></tbody></table>',
            'filename' => '',
            'from_name' => '##FROM_EMAIL##',
            'info' => 'Internal message will be sent to the Host, when the rented item was accepted by the host.',
            'reply_to' => '##REPLY_TO_EMAIL##'
        ]);

        EmailTemplate::create([
            'name' => 'Accepted VehicleRental Message To Booker',
            'subject' => 'Your rental has been accepted',
            'body_content' => '<table style="width:100%;max-width:700px;margin:auto;border:1px solid #dddddd;border-collapse:collapse;"><tbody><tr><td style="padding:15px;background-color:#f1f1f1;border-bottom:1px solid #dddddd;"><img src="http://bookorrent.servicepg.develag.com/assets/img/logo.png" alt="logo" /></td></tr><tr><td style="padding:15px;border-bottom:1px solid #dddddd;"><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Dear ##USERNAME##</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:30px 0px 0px;">Your rental has been accepted. Looking forward for your visit:)</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:30px 0px 0px;">Rented Item: ##ITEM_NAME##</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:30px 0px 0px;">##HOST_CONTACT_LINK##</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:50px 0px 5px;">Thanks,</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:0px 0px 10px;">##SITE_NAME##</p></td></tr><tr><td style="padding:15px;background-color:#383838;text-align:center;"><a href="##SITE_URL##" style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;color:#ff811c;margin-left:5px;text-decoration:none;">##SITE_URL##</a></td></tr></tbody></table>',
            'filename' => '',
            'from_name' => '##FROM_EMAIL##',
            'info' => 'Internal message will be sent to the Booker, when the rented item was accepted by the host.',
            'reply_to' => '##REPLY_TO_EMAIL##'
        ]);
        // dipute related email templates
        EmailTemplate::create([
            'name' => 'Dispute Open Notification',
            'subject' => '##USER_TYPE## has opened a dispute for this booking',
            'body_content' => '<table style="width:100%;max-width:700px;margin:auto;border:1px solid #dddddd;border-collapse:collapse;"><tbody><tr><td style="padding:15px;background-color:#f1f1f1;border-bottom:1px solid #dddddd;"><img src="http://bookorrent.servicepg.develag.com/assets/img/logo.png" alt="logo" /></td></tr><tr><td style="padding:15px;border-bottom:1px solid #dddddd;"><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Dear ##USERNAME##,</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:30px 0px 0px;">##OTHER_USER## has made a dispute on your booking#: ##ORDERNO## and sent the following dispute message</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:30px 0px 0px;">##MESSAGE##</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:30px 0px 0px;">You need to reply within ##REPLY_DAYS## to avoid making decision in favor of ##USER_TYPE_URL##.</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Please click the following link to reply: ##REPLY_LINK##</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Booked Item: ##ITEM_NAME##</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Vehicle Rental No#: ##ORDERNO##</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:50px 0px 5px;">Thanks,</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:0px 0px 10px;">##SITE_NAME##</p></td></tr><tr><td style="padding:15px;background-color:#383838;text-align:center;"><a href="##SITE_URL##" style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;color:#ff811c;margin-left:5px;text-decoration:none;">##SITE_URL##</a></td></tr></tbody></table>',
            'filename' => '',
            'from_name' => '##FROM_EMAIL##',
            'info' => 'Notification mail when dispute is opened.',
            'reply_to' => '##REPLY_TO_EMAIL##'
        ]);

        EmailTemplate::create([
            'name' => 'Dispute Conversation Notification',
            'subject' => '##OTHER_USER## sent the following dispute conversation',
            'body_content' => '<table style="width:100%;max-width:700px;margin:auto;border:1px solid #dddddd;border-collapse:collapse;"><tbody><tr><td style="padding:15px;background-color:#f1f1f1;border-bottom:1px solid #dddddd;"><img src="http://bookorrent.servicepg.develag.com/assets/img/logo.png" alt="logo" /></td></tr><tr><td style="padding:15px;border-bottom:1px solid #dddddd;"><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Hi,</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:30px 0px 0px;">##OTHER_USER## sent the following dispute conversation:</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:30px 0px 0px;">##MESSAGE##</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:50px 0px 5px;">Thanks,</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:0px 0px 10px;">##SITE_NAME##</p></td></tr><tr><td style="padding:15px;background-color:#383838;text-align:center;"><a href="##SITE_URL##" style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;color:#ff811c;margin-left:5px;text-decoration:none;">##SITE_URL##</a></td></tr></tbody></table>',
            'filename' => '',
            'from_name' => '##FROM_EMAIL##',
            'info' => 'Notification mail sent during dispute conversation',
            'reply_to' => '##REPLY_TO_EMAIL##'
        ]);
        EmailTemplate::create([
            'name' => 'Dispute Resolved Notification',
            'subject' => 'Dispute has been closed in favor of ##FAVOUR_USER##',
            'body_content' => '<table style="width:100%;max-width:700px;margin:auto;border:1px solid #dddddd;border-collapse:collapse;"><tbody><tr><td style="padding:15px;background-color:#f1f1f1;border-bottom:1px solid #dddddd;"><img src="http://bookorrent.servicepg.develag.com/assets/img/logo.png" alt="logo" /></td></tr><tr><td style="padding:15px;border-bottom:1px solid #dddddd;"><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Hi ##USERNAME##,</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:30px 0px 0px;">Your booking dispute has been closed in favor of ##FAVOUR_USER##.</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:30px 0px 0px;">Reason for closed: ##REASON_FOR_CLOSING##</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Resolved by: ##RESOLVED_BY##</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Dispute Information:</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Dispute ID#: ##DISPUTE_ID##</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Vehicle Rental ID#: ##ORDER_ID##</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Disputer: ##DISPUTER##</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">##DISPUTER_USER_TYPE## </p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Reason for dispute: ##REASON##</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:50px 0px 5px;">Thanks,</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:0px 0px 10px;">##SITE_NAME##</p></td></tr><tr><td style="padding:15px;background-color:#383838;text-align:center;"><a href="##SITE_URL##" style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;color:#ff811c;margin-left:5px;text-decoration:none;">##SITE_URL##</a></td></tr></tbody></table>',
            'filename' => '',
            'from_name' => '##FROM_EMAIL##',
            'info' => 'Notification mail to be sent on closing dispute',
            'reply_to' => '##REPLY_TO_EMAIL##'
        ]);
        EmailTemplate::create([
            'name' => 'Discussion Threshold for Admin Decision',
            'subject' => 'Admin will take decision shortly for this dispute.',
            'body_content' => '<table style="width:100%;max-width:700px;margin:auto;border:1px solid #dddddd;border-collapse:collapse;"><tbody><tr><td style="padding:15px;background-color:#f1f1f1;border-bottom:1px solid #dddddd;"><img src="http://bookorrent.servicepg.develag.com/assets/img/logo.png" alt="logo" /></td></tr><tr><td style="padding:15px;border-bottom:1px solid #dddddd;"><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Hi,</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:30px 0px 0px;">Admin will take decision shortly for this dispute.</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:50px 0px 5px;">Thanks,</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:0px 0px 10px;">##SITE_NAME##</p></td></tr><tr><td style="padding:15px;background-color:#383838;text-align:center;"><a href="##SITE_URL##" style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;color:#ff811c;margin-left:5px;text-decoration:none;">##SITE_URL##</a></td></tr></tbody></table>',
            'filename' => '',
            'from_name' => '##FROM_EMAIL##',
            'info' => 'Admin will take decision, after no of conversation to booker and host.',
            'reply_to' => '##REPLY_TO_EMAIL##'
        ]);
        // withdraw related templates
        EmailTemplate::create([
            'name' => 'Admin Approve Withdraw Request',
            'subject' => 'Your Withdraw request has been approved',
            'body_content' => '<table style="width:100%;max-width:700px;margin:auto;border:1px solid #dddddd;border-collapse:collapse;"><tbody><tr><td style="padding:15px;background-color:#f1f1f1;border-bottom:1px solid #dddddd;"><img src="http://bookorrent.servicepg.develag.com/assets/img/logo.png" alt="logo" /></td></tr><tr><td style="padding:15px;border-bottom:1px solid #dddddd;"><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Hi ##USERNAME##,</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:30px 0px 0px;">Your Withdraw request has been approved.</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Details:</p> <p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Request Amount: ##AMOUNT##</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:50px 0px 5px;">Thanks,</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:0px 0px 10px;">##SITE_NAME##</p></td></tr><tr><td style="padding:15px;background-color:#383838;text-align:center;"><a href="##SITE_URL##" style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;color:#ff811c;margin-left:5px;text-decoration:none;">##SITE_URL##</a></td></tr></tbody></table>',
            'filename' => '',
            'from_name' => '##FROM_EMAIL##',
            'info' => 'We will send this mail to user, when withdraw request is approved by administrator.',
            'reply_to' => '##REPLY_TO_EMAIL##'
        ]);

        EmailTemplate::create([
            'name' => 'Admin Reject Withdraw Request',
            'subject' => 'Your Withdraw request has been rejected',
            'body_content' => '<table style="width:100%;max-width:700px;margin:auto;border:1px solid #dddddd;border-collapse:collapse;"><tbody><tr><td style="padding:15px;background-color:#f1f1f1;border-bottom:1px solid #dddddd;"><img src="http://bookorrent.servicepg.develag.com/assets/img/logo.png" alt="logo" /></td></tr><tr><td style="padding:15px;border-bottom:1px solid #dddddd;"><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Hi ##USERNAME##,</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:30px 0px 0px;">Your Withdraw request has been rejected.</p> <p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Details:</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Request Amount: ##AMOUNT##</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:50px 0px 5px;">Thanks,</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:0px 0px 10px;">##SITE_NAME##</p></td></tr><tr><td style="padding:15px;background-color:#383838;text-align:center;"><a href="##SITE_URL##" style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;color:#ff811c;margin-left:5px;text-decoration:none;">##SITE_URL##</a></td></tr></tbody></table>',
            'filename' => '',
            'from_name' => '##FROM_EMAIL##',
            'info' => 'We will send this mail to user, when withdraw request is rejected by administrator.',
            'reply_to' => '##REPLY_TO_EMAIL##'
        ]);

        EmailTemplate::create([
            'name' => 'New VehicleRental Message To Host On Auto Approve',
            'subject' => 'You have a new booking',
            'body_content' => '<table style="width:100%;max-width:700px;margin:auto;border:1px solid #dddddd;border-collapse:collapse;"><tbody><tr><td style="padding:15px;background-color:#f1f1f1;border-bottom:1px solid #dddddd;"><img src="http://bookorrent.servicepg.develag.com/assets/img/logo.png" alt="logo" /></td></tr><tr><td style="padding:15px;border-bottom:1px solid #dddddd;"><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Dear ##USERNAME##,</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:30px 0px 0px;">You have a new booking from ##BOOKER_USERNAME##.</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:30px 0px 0px;">Booked item: ##ITEM_NAME##.</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:50px 0px 5px;">Thanks,</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:0px 0px 10px;">##SITE_NAME##</p></td></tr><tr><td style="padding:15px;background-color:#383838;text-align:center;"><a href="##SITE_URL##" style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;color:#ff811c;margin-left:5px;text-decoration:none;">##SITE_URL##</a></td></tr></tbody></table>',
            'filename' => '',
            'from_name' => '##FROM_EMAIL##',
            'info' => 'When new booking was made, an internal message will be sent to the owner of the item notify new booking.',
            'reply_to' => '##REPLY_TO_EMAIL##'
        ]);

        EmailTemplate::create([
            'name' => 'Feedback to Booker',
            'subject' => '##HOST## has left a feedback about you',
            'body_content' => '<table style="width:100%;max-width:700px;margin:auto;border:1px solid #dddddd;border-collapse:collapse;"><tbody><tr><td style="padding:15px;background-color:#f1f1f1;border-bottom:1px solid #dddddd;"><img src="http://bookorrent.servicepg.develag.com/assets/img/logo.png" alt="logo" /></td></tr><tr><td style="padding:15px;border-bottom:1px solid #dddddd;"><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Hi ##USERNAME##,</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">##HOST_URL## has left a feedback about you.</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Feedback: ##MESSAGE##</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:50px 0px 5px;">Thanks,</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:0px 0px 10px;">##SITE_NAME##</p></td></tr><tr><td style="padding:15px;background-color:#383838;text-align:center;"><a href="##SITE_URL##" style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;color:#ff811c;margin-left:5px;text-decoration:none;">##SITE_URL##</a></td></tr></tbody></table>',
            'filename' => '',
            'from_name' => '##FROM_EMAIL##',
            'info' => 'We will send this mail to user, when withdraw request is rejected by administrator.',
            'reply_to' => '##REPLY_TO_EMAIL##'
        ]);

        EmailTemplate::create([
            'name' => 'Feedback to Host',
            'subject' => '##BOOKER## has left a feedback on your item',
            'body_content' => '<table style="width:100%;max-width:700px;margin:auto;border:1px solid #dddddd;border-collapse:collapse;"><tbody><tr><td style="padding:15px;background-color:#f1f1f1;border-bottom:1px solid #dddddd;"><img src="http://bookorrent.servicepg.develag.com/assets/img/logo.png" alt="logo" /></td></tr><tr><td style="padding:15px;border-bottom:1px solid #dddddd;"><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Hi ##USERNAME##,</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">##BOOKER_URL## has left a feedback on your item.</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:10px 0px 0px;">Feedback: ##MESSAGE##</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:50px 0px 5px;">Thanks,</p><p style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;margin:0px 0px 10px;">##SITE_NAME##</p></td></tr><tr><td style="padding:15px;background-color:#383838;text-align:center;"><a href="##SITE_URL##" style="font-size:13px; font-family:Arial, Helvetica, sans-serif;line-height:20px;color:#ff811c;margin-left:5px;text-decoration:none;">##SITE_URL##</a></td></tr></tbody></table>',
            'filename' => '',
            'from_name' => '##FROM_EMAIL##',
            'info' => 'We will send this mail to user, when withdraw request is rejected by administrator.',
            'reply_to' => '##REPLY_TO_EMAIL##'
        ]);
    }
}