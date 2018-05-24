(function (module) {
    /**
     * @ngdoc controller
     * @name Vehicles.controller:VehiclesController
     * @description
     * This is VehiclesController having the methods init(), setMetaData(), and it defines the vehicle related funtions.
     **/
    module.controller('VehiclesController', function ($state, $scope, $http, Flash, $filter, AuthFactory, $rootScope, $location, $window, ConstPaymentGateways, GetCountries, GetGatewaysFactory, GetVehicleFactory, VehiclePaymentFactory) {
        $scope.buyer = {};
        $scope.ConstPaymentGateways = ConstPaymentGateways;
        $scope.apply_is_disabled = $scope.paynow_is_disabled = false;
        /**
         * @ngdoc method
         * @name setMetaData
         * @methodOf Vehicles.controller:VehiclesController
         * @description
         * This method will set the meta data dynamically by using the angular.element.
         * @returns {Element} New meta data element.
         **/
        $scope.setMetaData = function () {
            var pageTitle = $filter("translate")("Vehicle Details");
            var fullUrl = $location.absUrl();
            var appUrl = $rootScope.settings['scheme_name'] + ":/" + $location.url();
            angular.element('html head meta[property="og:title"], html head meta[name="twitter:title"]').attr("content", $rootScope.settings['site.name'] + " | " + pageTitle);
            angular.element('meta[property="al:ios:url"], meta[property="al:ipad:url"], meta[property="al:android:url"], meta[property="al:windows_phone:url"], html head meta[name="twitter:app:url:iphone"], html head meta[name="twitter:app:url:ipad"], html head meta[name="twitter:app:url:googleplay"]').attr('content', appUrl);
            angular.element('meta[property="og:url"]').attr('content', fullUrl);
        };
        /**
         * @ngdoc method
         * @name getGatewaysList
         * @methodOf Vehicles.controller:VehiclesController
         * @description
         * This method used to get payment gateway list.
         * @param {Object} Gateway_list Payment gateway list.
         * @returns {Object} Payment gateway list.
         */
        $scope.getGatewaysList = function () {
            GetGatewaysFactory.get().$promise.then(function (response) {
                if (response.paypal) {
                    $scope.paypal_enabled = (response.paypal.paypal_enabled) ? true : false;
                }
                if (response.wallet) {
                    $scope.wallet_enabled = (response.wallet.wallet_enabled) ? true : false;
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
                } else if ($scope.wallet_enabled) {
                    $scope.gateway_id = ConstPaymentGateways.Wallet;
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
            }
            else if (pane == 'wallet') {
                $scope.gateway_id = ConstPaymentGateways.Wallet;
            }
            else {
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
         * @methodOf Vehicles.controller:VehiclesController
         * @description
         * This method will initialize the meta data and functionalities.
         **/
        $scope.init = function () {
            $scope.setMetaData();
            $scope.getGatewaysList();
            $scope.gatewayTpl = 'Common/gateway.tpl.html';
            GetVehicleFactory.get({id: $state.params.vehicle_id}).$promise.then(function (response) {
                $scope.vehicleDetails = response;
                $scope.vehicleDetails.roundedRating = response.feedback_rating | 0;
            });
            //Get countries list
            GetCountries.get({'sort': 'name', 'sortby': 'asc'}).$promise.then(function (response) {
                $scope.countries = response.data;
            });
            //Vehicle rating
            $scope.maxRatings = [];
            $scope.maxRating = 5;
            for (var i = 0; i < $scope.maxRating; i++) {
                $scope.maxRatings.push(i);
            }
            //Get user available balance
            AuthFactory.fetch({}).$promise
                .then(function (response) {
                    $scope.user_available_balance = response.available_wallet_amount;
                });
        };
        $scope.init();
        /**
         * @ngdoc method
         * @name PaymentSubmit
         * @methodOf Vehicles.controller:VehiclesController
         * @description
         * This method will pay amount to booking item.
         * @param {object} form Payment details.
         * @returns {Array} Success or failure message.
         */
        $scope.PaymentSubmit = function (form) {
            payment_id = '';
            if ($scope.sel_payment_gateway && $scope.gateway_id == ConstPaymentGateways.SudoPay) {
                payment_id = $scope.sel_payment_gateway.split('_')[1];
            }

            $scope.buyer.payment_id = payment_id;
            $scope.buyer.gateway_id = $scope.gateway_id; // Paypal or sudopay
            $scope.buyer.vehicle_id = $state.params.vehicle_id; // vehicle id
            if ($scope.buyer.credit_card_expire_month || $scope.buyer.credit_card_expire_year) {
                $scope.buyer.credit_card_expire_month = $scope.buyer.credit_card_expire_month > 9 ? $scope.buyer.credit_card_expire_month: "0" + $scope.buyer.credit_card_expire_month;
                $scope.buyer.credit_card_expire = $scope.buyer.credit_card_expire_month + "/" + $scope.buyer.credit_card_expire_year;
            }
            if ($scope.gateway_id == ConstPaymentGateways.PayPal) { //if Paypal checkonly amount field
                form.$valid = true;
            }
            if (form.$valid) {
                $scope.paynow_is_disabled = true;
                VehiclePaymentFactory.save($scope.buyer, function (response) {
                    if (response.data == 'wallet') {
                        if($rootScope.settings['vehicle.auto_approve'] == 0) {
                            Flash.set($filter("translate")("Admin need to approve the vehicle."), 'success', true);
                        } else {
                            Flash.set($filter("translate")("Vehicle Add successfully completed."), 'success', true);
                        }
                        $state.go('myVehicles');
                    }
                    if (response.url != undefined) {
                        $window.location.href = response.url;
                    } else if (response.Success != undefined) {
                        if($rootScope.settings['vehicle.auto_approve'] == 0) {
                            Flash.set($filter("translate")("Admin need to approve the vehicle."), 'success', true);
                        } else {
                            Flash.set($filter("translate")(response.Success), 'success', true);
                        }
                        $state.go('myVehicles');
                    }
                    $scope.paynow_is_disabled = false;
                }, function (error) {
                    Flash.set($filter("translate")(error.data.message), 'error', false);
                    $scope.paynow_is_disabled = false;
                });
            }
        };
    });

}(angular.module("BookorRent.Vehicles")));

