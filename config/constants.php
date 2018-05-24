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
 
return [
    'ConstUserTypes' => [
        'Admin' => 1,
        'User' => 2
    ],
    'ConstAttachment' => [
        'UserAvatar' => 1,
        'VehicleAvatar' => 2
    ],
    'ConstTransactionTypes' => [
        'AddedToWallet' => 1,
        /*'BookItem' => 2,
        'RefundForExpiredBooking' => 3,
        'RefundForRejectedBooking' => 4,
        'RefundForCanceledBooking' => 5,
        'RefundForBookingCanceledByAdmin' => 6,
        'BookingHostAmountCleared' => 7,*/
        'RentItem' => 8,
        'RefundForExpiredRenting' => 9,
        'RefundForRejectedRenting' => 10,
        'RefundForCanceledRenting' => 11,
        'RefundForRentingCanceledByAdmin' => 12,
        'RentingHostAmountCleared' => 13,
        'CashWithdrawalRequest' => 14,
        'CashWithdrawalRequestApproved' => 15,
        'CashWithdrawalRequestRejected' => 16,
        'CashWithdrawalRequestPaid' => 17,
        'CashWithdrawalRequestFailed' => 18,
        'AdminAddFundToWallet' => 19,
        'AdminDeductFundFromWallet' => 20,
        'RefundForSpecificationDispute' => 21,
        'RefundForWallet' => 22,
        'VehicleListingFee' => 23,
        'SecurityDepositAmountSentToHost' => 24,
        'SecuirtyDepositAmountRefundedToBooker' => 25,
        'ManualTransferForClaimRequestAmount' => 26,
        'ManualTransferForLateFee' => 27,
        'AdminCommission' => 28
    ],
    'ConstWithdrawalStatus' => [
        'Pending' => 1,
        'Rejected' => 2,
        'Success' => 3
    ],
    'ConstSocialLogin' => [
        'User' => 10,
        'Facebook' => 1,
        'Twitter' => 2,
        'Google' => 3,
        'Github' => 4
    ],
    'Security.salt' => '2dd8271e35f1f7ee5aec1ca909d46dce7c6aec7e',
    'token_secret' => '258222255666514df22222',
    'thumb' => [
        'user' => [
            'small' => [
                'width' => 28,
                'height' => 28
            ],
            'smallmedium' => [
                'width' => 64,
                'height' => 64
            ],
            'medium' => [
                'width' => 90,
                'height' => 90
            ],
            'largemedium' => [
                'width' => 110,
                'height' => 110
            ],
			'large' => [
                'width' => 157,
                'height' => 157
            ]
			
        ],
        'vehicle' => [
            'small' => [
                'width' => 28,
                'height' => 28
            ],
            'medium' => [
                'width' => 212,
                'height' => 134
            ],
            'largemedium' => [
                'width' => 242,
                'height' => 162
            ],
            'large' => [
                'width' => 1366,
                'height' => 768
            ],
	    'smallmedium' => [
		'width'=> 360,
		'height'=> 240
	    ]
        ]
    ],
    'ConstItemUserStatus' => [
        'PaymentPending' => 1,
        'WaitingForAcceptance' => 2,
        'Rejected' => 3,
        'Cancelled' => 4,
        'CancelledByAdmin' => 5,
        'Expired' => 6,
        'Confirmed' => 7,
        'WaitingForReview' => 8,
        'BookerReviewed' => 9,
        'HostReviewed' => 10,
        'Completed' => 11,
        'Attended' => 12,
        'WaitingForPaymentCleared' => 13,
        'PrivateConversation' => 14
    ],
    'ConstBookingTypes' => [
        'Booking' => 1
    ],
    'ConstMessageFolder' => [
        'Inbox' => 1,
        'SentMail' => 2,
        'Drafts' => 3,
        'Spam' => 4,
        'Trash' => 5
    ],
    'ConstPaymentGateways' => [
        'Wallet' => 1,
        'SudoPay' => 2,
        'PayPal' => 3
    ],
    'ConstPaypalGatewaysProcess' => [
        'sale' => 'sale',
        'authorize' => 'authorize'
    ],
    'ConstBrandType' => [
        'TransparentBranding' => 1,
        'VisibleBranding' => 2,
        'AnyBranding' => 3
    ],
    'ConstDiscountTypes' => [
        'percentage' => 1,
        'amount' => 2
    ],
    'ConstPageLimit' => 20,
    'ConstUserIds' => [
        'Admin' => 1
    ],
    'ConstTransactionTypeGroups' => [
        'Wallet' => 1,
        'CashWithdrawal' => 2,
        'Booking' => 3,
        'Renting' => 4,
        'Dispute' => 5
    ],
    'ConstDisputeStatuses' => [
        'Open' => 1,
        'UnderDiscussion' => 2,
        'WaitingAdministratorDecision' => 3,
        'Closed' => 4
    ],
    'ConstDisputeTypes' => [
        'Specification' => 1,
        'Feedback' => 2,
        'Security' => 3
    ],
    'ConstDisputeClosedTypes' => [
        'SpecificationFavourBookerRefund' => 1,
        'SpecificationFavourHost' => 2,
        'SpecificationResponseFavourBooker' => 3,
        'FeedbackFavourBooker' => 4,
        'FeedbackFavourHost' => 5,
        'FeedbackResponseFavourHost' => 6,
        'SecurityFavourBooker' => 7,
        'SecurityFavourHost' => 8,
        'SecurityResponseFavourHost' => 9
    ],
    'ConstSettingCategories' => [
        'Analytics' => 3,
        'Wallet' => 6,
        'Withdrawal' => 7,
        'Banner' => 11,
        'Sudopay' => 12,
        'PayPal' => 13,
        'Plugins' => 14,
        'Vehicles' => 15,
        'VehicleRentals' => 16,
        'Disputes' => 17
    ],
    'ConstDurationTypes' => [
        'per_day' => 1,
        'per_rental' => 2
    ]
];