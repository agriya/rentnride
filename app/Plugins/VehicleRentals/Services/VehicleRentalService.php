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
 
namespace Plugins\VehicleRentals\Services;

use App\Services\MailService;
use App\Services\MessageService;
use App\Services\TransactionService;
use App\User;
use Plugins\VehicleRentals\Model\VehicleRental;
use Plugins\VehicleRentals\Model\VehicleRentalStatus;
use Plugins\Vehicles\Services\VehicleService;
use Plugins\Vehicles\Services\UnavailableVehicleService;
use Plugins\Vehicles\Model\Vehicle;
use Carbon;
use DB;
use Log;
use Cache;
use Plugins\Vehicles\Model\UnavailableVehicle;

class VehicleRentalService
{
    /**
     * @var
     */
    protected $transactionService;

    /**
     * @var
     */
    protected $vehicleRentalLatePaymentDetailService;

    /**
     * @var
     */
    protected $vehicleService;

    /**
     * @var
     */
    protected $unavailableVehicleService;

    /**
     * VehicleRentalService constructor.
     */
    public function __construct()
    {
        $this->setMailService();
        $this->setMessageService();
        $this->setTransactionService();
        $this->setVehicleRentalLatePaymentDetailService();
        $this->setVehicleService();
        $this->setUnavailableVehicleService();
        $this->setVehicleCouponService();
    }

    public function setMailService()
    {
        $this->mailService = new MailService();
    }

    public function setTransactionService()
    {
        $this->transactionService = new TransactionService();
    }

    public function setMessageService()
    {
        $this->messageService = new MessageService();
    }

    public function setVehicleCouponService()
    {
        $this->vehicleCouponService = new \Plugins\VehicleCoupons\Services\VehicleCouponService();
    }

    /**
     * setVehicleRentalLatePaymentDetailService
     */
    public function setVehicleRentalLatePaymentDetailService()
    {
        $this->vehicleRentalLatePaymentDetailService = new VehicleRentalLatePaymentDetailService();
    }

    /**
     * setVehicleService
     */
    public function setVehicleService()
    {
        $this->vehicleService = new VehicleService();
    }

    /**
     * setUnavailableVehicleService
     */
    public function setUnavailableVehicleService()
    {
        $this->unavailableVehicleService = new UnavailableVehicleService();
    }

    /**
     * get last vehicle_rental record for admin dashboard
     * @param $request
     * @return VehicleRental created_at
     */
    public function getLastVehicleRental()
    {
        $vehicle_rental_details = VehicleRental::select('created_at')->where('item_user_status_id', '!=', config('constants.ConstItemUserStatus.PaymentPending'))->filterByVehicleRental(false)->orderBy('created_at', 'desc')->first();
        return ($vehicle_rental_details) ? $vehicle_rental_details->created_at->diffForHumans() : "-";
    }

    /**
     * @param        $request
     * @param string $type
     * @return mixed
     */
    public function getVehicleRentalCount($request, $type = 'filter')
    {
        if ($type == 'filter') {
            $check_date = $this->getDateFilter($request);
            $check_date = Carbon::parse($check_date)->format('Y-m-d');
            $booking_count = VehicleRental::where('created_at', '>=', $check_date)
                ->filterByVehicleRental(false)
                ->count();
        } else {
            $booking_count = VehicleRental::filterByVehicleRental(false)->count();
        }
        return $booking_count;
    }

    /**
     * get the date filter
     * @return $check_date
     */
    public function getDateFilter($request)
    {
        $check_date = Carbon::now()->subDays(7);
        if ($request->has('filter')) {
            if ($request->filter == 'lastDays') {
                $check_date = Carbon::now()->subDays(7);
            } else if ($request->filter == 'lastWeeks') {
                $check_date = Carbon::now()->subWeeks(4);
            } else if ($request->filter == 'lastMonths') {
                $check_date = Carbon::now()->subMonths(3);
            } else if ($request->filter == 'lastYears') {
                $check_date = Carbon::now()->subYears(3);
            }
        }
        return $check_date;
    }

