(function (module) {
    /**
     * @ngdoc controller
     * @name VehicleRentals.controller:VehicleRentalOrderController
     * @description
     * This is VehicleRentalOrderController having the methods init(), setMetaData(), and it defines the item order related funtions.
     **/
    module.controller('VehicleRentalOrderController', function ($state, $scope, $http, Flash, $filter, $rootScope, $location, VehicleRentalFactory, ApplyCouponFactory, PaymentFactory, GetGatewaysFactory, ConstPaymentGateways, GetCountries, $window, AuthFactory, $uibModal) {
        var model = this;
        $scope.buyer = {};
        $scope.ConstPaymentGateways = ConstPaymentGateways;
        $scope.apply_is_disabled = $scope.paynow_is_disabled = false;
        /**
         * @ngdoc method
         * @name setMetaData
         * @methodOf  VehicleRentals.controller:VehicleRentalOrderController
         * @description
         * This method will set the meta data dynamically by using the angular.element.
         **/
        $scope.setMetaData = function () {
            var pageTitle = $filter("translate")("Book It");
            var fullUrl = $location.absUrl();
            var appUrl = $rootScope.settings['scheme_name'] + ":/" + $location.url();
            angular.element('html head meta[property="og:title"], html head meta[name="twitter:title"]').attr("content", $rootScope.settings['site.name'] + " | " + pageTitle);
            angular.element('meta[property="al:ios:url"], meta[property="al:ipad:url"], meta[property="al:android:url"], meta[property="al:windows_phone:url"], html head meta[name="twitter:app:url:iphone"], html head meta[name="twitter:app:url:ipad"], html head meta[name="twitter:app:url:googleplay"]').attr('content', appUrl);
            angular.element('meta[property="og:url"]').attr('content', fullUrl);
        };
        /**
         * @ngdoc method
         * @name getGatewaysList
         * @methodOf  VehicleRentals.controller:VehicleRentalOrderController
         * @description
         * This method used to get payment gateway list.
         * @param {Object} Gateway_list Payment gateway list.
         * @returns {html} Load the corresponding template.
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
        /**
         * @ngdoc method
         * @name paneChanged
         * @methodOf  VehicleRentals.controller:VehicleRentalOrderController
         * @description
         * This method used to change the tab.
         * @param {Object} Gateway_list Payment gateway list.
         * @returns {Object} Payment gateway list.
         */
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
        /**
         * @ngdoc method
         * @name rdoclick
         * @methodOf VehicleRentals.controller:VehicleRentalOrderController
         * @description
         * This method used to split the arrays.
         * @param {Object} res Payment gateway list.
         * @returns {Object} New Splited payment gateway list.
         */
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
         * @methodOf VehicleRentals.controller:VehicleRentalOrderController
         * @description
         * This method will initialize the meta data and functionalities.
         **/
        $scope.init = function () {
            $scope.currentYear = new Date().getFullYear();
            $scope.setMetaData();
            $scope.item_id = $state.params.id ? $state.params.id : '';
            $scope.vehicle_rental_id = $state.params.vehicle_rental_id ? $state.params.vehicle_rental_id : '';
            VehicleRentalFactory.get({'id': $scope.vehicle_rental_id,'type':'rental'}).$promise.then(function (response) {
                $scope.VehicleRentalDetails = response;
                var start_date = $scope.VehicleRentalDetails.item_booking_start_date.replace(/(.+) (.+)/, "$1T$2Z");
                var end_date = $scope.VehicleRentalDetails.item_booking_end_date.replace(/(.+) (.+)/, "$1T$2Z");
                $scope.VehicleRentalDetails.item_booking_start_date = $filter('date')(new Date(start_date), 'MMM d, y h:mm a', '+0');
                $scope.VehicleRentalDetails.item_booking_end_date = $filter('date')(new Date(end_date), 'MMM d, y h:mm a', '+0');
                $scope.vehicleDetails = response.item_userable;
                $scope.vehicleDetails.roundedRating = response.item_userable.feedback_rating | 0;
                if ($scope.VehicleRentalDetails.item_user_status.id != 1) {
                    Flash.set($filter("translate")("You can't pay for this order"), 'error', false);
                    $state.go('vehicle_rental_list_status', {statusID: 0, slug: 'all'});
                }
				//To display distance and unit
                $scope.unit_price = $scope.vehicleDetails.vehicle_type.drop_location_differ_unit_price;
                $scope.differ_location_distance = $scope.VehicleRentalDetails.total_distance+' ('+$scope.VehicleRentalDetails.distance_unit+') ';
            });
            AuthFactory.fetch({}).$promise
                .then(function (response) {
                    $scope.user_available_balance = response.available_wallet_amount;
                });
            $scope.getGatewaysList();
            $scope.gatewayTpl = 'Common/gateway.tpl.html';
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
        };
        $scope.init();
        /**
         * @ngdoc method
         * @name bookingCouponSubmit
         * @methodOf VehicleRentals.controller:VehicleRentalOrderController
         * @description
         * This method will apply coupon code to booking item.
         * @param {integer} vehicle_rental_id Rental identifier.
         * @returns {Array} Success or failure message.
         */
        $scope.vehicleCouponSubmit = function ($valid) {
            if ($valid) {
                $scope.apply_is_disabled = true;
                var bookingCoupon = {
                    name: $scope.coupon_code
                };
                ApplyCouponFactory.update({id: $state.params.vehicle_rental_id}, bookingCoupon, function (response) {
                    Flash.set($filter("translate")("Coupon code applied Successfully"), 'success', true);
                    $scope.apply_is_disabled = false;
                    $state.reload();
                }, function (error) {
                    Flash.set($filter("translate")(error.data.message), 'error', false);
                    $scope.apply_is_disabled = false;
                });
            }
        };
        /**
         * @ngdoc method
         * @name payNow
         * @methodOf VehicleRentals.controller:VehicleRentalOrderController
         * @description
         * This method will pay amount to booking item.
         * @param {integer} vehicle_rental_id Rental identifier.
         * @returns {Array} Success or failure message.
         */
        $scope.payNow = function () {
            PaymentFactory.update({id: $state.params.vehicle_rental_id}, function (response) {
                Flash.set($filter("translate")("VehicleRental fee paid Successfully"), 'success', true);
                $state.go('items');
            }, function (error) {
                Flash.set($filter("translate")("VehicleRental Could not be updated"), 'error', false);
            });
        };

        /**
         * @ngdoc method
         * @name PaymentSubmit
         * @methodOf VehicleRentals.controller:VehicleRentalOrderController
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
            $scope.buyer.vehicle_rental_id = $state.params.vehicle_rental_id; // booking id
            if ($scope.buyer.credit_card_expire_month || $scope.buyer.credit_card_expire_year) {
                $scope.buyer.credit_card_expire_month = $scope.buyer.credit_card_expire_month > 9 ? $scope.buyer.credit_card_expire_month: "0" + $scope.buyer.credit_card_expire_month;
                $scope.buyer.credit_card_expire = $scope.buyer.credit_card_expire_month + "/" + $scope.buyer.credit_card_expire_year;
            }
            $scope.buyer.amount = $scope.VehicleRentalDetails.total_amount;
            if ($scope.gateway_id == ConstPaymentGateways.PayPal && form.amount.$valid) { //if Paypal checkonly amount field
                form.$valid = true;
            }
            if (form.$valid) {
                $scope.paynow_is_disabled = true;
                PaymentFactory.save({id: $state.params.vehicle_rental_id}, $scope.buyer, function (response) {
                    if (response.data == 'wallet') {
                        Flash.set($filter("translate")("Vehicle booked successfully"), 'success', true);
                        $state.go('vehicle_rental_list_status', {statusID: 0, slug: 'all'});
                    }
                    if (response.url != undefined) {
                        $window.location.href = response.url;
                    } else if (response.Success != undefined) {
                        Flash.set($filter("translate")(response.Success), 'success', true);
                        $state.go('vehicle_rental_list_status', {statusID: 0, slug: 'all'});
                    }
                    $scope.paynow_is_disabled = false;
                }, function (error) {
                    Flash.set($filter("translate")(error.data.message), 'error', false);
                    $scope.paynow_is_disabled = false;
                });
            }
        };
        /**
         * @ngdoc method
         * @name modalOpen
         * @methodOf VehicleRentals.controller:VehicleRentalOrderController
         * @description
         * This method will initialze the page. It pen the modal with vehicle feedbacks.
         * @param {integer} vehicle_id Vehicle identifier.
         * @returns {html} Load new template.
         **/
        $scope.modalOpen = function (size, vehicle_id) {
            var modalInstance = $uibModal.open({
                templateUrl: 'Plugins/Vehicles/vehicle_feedback_modal.tpl.html',
                controller: 'VehicleModalController',
                size: size,
                resolve: {
                    vehicle_id: function () {
                        return vehicle_id;
                    }
                }
            });
        };
    });
}(angular.module("BookorRent.VehicleRentals")));
