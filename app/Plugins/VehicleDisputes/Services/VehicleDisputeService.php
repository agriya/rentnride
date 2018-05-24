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
 
namespace Plugins\VehicleDisputes\Services;

use App\Services\MailService;
use Carbon;
use App\Services\TransactionService;
use App\Services\MessageService;
use Plugins\VehicleDisputes\Model\VehicleDispute;
use Plugins\VehicleRentals\Services\VehicleRentalService;
use Illuminate\Support\Facades\Auth;

class VehicleDisputeService
{
    /**
     * @var
     */
    protected $mailService;
    /**
     * @var
     */
    protected $messageService;
    /**
     * @var
     */
    protected $walletService;
    /**
     * @var
     */
    protected $transactionService;
    /**
     * @var
     */
    protected $vehicleRentalService;
    /**
     * @var
     */
    protected $disputeClosedTypeService;
    /**
     * @var
     */
    protected $reply_link;

    /**
     * VehicleDisputeService constructor.
     */
    public function __construct()
    {
        $this->setMailService();
        $this->setMessageService();
        $this->setTransactionService();
        $this->setWalletService();
        $this->setVehicleRentalService();
        $this->setVehicleDisputeClosedTypeService();
    }

    /**
     * Set MailService
     */
    public function setMailService()
    {
        $this->mailService = new MailService();
    }

    /**
     * Set setTransactionService
     */
    public function setTransactionService()
    {
        $this->transactionService = new TransactionService();
    }

    /**
     * Set MessageService
     */
    public function setMessageService()
    {
        $this->messageService = new MessageService();
    }

    /**
     * Set WalletService
     */
    public function setWalletService()
    {
        $this->walletService = new \App\Services\WalletService();
    }

    /**
     * Set VehicleRentalService
     */
    public function setVehicleRentalService()
    {
        $this->vehicleRentalService = new VehicleRentalService();
    }

    /**
     * Set VehicleDisputeClosedTypeService
     */
    public function setVehicleDisputeClosedTypeService()
    {
        $this->disputeClosedTypeService = new VehicleDisputeClosedTypeService();
    }


