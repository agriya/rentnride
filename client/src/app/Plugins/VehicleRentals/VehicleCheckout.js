(function (module) {
    /**
     * @ngdoc controller
     * @name VehicleRentals.controller:VehicleCheckoutController
     * @description
     * This is items controller having the methods init(), setMetaData().
     * It controls the functionality of items.
     **/
    module.controller('VehicleCheckoutController', function ($scope, $rootScope, $filter, Flash, $state, $location, $window, VehicleRentalFactory, checkOutFactory, GetVehicleFeedbackFactory, $uibModal) {
        model = this;

        /**
         * @ngdoc method
         * @name setMetaData
         * @methodOf VehicleRentals.controller:VehicleCheckoutController
         * @description
         * This method will set the meta data dynamically by using the angular.element.
         * @returns {Element} New meta data element.
         **/
        $scope.setMetaData = function () {
            var pageTitle = $filter("translate")("Vehicle Checkout");
            var fullUrl = $location.absUrl();
            var appUrl = $rootScope.settings['scheme_name'] + ":/" + $location.url();
            angular.element('html head meta[property="og:title"], html head meta[name="twitter:title"]').attr("content", $rootScope.settings['site.name'] + " | " + pageTitle);
            angular.element('meta[property="al:ios:url"], meta[property="al:ipad:url"], meta[property="al:android:url"], meta[property="al:windows_phone:url"], html head meta[name="twitter:app:url:iphone"], html head meta[name="twitter:app:url:ipad"], html head meta[name="twitter:app:url:googleplay"]').attr('content', appUrl);
            angular.element('meta[property="og:url"]').attr('content', fullUrl);
        };

        /**
         * @ngdoc method
         * @name init
         * @methodOf VehicleRentals.controller:VehicleCheckoutController
         * @description
         * This method will initialze the page. It returns the page title.
         **/
        $scope.init = function () {
            $scope.setMetaData();
            $scope.currentPage = (model.currentPage !== undefined) ? parseInt(model.currentPage) : 1;
            $rootScope.pageTitle = $rootScope.settings['site.name'] + " | " + $filter("translate")("item Orders");
            //Get vehicles and vehicle booking details
            VehicleRentalFactory.get({'id': $state.params.order_id}).$promise.then(function (response) {
                $scope.vehicleDetails = response.item_userable;
                $scope.VehicleRentalDetails = response;
                var start_date = $scope.VehicleRentalDetails.item_booking_start_date.replace(/(.+) (.+)/, "$1T$2Z");
                var end_date = $scope.VehicleRentalDetails.item_booking_end_date.replace(/(.+) (.+)/, "$1T$2Z");
                $scope.VehicleRentalDetails.item_booking_start_date = $filter('date')(new Date(start_date), 'MMM d, y h:mm a', '+0');
                $scope.VehicleRentalDetails.item_booking_end_date = $filter('date')(new Date(end_date), 'MMM d, y h:mm a', '+0');
                //To display distance and unit
                $scope.unit_price = $scope.vehicleDetails.vehicle_type.drop_location_differ_unit_price;
                $scope.differ_location_distance = $scope.VehicleRentalDetails.total_distance+' ('+$scope.VehicleRentalDetails.distance_unit+') ';
            });

            //Vehicle rating
            $scope.maxRatings = [];
            $scope.maxRating = 5;
            for (var i = 0; i < $scope.maxRating; i++) {
                $scope.maxRatings.push(i);
            }
            //Feedback Modal
            $scope.feedbackModal = 'Plugins/Vehicles/vehicle_feedback_modal.tpl.html';
        };

        $scope.init();
        /**
         * @ngdoc method
         * @name calculateAmount
         * @methodOf VehicleRentals.controller:VehicleCheckoutController
         * @description
         * This method will initialze the page. It returns the checkout manual payment amount.
         * @param {float} Amount Amount for calculation.
         * @returns {float} Amount New calculated amount.
         **/
        $scope.calculateAmount = function (amount) {
            if ($scope.VehicleRentalDetails.late_checkout_total_fee) {
                amount = amount + $scope.VehicleRentalDetails.late_checkout_total_fee;
            }
            if (($scope.VehicleRentalDetails.deposit_amount > 0)) {
                if ($scope.VehicleRentalDetails.deposit_amount > amount) {
                    $scope.manualPay = 0;
                    $scope.claimToDeposit = amount;
                } else {
                    $scope.manualPay = amount - $scope.VehicleRentalDetails.deposit_amount;
                    $scope.claimToDeposit = $scope.VehicleRentalDetails.deposit_amount;
                }
            } else {
                $scope.manualPay = amount;
            }
        };
        /**
         * @ngdoc method
         * @name checkout
         * @methodOf VehicleRentals.controller:VehicleCheckoutController
         * @description
         * This method is used to check in.
         * @param {float} claim_mount new claim amount.
         * @returns {Array} Success or failure message.
         */
        $scope.checkout = function ($valid) {
            var checkout = $window.confirm('Are you sure want to checkout?');
            if (checkout) {
                $scope.claim_request_amount = ($scope.claim_amount) ? $scope.claim_amount : 0;
                checkOutFactory.checkout({'id': $state.params.order_id}, {'claim_request_amount': $scope.claim_request_amount}, function (response) {
                    Flash.set($filter("translate")("VehicleRental has been checked-out successfully!"), 'success', true);
                    $state.go('orders', {'statusID': 0, 'slug': 'all'})
                }, function (error) {
                    Flash.set($filter("translate")("VehicleRental could not be updated. Please, try again"), 'error', false);
                });
            }
        };
        /**
         * @ngdoc method
         * @name getFeedback
         * @methodOf VehicleRentals.controller:VehicleCheckoutController
         * @description
         * This method is used to check in.
         * @param {integer} vehicle_id Vehicle identifier.
         * @returns {Array} Feedback new response.
         */
            //Vehicle Feedbacks
        $scope.getFeedback = function (vehicle_id) {
            // $scope.vehicle_metadata = {};
            GetVehicleFeedbackFactory.get({vehicle_id: vehicle_id}).$promise.then(function (response) {
                $scope.vehicleFeedbacks = response.data;
                //  $scope.vehicle_metadata = response.meta;
                // console.log("response", $scope.vehicleFeedbacks.vehicle_metadata);
            });
        };

        //Go to user page
        $scope.userDashboard = function (name) {
            $('.modal-backdrop').hide();
            $state.go('userView', {'username': name});
        };
        /**
         * @ngdoc method
         * @name modalOpen
         * @methodOf Vehicles.controller:VehicleCheckoutController
         * @description
         * This method will initialze the page. It pen the modal with vehicle feedbacks.
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
