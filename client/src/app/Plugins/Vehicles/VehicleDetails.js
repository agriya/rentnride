(function (module) {
    /**
     * @ngdoc controller
     * @name Vehicles.controller:VehicleDetailsController
     * @description
     * This is VehicleDetailsController having the methods init(), setMetaData(), and it defines the vehicle related funtions.
     **/
    module.controller('VehicleDetailsController', function ($state, $scope, $http, Flash, $filter, AuthFactory, $rootScope, $location, VehicleDetailFactory, UpdateVehicleRentalFactory, $uibModal, ConstDiscountTypes, ConstDurationTypes) {
        $scope.vehicle_rental = {};
        $scope.booker_detail = {};
        $scope.vehicle_rental.vehicle_type_extra_accessories = [];
        $scope.vehicle_rental.vehicle_type_fuel_options = [];
        $scope.vehicle_rental.vehicle_type_insurances = [];
        $scope.isPayment = true;
        $scope.ConstDiscountTypes = ConstDiscountTypes;
        $scope.ConstDurationTypes = ConstDurationTypes;
        /**
         * @ngdoc method
         * @name setMetaData
         * @methodOf Vehicles.controller:VehicleDetailsController
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
         * @name additionalCharges
         * @methodOf Vehicles.controller:VehicleDetailsController
         * @description
         * This method is used to store vehicle rental.
         * @param {Object} additional_charge Additional charge details.
         * @returns {Array} New additional charge details.
         */
        $scope.additionalCharges = function (additional_charge) {
            angular.forEach(additional_charge, function (value, key) {
                if (value.item_user_additional_chargable_type == 'MorphInsurance') {
                    $scope.vehicle_rental.vehicle_type_insurances.push(parseInt(value.item_user_additional_chargable_id));
                }
                if (value.item_user_additional_chargable_type == 'MorphFuelOption') {
                    $scope.vehicle_rental.vehicle_type_fuel_options.push(parseInt(value.item_user_additional_chargable_id));
                }
                if (value.item_user_additional_chargable_type == 'MorphExtraAccessory') {
                    $scope.vehicle_rental.vehicle_type_extra_accessories.push(parseInt(value.item_user_additional_chargable_id));
                }
            });
        };
        /**
         * @ngdoc method
         * @name init
         * @methodOf Vehicles.controller:VehicleDetailsController
         * @description
         * This method will initialize the meta data and functionalities.
         **/
        $scope.init = function () {
            $scope.setMetaData();
            VehicleDetailFactory.get({id: $state.params.vehicle_rental_id, 'type':'rental'}).$promise.then(function (response) {
                $scope.VehicleRentalDetails = response;
                var start_date = $scope.VehicleRentalDetails.item_booking_start_date.replace(/(.+) (.+)/, "$1T$2Z");
                var end_date = $scope.VehicleRentalDetails.item_booking_end_date.replace(/(.+) (.+)/, "$1T$2Z");
                $scope.VehicleRentalDetails.item_booking_start_date = $filter('date')(new Date(start_date), 'MMM d, y h:mm a', '+0');
                $scope.VehicleRentalDetails.item_booking_end_date = $filter('date')(new Date(end_date), 'MMM d, y h:mm a', '+0');
                $scope.vehicleDetails = response.item_userable;
                $scope.vehicleDetails.roundedRating = response.item_userable.feedback_rating | 0;
                if ($scope.vehicleDetails.vehicle_type.vehicle_type_extra_accessory) {
                    $scope.vehicle_extra_sccessories = $scope.vehicleDetails.vehicle_type.vehicle_type_extra_accessory.data;
                    $scope.vehicle_extra_sccessories.status = false;
                }
                if ($scope.vehicleDetails.vehicle_type.vehicle_type_fuel_option) {
                    $scope.vehicle_type_fuel_option = $scope.vehicleDetails.vehicle_type.vehicle_type_fuel_option.data;
                    $scope.vehicle_type_fuel_option.status = false;
                }
                if ($scope.vehicleDetails.vehicle_type.vehicle_type_insurance) {
                    $scope.vehicle_type_insurance = $scope.vehicleDetails.vehicle_type.vehicle_type_insurance.data;
                    $scope.vehicle_type_insurance.status = false;
                }
                $scope.vehicle_additional_charges = response.vehicle_rental_additional_chargable.data;
                $scope.additionalCharges($scope.vehicle_additional_charges);
                //For drop location differ charge fee
                $scope.unit_price = $scope.vehicleDetails.vehicle_type.drop_location_differ_unit_price;
                $scope.differ_location_distance = $scope.VehicleRentalDetails.total_distance+' ('+$scope.VehicleRentalDetails.distance_unit+') ';

                if (response.booker_detail) {
                    $scope.booker_detail = response.booker_detail;
                } else {
                    AuthFactory.fetch().$promise.then(function (user) {
                        if (user.user_profile) {
                            $scope.booker_detail.first_name = user.user_profile.first_name;
                            $scope.booker_detail.last_name = user.user_profile.last_name;
                        }
                        $scope.booker_detail.email = user.email;
                    });
                }
            }, function (error) {
                Flash.set($filter("translate")(error.data.message), 'error', false);
                $state.go('vehicle_rental_list_status', {'statusID':0, 'slug':'all'});
            });
            //Vehicle rating
            $scope.maxRatings = [];
            $scope.maxRating = 5;
            for (var i = 0; i < $scope.maxRating; i++) {
                $scope.maxRatings.push(i);
            }
        };
        $scope.init();

        $scope.getExtraAccessory = function (id) {
            id = parseInt(id);
            var selected_id = $scope.vehicle_rental.vehicle_type_extra_accessories.indexOf(id);
            if (selected_id > -1) {
                $scope.vehicle_rental.vehicle_type_extra_accessories.splice(selected_id, 1);
            } else {
                $scope.vehicle_rental.vehicle_type_extra_accessories.push(id);
            }
        };
        $scope.getfuelOption = function (id) {
            id = parseInt(id);
            var selected_id = $scope.vehicle_rental.vehicle_type_fuel_options.indexOf(id);
            if (selected_id > -1) {
                $scope.vehicle_rental.vehicle_type_fuel_options.splice(selected_id, 1);
            } else {
                $scope.vehicle_rental.vehicle_type_fuel_options.push(id);
            }
        };
        $scope.getVehicleInsurance = function (id) {
            id = parseInt(id);
            var selected_id = $scope.vehicle_rental.vehicle_type_insurances.indexOf(id);
            if (selected_id > -1) {
                $scope.vehicle_rental.vehicle_type_insurances.splice(selected_id, 1);
            } else {
                $scope.vehicle_rental.vehicle_type_insurances.push(id);
            }
        };
        /**
         * @ngdoc method
         * @name updateVehicleRental
         * @methodOf Vehicles.controller:VehicleDetailsController
         * @description
         * This method is used to update vehicle rental details.
         * @param {Object} vehicle_rental Vehicle rental details.
         * @returns {Array} Success or failure message.
         */
        $scope.updateVehicleRental = function ($valid) {
            if ($valid) {
                $scope.updateBookingForm.$setPristine();
                $scope.updateBookingForm.$setUntouched();
                $scope.vehicle_rental.first_name = $scope.booker_detail.first_name;
                $scope.vehicle_rental.last_name = $scope.booker_detail.last_name;
                $scope.vehicle_rental.email = $scope.booker_detail.email;
                $scope.vehicle_rental.mobile = $scope.booker_detail.mobile;
                $scope.vehicle_rental.address = ($scope.booker_detail.address.formatted_address == undefined) ? $scope.booker_detail.address : $scope.booker_detail.address.formatted_address;
                $scope.vehicle_rental.id = $state.params.vehicle_rental_id;
                UpdateVehicleRentalFactory.update({id: $scope.vehicle_rental.id}, $scope.vehicle_rental, function (response) {
                    Flash.set($filter("translate")("Details Updated Successfully"), 'success', true);
                    $state.reload();
                }, function (error) {
                    Flash.set($filter("translate")("Details Could not be updated"), 'error', false);
                });
            }
        };
        $scope.continuePayment = function () {
            $state.go('order', {'vehicle_rental_id': $state.params.vehicle_rental_id});
        };
        /**
         * @ngdoc method
         * @name modalOpen
         * @methodOf Vehicles.controller:VehicleDetailsController
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

}(angular.module("BookorRent.Vehicles")));

