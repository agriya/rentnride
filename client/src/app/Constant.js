var app = angular.module('BookorRent.Constant', [])
    .constant('GENERAL_CONFIG', {
        'api_url': '/bookorrent/public/api',
        'preferredLanguage': 'en',
    })
    .constant('ConstWithdrawalStatuses', {
        'Pending': 1,
        'UnderProcess': 2,
        'Rejected': 3,
        'Failed': 4,
        'Success': 5
    })
    .constant('ConstSocialLogin', {
        'User':10,
        'Facebook': 1,
        'Twitter': 2,
        'Google': 3,
        'Github': 4
    }).constant('ConstThumb', {
        'user' : {
            'small' : {
                'width': 28,
                'height': 28
            },
            'medium' : {
                'width': 110,
                'height': 110
            },
        }

    })
	.constant('ConstItemUserStatus', {
        'PaymentPending' : 1,
        'WaitingForAcceptance' : 2,
        'Rejected' : 3,
        'Cancelled' : 4,
        'CancelledByAdmin' : 5,
        'Expired' : 6,
        'Confirmed' : 7,
        'WaitingForReview' : 8,
        'BookerReviewed' : 9,
        'HostReviewed' : 10,
        'Completed' : 11,
        'Attended' : 12,
        'WaitingForPaymentCleared' : 13,
        'PrivateConversation':14

    })
    .constant('ConstPaymentGateways', {
        'Wallet' :1,
        'SudoPay' :2,
        'PayPal':3
    })
	.constant('ConstDisputeClosedType', {
        'SpecificationFavourBookerRefund' : 1,
        'SpecificationFavourHost' : 2,
        'SpecificationResponseFavourBooker' : 3,
        'FeedbackFavourBooker' : 4,
        'FeedbackFavourHost' : 5,
        'FeedbackResponseFavourHost' : 6,
        'SecurityFavourBooker' : 7,
        'SecurityFavourHost' : 8,
        'SecurityResponseFavourHost' : 9
    })
    .constant('ConstDiscountTypes', {
        'percentage':1,
        'amount':2
    })
    .constant('ConstDurationTypes', {
        'per_day':1,
        'per_rental':2
    });
