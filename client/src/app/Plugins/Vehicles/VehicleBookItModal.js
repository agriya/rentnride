(function (module) {
    /**
     * @ngdoc controller
     * @name Vehicles.controller:VehicleBookitController
     * @description
     * This is modal controller. It contains all the details about the vehicle rental.
     **/
    module.controller('VehicleBookitController', function ($state, $scope, $http, Flash, AuthFactory, GENERAL_CONFIG, $filter, $rootScope, $uibModalInstance, vehicleDetails, VehicleBookingFactory, searchValue) {
        var model = this;
        model.init = function () {
            $scope.currentDate = new Date();
            $scope.vehicle = vehicleDetails;
            $scope.pickup_locations = vehicleDetails.pickup_locations;
            $scope.drop_locations = vehicleDetails.drop_locations;
            if(searchValue != null) {
                $scope.vehicle = JSON.parse(searchValue);
				$scope.vehicle.id = vehicleDetails.id;
				$scope.vehicle.slug = vehicleDetails.slug;
				$scope.vehicle.pickup_location_id = $scope.vehicle.pickup_location_id;
				$scope.vehicle.drop_location_id = $scope.vehicle.drop_location_id;
                $scope.vehicle.start_date = new Date($scope.vehicle.start_date);
                $scope.vehicle.end_date = new Date($scope.vehicle.end_date);
            }
        };
        model.init();
        $scope.modalClose = function () {
            $uibModalInstance.dismiss('close');
        };
        /**
         * Open Picup and drop off calendar
         * @param e
         * @param date
         */
        $scope.openPickupCalendar = function (e, date) {
            $scope.open_pickup[date] = true;
        };
        $scope.openDropCalendar = function (e, date) {
            $scope.open_drop[date] = true;
        };

        $scope.open_pickup = {
            date: false
        };
        $scope.open_drop = {
            date: false
        };
	$scope.buttonBar = {
	    show: true,
	    now: {
		show: true,
		text: $filter('translate')('Now')
	    },
	    today: {
		show: true,
		text: $filter('translate')('Today')
	    },
	    clear: {
		show: true,
		text: $filter('translate')('Clear')
	    },
	    date: {
		show: true,
		text: $filter('translate')('Date')
	    },
	    time: {
		show: true,
		text: $filter('translate')('Time')
	    },
	    close: {
		show: true,
		text: $filter('translate')('Close')
	    }
	};

        /**
         * @ngdoc method
         * @name Bookit
         * @methodOf Vehicles.controller:VehicleBookitController
         * @description
         * This method is used to store vehicle rental.
         * @param {Object} vehicle Vehicle details.
         * @returns {Array} Success or failure message.
         */
        $scope.Bookit = function ($valid) {
            if ($valid && ($scope.vehicle.start_date > $scope.currentDate) && ($scope.vehicle.end_date > $scope.vehicle.start_date)) {
                if ($rootScope.auth == undefined) {
                    $scope.modalClose();
                    localStorage.vehicle_search_value = JSON.stringify($scope.vehicle);
                    Flash.set($filter("translate")("Sign in for an account"), 'error', false);
                    $state.go('login');
                } else {
                    $scope.bookingObj = {
                        vehicle_id: $scope.vehicle.id,
                        item_booking_start_date: $scope.vehicle.start_date,
                        item_booking_end_date: $scope.vehicle.end_date,
                        pickup_counter_location_id: $scope.vehicle.pickup_location_id,
                        drop_counter_location_id: $scope.vehicle.pickup_location_id
                    };
                    if ($scope.vehicle.drop_location_id) {
                        $scope.bookingObj.drop_counter_location_id = $scope.vehicle.drop_location_id;
                    }
                    VehicleBookingFactory.save($scope.bookingObj, function (response) {
                        $uibModalInstance.dismiss('close');
                        $state.go('vehicle_detail', {'vehicle_rental_id': response.id})
                    }, function (error) {
                        Flash.set($filter("translate")(error.data.message), 'error', false);
                    });
                }
            }
        };
    });
}(angular.module("BookorRent.Vehicles")));
