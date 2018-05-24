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
 
namespace Plugins\Withdrawals\Services;

use App\Services\MailService;
use App\Services\MessageService;

class UserCashWithdrawalService
{
    /**
     * UserCashWithdrawalService constructor.
     */
    public function __construct()
    {
        $this->setMailService();
        $this->setMessageService();
    }

    public function setMailService()
    {
        $this->mailService = new MailService();
    }

    public function setMessageService()
    {
        $this->messageService = new MessageService();
    }

    /**
     * @param $userid
     * @param $username
     * @param $email
     * @param $withdrawal
     */
    public function withdrawMail($userid, $username, $email, $withdrawal)
    {
        $from = config('constants.ConstUserIds.Admin');
        if ($withdrawal->withdrawal_status_id == config('constants.ConstWithdrawalStatus.Rejected')) {
            $template = $this->mailService->getTemplate('Admin Reject Withdraw Request');
        }
        if ($withdrawal->withdrawal_status_id == config('constants.ConstWithdrawalStatus.Success')) {
            $template = $this->mailService->getTemplate('Admin Approve Withdraw Request');
        }
        $emailFindReplace = array(
            '##USERNAME##' => $username,
            '##AMOUNT##' => $withdrawal->amount,
        );
        $this->mailService->sendMail($template, $emailFindReplace, $email, $username);
        $default_content = array(
            '##SITE_NAME##' => config('site.name'),
            '##SITE_URL##' => '<a href="' . url('/') . '">' . url('/') . '<a>',
            '##FROM_EMAIL##' => config('site.from_email'),
            '##CONTACT_MAIL##' => config('site.contact_email')
        );
        $withdraw_template = array_merge($emailFindReplace, $default_content);
        $message_content_arr = array();
        $message_content_arr['message'] = strtr($template['body_content'], $withdraw_template);
        $message_content_arr['subject'] = strtr($template['subject'], $withdraw_template);
        $this->messageService->saveMessageContent($message_content_arr, null, $withdrawal->id, $from, $userid, null, 'Withdrawals');
    }
}