    /**
     * Send vehicle_rental mail
     * @param $vehicle_rental
     * @param $item
     */
    public function sendVehicleRentalMail($vehicle_rental, $item)
    {
        $from = config('constants.ConstUserIds.Admin');
        $host = $item->user;
        $booker = $vehicle_rental->user;
        $default_content = array(
            '##SITE_NAME##' => config('site.name'),
            '##SITE_URL##' => '<a href="' . url('/') . '">' . url('/') . '<a>',
            '##FROM_EMAIL##' => config('site.from_email'),
            '##CONTACT_MAIL##' => config('site.contact_email'),
            '##CONTACT_URL##' => '<a href="' . url('/#/contactus') . '">Contact Us</a>'
        );
        //VehicleRental mail to booker
        $template = $this->mailService->getTemplate('New VehicleRental Message To Booker');
        if (config('vehicle_rental.is_auto_approve')) {
            $cancel_url = 'cancel';
        } else {
            $cancel_url = '<a href="' . url('/#/vehicle_rentals/' . $vehicle_rental->id . '/cancel') . '">Cancel</a>';
        }
        $item_link = '<a href="' . url('/#/vehicle/' . $item->id) . '/' . $item->slug . '">' . $item->name . '</a>';
        $order_link = '<a href="' . url('/#/activity/' . $vehicle_rental->id . '/all') . '">' . $vehicle_rental->id . '</a>';
        $host_link = '<a href="' . url('/#/user/' . $host->username) . '">' . $host->username . '</a>';
        $booker_link = '<a href="' . url('/#/user/' . $booker->username) . '">' . $booker->username . '</a>';
        $emailFindReplace = array(
            '##USERNAME##' => $booker->username,
            '##HOST_NAME##' => $host_link,
            '##ITEM_NAME##' => $item_link,
            '##ORDER_NO##' => $order_link,
            '##ITEM_FULL_NAME##' => $item_link,
            '##ITEM_DESCRIPTION##' => $item->description,
            '##FROM_DATE##' => $vehicle_rental->item_booking_start_date,
            '##HOST_CONTACT_LINK##' => $host_link,
            '##CANCEL_URL##' => $cancel_url,
            '##ITEM_AUTO_EXPIRE_DATE##' => config('vehicle_rental.auto_expire')
        );
        $this->mailService->sendMail($template, $emailFindReplace, $booker->email, $booker->username);
        //Save vehicle_rental message to booker
        $vehicle_rental_mail_template = array_merge($emailFindReplace, $default_content);
        $message_content_arr = array();
        $message_content_arr['message'] = strtr($template['body_content'], $vehicle_rental_mail_template);
        $message_content_arr['subject'] = strtr($template['subject'], $vehicle_rental_mail_template);
        $this->messageService->saveMessageContent($message_content_arr, $item->id, $vehicle_rental->id, $from, $vehicle_rental->user_id, config('constants.ConstItemUserStatus.PaymentPending'), 'VehicleRental');
        //VehicleRental mail to host
        if (config('vehicle_rental.is_auto_approve')) {
            $template = $this->mailService->getTemplate('New VehicleRental Message To Host On Auto Approve');
        } else {
            $template = $this->mailService->getTemplate('New VehicleRental Message To Host');
        }
        $emailFindReplace = array(
            '##USERNAME##' => $host->username,
            '##BOOKER_USERNAME##' => $booker_link,
            '##ITEM_NAME##' => $item_link,
            '##ORDER_NO##' => $order_link,
            '##ACCEPT_URL##' => '<a href="' . url('/#/vehicle_orders/' . $vehicle_rental->id . '/confirm') . '">Accept</a>',
            '##REJECT_URL##' => '<a href="' . url('/#/vehicle_orders/' . $vehicle_rental->id . '/reject') . '">Reject</a>'
        );
        $this->mailService->sendMail($template, $emailFindReplace, $host->email, $host->username);
        //Save vehicle_rental message to host
        $vehicle_rental_mail_template = array_merge($emailFindReplace, $default_content);
        $message_content_arr = array();
        $message_content_arr['message'] = strtr($template['body_content'], $vehicle_rental_mail_template);
        $message_content_arr['subject'] = strtr($template['subject'], $vehicle_rental_mail_template);
        $this->messageService->saveMessageContent($message_content_arr, $item->id, $vehicle_rental->id, $from, $item->user_id, config('constants.ConstItemUserStatus.PaymentPending'), 'VehicleRental');
    }

    /**
     * return enable gateways
     * @return array
     */
    public function getEnableGateway()
    {
        $enabledIncludes = array();
        $enabledIncludes[] = 'wallet_transaction_log';
        // check if plugin enabled and include
        (isPluginEnabled('Paypal')) ? $enabledIncludes[] = 'paypal_transaction_log' : '';
        (isPluginEnabled('Sudopays')) ? $enabledIncludes[] = 'sudopay_transaction_logs' : '';
        return $enabledIncludes;
    }

    /**
     * @param $vehicle_rental
     * @param $item
     */
    public function sendConfirmationMail($vehicle_rental, $item)
    {
        $host = $item->user;
        $booker = $vehicle_rental->user;
        $item_link = '<a href="' . url('/#/vehicle/' . $item->id) . '/' . $item->slug . '">' . $item->name . '</a>';
        $order_link = '<a href="' . url('/#/activity/' . $vehicle_rental->id . '/all') . '">' . $vehicle_rental->id . '</a>';
        $host_link = '<a href="' . url('/#/user/' . $host->username) . '">' . $host->username . '</a>';
        $site_link = '<a href="' . url('/') . '">' . url('/') . '<a>';
        $default_content = array(
            '##SITE_NAME##' => config('site.name'),
            '##SITE_URL##' => $site_link,
            '##FROM_EMAIL##' => config('site.from_email'),
            '##CONTACT_MAIL##' => config('site.contact_email')
        );
        //VehicleRental mail to booker
        $template = $this->mailService->getTemplate('Accepted VehicleRental Message To Booker');
        $emailFindReplace = array(
            '##USERNAME##' => $booker->username,
            '##ITEM_NAME##' => $item_link,
            '##HOST_CONTACT_LINK##' => $host_link
        );
        $this->mailService->sendMail($template, $emailFindReplace, $booker->email, $booker->username);
        //VehicleRental mail to host
        $template = $this->mailService->getTemplate('Accepted VehicleRental Message To Host');
        $emailFindReplace = array(
            '##USERNAME##' => $host->username,
            '##ITEM_NAME##' => $item_link,
            '##ORDER_NO##' => $order_link
        );
        $this->mailService->sendMail($template, $emailFindReplace, $host->email, $host->username);
    }

