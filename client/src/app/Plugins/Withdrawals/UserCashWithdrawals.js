(function (module) {
    /**
     * @ngdoc controller
     * @name Withdrawals.controller:UserCashWithdrawalsController
     * @description
     * This is userCashWithdrawalsController having the methods init(), setMetaData(), userCashWithdrawSubmit() and it defines the user cash withdraw relted funtions.
     **/
    module.controller('UserCashWithdrawalsController', function ($state, $scope, $http, Flash, $filter, UserCashWithdrawalsFactory, AuthFactory, $rootScope, $location, MoneyTransferAccountsFactory) {
        var model = this;
        $rootScope.pageTitle = $rootScope.settings['site.name'] + " | " + $filter("translate")("Withdrawals");
        model.moneyTransferList = [];
        $scope.withdrawals = [];
        $scope.withdrawals.minimum_withdraw_amount = $rootScope.settings['user.minimum_withdraw_amount'];
        $scope.withdrawals.maximum_withdraw_amount = $rootScope.settings['user.maximum_withdraw_amount'];
        $scope.infoMessage = '';
        $scope.user_available_balance = '';
        $scope.maxSize = 5;
        model.userCashWithdrawalsList = [];
        model.userCashWithdrawSubmit = userCashWithdrawSubmit;
        model.moneyTransfer = new UserCashWithdrawalsFactory();
        var user_id = $rootScope.auth ? parseInt($rootScope.auth.id) : '';
        /**
         * @ngdoc method
         * @name setMetaData
         * @methodOf Withdrawals.controller:UserCashWithdrawalsController
         * @description
         * This method will set the meta data's dynamically by using the angular.element
         * @returns {Element} New meta data element.
         **/
        $scope.setMetaData = function () {
            var pageTitle = $filter("translate")("User Cash Withdrawals");
            var fullUrl = $location.absUrl();
            var appUrl = $rootScope.settings['scheme_name'] + ":/" + $location.url();
            angular.element('html head meta[property="og:title"], html head meta[name="twitter:title"]').attr("content", $rootScope.settings['site.name'] + " | " + pageTitle);
            angular.element('meta[property="al:ios:url"], meta[property="al:ipad:url"], meta[property="al:android:url"], meta[property="al:windows_phone:url"], html head meta[name="twitter:app:url:iphone"], html head meta[name="twitter:app:url:ipad"], html head meta[name="twitter:app:url:googleplay"]').attr('content', appUrl);
            angular.element('meta[property="og:url"]').attr('content', fullUrl);
        };
        /**
         * @ngdoc method
         * @name getMoneyTransferList
         * @methodOf Withdrawals.controller:UserCashWithdrawalsController
         * @description
         * This method handles the form which is used for contact, and add contact details.
         * @param {integer} FormDetails Contact form details.
         * @returns {Array} Success or failure message.
         **/
        $scope.getMoneyTransferList = function () {
            MoneyTransferAccountsFactory.list()
                .$promise
                .then(function (response) {
                    model.moneyTransferList = response.data;
                });
        };

        var params = {};
        /**
         * @ngdoc method
         * @name getUserCashWithdrawals
         * @methodOf Withdrawals.controller:UserCashWithdrawalsController
         * @description
         * This method will be used in get withdraw request.
         **/
        $scope.getUserCashWithdrawals = function () {
            params.page = $scope.currentPage;
            UserCashWithdrawalsFactory.list(params)
                .$promise
                .then(function (response) {
                    //$scope.userCashWithdrawalsList = response.data;
                    model.userCashWithdrawalsList = response.data;
                    $scope._metadata = response.meta.pagination;
                });
        };

        /**
         * @ngdoc method
         * @name userCashWithdrawSubmit
         * @methodOf Withdrawals.controller:UserCashWithdrawalsController
         * @description
         * This method will be used in submitting a request for withdraw.
         **/
        function userCashWithdrawSubmit($valid) {
            if ($valid) {
                if($scope.user_available_balance >= model.moneyTransfer.amount) {
                    model.moneyTransfer.$save()
                        .then(function (response) {
                            Flash.set($filter("translate")("Your request submitted successfully."), "success", false);
                            $state.reload('user_cash_withdrawals');
                        })
                        .catch(function (error) {
                            Flash.set("Withdraw request could not be added", "error", false);
                            $scope.amountErr = '';
                            var errorResponse = error.data.errors;
                            if (errorResponse.amount) {
                                $scope.amountErr = $filter("translate")(errorResponse.amount[0]);
                            }
                        })
                        .finally();
                }else{
                    Flash.set("You Dont have sufficient amount in your wallet.", "error", false);
                }
            }
        }
        /**
         * @ngdoc method
         * @name init
         * @methodOf Withdrawals.controller:UserCashWithdrawalsController
         * @description
         * This method will initialize the meta data and functionalities.
         **/
        $scope.init = function() {
            $scope.setMetaData();
            $scope.currentPage = ($scope.currentPage !== undefined) ? parseInt($scope.currentPage) : 1;
            AuthFactory.fetch({}).$promise
                .then(function (response) {
                    $scope.user_available_balance = response.available_wallet_amount;
                });
            $scope.getUserCashWithdrawals();
            $scope.getMoneyTransferList();
        };
        $scope.init();
        $scope.paginate = function(pageno) {
            $scope.currentPage = parseInt($scope.currentPage);
            $scope.init();
        };

    });

}(angular.module("BookorRent.Withdrawals")));