    /**
     * @param $vehicle_dispute
     * @param $vehicle_rental
     */
    public function sendDisputeOpenMail($vehicle_dispute, $vehicle_rental)
    {
        $template = $this->mailService->getTemplate('Dispute Open Notification');
        $item_link = '<a href="' . url('/#/vehicle/' . $vehicle_rental->item_userable->id) . '/' . $vehicle_rental->item_userable->slug . '">' . $vehicle_rental->item_userable->name . '</a>';
        $order_link = '<a href="' . url('/#/activity/' . $vehicle_rental->id . '/dispute') . '">' . $vehicle_rental->id . '</a>';
        $site_link = '<a href="' . url('/') . '">' . url('/') . '<a>';
        $booker_link = '<a href="' . url('/#/user/' . $vehicle_rental->user->username) . '">' . $vehicle_rental->user->username . '</a>';
        $host_link = '<a href="' . url('/#/user/' . $vehicle_rental->item_userable->user->username) . '">' . $vehicle_rental->item_userable->user->username . '</a>';
        $to_user_booker_id = $vehicle_rental->user_id;
        $to_user_host_id = $vehicle_rental->item_userable->user_id;
        $common_emailFindReplace = array(
            '##ITEM_NAME##' => $item_link,
            '##ORDERNO##' => $order_link,
            '##MESSAGE##' => $vehicle_dispute->reason,
            '##REPLY_DAYS##' => config('dispute.days_left_for_disputed_user_to_reply') . ' days'
        );
        $reply_link = '<a href="' . url('/#/activity/' . $vehicle_rental->id . '/note') . '">REPLY</a>';
        if ($vehicle_dispute->is_booker === 0) {
            $emailFindReplace = array(
                '##OTHER_USER##' => $host_link,
                '##USER_TYPE##' => 'Host (' . $vehicle_rental->item_userable->user->username . ')',
                '##USER_TYPE_URL##' => 'Host (' . $host_link . ')',
                '##REPLY_LINK##' => $reply_link,
            );
            $to_username = $vehicle_rental->user->id;
            $to_email = $vehicle_rental->user->email;
            $from_user_id = $vehicle_rental->item_userable->user->id;
        } elseif ($vehicle_dispute->is_booker === 1) {
            $emailFindReplace = array(
                '##OTHER_USER##' => $booker_link,
                '##USER_TYPE##' => 'Booker (' . $vehicle_rental->user->username . ')',
                '##USER_TYPE_URL##' => 'Booker (' . $booker_link . ')',
                '##REPLY_LINK##' => $reply_link,
            );
            $to_username = $vehicle_rental->item_userable->user->username;
            $to_email = $vehicle_rental->item_userable->user->email;
            $from_user_id = $vehicle_rental->user->id;
        }
        $emailFindReplace = array_merge($common_emailFindReplace, $emailFindReplace);
        $this->mailService->sendMail($template, $emailFindReplace, $to_email, $to_username);

        //Save internal message
        $default_content = array(
            '##SITE_NAME##' => config('site.name'),
            '##SITE_URL##' => $site_link,
            '##FROM_EMAIL##' => config('site.from_email'),
            '##CONTACT_MAIL##' => config('site.contact_email')
        );
        $message_content_arr_booker = array();
        $message_content_arr_host = array();
        $vehicle_rental_mail_template_booker = array_merge($emailFindReplace, $default_content);
		$username_replace = array(
			'##USERNAME##' => $vehicle_rental->user->username
		);
		$message_content_arr_booker = array_merge($vehicle_rental_mail_template_booker, $username_replace);
        $message_content_arr_booker['message'] = strtr($template['body_content'], $message_content_arr_booker);
        $message_content_arr_booker['subject'] = strtr($template['subject'], $message_content_arr_booker);
        $this->messageService->saveMessageContent($message_content_arr_booker, $vehicle_rental->item_userable->id, $vehicle_rental->id, config('constants.ConstUserIds.Admin'), $to_user_booker_id, $vehicle_rental->item_user_status_id, 'VehicleRental', config('constants.ConstDisputeStatuses.Open'));
		$vehicle_rental_mail_template_host = array_merge($emailFindReplace, $default_content);
		$username_replace = array(
			'##USERNAME##' => $vehicle_rental->item_userable->user->username
		);
		$message_content_arr_host = array_merge($vehicle_rental_mail_template_host, $username_replace);		
        $message_content_arr_host['message'] = strtr($template['body_content'], $message_content_arr_host);
        $message_content_arr_host['subject'] = strtr($template['subject'], $message_content_arr_host);
        $this->messageService->saveMessageContent($message_content_arr_host, $vehicle_rental->item_userable->id, $vehicle_rental->id, config('constants.ConstUserIds.Admin'), $to_user_host_id, $vehicle_rental->item_user_status_id, 'VehicleRental', config('constants.ConstDisputeStatuses.Open'));
    }

    /**
     * @param $vehicle_rental
     * @param $message
     * @param $is_booker
     * @param $user_name
     */
    public function disputeConversationMail($vehicle_rental, $message, $is_booker, $user_name)
    {
        $template = $this->mailService->getTemplate('Dispute Conversation Notification');
        $site_link = '<a href="' . url('/') . '">' . url('/') . '<a>';
        $emailFindReplace = array(
            '##OTHER_USER##' => $user_name,
            '##MESSAGE##' => $message
        );
        $to_email = ($is_booker) ? $vehicle_rental->user->email : $vehicle_rental->item_userable->user->email;
        $to_username = ($is_booker) ? $vehicle_rental->user->username : $vehicle_rental->item_userable->user->username;
        $this->mailService->sendMail($template, $emailFindReplace, $to_email, $to_username);

        $to_user_id = ($is_booker) ? $vehicle_rental->user->id : $vehicle_rental->item_userable->user->id;
        $from_user_id = ($is_booker) ? $vehicle_rental->item_userable->user->id : $vehicle_rental->user->id;

        //Save internal message
        $default_content = array(
            '##SITE_NAME##' => config('site.name'),
            '##SITE_URL##' => $site_link,
            '##FROM_EMAIL##' => config('site.from_email'),
            '##CONTACT_MAIL##' => config('site.contact_email')
        );
        $vehicle_rental_mail_template = array_merge($emailFindReplace, $default_content);
        $message_content_arr = array();
        $message_content_arr['message'] = strtr($template['body_content'], $vehicle_rental_mail_template);
        $message_content_arr['subject'] = strtr($template['subject'], $vehicle_rental_mail_template);
        $this->messageService->saveMessageContent($message_content_arr, $vehicle_rental->item_userable->id, $vehicle_rental->id, $from_user_id, $to_user_id, $vehicle_rental->item_user_status_id, 'VehicleRental', config('constants.ConstDisputeStatuses.UnderDiscussion'));
    }