    /**
     * @param $vehicle_rental
     * @param $item
     * @param $previous_status
     * @param $current_status
     */
    public function changeStatusMail($vehicle_rental, $item, $previous_status, $current_status)
    {
        $from = config('constants.ConstUserIds.Admin');
        $host = $item->user;
        $booker = $vehicle_rental->user;
        $item_link = '<a href="' . Cache::get('site_url_for_shell').'/#/vehicle/' . $item->id . '/' . $item->slug . '">' . $item->name . '</a>';
        $order_link = '<a href="' . Cache::get('site_url_for_shell').'/#/activity/' . $vehicle_rental->id . '/all' . '">' . $vehicle_rental->id . '</a>';
        $site_link = '<a href="' . Cache::get('site_url_for_shell') . '">' . Cache::get('site_url_for_shell') . '<a>';
        $default_content = array(
            '##SITE_NAME##' => config('site.name'),
            '##SITE_URL##' => $site_link,
            '##FROM_EMAIL##' => config('site.from_email'),
            '##CONTACT_MAIL##' => config('site.contact_email')
        );
        $status_arr = [$previous_status, $current_status];
        $status = VehicleRentalStatus::whereIn('id', $status_arr)->lists('name', 'id')->all();
        $template = $this->mailService->getTemplate('Item User Change Status Alert');
        $emailFindReplace = array(
            '##SITE_NAME##' => config('site.name'),
            '##SITE_URL##' => $site_link,
            '##ITEM##' => $item->name,
            '##PREVIOUS_STATUS##' => $status[$previous_status],
            '##CURRENT_STATUS##' => $status[$current_status],
            '##ITEM_NAME##' => $item_link,
            '##ITEM_URL##' => $item_link,
            '##ORDER_NO##' => $order_link,
        );
        //Status change mail and message to booker
        $this->mailService->sendMail($template, $emailFindReplace, $booker->email, $booker->username);
        $vehicle_rental_mail_template = array_merge($emailFindReplace, $default_content);
        $message_content_arr = array();
        $message_content_arr['message'] = strtr($template['body_content'], $vehicle_rental_mail_template);
        $message_content_arr['subject'] = strtr($template['subject'], $vehicle_rental_mail_template);
        $this->messageService->saveMessageContent($message_content_arr, $item->id, $vehicle_rental->id, $from, $vehicle_rental->user_id, $current_status, 'VehicleRental');
        //Status change mail and message to host
        $this->mailService->sendMail($template, $emailFindReplace, $host->email, $host->username);
        $vehicle_rental_mail_template = array_merge($emailFindReplace, $default_content);
        $message_content_arr = array();
        $message_content_arr['message'] = strtr($template['body_content'], $vehicle_rental_mail_template);
        $message_content_arr['subject'] = strtr($template['subject'], $vehicle_rental_mail_template);
        $this->messageService->saveMessageContent($message_content_arr, $item->id, $vehicle_rental->id, $from, $item->user_id, $current_status, 'VehicleRental');
    }


    /**
     *  Update VehicleRental count in item user status table
     */
    public function updateItemUserCount()
    {
        $vehicle_rental_status = VehicleRentalStatus::select('id', 'booking_count')->get();
        $status_count = DB::table('item_users')
            ->where('item_userable_type', 'MorphVehicle')
            ->select('item_user_status_id', DB::raw('count(*) as total'))
            ->groupBy('item_user_status_id')
            ->lists('total', 'item_user_status_id');
        foreach ($status_count as $key => $value) {
            VehicleRentalStatus::where('id', '=', $key)->update(['booking_count' => $value]);
        }
        foreach ($vehicle_rental_status as $value) {
            if (!array_key_exists($value->id, $status_count)) {
                VehicleRentalStatus::where('id', '=', $value->id)->update(['booking_count' => 0]);
            }
        }
    }

    public function updateVehicleItemUserCount($vehicle_id)
    {
        $vehicle = Vehicle::find($vehicle_id);
        if ($vehicle) {
            $data['vehicle_rental_count'] = VehicleRental::where('item_userable_type', 'MorphVehicle')->where('item_userable_id', $vehicle_id)->whereNotIn('item_user_status_id', array(config('ConstItemUserStatus.PaymentPending', 'ConstItemUserStatus.PrivateConversation')))->count();
            $vehicle->update($data);
        }
    }

    public function updateUserItemUserCount($user_id)
    {
        $user = User::find($user_id);
        if ($user) {
            $data['vehicle_rental_count'] = VehicleRental::where('user_id', $user_id)->whereNotIn('item_user_status_id', array(config('ConstItemUserStatus.PaymentPending', 'ConstItemUserStatus.PrivateConversation')))->count();
            $user->update($data);
        }
    }

