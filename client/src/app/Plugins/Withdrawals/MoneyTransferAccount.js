(function (module) {
    /**
     * @ngdoc controller
     * @name Withdrawals.controller:MoneyTransferAccountsController
     * @description
     * Money Transfer accounts details and its listing functions developed here.
     */
    module.controller('MoneyTransferAccountsController', function ($state, $scope, $http, Flash, $filter, MoneyTransferAccountsFactory, MoneyTransferAccountFactory, MoneyTransferAccountPrimaryFactory, $rootScope, $location) {
        /**
         * @ngdoc method
         * @name setMetaData
         * @methodOf Withdrawals.controller:MoneyTransferAccountsController
         * @description
         * This method will set the meta data's dynamically by using the angular.element
         * @returns {Element} New meta data element.
         **/
        $scope.setMetaData = function () {
            var pageTitle = $filter("translate")("Money Transfer Accounts");
            var fullUrl = $location.absUrl();
            var appUrl = $rootScope.settings['scheme_name'] + ":/" + $location.url();
            angular.element('html head meta[property="og:title"], html head meta[name="twitter:title"]').attr("content", $rootScope.settings['site.name'] + " | " + pageTitle);
            angular.element('meta[property="al:ios:url"], meta[property="al:ipad:url"], meta[property="al:android:url"], meta[property="al:windows_phone:url"], html head meta[name="twitter:app:url:iphone"], html head meta[name="twitter:app:url:ipad"], html head meta[name="twitter:app:url:googleplay"]').attr('content', appUrl);
            angular.element('meta[property="og:url"]').attr('content', fullUrl);
        };
        /**
         * @ngdoc method
         * @name init
         * @methodOf Withdrawals.controller:MoneyTransferAccountsController
         * @description
         * This method will initialize the page. It returns the page title.
         **/
        $scope.init = function () {
            $scope.setMetaData();
            $rootScope.pageTitle = $rootScope.settings['site.name'] + " | " + $filter("translate")("Money Transfer Accounts");
            MoneyTransferAccountsFactory.list()
                .$promise
                .then(function (response) {
                    $scope.moneyTransferAccLists = response.data;
                });
        };
        /**
         * @ngdoc method
         * @name MoneyTransferAccSubmit
         * @methodOf Withdrawals.controller:MoneyTransferAccountsController
         * @description
         * This method used to store money transfer account.
         * @param {Object} money_transfer_account Money transfer account details.
         * @returns {Array} Success or failure message.
         **/
        $scope.MoneyTransferAccSubmit = function ($valid) {
            if($valid) {
                MoneyTransferAccountsFactory.save($scope.money_transfer_account, function (response) {
                    Flash.set($filter("translate")("Account Added successfully"), 'success', true);
                    $state.reload();
                }, function (error) {
                    Flash.set($filter("translate")("Account could not be added"), 'error', false);
                });
            }
        };
        /**
         * @ngdoc method
         * @name MoneyTransferAccDelete
         * @methodOf Withdrawals.controller:MoneyTransferAccountsController
         * @description
         * This method used to delete money transfer account.
         * @param {integer} account_id Money transfer account identifier.
         * @returns {Array} Success or failure message.
         **/
        $scope.MoneyTransferAccDelete = function (id) {
            MoneyTransferAccountFactory.delete({
                id: id
            }).$promise.then(function (data) {
                Flash.set($filter("translate")("Account deleted successfully"), 'success', true);
                $state.reload();
            }, function (error) {
                errmsg = (error.data.message != undefined) ? error.data.message : "Account could not be deleted";
                Flash.set($filter("translate")(errmsg), 'error', false);
            });
        };
        $scope.init();
    });
}(angular.module("BookorRent.Withdrawals")));