    /**
     * @param $vehicle_rental
     * @param $close_type
     * @param $is_favor_booker
     */
    public function disputeClosedMail($vehicle_rental, $close_type, $is_favor_booker)
    {
        $template = $this->mailService->getTemplate('Dispute Resolved Notification');
        $item_link = '<a href="' . url('/#/vehicle/' . $vehicle_rental->item_userable->id) . '/' . $vehicle_rental->item_userable->slug . '">' . $vehicle_rental->item_userable->name . '</a>';
        $order_link = '<a href="' . url('/#/activity/' . $vehicle_rental->id . '/dispute') . '">' . $vehicle_rental->id . '</a>';
        $site_link = '<a href="' . url('/') . '">' . url('/') . '<a>';
        $booker_link = '<a href="' . url('/#/user/' . $vehicle_rental->user->username) . '">' . $vehicle_rental->user->username . '</a>';
        $host_link = '<a href="' . url('/#/user/' . $vehicle_rental->item_userable->user->username) . '">' . $vehicle_rental->item_userable->user->username . '</a>';
        $disputer_link = '<a href="' . url('/#/user/' . $vehicle_rental->item_user_dispute->user->username) . '">' . $vehicle_rental->item_user_dispute->user->username . '</a>';
        $common_emailFindReplace = array(
            '##ORDER_ID##' => $order_link,
            '##DISPUTE_ID##' => $vehicle_rental->item_user_dispute->id,
            '##DISPUTER##' => $disputer_link,
            '##DISPUTER_USER_TYPE##' => ($vehicle_rental->item_user_dispute->is_booker) ? 'Booker' : 'Host',
            '##REASON##' => $vehicle_rental->item_user_dispute->reason,
            '##ITEM_NAME##' => $item_link,
        );
        $emailFindReplace = array();
        $to_email = '';
        $to_username = '';
        $close_type = (int)$close_type;
        if ($close_type === config('constants.ConstDisputeClosedTypes.SpecificationFavourBookerRefund')) {
            $emailFindReplace['##FAVOUR_USER##'] = 'Booker (' . $vehicle_rental->user->username . ')';
			$emailFindReplace['##FAVOUR_USER_LINK##'] = 'Booker (' . $booker_link . ')';
            $reason_for_closing = $this->disputeClosedTypeService->getDisputeClosedType(config('constants.ConstDisputeClosedTypes.SpecificationFavourBookerRefund'));
            $emailFindReplace['##REASON_FOR_CLOSING##'] = $reason_for_closing->reason;
            $emailFindReplace['##RESOLVED_BY##'] = $reason_for_closing->resolved_type;
            $to_username = $vehicle_rental->user->username;
            $to_email = $vehicle_rental->user->email;
        } elseif ($close_type === config('constants.ConstDisputeClosedTypes.SpecificationFavourHost')) {
            $emailFindReplace['##FAVOUR_USER##'] = 'Host (' . $vehicle_rental->item_userable->user->username . ')';
			$emailFindReplace['##FAVOUR_USER_LINK##'] = 'Host (' . $host_link . ')';
            $reason_for_closing = $this->disputeClosedTypeService->getDisputeClosedType(config('constants.ConstDisputeClosedTypes.SpecificationFavourHost'));
            $emailFindReplace['##REASON_FOR_CLOSING##'] = $reason_for_closing->reason;
            $emailFindReplace['##RESOLVED_BY##'] = $reason_for_closing->resolved_type;
            $to_username = $vehicle_rental->item_userable->user->username;
            $to_email = $vehicle_rental->item_userable->user->email;
        } elseif ($close_type === config('constants.ConstDisputeClosedTypes.SpecificationResponseFavourBooker')) {
            $emailFindReplace['##FAVOUR_USER##'] = 'Booker (' . $vehicle_rental->user->username . ')';
			$emailFindReplace['##FAVOUR_USER_LINK##'] = 'Booker (' . $booker_link . ')';
            $reason_for_closing = $this->disputeClosedTypeService->getDisputeClosedType(config('constants.ConstDisputeClosedTypes.SpecificationResponseFavourBooker'));
            $emailFindReplace['##REASON_FOR_CLOSING##'] = $reason_for_closing->reason;
            $emailFindReplace['##RESOLVED_BY##'] = $reason_for_closing->resolved_type;
            $to_username = $vehicle_rental->user->username;
            $to_email = $vehicle_rental->user->email;
        } elseif ($close_type === config('constants.ConstDisputeClosedTypes.FeedbackFavourBooker')) {
            $emailFindReplace['##FAVOUR_USER##'] = 'Booker (' . $vehicle_rental->user->username . ')';
			$emailFindReplace['##FAVOUR_USER_LINK##'] = 'Booker (' . $booker_link . ')';
            $reason_for_closing = $this->disputeClosedTypeService->getDisputeClosedType(config('constants.ConstDisputeClosedTypes.FeedbackFavourBooker'));
            $emailFindReplace['##REASON_FOR_CLOSING##'] = $reason_for_closing->reason;
            $emailFindReplace['##RESOLVED_BY##'] = $reason_for_closing->resolved_type;
            $to_username = $vehicle_rental->user->username;
            $to_email = $vehicle_rental->user->email;
        } elseif ($close_type === config('constants.ConstDisputeClosedTypes.FeedbackFavourHost')) {
			$emailFindReplace['##FAVOUR_USER##'] = 'Host (' . $vehicle_rental->item_userable->user->username . ')';
			$emailFindReplace['##FAVOUR_USER_LINK##'] = 'Host (' . $host_link . ')';
            $reason_for_closing = $this->disputeClosedTypeService->getDisputeClosedType(config('constants.ConstDisputeClosedTypes.FeedbackFavourHost'));
            $emailFindReplace['##REASON_FOR_CLOSING##'] = $reason_for_closing->reason;
            $emailFindReplace['##RESOLVED_BY##'] = $reason_for_closing->resolved_type;
            $to_username = $vehicle_rental->item_userable->user->username;
            $to_email = $vehicle_rental->item_userable->user->email;
        } elseif ($close_type === config('constants.ConstDisputeClosedTypes.FeedbackResponseFavourHost')) {
            $emailFindReplace['##FAVOUR_USER##'] = 'Host (' . $vehicle_rental->item_userable->user->username . ')';
			$emailFindReplace['##FAVOUR_USER_LINK##'] = 'Host (' . $host_link . ')';
            $reason_for_closing = $this->disputeClosedTypeService->getDisputeClosedType(config('constants.ConstDisputeClosedTypes.FeedbackResponseFavourHost'));
            $emailFindReplace['##REASON_FOR_CLOSING##'] = $reason_for_closing->reason;
            $emailFindReplace['##RESOLVED_BY##'] = $reason_for_closing->resolved_type;
            $to_username = $vehicle_rental->item_userable->user->username;
            $to_email = $vehicle_rental->item_userable->user->email;
        } elseif ($close_type === config('constants.ConstDisputeClosedTypes.SecurityFavourBooker')) {
            $emailFindReplace['##FAVOUR_USER##'] = 'Booker (' . $vehicle_rental->user->username . ')';
			$emailFindReplace['##FAVOUR_USER_LINK##'] = 'Booker (' . $booker_link . ')';
            $reason_for_closing = $this->disputeClosedTypeService->getDisputeClosedType(config('constants.ConstDisputeClosedTypes.SecurityFavourBooker'));
            $emailFindReplace['##REASON_FOR_CLOSING##'] = $reason_for_closing->reason;
            $emailFindReplace['##RESOLVED_BY##'] = $reason_for_closing->resolved_type;
            $to_username = $vehicle_rental->user->username;
            $to_email = $vehicle_rental->user->email;
        } elseif ($close_type === config('constants.ConstDisputeClosedTypes.SecurityFavourHost')) {
            $emailFindReplace['##FAVOUR_USER##'] = 'Host (' . $vehicle_rental->item_userable->user->username . ')';
			$emailFindReplace['##FAVOUR_USER_LINK##'] = 'Host (' . $host_link . ')';
            $reason_for_closing = $this->disputeClosedTypeService->getDisputeClosedType(config('constants.ConstDisputeClosedTypes.SecurityFavourHost'));
            $emailFindReplace['##REASON_FOR_CLOSING##'] = $reason_for_closing->reason;
            $emailFindReplace['##RESOLVED_BY##'] = $reason_for_closing->resolved_type;
            $to_username = $vehicle_rental->item_userable->user->username;
            $to_email = $vehicle_rental->item_userable->user->email;
        } elseif ($close_type === config('constants.ConstDisputeClosedTypes.SecurityResponseFavourHost')) {
            $emailFindReplace['##FAVOUR_USER##'] = 'Host (' . $vehicle_rental->item_userable->user->username . ')';
			$emailFindReplace['##FAVOUR_USER_LINK##'] = 'Host (' . $host_link . ')';
            $reason_for_closing = $this->disputeClosedTypeService->getDisputeClosedType(config('constants.ConstDisputeClosedTypes.SecurityResponseFavourHost'));
            $emailFindReplace['##REASON_FOR_CLOSING##'] = $reason_for_closing->reason;
            $emailFindReplace['##RESOLVED_BY##'] = $reason_for_closing->resolved_type;
            $to_username = $vehicle_rental->item_userable->user->username;
            $to_email = $vehicle_rental->item_userable->user->email;
        }
        $emailFindReplace = array_merge($common_emailFindReplace, $emailFindReplace);
        $this->mailService->sendMail($template, $emailFindReplace, $to_email, $to_username);

        //Save internal message
        $default_content = array(
            '##SITE_NAME##' => config('site.name'),
            '##SITE_URL##' => $site_link,
            '##FROM_EMAIL##' => config('site.from_email'),
            '##CONTACT_MAIL##' => config('site.contact_email')
        );
		$message_content_arr_booker = array();
        $message_content_arr_host = array();
		/// message save to host
        $vehicle_rental_mail_template_host = array_merge($emailFindReplace, $default_content); 
		$username_replace = array(
			'##USERNAME##' => $vehicle_rental->item_userable->user->username
		);
		$message_content_arr_host = array_merge($vehicle_rental_mail_template_host, $username_replace);
        $message_content_arr_host['message'] = strtr($template['body_content'], $message_content_arr_host);
        $message_content_arr_host['subject'] = strtr($template['subject'], $message_content_arr_host);        
        $this->messageService->saveMessageContent($message_content_arr_host, $vehicle_rental->item_userable->id, $vehicle_rental->id, config('constants.ConstUserIds.Admin'), $vehicle_rental->item_userable->user_id, $vehicle_rental->item_user_status_id, 'VehicleRental', config('constants.ConstDisputeStatuses.Closed'));
        //message save to booker
		$vehicle_rental_mail_template_booker = array_merge($emailFindReplace, $default_content);
		$username_replace = array(
			'##USERNAME##' => $vehicle_rental->user->username
		);
		$message_content_arr_booker = array_merge($vehicle_rental_mail_template_booker, $username_replace);
        $message_content_arr_booker['message'] = strtr($template['body_content'], $message_content_arr_booker);
        $message_content_arr_booker['subject'] = strtr($template['subject'], $message_content_arr_booker);
        $this->messageService->saveMessageContent($message_content_arr_booker, $vehicle_rental->item_userable->id, $vehicle_rental->id, config('constants.ConstUserIds.Admin'), $vehicle_rental->user_id, $vehicle_rental->item_user_status_id, 'VehicleRental', config('constants.ConstDisputeStatuses.Closed'));
		
    }

