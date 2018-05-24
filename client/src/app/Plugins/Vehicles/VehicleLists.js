(function (module) {
    /**
     * @ngdoc controller
     * @name Vehicles.controller:VehicleListController
     * @description
     * This is VehicleListController having the methods init(), setMetaData(), and it defines the vehicle list related funtions.
     **/
    module.controller('VehicleListController', function ($state, $scope, $http, Flash, $filter, AuthFactory, $rootScope, $location, VehicleSearchFactory, CounterLocationFactory, VehicleFilterFactory, VehicleBookingFactory, $uibModal) {
        $scope.maxSize = 5;
        var params = {};
        $scope.issearchTpl = false;
        $scope.searchTpl = "Plugins/Vehicles/vehicle_search.tpl.html";
        $scope.seatRange = {};
        $scope.priceRange = {};
        $scope.check_drop_location = false;
        $scope.maxRating = 5;
        $scope.returnLocation = function (status) {
            if (!status) {
                $scope.vehicle.drop_location = '';
                $scope.check_drop_location = false;
            }
        };
        $scope.sort_name = $filter("translate")("Lowest Price");
        $scope.closeSearch = function () {
            $scope.issearchTpl = false;
        };
        $scope.status = {
            type: true,
            preference: true,
            fuel: true,
            seat: true,
            price: true
        };
        $scope.currentDate = new Date();
        /**
         * @ngdoc method
         * @name setMetaData
         * @methodOf Vehicles.controller:VehicleListController
         * @description
         * This method will set the meta data dynamically by using the angular.element.
         * @returns {Element} New meta data element.
         **/
        $scope.setMetaData = function () {
            var pageTitle = $filter("translate")("Vehicle Lists");
            var fullUrl = $location.absUrl();
            var appUrl = $rootScope.settings['scheme_name'] + ":/" + $location.url();
            angular.element('html head meta[property="og:title"], html head meta[name="twitter:title"]').attr("content", $rootScope.settings['site.name'] + " | " + pageTitle);
            angular.element('meta[property="al:ios:url"], meta[property="al:ipad:url"], meta[property="al:android:url"], meta[property="al:windows_phone:url"], html head meta[name="twitter:app:url:iphone"], html head meta[name="twitter:app:url:ipad"], html head meta[name="twitter:app:url:googleplay"]').attr('content', appUrl);
            angular.element('meta[property="og:url"]').attr('content', fullUrl);
        };
        /**
         * @ngdoc method
         * @name search
         * @methodOf Vehicles.controller:VehicleListController
         * @description
         * This method will search the vehicles based on the request.
         * @param {Object} vehicle Vehicle details.
         * @returns {Object} Vehicle list details.
         **/
        $scope.search = function (vehicle) {
            VehicleSearchFactory.post(vehicle, function (response) {
                $scope.searchData = response.data;
                $scope.searchData.status = false;
                $scope._metadata = response.meta.pagination;
                angular.forEach($scope.searchData, function(value, key) {
                    //filter changes - show rating
                    value.roundedRating = value.feedback_rating | 0;
					value.open_pickup = false;
					value.open_drop = false;
                });
                $scope.vehicleListErr = false;
            }, function(error) {
				$scope.vehicleListErr = true;
                Flash.set($filter("translate")(error.data.message), 'error', false);
            });
        };
        /**
         * @ngdoc method
         * @name counter_location
         * @methodOf Vehicles.controller:VehicleListController
         * @description
         * This method will get location list by type.
         * @param {integer} type Vehicle type.
         * @returns {Object} location list.
         **/
        $scope.counter_location = function () {
            CounterLocationFactory.get({type: 'list'}).$promise.then(function (response) {
                $scope.locations = response.data;
            });
        };
        /**
         * @ngdoc method
         * @name getFilterValues
         * @methodOf Vehicles.controller:VehicleListController
         * @description
         * This method will get vehicle list by type.
         * @param {integer} type Vehicle type.
         * @returns {Object} Vehicle details.
         **/
        $scope.getFilterValues = function () {
            VehicleFilterFactory.get().$promise.then(function (response) {
                $scope.vehicle_company_lists = response.vehicle_company_list;
                $scope.vehicle_type_lists = response.vehicle_type_list;
                $scope.seats = response.settings.seats;
                $scope.fuel_lists = response.fuel_type_list;
                $scope.vehicle_price = response.vehicle_type_price;
                //Set seat slider value
                $scope.seatRange = {
                    min: 1,
                    max: parseInt($scope.seats),
                    options: {
                        floor: 1,
                        ceil: parseInt($scope.seats),
                        onEnd: function () {
                            $scope.vehicle.seat_min = $scope.seatRange.min;
                            $scope.vehicle.seat_max = $scope.seatRange.max;
                            $scope.search($scope.vehicle);
                        }
                    }
                };
                //Set day price slider value
                var minPrice, maxPrice;
                $scope.$watch('booking_details', function (booking_details) {
                    if (angular.isDefined(booking_details)) {
                        minPrice = (booking_details.is_day_price == 1) ? $scope.vehicle_price.min_day_price : $scope.vehicle_price.min_hour_price;
                        maxPrice = (booking_details.is_day_price == 1) ? $scope.vehicle_price.max_day_price : $scope.vehicle_price.max_hour_price;
                        $scope.priceRange = {
                            min: parseFloat(minPrice),
                            max: parseFloat(maxPrice),
                            options: {
                                floor: parseFloat(minPrice),
                                ceil: parseFloat(maxPrice),
                                onEnd: function () {
                                    $scope.vehicle.price_min = $scope.priceRange.min;
                                    $scope.vehicle.price_max = $scope.priceRange.max;
                                    $scope.search($scope.vehicle);
                                }
                            }
                        }
                    }
                });

            });
        };
        /**
         * @ngdoc method
         * @name init
         * @methodOf Vehicles.controller:VehicleListController
         * @description
         * This method will initialize the meta data and functionalities.
         **/
        $scope.init = function () {
            $scope.is_drop_location = false;
            $scope.setMetaData();

            $scope.currentPage = ($scope.currentPage !== undefined) ? parseInt($scope.currentPage) : 1;
            //Get search value item from localstorage
            var searchValue = localStorage.getItem('searchValue');
            localStorage.setItem('vehicle_search_value', searchValue);
            if (searchValue != null) {
                $scope.counter_location();
                $scope.getFilterValues();
                $scope.vehicle = JSON.parse(searchValue);
                $scope.vehicle.vehicle_type = [];
                $scope.vehicle.fuel_type = [];
                $scope.vehicle.sort = 'price';
                $scope.vehicle.sortby = 'asc';
                $scope.vehicle.page = $scope.currentPage;
                //search results from localstorage
                VehicleSearchFactory.post($scope.vehicle, function (response) {
                    $scope.searchData = response.data;
                    $scope.searchData.status = false;
                    $scope._metadata = response.meta.pagination;
                    $scope.booking_details = response.meta.booking_details;
                    // if booking details empty calculate the hours differnce
                    if($scope.booking_details.length == 0) {
                        $scope.booking_details.is_day_price = 1;
                        var diff = new Date($scope.vehicle.end_date).getTime() - new Date($scope.vehicle.start_date).getTime();
                        var hours = Math.abs(diff) / 36e5;
                        if(hours < 1) {
                            $scope.booking_details.is_day_price = 0;
                        }
                    }
                    angular.forEach($scope.searchData, function(value, key) {
                        //if number is decimal return only integer
                        value.roundedRating = value.feedback_rating | 0;
                    });
                    $scope.vehicleListErr = false;
                }, function(error) {
                    $scope.vehicleListErr = true;
                    Flash.set($filter("translate")(error.data.message), 'error', false);
                });
                $scope.vehicle.start_date = new Date($scope.vehicle.start_date);
                $scope.vehicle.end_date = new Date($scope.vehicle.end_date);
                if ($scope.vehicle.drop_location && $scope.vehicle.drop_location.id != $scope.vehicle.pickup_location.id) {
                    $scope.check_drop_location = true;
                    $scope.is_drop_location = true;
                }
            } else {
                $state.go('home');
            }
            //Vehicle rating
            $scope.maxRatings = [];
            for (var i = 0; i < $scope.maxRating; i++) {
                $scope.maxRatings.push(i);
            }
            //Set vehicle start and end date to new scope
            $scope.vehicle.pickup_date = $scope.vehicle.start_date;
            $scope.vehicle.drop_date = $scope.vehicle.end_date;
        };
        $scope.init();
        $scope.paginate = function (pageno) {
            $scope.currentPage = parseInt($scope.currentPage);
            $scope.init();
        };
        /**
         * Open Picup and drop off calendar
         * @param e
         * @param date
         */
        $scope.openPickupCalendar = function (e, date, index) {
			$scope.searchData[index].open_pickup = true;
        };
        $scope.openDropCalendar = function (e, date, index) {
			$scope.searchData[index].open_drop = true;
        };
		
		$scope.openSearchPickupCalendar = function (e, date) {
            $scope.open_pickup[date] = true;
        };
        $scope.openSearchDropCalendar = function (e, date) {
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
	}
        /**
         * @ngdoc method
         * @name SearchSubmit
         * @methodOf Vehicles.controller:VehicleListController
         * @description
         * This method store search details.
         * @param {Object} vehicle Vehicle serach details.
         * @returns {Object} Vehicle list.
         **/
        $scope.SearchSubmit = function ($valid) {
            if ($valid && ($scope.vehicle.start_date > $scope.currentDate) && ($scope.vehicle.end_date > $scope.vehicle.start_date)) {
                if (!$scope.vehicle.drop_location) {
                    $scope.vehicle.drop_location = $scope.vehicle.pickup_location;
                }
                $scope.setLocalStorage = {
                    start_date: $scope.vehicle.start_date,
                    end_date: $scope.vehicle.end_date,
                    pickup_location_id: $scope.vehicle.pickup_location.id,
                    drop_location_id: $scope.vehicle.drop_location.id,
                    pickup_location: $scope.vehicle.pickup_location,
                    drop_location: $scope.vehicle.drop_location
                };
                localStorage.setItem('searchValue', JSON.stringify($scope.setLocalStorage));
                $scope.init();
                $scope.fuel_lists = [];
                $scope.vehicle_type_lists = [];
                if ($scope.vehicle.drop_location && $scope.vehicle.drop_location.id != $scope.vehicle.pickup_location.id) {
                    $scope.check_drop_location = true;
                }
                $scope.issearchTpl = false;
            }
        };
        /**
         * @ngdoc method
         * @name filterBasedSearch
         * @methodOf Vehicles.controller:VehicleListController
         * @description
         * This method search vehicles.
         * @param {Object} vehicle Vehicle search details.
         * @returns {Object} Vehicle list.
         **/
        $scope.filterBasedSearch = function (filter, id) {
            if (filter == 'type') {
                $scope.selected = $scope.vehicle.vehicle_type.indexOf(id);
                if ($scope.selected > -1) {
                    $scope.vehicle.vehicle_type.splice($scope.selected, 1);
                } else {
                    $scope.vehicle.vehicle_type.push(id);
                }
            }
            if (filter == 'fuel') {
                $scope.selected = $scope.vehicle.fuel_type.indexOf(id);
                if ($scope.selected > -1) {
                    $scope.vehicle.fuel_type.splice($scope.selected, 1);
                } else {
                    $scope.vehicle.fuel_type.push(id);
                }
            }
            $scope.search($scope.vehicle);
        };
        /**
         * @ngdoc method
         * @name sortVehicles
         * @methodOf Vehicles.controller:VehicleListController
         * @description
         * This method used to sort the vehicles.
         * @param {Object} type Vehicle type.
         * @returns {Object} Vehicle list.
         **/
        $scope.sortVehicles = function (type, sortby) {
            if (type == 'price') {
                $scope.sort_name = (sortby == 'desc') ? $filter("translate")("Highest Price") : $filter("translate")("Lowest Price");
            }
            if (type == 'rating') {
                $scope.sort_name = (sortby == 'desc') ? $filter("translate")("Higher Rating") : $filter("translate")("Lower Rating");
            }
            $scope.vehicle.sort = type;
            $scope.vehicle.sortby = sortby;
            $scope.search($scope.vehicle);
        };
        /**
         * @ngdoc method
         * @name vehicleBooking
         * @methodOf Vehicles.controller:VehicleListController
         * @description
         * This method used to store vehicle rentals.
         * @param {Object} vehicle Vehicle rental details.
         * @returns {Object} Vehicle list.
         **/
        $scope.vehicleBooking = function (vehicle_id, slug, $valid) {
            if ($valid && ($scope.vehicle.pickup_date > $scope.currentDate) && ($scope.vehicle.drop_date > $scope.vehicle.pickup_date)) {
                if ($rootScope.auth == undefined) {
                    $scope.vehicle = {
                        id: vehicle_id,
                        slug: slug,
                        start_date: $scope.vehicle.pickup_date,
                        end_date: $scope.vehicle.drop_date,
                        pickup_location_id: $scope.vehicle.pickup_location.id,
                        drop_location_id: $scope.vehicle.drop_location.id
                    };
                    localStorage.vehicle_search_value = JSON.stringify($scope.vehicle);
                    Flash.set($filter("translate")("Sign in for an account"), 'error', false);
                    $state.go('login');
                } else {
                    $scope.bookingObj = {
                        vehicle_id: vehicle_id,
                        item_booking_start_date: $scope.vehicle.pickup_date,
                        item_booking_end_date: $scope.vehicle.drop_date,
                        pickup_counter_location_id: $scope.vehicle.pickup_location.id,
                        drop_counter_location_id: $scope.vehicle.pickup_location.id
                    };
                    if ($scope.vehicle.drop_location) {
                        $scope.bookingObj.drop_counter_location_id = $scope.vehicle.drop_location.id;
                    }
                    VehicleBookingFactory.save($scope.bookingObj, function (response) {
                        $state.go('vehicle_detail', {'vehicle_rental_id': response.id});
                    }, function (error) {
                        Flash.set($filter("translate")(error.data.message), 'error', false);
                    });
                }
            }
        };

        /**
         * @ngdoc method
         * @name modalOpen
         * @methodOf Vehicles.controller:VehicleListController
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