    public function getBookAndOrderCount($user_id)
    {
        $data = array();
        $data['booking'] = array();
        $data['host'] = array();
        if ($user_id) {
            // get status
            $vehicle_rental_status = VehicleRentalStatus::where('id', '!=', config('constants.ConstItemUserStatus.PrivateConversation'))->orderBy('display_order', 'ASC')->select('id', 'name', 'slug')->get();
            $vehicle_rental_status = $vehicle_rental_status->toArray();
            // get booking count
            $status_count = DB::table('item_users')
                ->where('user_id', $user_id)
                ->where('item_userable_type', 'MorphVehicle')
                ->select('item_user_status_id', DB::raw('count(*) as total'))
                ->groupBy('item_user_status_id')
                ->lists('total', 'item_user_status_id');

            // get host order count
            $vehicle_list = Vehicle::where('user_id', $user_id)->lists('id', 'id');
            $vehicle_list = $vehicle_list->toArray();
            $host_status_count = DB::table('item_users')
                ->where('item_userable_type', 'MorphVehicle')
                ->whereIN('item_userable_id', $vehicle_list)
                ->select('item_user_status_id', DB::raw('count(*) as total'))
                ->groupBy('item_user_status_id')
                ->lists('total', 'item_user_status_id');

            // response process
            $total_booking_count = 0;
            $total_order_count = 0;
            if (!isset($status_count[config('constants.ConstItemUserStatus.WaitingForReview')])) {
                $status_count[config('constants.ConstItemUserStatus.WaitingForReview')] = 0;
            }
            if (!isset($status_count[config('constants.ConstItemUserStatus.HostReviewed')])) {
                $status_count[config('constants.ConstItemUserStatus.HostReviewed')] = 0;
            }
            $status_count[config('constants.ConstItemUserStatus.WaitingForReview')] = $status_count[config('constants.ConstItemUserStatus.WaitingForReview')] + $status_count[config('constants.ConstItemUserStatus.HostReviewed')];
            if (!isset($status_count[config('constants.ConstItemUserStatus.Completed')])) {
                $status_count[config('constants.ConstItemUserStatus.Completed')] = 0;
            }
            if (!isset($status_count[config('constants.ConstItemUserStatus.BookerReviewed')])) {
                $status_count[config('constants.ConstItemUserStatus.BookerReviewed')] = 0;
            }
            if (!isset($status_count[config('constants.ConstItemUserStatus.WaitingForPaymentCleared')])) {
                $status_count[config('constants.ConstItemUserStatus.WaitingForPaymentCleared')] = 0;
            }
            $status_count[config('constants.ConstItemUserStatus.Completed')] = $status_count[config('constants.ConstItemUserStatus.Completed')] + $status_count[config('constants.ConstItemUserStatus.BookerReviewed')] + +$status_count[config('constants.ConstItemUserStatus.WaitingForPaymentCleared')];
            foreach ($vehicle_rental_status as $status) {
                $tmp = $status;
                $tmp1 = $status;
                if ($status['id'] != config('constants.ConstItemUserStatus.CancelledByAdmin')) {
                    if (!in_array($status['id'], array(config('constants.ConstItemUserStatus.HostReviewed'), config('constants.ConstItemUserStatus.BookerReviewed'), config('constants.ConstItemUserStatus.WaitingForPaymentCleared')))) {
                        if (isset($status_count[$status['id']])) {
                            $tmp['booking_count'] = $status_count[$status['id']];
                        } else {
                            $tmp['booking_count'] = 0;
                        }
                        $total_booking_count = $total_booking_count + $tmp['booking_count'];
                        $data['booking'][] = $tmp;
                    }
                    if(!in_array($status['id'], array(config('constants.ConstItemUserStatus.PaymentPending')))) {
                        if (isset($host_status_count[$status['id']])) {
                            $tmp1['host_count'] = $host_status_count[$status['id']];
                        } else {
                            $tmp1['host_count'] = 0;
                        }
                        $data['host'][] = $tmp1;
                        $total_order_count = $total_order_count + $tmp1['host_count'];
                    }
                }
            }
            $data['booking'] = array_merge(array(array(
                'id' => 0,
                'name' => 'All',
                'slug' => 'all',
                'booking_count' => $total_booking_count
            )), $data['booking']);
            $data['host'] = array_merge(array(array(
                'id' => 0,
                'name' => 'All',
                'slug' => 'all',
                'host_count' => $total_order_count
            )), $data['host']);
            $data['total_booking_count'] = $total_booking_count;
            $data['total_order_count'] = $total_order_count;
        }
        return $data;
    }

    public function updateUserOrderCount($user_id)
    {
        $user = User::find($user_id);
        if ($user) {
            $data['vehicle_rental_order_count'] = Vehicle::where('user_id', $user_id)->sum('vehicle_rental_count');
            $user->update($data);
        }
    }

    /**
     *  status update calls
     */
    public function autoUpdateStatus()
    {
        $this->updateCompletedStatus();
        $this->updatePendingPaymentClearedStatus();
        $this->autoExpire();
        $this->autoExpirePaymentPendingVehicleRental();
        $this->autoDeleteUnavailableVehicleRecords();
        $this->autoCompleteUnattendedRecords();
    }

    /**
     *  This updates the status from waiting for review to Pending payment cleared status
     */
    public function updatePendingPaymentClearedStatus()
    {
        $auto_expire_days = config('vehicle_rental.auto_update_pending_payment_status');
        $expire_date = Carbon::now()->subDays($auto_expire_days);
        $expire_date = $expire_date->toDateTimeString();
        $vehicle_rentals = VehicleRental::where('item_user_status_id', '=', config('constants.ConstItemUserStatus.WaitingForReview'))->where('is_dispute', '!=', 1)
            ->filterByVehicleRental(false)
            ->where('status_updated_at', '<=', $expire_date)->get();
        if ($vehicle_rentals) {
            foreach ($vehicle_rentals as $vehicle_rental) {
                $item = $vehicle_rental->item_userable;
                if (!is_null($item)) {
                    $item_user_data['id'] = $vehicle_rental->id;
                    $item_user_data['item_user_status_id'] = config('constants.ConstItemUserStatus.WaitingForPaymentCleared');
                    $item_user_data['status_updated_at'] = Carbon::now()->toDateTimeString();
                    try {
                        $vehicle_rental->update($item_user_data);
                        $this->updateItemUserCount();
                        //Send Mail to Booker and save message contents and messages
                        $this->changeStatusMail($vehicle_rental, $item, config('constants.ConstItemUserStatus.WaitingForReview'), config('constants.ConstItemUserStatus.WaitingForPaymentCleared'));

                    } catch (\Exception $e) {
                        throw new \Dingo\Api\Exception\StoreResourceFailedException('Status could not be changed');
                    }
                }
            }
        }
    }