    /**
     * @param $vehicle_rental
     */
    public function resolveByRefund($vehicle_rental)
    {
        $refund_percentage = config('dispute.refund_amount_during_dispute_cancellation') / 100;
        $refund_commission = $vehicle_rental->paid_manual_amount + ($vehicle_rental->total_amount * $refund_percentage);
        $booker_transaction_amount = $vehicle_rental->total_amount - $refund_commission;
        // for specification dispute closed in favour of booker host should not get any amount, so it is set to 0.
        $vehicle_rental_data['host_service_amount'] = 0;
        $vehicle_rental_data['admin_commission_amount'] = $refund_commission;
        $vehicle_rental->update($vehicle_rental_data);
        //update user wallet amount
        $this->walletService->updateWalletForUser($vehicle_rental->user_id, $booker_transaction_amount, $vehicle_rental->id, 'VehicleRentals');
        // update transaction logs
        $this->transactionService->log(config('constants.ConstUserIds.Admin'), $vehicle_rental->user_id, config('constants.ConstTransactionTypes.RefundForSpecificationDispute'), $booker_transaction_amount, $vehicle_rental->id, 'VehicleRentals');
    }

    /**
     * @param $vehicle_rental
     */
    public function resolveByDepositAmountRefund($vehicle_rental, $dispute_closed_type_id)
    {
        //update user wallet amount
        if ($dispute_closed_type_id == config('constants.ConstDisputeClosedTypes.SecurityFavourBooker')) {
            $item_user_data['paid_deposit_amount'] = 0;
            $vehicle_rental->update($item_user_data);
            $this->walletService->updateWalletForUser($vehicle_rental->user_id, $vehicle_rental->deposit_amount, $vehicle_rental->id, 'VehicleRentals');
            // update transaction logs
            $this->transactionService->log(config('constants.ConstUserIds.Admin'), $vehicle_rental->user_id, config('constants.ConstTransactionTypes.SecuirtyDepositAmountRefundedToBooker'), $vehicle_rental->deposit_amount, $vehicle_rental->id, 'VehicleRentals');
        } else if ($dispute_closed_type_id == config('constants.ConstDisputeClosedTypes.SecurityFavourHost') || $dispute_closed_type_id === config('constants.ConstDisputeClosedTypes.SecurityResponseFavourHost')) {
            $payable_manual_claim = $vehicle_rental->paid_manual_amount;
            if ($payable_manual_claim > 0) {
                // manually paid claim
                $this->transactionService->log($vehicle_rental->user_id, config('constants.ConstUserIds.Admin'), config('constants.ConstTransactionTypes.ManualTransferForClaimRequestAmount'), $payable_manual_claim, $vehicle_rental->item_userable->id, 'Vehicles');
            }
        }

    }

