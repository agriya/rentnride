(function (module) {
    /**
     * @ngdoc controller
     * @name Wallets.controller:WalletsStatusController
     * @description
     *
     * This is Wallets Status Controller used for payment after return flash message set.
     **/
    module.controller('WalletsStatusController', function ($state, Flash, $stateParams, $filter) {
        if ($stateParams.status == 'fail') {
            Flash.set($filter("translate")("Amount could not be added, please try again."), 'error', false);
        } else if ($stateParams.status == 'success') {
            Flash.set($filter("translate")("Amount Added successfully"), 'success', true);
        }
        $state.go('wallets');

    });
    /**
     * @ngdoc controller
     * @name Wallets.controller:WalletsController
     * @description
     *
     * This is wallets controller having the methods init(), setMetaData(), WalletSubmit()
     **/
    module.controller('WalletsController', function ($state, $scope, $http, Flash, $filter, WalletsFactory, $rootScope, $location, $window, GetGatewaysFactory, $sce, GetCountries, ConstPaymentGateways) {
        $scope.ConstPaymentGateways = ConstPaymentGateways;
        $scope.buyer = {};
        $scope.paynow_is_disabled = false;
        /**
         * @ngdoc method
         * @name setMetaData
         * @methodOf Wallets.controller:WalletsController
         * @description
         *
         * This method will set the meta data dynamically by using the angular.element.
         **/
        $scope.setMetaData = function () {
            var pageTitle = $filter("translate")("Add To Wallet");
            var fullUrl = $location.absUrl();
            var appUrl = $rootScope.settings['scheme_name'] + ":/" + $location.url();
            angular.element('html head meta[property="og:title"], html head meta[name="twitter:title"]').attr("content", $rootScope.settings['site.name'] + " | " + pageTitle);
            angular.element('meta[property="al:ios:url"], meta[property="al:ipad:url"], meta[property="al:android:url"], meta[property="al:windows_phone:url"], html head meta[name="twitter:app:url:iphone"], html head meta[name="twitter:app:url:ipad"], html head meta[name="twitter:app:url:googleplay"]').attr('content', appUrl);
            angular.element('meta[property="og:url"]').attr('content', fullUrl);
        };

        $scope.getGatewaysList = function () {
            GetGatewaysFactory.get({'page': 'wallet'}).$promise.then(function (response) {
                $scope.wallet_enabled = false;
                if (response.paypal) {
                    $scope.paypal_enabled = (response.paypal.paypal_enabled) ? true : false;
                }
                if (response.sudopay) {
                    $scope.gateway_groups = response.sudopay.gateway_groups;
                    $scope.payment_gateways = response.sudopay.payment_gateways;
                    $scope.form_fields_tpls = response.sudopay.form_fields_tpls;
                    $scope.sel_payment_gateway = response.sudopay.selected_payment_gateway_id;
                    $scope.show_form = [];
                    $scope.form_fields = [];
                    $scope.group_gateway_id = response.sudopay.selected_gateway_id;
                    angular.forEach($scope.form_fields_tpls, function (key, value) {
                        if (value == 'buyer') {
                            $scope.form_fields[value] = 'Plugins/Sudopays/buyer.tpl.html';
                        }
                        if (value == 'credit_card') {
                            $scope.form_fields[value] = 'Plugins/Sudopays/credit_card.tpl.html';
                        }
                        if (value == 'manual') {
                            $scope.form_fields[value] = 'Plugins/Sudopays/manual.tpl.html';
                        }
                        $scope.show_form[value] = true;
                    });
                }
                if ($scope.paypal_enabled) {
                    $scope.gateway_id = ConstPaymentGateways.PayPal;
                } else {
                    $scope.gateway_id = ConstPaymentGateways.SudoPay;
                }
            });
        };
        $scope.paneChanged = function (pane) {
            var keepGoing = true;
            $scope.buyer = {};
            $scope.PaymentForm.$setPristine();
            $scope.PaymentForm.$setUntouched();
            angular.forEach($scope.form_fields_tpls, function (key, value) {
                $scope.show_form[value] = false;
            });
            if (pane == 'paypal') {
                $scope.gateway_id = ConstPaymentGateways.PayPal;
            } else {
                $scope.gateway_id = ConstPaymentGateways.SudoPay;
                angular.forEach($scope.gateway_groups, function (res) {
                    if (res.display_name == pane) {
                        var selPayment = '';
                        angular.forEach($scope.payment_gateways, function (response) {
                            if (keepGoing) {
                                if (response.group_id == res.id) {
                                    selPayment = response;
                                    keepGoing = false;
                                    $scope.rdoclick(selPayment.id, selPayment.form_fields);
                                }
                            }
                        });
                        $scope.sel_payment_gateway = "sp_" + selPayment.id;
                        $scope.group_gateway_id = selPayment.group_id;
                    }
                });
            }
        };

        $scope.rdoclick = function (res, res1) {
            $scope.paynow_is_disabled = false;
            $scope.sel_payment_gateway = "sp_" + res;
            $scope.array = res1.split(',');
            angular.forEach($scope.array, function (value) {
                $scope.show_form[value] = true;
            });
        };

        /**
         * @ngdoc method
         * @name init
         * @methodOf Wallets.controller:WalletsController
         * @description
         *
         * This method will initialize the meta data and functionalities.
         **/
        $scope.init = function () {
            $scope.setMetaData();
            $rootScope.pageTitle = $rootScope.settings['site.name'] + " | " + $filter("translate")("Add To Wallet");
            $scope.min_wallet_amount = $rootScope.settings['wallet.min_wallet_amount'];
            $scope.max_wallet_amount = $rootScope.settings['wallet.max_wallet_amount'];
            //Get gateways list
            $scope.getGatewaysList();
            $scope.gatewayTpl = 'Common/gateway.tpl.html';
            //Get countries list
            GetCountries.get({'sort': 'name', 'sortby': 'asc'}).$promise.then(function (response) {
                $scope.countries = response.data;
            });

        };
        $scope.init();
        /**
         * @ngdoc method
         * @name WalletSubmit
         * @methodOf Wallets.controller:WalletsController
         * @description
         *
         * This method will be used in submitting the wallet amount to the user's account.
         **/
        $scope.PaymentSubmit = function (form) {
            if ($scope.sel_payment_gateway) {
                payment_id = $scope.sel_payment_gateway.split('_')[1];
                $scope.buyer.payment_id = payment_id;
            }
            $scope.buyer.gateway_id = $scope.gateway_id; // Paypal or sudopay
            if($scope.buyer.credit_card_expire_month || $scope.buyer.credit_card_expire_year) {
                $scope.buyer.credit_card_expire_month = $scope.buyer.credit_card_expire_month > 9 ? $scope.buyer.credit_card_expire_month: "0" + $scope.buyer.credit_card_expire_month;
                $scope.buyer.credit_card_expire = $scope.buyer.credit_card_expire_month + "/" + $scope.buyer.credit_card_expire_year;
            }
            if($scope.wallet) {
                $scope.buyer.amount = $scope.wallet.amount;
            }
            if ($scope.gateway_id == ConstPaymentGateways.PayPal && form.amount.$valid) { //if Paypal checkonly amount field
                form.$valid = true;
            }
            if (form.$valid) {
                $scope.paynow_is_disabled = true;
                WalletsFactory.save($scope.buyer, function (response) {
                    if (response.url !== undefined) {
                        $window.location.href = response.url;
                    } else {
                        Flash.set($filter("translate")("Amount Added successfully"), 'success', true);
                        $state.reload();
                    }
                    $scope.paynow_is_disabled = false;
                }, function (error) {
                    $scope.paynow_is_disabled = false;
                    Flash.set($filter("translate")("Amount could not be added"), 'error', false);
                    $scope.amountErr = '';
                    var errorResponse = error.data.errors;
                    if (errorResponse.amount) {
                        $scope.amountErr = $filter("translate")(errorResponse.amount[0]);
                    }
                });
            }
        };

    });
}(angular.module("BookorRent.Wallets")));