    /**
     * This updates the status from  Pending payment cleared status to complete and amount released to host
     */
    public function updateCompletedStatus()
    {
        $auto_expire_days = config('vehicle_rental.days_after_amount_cleared_to_host');
        $expire_date = Carbon::now()->subDays($auto_expire_days);
        $expire_date = $expire_date->toDateTimeString();
        $vehicle_rentals = VehicleRental::where('item_user_status_id', '=', config('constants.ConstItemUserStatus.WaitingForPaymentCleared'))
            ->filterByVehicleRental(false)
            ->where('status_updated_at', '<=', $expire_date)->get();
        if ($vehicle_rentals) {
            foreach ($vehicle_rentals as $vehicle_rental) {
                $item = $vehicle_rental->item_userable;
                if (!is_null($item)) {
                    $item_user_data['id'] = $vehicle_rental->id;
                    $item_user_data['item_user_status_id'] = config('constants.ConstItemUserStatus.Completed');
                    $item_user_data['status_updated_at'] = Carbon::now()->toDateTimeString();

                }
                try {
                    $vehicle_rental->update($item_user_data);
                    $this->updateItemUserCount();
                    // all transactions
                    $this->completeTransactionAmounts($vehicle_rental);
                    //Send Mail to Booker and save message contents and messages
                    $this->changeStatusMail($vehicle_rental, $item, config('constants.ConstItemUserStatus.WaitingForPaymentCleared'), config('constants.ConstItemUserStatus.Completed'));
                } catch (\Exception $e) {
                    throw new \Dingo\Api\Exception\StoreResourceFailedException('Status could not be changed');
                }
            }
        }
    }

    /**
     * This updates the status from pending payment status to expired and amount refunded to booker
     */
    public function autoExpirePaymentPendingVehicleRental()
    {
        $expire_date = Carbon::now()->toDateTimeString();
        $vehicle_rentals = VehicleRental::where('item_user_status_id', '=', config('constants.ConstItemUserStatus.PaymentPending'))
            ->filterByVehicleRental(false)
            ->where('item_booking_start_date', '<', $expire_date)->get();
        if ($vehicle_rentals) {
            foreach ($vehicle_rentals as $vehicle_rental) {
                if (!is_null($vehicle_rental->item_userable)) {
                    $item = $vehicle_rental->item_userable;
                    $item_user_data['id'] = $vehicle_rental->id;
                    $item_user_data['item_user_status_id'] = config('constants.ConstItemUserStatus.Expired');
                    $item_user_data['status_updated_at'] = Carbon::now()->toDateTimeString();
                    try {
                        $vehicle_rental->update($item_user_data);
                        $this->updateItemUserCount();
                        //Send Mail to Booker and save message contents and messages
                        $this->changeStatusMail($vehicle_rental, $item, config('constants.ConstItemUserStatus.PaymentPending'), config('constants.ConstItemUserStatus.Expired'));
                    } catch (\Exception $e) {
                        throw new \Dingo\Api\Exception\StoreResourceFailedException('Status could not be changed', array($e->getMessage()));
                    }
                }
            }
        }

    }

    /**
     * This updates the status from waiting for acceptance to Expired and amount refunded to booker
     */
    public function autoExpire()
    {
        $auto_expire_days = config('vehicle_rental.auto_expire');
        $expire_date = Carbon::now()->subDays($auto_expire_days);
        $expire_date = $expire_date->toDateTimeString();
        $enabledIncludes = $this->getEnableGateway();
        $vehicle_rentals = VehicleRental::with($enabledIncludes)
            ->where('item_user_status_id', '=', config('constants.ConstItemUserStatus.WaitingForAcceptance'))
            ->filterByVehicleRental(false)
            ->where('status_updated_at', '<=', $expire_date)->get();
        if ($vehicle_rentals) {
            foreach ($vehicle_rentals as $vehicle_rental) {
                if (!is_null($vehicle_rental->item_userable) && !is_null($vehicle_rental->user)) {
                    try {
                        $error_msg = '';
                        $is_payment_transaction = false;
                        $transaction_log = array();
                        if (isPluginEnabled('Paypal') && !is_null($vehicle_rental->paypal_transaction_log)) {
                            $gateway_id = config('constants.ConstPaymentGateways.PayPal');
                            if ($vehicle_rental->paypal_transaction_log->payment_type == 'authorized') {
                                $paypal = new \Plugins\Paypal\Services\PayPalService();
                                $voidPayment = $paypal->voidPayment($vehicle_rental->paypal_transaction_log->authorization_id);
                                if (is_object($voidPayment)) {
                                    $transaction_log['payment_type'] = $voidPayment->getState();
                                    $transaction_log['void_id'] = $voidPayment->getId();
                                    if ($transaction_log['payment_type'] == 'voided') {
                                        $is_payment_transaction = true;
                                    }
                                } else if (is_array($voidPayment) && $voidPayment['error']) {
                                    if ($voidPayment['error']['message']) {
                                        $error_msg = $voidPayment['error']['message'];
                                    } else if($voidPayment['error_message']) {
                                        $error_msg = $voidPayment['error_message'];
                                    }
                                }
                                $vehicle_rental->paypal_transaction_log->update($transaction_log);
                            }
                        }
                        if (isPluginEnabled('Sudopays') && !is_null($vehicle_rental->sudopay_transaction_logs)) {
                            $gateway_id = config('constants.ConstPaymentGateways.SudoPay');
                            $sudopayService = new \Plugins\Sudopays\Services\SudopayService();
                            if ($vehicle_rental->sudopay_transaction_logs->status == 'Authorized') {
                                $voidPayment = $sudopayService->voidPayment($vehicle_rental->sudopay_transaction_logs);
                                if (!empty($voidPayment) && ($voidPayment['status'] == 'Voided' || $voidPayment['status'] == 'Canceled')) {
                                    $transaction_log['status'] = $voidPayment['status'];
                                    $is_payment_transaction = true;
                                    $vehicle_rental->sudopay_transaction_logs->update($transaction_log);
                                } else if (is_array($voidPayment) && $voidPayment['error']) {
                                    if ($voidPayment['error']['message']) {
                                        $error_msg = $voidPayment['error']['message'];
                                    } else if($voidPayment['error_message']) {
                                        $error_msg = $voidPayment['error_message'];
                                    }
                                }
                            } elseif ($vehicle_rental->sudopay_transaction_logs->status == 'Captured') {
                                $refundPayment = $sudopayService->refundPayment($vehicle_rental->sudopay_transaction_logs);
                                if (!empty($refundPayment) && $refundPayment['status'] == 'Refunded') {
                                    $transaction_log['status'] = $refundPayment['status'];
                                    $is_payment_transaction = true;
                                    $vehicle_rental->sudopay_transaction_logs->update($transaction_log);
                                } else if (is_array($refundPayment) && $refundPayment['error']) {
                                    if ($refundPayment['error']['message']) {
                                        $error_msg = $refundPayment['error']['message'];
                                    } else if($refundPayment['error_message']) {
                                        $error_msg = $refundPayment['error_message'];
                                    }
                                }
                            }
                        }
                        if (!is_null($vehicle_rental->wallet_transaction_log)) {
                            $gateway_id = config('constants.ConstPaymentGateways.Wallet');
                            if ($vehicle_rental->wallet_transaction_log->payment_type == 'Captured') {
                                $walletService = new \App\Services\WalletService();
                                $is_payment_transaction = $walletService->voidPayment($vehicle_rental);
                            }
                        }
                        if ($is_payment_transaction) {
                            $vehicle_rental_data['item_user_status_id'] = config('constants.ConstItemUserStatus.Expired');
                            $vehicle_rental_data['status_updated_at'] = Carbon::now()->toDateTimeString();
                            $vehicle_rental->update($vehicle_rental_data);
                            $this->updateItemUserCount();
                            //Save transactions
                            $this->transactionService->log(config('constants.ConstUserTypes.Admin'), $vehicle_rental->user_id, config('constants.ConstTransactionTypes.RefundForExpiredRenting'), $vehicle_rental->total_amount, $vehicle_rental->id, 'VehicleRentals', $gateway_id);
                            //Send Mail to Booker and save message contents and messages
                            $this->changeStatusMail($vehicle_rental, $vehicle_rental->item_userable, $vehicle_rental->item_user_status_id, config('constants.ConstItemUserStatus.Expired'));
                        } else {
                            Log::info($error_msg);
                        }
                    } catch (\Exception $e) {
                        throw new \Dingo\Api\Exception\StoreResourceFailedException('Status could not be changed', array($e->getMessage()));
                    }
                }
            }
        }
    }

