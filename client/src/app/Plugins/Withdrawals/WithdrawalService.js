(function (module) {
    /**
     * @ngdoc service
     * @name Withdrawals.UserCashWithdrawalsFactory
     * @description
     * UserCashWithdrawalsFactory used in listing the user cash withdrawal requests and submitting a withdraw request.
     * @param {string} UserCashWithdrawalsFactory The name of the factory
     * @param {function()} function It uses get method for listing, post method for save and defines the url.
     * @param {string} url Base url accessed in GENERAL_CONFIG.
     * @returns {object} The service contains these actions:
     *
     *      {
	 *			'save':   {method:'POST'},
	 *			'list':   {method:'GET'}
	 *		};
     */
    module.factory('UserCashWithdrawalsFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(GENERAL_CONFIG.api_url + '/user_cash_withdrawals', {}, {
                'save': {
                    method: 'POST'
                },
                'list': {
                    method: 'GET'
                }
            }
        );
    });
    /**
     * @ngdoc service
     * @name Withdrawals.MoneyTransferAccountsFactory
     * @description
     * MoneyTransferAccountsFactory is used in money transfer accounts
     * @param {string} MoneyTransferAccountsFactory The name of the factory
     * @param {function()} function It uses get method for listing, post method for save and defines the url
     * @param {string} url Base url accessed in GENERAL_CONFIG.
     * @returns {object} The service contains these actions:
     *
     *      {
	 *			'save':   {method:'POST'},
	 *			'list':   {method:'GET'}
	 *		};
     */
    module.factory('MoneyTransferAccountsFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(GENERAL_CONFIG.api_url + '/money_transfer_accounts', {}, {
            'list': {
                method: 'GET'
            },
            'save': {
                method: 'POST'
            }

        });
    });
    /**
     * @ngdoc service
     * @name Withdrawals.MoneyTransferAccountFactory
     * @description
     * MoneyTransferAccountFactory is used in delete the money transfer accounts
     * @param {string} MoneyTransferAccountFactory The name of the factory
     * @param {function()} function It uses delete method, and returns the url
     * @param {string} url Base url accessed in GENERAL_CONFIG.
     * @returns {object} The service contains these actions:
     *
     *      {
	 *			'delete':   {method:'DELETE'}
	 *		};
     */
    module.factory('MoneyTransferAccountFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(GENERAL_CONFIG.api_url + '/money_transfer_accounts/:id', {
                id: '@id'
            }, {
                'delete': {
                    method: 'DELETE'
                }
            }
        );
    });
    /**
     * @ngdoc service
     * @name Withdrawals.MoneyTransferAccountPrimaryFactory
     * @description
     * MoneyTransferAccountPrimaryFactory is used in money transfer account primary
     * @param {string} MoneyTransferAccountPrimaryFactory The name of the factory
     * @param {function()} function It uses get method for primary, and returns the url
     * @param {string} url Base url accessed in GENERAL_CONFIG.
     * @returns {object} The service contains these actions:
     *
     *      {
	 *			'primary':   {method:'GET'},
	 *		};
     */
    module.factory('MoneyTransferAccountPrimaryFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(GENERAL_CONFIG.api_url + '/money_transfer_accounts/:id/primary', {
                id: '@id'
            }, {
                'primary': {
                    method: 'GET'
                }
            }
        );
    });


})(angular.module('BookorRent.Withdrawals'));
