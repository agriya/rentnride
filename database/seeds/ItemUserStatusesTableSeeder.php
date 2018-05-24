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
use App\ItemUserStatus;
use Illuminate\Support\Facades\Hash;

class ItemUserStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ItemUserStatus::create([
            'id' => 1,
            'name' => 'Payment Pending',
            'slug' => 'payment-pending',
            'description' => 'Vehicle Rental is in payment pending status.',
            'display_order' => 1
        ]);
        ItemUserStatus::create([
            'id' => 2,
            'name' => 'Waiting For Acceptance',
            'slug' => 'waiting-for-acceptance',
            'description' => 'Vehicle Rental was made by the ##BOOKER## on ##CREATED_DATE##. Waiting for Host ##HOSTER## to accept the order.',
            'display_order' => 2
        ]);
        ItemUserStatus::create([
            'id' => 3,
            'name' => 'Rejected',
            'slug' => 'rejected',
            'description' => 'Vehicle Rental was rejected by the ##HOSTER##. Vehicle Rental amount has been refunded.',
            'display_order' => 5
        ]);
        ItemUserStatus::create([
            'id' => 4,
            'name' => 'Cancelled',
            'slug' => 'cancelled',
            'description' => 'Vehicle Rental was cancelled by ##BOOKER##. Vehicle Rental amount has been refunded based on cancellation policies.',
            'display_order' => 4
        ]);
        ItemUserStatus::create([
            'id' => 5,
            'name' => 'Cancelled By Admin',
            'slug' => 'cancelled-by-admin',
            'description' => 'Vehicle Rental was cancelled by Administrator. Vehicle Rental amount has been refunded based on cancellation policies.',
            'display_order' => 13
        ]);
        ItemUserStatus::create([
            'id' => 6,
            'name' => 'Expired',
            'slug' => 'expired',
            'description' => 'expired Vehicle Rental was expired due to non acceptance by the host ##HOSTER##. Vehicle Rental amount has been refunded.',
            'display_order' => 6
        ]);
        ItemUserStatus::create([
            'id' => 7,
            'name' => 'Confirmed',
            'slug' => 'confirmed',
            'description' => 'Vehicle Rental was accepted by ##HOSTER## on ##ACCEPTED_DATE##.',
            'display_order' => 3
        ]);
        ItemUserStatus::create([
            'id' => 8,
            'name' => 'Waiting for Review',
            'slug' => 'waiting-for-review',
            'description' => '##BOOKER## has checked out.',
            'display_order' => 8
        ]);
        ItemUserStatus::create([
            'id' => 9,
            'name' => 'Booker Reviewed',
            'slug' => 'booker-reviewed',
            'description' => 'Booker reviewed.',
            'display_order' => 9
        ]);
        ItemUserStatus::create([
            'id' => 10,
            'name' => 'Host Reviewed',
            'slug' => 'host-reviewed',
            'description' => 'Host reviewed.',
            'display_order' => 10
        ]);
        ItemUserStatus::create([
            'id' => 11,
            'name' => 'Completed',
            'slug' => 'completed',
            'description' => 'Order completed.',
            'display_order' => 12
        ]);
        ItemUserStatus::create([
            'id' => 12,
            'name' => 'Attended',
            'slug' => 'attended',
            'description' => 'Attended.',
            'display_order' => 7
        ]);
        ItemUserStatus::create([
            'id' => 13,
            'name' => 'Waiting For Payment Cleared',
            'slug' => 'waiting-for-payment-cleared',
            'description' => 'Waiting For Payment Cleared.',
            'display_order' => 11
        ]);
        ItemUserStatus::create([
            'id' => 14,
            'name' => 'Private Note',
            'slug' => 'private-note',
            'description' => 'Private Note.',
            'display_order' => 14
        ]);
    }
}