    public function updateVehicleRental($vehicle_rental_id, $gateway_id)
    {
        $vehicle_rental = VehicleRental::find($vehicle_rental_id);
        $item = $vehicle_rental->item_userable;
        if (!is_null($item) && $vehicle_rental->item_user_status_id == config('constants.ConstItemUserStatus.PaymentPending')) {
            $vehicle_rental_data['item_user_status_id'] = config('constants.ConstItemUserStatus.WaitingForAcceptance');
            if (config('vehicle_rental.is_auto_approve')) {
                $vehicle_rental_data['item_user_status_id'] = config('constants.ConstItemUserStatus.Confirmed');
            }
            $vehicle_rental_data['status_updated_at'] = Carbon::now()->toDateTimeString();
            $vehicle_rental->update($vehicle_rental_data);
            // Status table update count
            $this->updateItemUserCount();
            // Vehicle table update count
            $this->updateVehicleItemUserCount($item->id);
            // User table update count
            $this->updateUserItemUserCount($vehicle_rental->user_id);
            // vehicle owner order count update on user table
            $this->updateUserOrderCount($item->user_id);
            // Update coupon quantity used count
            if (isPluginEnabled('VehicleCoupons')) {
                $this->vehicleCouponService->updateCouponquantity($vehicle_rental->coupon_id);
            }
            // unavailable vehicle dummy record put changes
            $this->vehicleService->addVehicleSearchRecord($item->id, $vehicle_rental_id, $vehicle_rental->item_booking_start_date, $vehicle_rental->item_booking_end_date, 0);

            if (config('vehicle_rental.is_auto_approve')) {
                $this->sendVehicleRentalMail($vehicle_rental, $item);
                $this->changeStatusMail($vehicle_rental, $item, config('constants.ConstItemUserStatus.PaymentPending'), config('constants.ConstItemUserStatus.Confirmed'));
            } else {
                //Send VehicleRental mail and status change mail to host and booker
                $this->sendVehicleRentalMail($vehicle_rental, $item);
                $this->changeStatusMail($vehicle_rental, $item, config('constants.ConstItemUserStatus.PaymentPending'), config('constants.ConstItemUserStatus.WaitingForAcceptance'));
            }
            $this->transactionService->log($vehicle_rental->user_id, config('constants.ConstUserTypes.Admin'), config('constants.ConstTransactionTypes.RentItem'), $vehicle_rental->total_amount, $vehicle_rental->id, 'VehicleRentals', $gateway_id);
        }
    }

    /**
     * @param $vehicle_rental_id
     * @param $gateway_id
     */
    public function updateVoid($vehicle_rental_id, $gateway_id)
    {
        $vehicle_rental = VehicleRental::find($vehicle_rental_id);
        if ($vehicle_rental && !is_null($vehicle_rental->item_userable)) {
            if ($vehicle_rental['item_user_status_id'] == config('constants.ConstItemUserStatus.WaitingForAcceptance')) {
                $vehicle_rental_data['item_user_status_id'] = config('constants.ConstItemUserStatus.CancelledByAdmin');
                $vehicle_rental_data['status_updated_at'] = Carbon::now()->toDateTimeString();
                $vehicle_rental->update($vehicle_rental_data);
                $this->changeStatusMail($vehicle_rental, $vehicle_rental->item_userable, config('constants.ConstItemUserStatus.WaitingForAcceptance'), config('constants.ConstItemUserStatus.CancelledByAdmin'));
                $this->transactionService->log(config('constants.ConstUserTypes.Admin'), $vehicle_rental->user_id, config('constants.ConstTransactionTypes.RefundForRentingCanceledByAdmin'), $vehicle_rental->total_amount, $vehicle_rental->id, 'VehicleRentals', $gateway_id);
            }
        }
    }

