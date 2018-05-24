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
use App\TransactionType;
use Illuminate\Support\Facades\Hash;

class TransactionTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TransactionType::create([
            'id' => 1,
            'name' => 'Amount added to wallet',
            'transaction_type_group_id' => 1,
            'is_credit' => 1,
            'is_credit_to_receiver' => 0,
            'is_credit_to_admin' => 1,
            'message' => 'Amount added to wallet',
            'message_for_receiver' => '',
            'message_for_admin' => '##USER## added amount to own wallet',
            'transaction_variables' => 'USER'
        ]);
        /*TransactionType::create([
            'id' => 2,
            'name' => 'Booked a item',
            'transaction_type_group_id' => 3,
            'is_credit' => 0,
            'is_credit_to_receiver' => 1,
            'is_credit_to_admin' => 0,
            'message' => 'Booked ###ORDER_NO## a item ##ITEM##',
            'message_for_receiver' => '##BOOKER## booked ###ORDER_NO## item ##ITEM##',
            'message_for_admin' => '##BOOKER## booked ###ORDER_NO## a item ##ITEM##',
            'transaction_variables' => 'BOOKER, ITEM, ORDER_NO'
        ]);
        TransactionType::create([
            'id' => 3,
            'name' => 'Refund for expired booking',
            'transaction_type_group_id' => 3,
            'is_credit' => 0,
            'is_credit_to_receiver' => 1,
            'is_credit_to_admin' => 0,
            'message' => 'Vehicle Rental ###ORDER_NO## expired for item ##ITEM##',
            'message_for_receiver' => 'Vehicle Rental ###ORDER_NO## expired for item ##ITEM##',
            'message_for_admin' => 'Vehicle Rental ###ORDER_NO## expired for item ##ITEM##',
            'transaction_variables' => 'ITEM, ORDER_NO'
        ]);
        TransactionType::create([
            'id' => 4,
            'name' => 'Refund for rejected booking',
            'transaction_type_group_id' => 3,
            'is_credit' => 0,
            'is_credit_to_receiver' => 1,
            'is_credit_to_admin' => 0,
            'message' => '##HOST## rejected booking ###ORDER_NO## for item ##ITEM##',
            'message_for_receiver' => 'You have rejected booking ###ORDER_NO## for item ##ITEM##',
            'message_for_admin' => '##HOST## rejected booking ###ORDER_NO## for item ##ITEM##',
            'transaction_variables' => 'ITEM, ORDER_NO, HOST'
        ]);
        TransactionType::create([
            'id' => 5,
            'name' => 'Refund for cancelled booking',
            'transaction_type_group_id' => 3,
            'is_credit' => 0,
            'is_credit_to_receiver' => 1,
            'is_credit_to_admin' => 0,
            'message' => 'Cancelled booking ###ORDER_NO## for item ##ITEM##',
            'message_for_receiver' => 'Cancelled booking ###ORDER_NO## for item ##ITEM##',
            'message_for_admin' => 'Cancelled booking ###ORDER_NO## for item ##ITEM##',
            'transaction_variables' => 'ITEM, ORDER_NO'
        ]);
        TransactionType::create([
            'id' => 6,
            'name' => 'Refund for admin cancelled booking',
            'transaction_type_group_id' => 3,
            'is_credit' => 0,
            'is_credit_to_receiver' => 1,
            'is_credit_to_admin' => 0,
            'message' => 'Administrator cancelled booking ###ORDER_NO## for item ##ITEM##',
            'message_for_receiver' => 'Administrator cancelled booking ###ORDER_NO## for item ##ITEM##',
            'message_for_admin' => 'Cancelled booking ###ORDER_NO## for item ##ITEM##',
            'transaction_variables' => 'ITEM, ORDER_NO'
        ]);
        TransactionType::create([
            'id' => 7,
            'name' => 'Vehicle Rental host amount cleared',
            'transaction_type_group_id' => 3,
            'is_credit' => 0,
            'is_credit_to_receiver' => 1,
            'is_credit_to_admin' => 0,
            'message' => 'Vehicle Rental ###ORDER_NO## amount cleared to ##HOST## for item ##ITEM##',
            'message_for_receiver' => 'Vehicle Rental ###ORDER_NO## amount cleared for item ##ITEM##',
            'message_for_admin' => 'Vehicle Rental ###ORDER_NO## amount cleared to ##HOST## for item ##ITEM##',
            'transaction_variables' => 'ITEM, ORDER_NO, HOST'
        ]);*/
        TransactionType::create([
            'id' => 8,
            'name' => 'Rent a item',
            'transaction_type_group_id' => 4,
            'is_credit' => 0,
            'is_credit_to_receiver' => 1,
            'is_credit_to_admin' => 0,
            'message' => 'Rent ###ORDER_NO## a item ##ITEM##',
            'message_for_receiver' => '##BOOKER## booked ###ORDER_NO## item ##ITEM##',
            'message_for_admin' => '##BOOKER## booked ###ORDER_NO## a item ##ITEM##',
            'transaction_variables' => 'BOOKER, ITEM, ORDER_NO'
        ]);
        TransactionType::create([
            'id' => 9,
            'name' => 'Refund for expired renting',
            'transaction_type_group_id' => 4,
            'is_credit' => 0,
            'is_credit_to_receiver' => 1,
            'is_credit_to_admin' => 0,
            'message' => 'renting ###ORDER_NO## expired for item ##ITEM##',
            'message_for_receiver' => 'renting ###ORDER_NO## expired for item ##ITEM##',
            'message_for_admin' => 'renting ###ORDER_NO## expired for item ##ITEM##',
            'transaction_variables' => 'ITEM, ORDER_NO'
        ]);
        TransactionType::create([
            'id' => 10,
            'name' => 'Refund for rejected renting',
            'transaction_type_group_id' => 4,
            'is_credit' => 0,
            'is_credit_to_receiver' => 1,
            'is_credit_to_admin' => 0,
            'message' => 'You have rejected renting ###ORDER_NO## for item ##ITEM##',
            'message_for_receiver' => '##HOST## rejected renting ###ORDER_NO## for item ##ITEM##',
            'message_for_admin' => '##HOST## rejected renting ###ORDER_NO## for item ##ITEM##',
            'transaction_variables' => 'ITEM, ORDER_NO, HOST'
        ]);
        TransactionType::create([
            'id' => 11,
            'name' => 'Refund for cancelled renting',
            'transaction_type_group_id' => 4,
            'is_credit' => 0,
            'is_credit_to_receiver' => 1,
            'is_credit_to_admin' => 0,
            'message' => 'Cancelled renting ###ORDER_NO## for item ##ITEM##',
            'message_for_receiver' => 'Cancelled renting ###ORDER_NO## for item ##ITEM##',
            'message_for_admin' => 'Cancelled booking ###ORDER_NO## for item ##ITEM##',
            'transaction_variables' => 'ITEM, ORDER_NO'
        ]);
        TransactionType::create([
            'id' => 12,
            'name' => 'Refund for admin cancelled renting',
            'transaction_type_group_id' => 4,
            'is_credit' => 0,
            'is_credit_to_receiver' => 1,
            'is_credit_to_admin' => 0,
            'message' => 'Administrator cancelled renting ###ORDER_NO## for item ##ITEM##',
            'message_for_receiver' => 'Administrator cancelled renting ###ORDER_NO## for item ##ITEM##',
            'message_for_admin' => 'Cancelled renting ###ORDER_NO## for item ##ITEM##',
            'transaction_variables' => 'ITEM, ORDER_NO'
        ]);
        TransactionType::create([
            'id' => 13,
            'name' => 'Renting host amount cleared',
            'transaction_type_group_id' => 3,
            'is_credit' => 0,
            'is_credit_to_receiver' => 1,
            'is_credit_to_admin' => 0,
            'message' => 'Vehicle Rental ###ORDER_NO## amount cleared to ##HOST## for item ##ITEM##',
            'message_for_receiver' => 'Vehicle Rental ###ORDER_NO## amount cleared for item ##ITEM##',
            'message_for_admin' => 'Vehicle Rental ###ORDER_NO## amount cleared to ##HOST## for item ##ITEM##',
            'transaction_variables' => 'ITEM, ORDER_NO, HOST'
        ]);
        TransactionType::create([
            'id' => 14,
            'name' => 'Cash withdrawal request',
            'transaction_type_group_id' => 2,
            'is_credit' => 0,
            'is_credit_to_receiver' => 0,
            'is_credit_to_admin' => 0,
            'message' => 'Cash withdrawal request made by you',
            'message_for_receiver' => '',
            'message_for_admin' => 'Cash withdrawal request made by ##USER##',
            'transaction_variables' => ''
        ]);
        TransactionType::create([
            'id' => 15,
            'name' => 'Cash withdrawal request approved',
            'transaction_type_group_id' => 2,
            'is_credit' => 0,
            'is_credit_to_receiver' => 0,
            'is_credit_to_admin' => 0,
            'message' => 'Your cash withdrawal request approved by Administrator',
            'message_for_receiver' => '',
            'message_for_admin' => 'You (Administrator) have approved ##USER## cash withdrawal request',
            'transaction_variables' => 'USER'
        ]);
        TransactionType::create([
            'id' => 16,
            'name' => 'Cash withdrawal request rejected',
            'transaction_type_group_id' => 2,
            'is_credit' => 1,
            'is_credit_to_receiver' => 0,
            'is_credit_to_admin' => 1,
            'message' => 'Amount refunded for rejected cash withdrawal request',
            'message_for_receiver' => '',
            'message_for_admin' => 'Amount refunded to ##USER## for rejected cash withdrawal request',
            'transaction_variables' => 'USER'
        ]);
        TransactionType::create([
            'id' => 17,
            'name' => 'Cash withdrawal request paid',
            'transaction_type_group_id' => 2,
            'is_credit' => 1,
            'is_credit_to_receiver' => 1,
            'is_credit_to_admin' => 1,
            'message' => 'Cash withdraw request amount paid to you',
            'message_for_receiver' => '',
            'message_for_admin' => 'Cash withdraw request amount paid to ##USER##',
            'transaction_variables' => 'USER'
        ]);
        TransactionType::create([
            'id' => 18,
            'name' => 'Cash withdrawal request failed',
            'transaction_type_group_id' => 2,
            'is_credit' => 0,
            'is_credit_to_receiver' => 1,
            'is_credit_to_admin' => 0,
            'message' => 'Amount refunded for failed cash withdrawal request',
            'message_for_receiver' => '',
            'message_for_admin' => 'Amount refunded to ##USER## for failed cash withdrawal request',
            'transaction_variables' => 'USER'
        ]);
        TransactionType::create([
            'id' => 19,
            'name' => 'Admin add fund to wallet',
            'transaction_type_group_id' => 1,
            'is_credit' => 1,
            'is_credit_to_receiver' => 0,
            'is_credit_to_admin' => 1,
            'message' => 'Administrator added fund to your wallet',
            'message_for_receiver' => 'Administrator added fund to your wallet',
            'message_for_admin' => 'Added fund to ##USER## wallet',
            'transaction_variables' => 'USER'
        ]);
        TransactionType::create([
            'id' => 20,
            'name' => 'Admin deduct fund from wallet',
            'transaction_type_group_id' => 1,
            'is_credit' => 0,
            'is_credit_to_receiver' => 0,
            'is_credit_to_admin' => 1,
            'message' => 'Administrator deducted fund from your wallet',
            'message_for_receiver' => 'Administrator deducted fund from your wallet',
            'message_for_admin' => 'Deducted fund from ##USER## wallet',
            'transaction_variables' => 'USER'
        ]);
        TransactionType::create([
            'id' => 21,
            'name' => 'Refund For Specification Dispute',
            'transaction_type_group_id' => 5,
            'is_credit' => 0,
            'is_credit_to_receiver' => 1,
            'is_credit_to_admin' => 0,
            'message' => 'Specifications dispute resolved favor to ##BOOKER## for Item ##ITEM##, booking ###ORDER_NO##.',
            'message_for_receiver' => 'Specifications dispute resolved favor to you for Item ##ITEM##, booking ###ORDER_NO##.',
            'message_for_admin' => 'Specifications dispute resolved favor to ##BOOKER## for Item ##ITEM##, booking ###ORDER_NO##.',
            'transaction_variables' => 'BOOKER, ITEM, ORDER_NO'
        ]);
        TransactionType::create([
            'id' => 22,
            'name' => 'Refund for wallet',
            'transaction_type_group_id' => 1,
            'is_credit' => 0,
            'is_credit_to_receiver' => 0,
            'is_credit_to_admin' => 0,
            'message' => 'Amount refunded to your account',
            'message_for_receiver' => 'Amount refunded to your account',
            'message_for_admin' => 'Amount refunded to ##USER## account',
            'transaction_variables' => 'USER'
        ]);
        TransactionType::create([
            'id' => 23,
            'name' => 'Vehicle Listing Fee Paid',
            'transaction_type_group_id' => 4,
            'is_credit' => 0,
            'is_credit_to_receiver' => 0,
            'is_credit_to_admin' => 1,
            'message' => '##ITEM## Vehicle Listing Fee Paid',
            'message_for_receiver' => '##ITEM## vehicle listing fee paid',
            'message_for_admin' => '##ITEM## vehicle listing fee paid',
            'transaction_variables' => 'ITEM'
        ]);
        TransactionType::create([
            'id' => 24,
            'name' => 'Security Deposit Amount Sent To Host',
            'transaction_type_group_id' => 5,
            'is_credit' => 1,
            'is_credit_to_receiver' => 0,
            'is_credit_to_admin' => 1,
            'message' => 'Security deposit dispute resolved favor to ##HOST## for Item ##ITEM##, booking# ##ORDER_NO##.',
            'message_for_receiver' => 'Security deposit dispute resolved favor to you for Item ##ITEM##, booking# ##ORDER_NO##.',
            'message_for_admin' => 'Security deposit dispute resolved favor to ##HOST## for Item ##ITEM##, booking# ##ORDER_NO##.',
            'transaction_variables' => 'ITEM, ORDER_NO, HOST'
        ]);
        TransactionType::create([
            'id' => 25,
            'name' => 'Secuirty Deposit Amount Refunded To Booker',
            'transaction_type_group_id' => 5,
            'is_credit' => 0,
            'is_credit_to_receiver' => 1,
            'is_credit_to_admin' => 0,
            'message' => 'Security deposit dispute resolved favor to ##BOOKER## for Item ##ITEM##, booking# ##ORDER_NO##.',
            'message_for_receiver' => 'Security deposit dispute resolved favor to you for Item ##ITEM##, booking# ##ORDER_NO##.',
            'message_for_admin' => 'Security deposit amount refunded to ##BOOKER## for Item ##ITEM##, booking# ##ORDER_NO##.',
            'transaction_variables' => 'ITEM, ORDER_NO, BOOKER'
        ]);

        TransactionType::create([
            'id' => 26,
            'name' => 'Manual Transfer For Claim Request Amount',
            'transaction_type_group_id' => 5,
            'is_credit' => 1,
            'is_credit_to_receiver' => 0,
            'is_credit_to_admin' => 1,
            'message' => 'Manually transferred claiming amount to ##HOST## for Item ##ITEM##, booking# ##ORDER_NO##.',
            'message_for_receiver' => 'Manually transferred for claiming amount to you for Item ##ITEM##, booking# ##ORDER_NO##.',
            'message_for_admin' => 'Manually transferred for claiming amount to ##HOST## for Item ##ITEM##, booking# ##ORDER_NO##.',
            'transaction_variables' => 'HOST, ITEM, ORDER_NO'
        ]);

        TransactionType::create([
            'id' => 27,
            'name' => 'Manual Transfer For Late Fee Amount',
            'transaction_type_group_id' => 4,
            'is_credit' => 0,
            'is_credit_to_receiver' => 1,
            'is_credit_to_admin' => 0,
            'message' => 'Manually transferred late fee amount to ##HOST## for Item ##ITEM##, booking# ##ORDER_NO##.',
            'message_for_receiver' => 'Manually transferred late fee amount to you for Item ##ITEM##, booking# ##ORDER_NO##.',
            'message_for_admin' => 'Manually transferred late fee amount to ##HOST## for Item ##ITEM##, booking# ##ORDER_NO##.',
            'transaction_variables' => 'HOST, ITEM, ORDER_NO'
        ]);

        TransactionType::create([
            'id' => 28,
            'name' => 'Admin Commision Payment',
            'transaction_type_group_id' => 4,
            'is_credit' => 0,
            'is_credit_to_receiver' => 0,
            'is_credit_to_admin' => 1,
            'message' => 'Admin Commission for Item ##ITEM##, booking# ##ORDER_NO##.',
            'message_for_receiver' => 'Admin Commission for Item ##ITEM##, booking# ##ORDER_NO##.',
            'message_for_admin' => 'Admin Commission for Item ##ITEM##, booking# ##ORDER_NO##.',
            'transaction_variables' => 'ITEM, ORDER_NO'
        ]);

    }
}