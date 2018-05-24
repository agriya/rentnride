(function (module) {
    /**
     * @ngdoc directive
     * @name Vehicles.directive:vehicleSearch
     * @scope
     * @restrict EA
     * @description
     * vehicleSearch directive used to load the search template.
     * @param {string} vehicleSearch Name of the directive
     **/
    module.directive('myTransactions', function() {
        return {
            restrict: 'EA',
            templateUrl: "Transactions/transaction_list.tpl.html",
            controller: "TransactionController"
        };
    });
    /**
     * @ngdoc controller
     * @name Transaction.controller:TransactionController
     * @description
     *
     * This is TransactionController having the methods init(), setMetaData(), and it defines the item list related funtions.
     **/
    module.controller('TransactionController', function ($state, $scope, $http, Flash, $filter, TransactionFactory, AuthFactory, $rootScope, $location) {
        var model = this;
        $scope.maxSize = 5;
        $scope.user_available_balance = '';
        /**
         * @ngdoc method
         * @name setMetaData
         * @methodOf  Transaction.controller:TransactionController
         * @description
         *
         * This method will set the meta data dynamically by using the angular.element.
         **/
        $scope.setMetaData = function () {
            var pageTitle = $filter("translate")("My Transactions");
            var fullUrl = $location.absUrl();
            var appUrl = $rootScope.settings['scheme_name'] + ":/" + $location.url();
            angular.element('html head meta[property="og:title"], html head meta[name="twitter:title"]').attr("content", $rootScope.settings['site.name'] + " | " + pageTitle);
            angular.element('meta[property="al:ios:url"], meta[property="al:ipad:url"], meta[property="al:android:url"], meta[property="al:windows_phone:url"], html head meta[name="twitter:app:url:iphone"], html head meta[name="twitter:app:url:ipad"], html head meta[name="twitter:app:url:googleplay"]').attr('content', appUrl);
            angular.element('meta[property="og:url"]').attr('content', fullUrl);
        };
        /**
         * @ngdoc method
         * @name init
         * @methodOf Transaction.controller:TransactionController
         * @description
         *
         * This method will initialize the meta data and functionalities.
         **/

        var params = {};
        $scope.getTransactionList = function () {
            params.page = $scope.transaction_currentPage;
            TransactionFactory.list(params)
                .$promise
                .then(function (response) {
                    $scope.TransactionLists = response.data;
                    $scope.transaction_metadata = response.meta.pagination;
                });
        };
        /**
         * @ngdoc method
         * @name init
         * @methodOf Transaction.controller:TransactionController
         * @description
         *
         * This method will initialize the meta data and functionalities.
         **/
        $scope.init = function () {
            $scope.setMetaData();
            $rootScope.pageTitle = $rootScope.settings['site.name'] + " | " + $filter("translate")("My Transactions");
            $scope.transaction_currentPage = ($scope.transaction_currentPage !== undefined) ? parseInt($scope.transaction_currentPage) : 1;
            AuthFactory.fetch({}).$promise
                .then(function (response) {
                    $scope.user_available_balance = response.available_wallet_amount;
                });
            $scope.getTransactionList();
        };
        $scope.init();
        $scope.transaction_paginate = function (pageno) {
            $scope.transaction_currentPage = parseInt($scope.transaction_currentPage);
            $scope.init();
        };
        $scope.activeMenu = "all";
        $scope.filterTransaction = function (status) {
            if (status === undefined) {
                $scope.activeMenu = "all";
                $scope.getTransactionList();
            } else {
                TransactionFactory.filter({
                    'filter': status,
                    'page': $scope.transaction_currentPage
                }).$promise.then(function (response) {
                    $scope.TransactionLists = response.data;
                    $scope.transaction_metadata = response.meta.pagination;
                    $scope.activeMenu = status;
                });
            }
        };

    });
}(angular.module("BookorRent.Transactions")));