    /**
     * @param $vehicle_rental_id
     * @param $gateway_id
     */
    public function updateRefund($vehicle_rental_id, $gateway_id)
    {
        $vehicle_rental = VehicleRental::find($vehicle_rental_id);
        if ($vehicle_rental && !is_null($vehicle_rental->item_userable)) {
            if ($vehicle_rental['item_user_status_id'] == config('constants.ConstItemUserStatus.Confirmed')) {
                $vehicle_rental_data['item_user_status_id'] = config('constants.ConstItemUserStatus.CancelledByAdmin');
                $vehicle_rental_data['status_updated_at'] = Carbon::now()->toDateTimeString();
                $vehicle_rental->update($vehicle_rental_data);
                $this->changeStatusMail($vehicle_rental, $vehicle_rental->item_userable, config('constants.ConstItemUserStatus.Confirmed'), config('constants.ConstItemUserStatus.CancelledByAdmin'));
                $this->transactionService->log(config('constants.ConstUserTypes.Admin'), $vehicle_rental->user_id, config('constants.ConstTransactionTypes.RefundForRentingCanceledByAdmin'), $vehicle_rental->total_amount, $vehicle_rental->id, 'VehicleRentals', $gateway_id);
            }
        }
    }

    /**
     * This updates the status from confirmed to waiting for review status
     */
    public function updateWaitingForReview()
    {
        $expire_date = Carbon::now()->toDateTimeString();
        $vehicle_rentals = VehicleRental::where('item_user_status_id', '=', config('constants.ConstItemUserStatus.Confirmed'))
            ->filterByVehicleRental(false)
            ->where('item_booking_start_date', '<', $expire_date)->get();
        if ($vehicle_rentals) {
            foreach ($vehicle_rentals as $vehicle_rental) {
                if (!is_null($vehicle_rental->item_userable)) {
                    $item_user_data['id'] = $vehicle_rental->id;
                    $item_user_data['item_user_status_id'] = config('constants.ConstItemUserStatus.WaitingForReview');
                    $item_user_data['status_updated_at'] = Carbon::now()->toDateTimeString();
                    try {
                        $vehicle_rental->update($item_user_data);
                        $this->updateItemUserCount();
                        //Send Mail to Booker and save message contents and messages

                        $this->changeStatusMail($vehicle_rental, $vehicle_rental->item_userable, config('constants.ConstItemUserStatus.Confirmed'), config('constants.ConstItemUserStatus.WaitingForReview'));

                    } catch (\Exception $e) {
                        throw new \Dingo\Api\Exception\StoreResourceFailedException('Status could not be changed');
                    }
                }
            }
        }
    }

    /**
     * Update dispute status in item users
     * @param $id
     */
    public function updateDispute($id)
    {
        $vehicle_rental_data['is_dispute'] = 1;
        VehicleRental::where('id', '=', $id)->update($vehicle_rental_data);
    }

    /**
     * Save the checkin status
     * @param $vehicle_rental
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function saveCheckInDetail($vehicle_rental)
    {
        if (!$vehicle_rental) {
            return $this->response->errorNotFound("Invalid Request");
        }
        try {
            $vehicle_rental_data['item_user_status_id'] = config('constants.ConstItemUserStatus.Attended');
            $vehicle_rental_data['status_updated_at'] = Carbon::now()->toDateTimeString();
            $vehicle_rental->update($vehicle_rental_data);
            $this->updateItemUserCount();
            $this->changeStatusMail($vehicle_rental, $vehicle_rental->item_userable, config('constants.ConstItemUserStatus.Confirmed'), config('constants.ConstItemUserStatus.Attended'));
            // insert record into late_payment_detail table
            $this->vehicleRentalLatePaymentDetailService->addRentalDetail($vehicle_rental);
            return response()->json(['Success' => 'VehicleRental has been checked-in successfully'], 200);
        } catch (\Exception $e) {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleRental could not be updated. Please, try again.', array($e->getMessage()));
        }
    }

    /**
     * Save the checkout status and calcualte late payment fee if any
     * @param $vehicle_rental
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function saveCheckoutDetail($vehicle_rental, $claim_request_amount)
    {
        if (!$vehicle_rental) {
            return $this->response->errorNotFound("Invalid Request");
        }
        try {
            // calculate late payment charge
            $late_fee_details = $this->vehicleService->processCheckoutLateFee($vehicle_rental, $claim_request_amount);
            $vehicle_rental_data['item_user_status_id'] = config('constants.ConstItemUserStatus.WaitingForReview');
            $vehicle_rental_data['status_updated_at'] = $late_fee_details['current_date'];
            $vehicle_rental_data['late_fee'] = $late_fee_details['late_checkout_total_fee'];
            $vehicle_rental_data['paid_manual_amount'] = $late_fee_details['late_checkout_total_fee'];
            $vehicle_rental_data['claim_request_amount'] = $claim_request_amount;
            $vehicle_rental_data['paid_deposit_amount'] = $late_fee_details['paid_deposit_amount'];
            $vehicle_rental_data['paid_manual_amount'] = $late_fee_details['paid_manual_amount'];
            $vehicle_rental->update($vehicle_rental_data);
            $this->updateItemUserCount();
            $this->vehicleService->updateTotalAmount($vehicle_rental);
            // clear records in unavailable vehicles
            $this->unavailableVehicleService->clearUnavaialablelist($vehicle_rental->id);
            $this->changeStatusMail($vehicle_rental, $vehicle_rental->item_userable, config('constants.ConstItemUserStatus.Attended'), config('constants.ConstItemUserStatus.WaitingForReview'));
            // insert record into late_payment_detail table
            $this->vehicleRentalLatePaymentDetailService->updateRentalDetail($vehicle_rental, $late_fee_details['total_late_hours']);
            return response()->json(['Success' => 'VehicleRental has been checked-out successfully'], 200);
        } catch (\Exception $e) {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('VehicleRental could not be updated. Please, try again.', array($e->getMessage()));
        }
    }

    /**
     *  automatically delete records from unavailable_vehicles table records once the booking_date completes
     */
    public function autoDeleteUnavailableVehicleRecords()
    {
        $current_date = Carbon::now()->toDateTimeString();
        try {
            UnavailableVehicle::where('end_date', '<', $current_date)->where(['is_dummy' => 0])->delete();
        } catch (\Exception $e) {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Unavaialable Records Could not be deleted. Please, try again.');
        }
    }