    /**
     * @param      $request
     * @param      $vehicle_rental
     * @param null $is_favour_booker
     */
    public function closeDispute($request, $vehicle_rental, $is_favour_booker)
    {
        $dispute_data = array();
        if (is_array($request)) {
            $dispute_data['dispute_closed_type_id'] = $request['dispute_closed_type_id'];
        } else {
            $dispute_data['dispute_closed_type_id'] = $request->dispute_closed_type_id;
        }
        $dispute_data['dispute_status_id'] = config('constants.ConstDisputeStatuses.Closed');
        $dispute_data['resolved_date'] = Carbon::now()->toDateTimeString();
        $dispute_data['is_favor_booker'] = $is_favour_booker;
        $vehicle_dispute = VehicleDispute::where('id', '=', $vehicle_rental->item_user_dispute->id)->update($dispute_data);
        if ($vehicle_dispute) {
            $this->disputeClosedMail($vehicle_rental, $dispute_data['dispute_closed_type_id'], $is_favour_booker);
            $item_user_data['is_dispute'] = 0;
            $item_user_data['item_user_status_id'] = config('constants.ConstItemUserStatus.Completed');
            if ($vehicle_rental->update($item_user_data)) {
                $this->vehicleRentalService->completeTransactionAmounts($vehicle_rental, 1);
                $this->vehicleRentalService->changeStatusMail($vehicle_rental, $vehicle_rental->item_userable, $vehicle_rental->item_user_status_id, config('constants.ConstItemUserStatus.Completed'));
            }
        }
    }