    /**
     * this cfunction makes all transactions on complete status
     * @param $vehicle_rental
     */
    public function completeTransactionAmounts($vehicle_rental, $is_from_dispute = 0)
    {
        $walletService = new \App\Services\WalletService();

        // transaction for booker
        if ($vehicle_rental->booker_amount > 0 && !$is_from_dispute) {
            $walletService->updateWalletForUser($vehicle_rental->user_id, $vehicle_rental->booker_amount, $vehicle_rental->id, 'VehicleRentals');
            $this->transactionService->log(config('constants.ConstUserIds.Admin'), $vehicle_rental->user_id, config('constants.ConstTransactionTypes.SecuirtyDepositAmountRefundedToBooker'), $vehicle_rental->booker_amount, $vehicle_rental->id, 'VehicleRentals', config('constants.ConstPaymentGateways.Wallet'));
        }

        //Save transactions for host. Ad transaction logs are moved in common function while change to completed status (specification dispute closed in favour of booker type host amount is set to 0)
        if ($vehicle_rental->host_service_amount > 0) {
            $amount_from_booking = $vehicle_rental->host_service_amount - $vehicle_rental->paid_manual_amount;
            $walletService->updateWalletForUser($vehicle_rental->item_userable->user_id, $amount_from_booking, $vehicle_rental->id, 'VehicleRentals');
            $this->transactionService->log(config('constants.ConstUserIds.Admin'), $vehicle_rental->item_userable->user_id, config('constants.ConstTransactionTypes.RentingHostAmountCleared'), $amount_from_booking, $vehicle_rental->id, 'VehicleRentals', config('constants.ConstPaymentGateways.Wallet'));
        }

        if ($vehicle_rental->paid_manual_amount > 0 && $vehicle_rental->claim_request_amount > 0) {
            // manually paid claim
            $walletService->updateWalletForUser($vehicle_rental->item_userable->user_id, $vehicle_rental->claim_request_amount, $vehicle_rental->id, 'VehicleRentals');
            $this->transactionService->log($vehicle_rental->user_id, $vehicle_rental->item_userable->user_id, config('constants.ConstTransactionTypes.ManualTransferForClaimRequestAmount'), $vehicle_rental->claim_request_amount, $vehicle_rental->id, 'VehicleRentals');
        }
        if ($vehicle_rental->paid_manual_amount > 0 && $vehicle_rental->late_fee > 0) {
            // manually paid late fee
            $this->transactionService->log($vehicle_rental->user_id, $vehicle_rental->item_userable->user_id, config('constants.ConstTransactionTypes.ManualTransferForLateFee'), $vehicle_rental->late_fee, $vehicle_rental->id, 'VehicleRentals');
        }
        // transactions fo admin
        if ($vehicle_rental->admin_commission_amount > 0) {
            $walletService->updateWalletForUser(config('constants.ConstUserIds.Admin'), $vehicle_rental->admin_commission_amount, $vehicle_rental->id, 'VehicleRentals');
            $this->transactionService->log($vehicle_rental->item_userable->user_id, config('constants.ConstUserIds.Admin'), config('constants.ConstTransactionTypes.AdminCommission'), $vehicle_rental->admin_commission_amount, $vehicle_rental->id, 'VehicleRentals', config('constants.ConstPaymentGateways.Wallet'));
        }
    }

    public function autoCompleteUnattendedRecords()
    {
        $expire_date = Carbon::now()->toDateTimeString();
        $vehicle_rentals = VehicleRental::where('item_user_status_id', '=', config('constants.ConstItemUserStatus.Confirmed'))
            ->filterByVehicleRental(false)
            ->where('item_booking_end_date', '<', $expire_date)->get();
        if ($vehicle_rentals) {
            foreach ($vehicle_rentals as $vehicle_rental) {
                if (!is_null($vehicle_rental->item_userable)) {
                    $item_user_data['id'] = $vehicle_rental->id;
                    $item_user_data['item_user_status_id'] = config('constants.ConstItemUserStatus.Completed');
                    $item_user_data['status_updated_at'] = Carbon::now()->toDateTimeString();
                    try {
                        $vehicle_rental->update($item_user_data);
                        $this->updateItemUserCount();
                        // all transactions
                        $this->completeTransactionAmounts($vehicle_rental);
                        //Send Mail to Booker and save message contents and messages
                        $this->changeStatusMail($vehicle_rental, $vehicle_rental->item_userable, config('constants.ConstItemUserStatus.Confirmed'), config('constants.ConstItemUserStatus.Completed'));
                    } catch (\Exception $e) {
                        throw new \Dingo\Api\Exception\StoreResourceFailedException('Status could not be changed');
                    }
                }
            }
        }
    }
}