    /**
     * update dispute conversation
     * @param $vehicle_rental
     */
    public function updateConversation($vehicle_rental, $message)
    {
        $user = Auth::user();
        $dispute_data = array();
        $conversation_count = ($vehicle_rental->item_user_dispute->dispute_conversation_count) + 1;
        $dispute_data['dispute_status_id'] = config('constants.ConstDisputeStatuses.UnderDiscussion');
        $dispute_data['dispute_conversation_count'] = $conversation_count;
        ($user->id === $vehicle_rental->user_id) ? $is_booker = 1 : $is_booker = 0;
        if ($user->id === $vehicle_rental->item_userable->user_id || $user->id == $vehicle_rental->user_id) {
            // user update
            $dispute_data['last_replied_user_id'] = $user->id;
            $dispute_data['last_replied_date'] = Carbon::now()->toDateTimeString();
        }
        VehicleDispute::where('id', $vehicle_rental->item_user_dispute->id)->update($dispute_data);
        if ($conversation_count < config('dispute.discussion_threshold_for_admin_decision')) {
            $this->disputeConversationMail($vehicle_rental, $message, $is_booker, $user->username);
        } else {
            $dispute_data['dispute_status_id'] = config('constants.ConstDisputeStatuses.WaitingAdministratorDecision');
            VehicleDispute::where('id', $vehicle_rental->item_user_dispute->id)->update($dispute_data);
            $this->disputeAdminDecisionMail($vehicle_rental);
        }
    }

    public function disputeAdminDecisionMail($vehicle_rental)
    {
        $template = $this->mailService->getTemplate('Discussion Threshold for Admin Decision');
        // send booker mail
        $emailFindReplace = array(
            '##USERNAME##' => $vehicle_rental->user->username,
            '##OTHER_USER##' => 'Admin'
        );
        $to_username = $vehicle_rental->user->id;
        $to_email = $vehicle_rental->user->email;

        $this->mailService->sendMail($template, $emailFindReplace, $to_email, $to_username);

        // send host mail
        $emailFindReplace = array(
            '##USERNAME##' => $vehicle_rental->item_userable->user->username,
            '##OTHER_USER##' => 'Admin'
        );
        $to_username = $vehicle_rental->item_userable->user->id;
        $to_email = $vehicle_rental->item_userable->user->email;
        $this->mailService->sendMail($template, $emailFindReplace, $to_email, $to_username);

        //Save internal message
        $default_content = array(
            '##SITE_NAME##' => config('site.name'),
            '##SITE_URL##' => '<a href="' . url('/') . '">' . url('/') . '<a>',
            '##FROM_EMAIL##' => config('site.from_email'),
            '##CONTACT_MAIL##' => config('site.contact_email')
        );
        $vehicle_rental_mail_template = array_merge($emailFindReplace, $default_content);

        // save message booker
        $message_content_arr = array();
        $message_content_arr['message'] = strtr($template['body_content'], $vehicle_rental_mail_template);
        $message_content_arr['subject'] = strtr($template['subject'], $vehicle_rental_mail_template);
        $this->messageService->saveMessageContent($message_content_arr, $vehicle_rental->item_userable->id, $vehicle_rental->id, config('constants.ConstUserIds.Admin'), $vehicle_rental->user_id, $vehicle_rental->item_user_status_id, 'VehicleRental', config('constants.ConstDisputeStatuses.WaitingAdministratorDecision'));

        //save message to host
        $message_content_arr = array();
        $message_content_arr['message'] = strtr($template['body_content'], $vehicle_rental_mail_template);
        $message_content_arr['subject'] = strtr($template['subject'], $vehicle_rental_mail_template);
        $this->messageService->saveMessageContent($message_content_arr, $vehicle_rental->item_userable->id, $vehicle_rental->id, config('constants.ConstUserIds.Admin'), $vehicle_rental->item_userable->user_id, $vehicle_rental->item_user_status_id, 'VehicleRental', config('constants.ConstDisputeStatuses.WaitingAdministratorDecision'));
    }
}
