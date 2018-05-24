/**
 * BookorRent - v1.0a.01 - 2016-06-07
 *
 * Copyright (c) 2016 Agriya
 */
/**
 * @ngdoc object
 * @name Vehicles
 * @description
 *
 * This is the module for Vehicles.
 *
 * The vehicle module have initialize, directive and controllers. The vehicle module is used to vehicle the item with date and quantity.
 *
 * @param {Array.<string>=} dependencies The dependencies are included in main BookorRent.Banner module.
 *
 *        [
 *            'ui.router',
 *            'ngResource'
 *        ]
 * @param {controller=} Controller controller function for the module.
 * @returns {BookorRent.Vehicles} new BookorRent.Vehicles module.
 **/
(function (module) {


    /**
     * @ngdoc controller
     * @name Vehicles.controller:VehicleAllLsitController
     * @description
     *
     * This is VehicleAllLsitController having the methods init(), setMetaData(), and it defines the vehicle search related funtions.
     **/
    module.controller('VehicleAllLsitController', function ($state, $scope, $http, Flash, $filter, AuthFactory, $rootScope, $location, $timeout, VehicleSearchFactory, CounterLocationFactory, VehicleFilterFactory, VehicleBookingFactory, $uibModal, GetVehicleFactory) {
        $scope.maxSize = 5;
        $scope.indextab = 1;
        var params = {};
        $scope.vehicle = {};
        $scope.seatRange = {};
        $scope.dayPriceRange = {};
        $scope.hourPriceRange = {};
        $scope.maxRating = 5;
        $scope.sort_name = $filter("translate")("Sort by");
        $scope.status = {
            type:true,
            preference:true,
            fuel:true,
            seat:true,
            price:true
        };
        $scope.currentDate = new Date();
        $scope.vehicle.vehicle_type = [];
        $scope.vehicle.fuel_type = [];
        /**
         * @ngdoc method
         * @name setMetaData
         * @methodOf Vehicles.controller:VehicleAllLsitController
         * @description
         *
         * This method will set the meta data dynamically by using the angular.element.
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
         * @methodOf Vehicles.controller:VehicleAllLsitController
         * @description
         *
         * This method will search the vehicles based on the request.
         **/
        $scope.search = function(vehicle){
            vehicle.page = $scope.currentPage;
            VehicleSearchFactory.post(vehicle, function(response) {
                $scope.searchData = response.data;
                $scope.searchData.status = false;
                $scope._metadata = response.meta.pagination;
                angular.forEach($scope.searchData, function(value, key) {
                    //if number is decimal return only integer
                    value.roundedRating = value.feedback_rating | 0;
					value.open_pickup = false;
					value.open_drop = false;
                });
            });
        };
        /**
         * @ngdoc method
         * @name getFilterValues
         * @methodOf Vehicles.controller:VehicleSearchController
         * @description
         *
         * This method will get the list of filters.
         **/
        $scope.getFilterValues = function() {
            VehicleFilterFactory.get().$promise.then(function(response) {
                $scope.vehicle_company_lists = response.vehicle_company_list;
                $scope.vehicle_type_lists = response.vehicle_type_list;
                $scope.seats = response.settings.seats;
                $scope.fuel_lists = response.fuel_type_list;
                $scope.vehicle_price = response.vehicle_type_price;
                //Set seat slider value
                $scope.seatRange = {
                    min:1,
                    max:parseInt($scope.seats),
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
                //if length > 0,display price range
                $scope.vehicle_price_length = Object.keys($scope.vehicle_price).length;
                //Set day and hour price slider value
                var minDayPrice, maxDayPrice, minHourPrice, maxHourPrice;
                minDayPrice = $scope.vehicle_price.min_day_price;
                maxDayPrice = $scope.vehicle_price.max_day_price;
                minHourPrice = $scope.vehicle_price.min_hour_price;
                maxHourPrice = $scope.vehicle_price.max_hour_price;
                //Day price filter
                $scope.dayPriceRange = {
                    min:parseFloat(minDayPrice),
                    max:parseFloat(maxDayPrice),
                    options: {
                        floor: parseFloat(minDayPrice),
                        ceil: parseFloat(maxDayPrice),
                        onEnd: function () {
                            $scope.vehicle.price_type = 'day';
                            $scope.vehicle.price_min = $scope.dayPriceRange.min;
                            $scope.vehicle.price_max = $scope.dayPriceRange.max;
                            $scope.search($scope.vehicle);
                            //reset if day price set
                            $scope.hourPriceRange.min = minHourPrice;
                            $scope.hourPriceRange.max = maxHourPrice;
                        }
                    }
                }
                //hour price filter
                $scope.hourPriceRange = {
                    min:parseFloat(minHourPrice),
                    max:parseFloat(maxHourPrice),
                    options: {
                        floor: parseFloat(minHourPrice),
                        ceil: parseFloat(maxHourPrice),
                        onEnd: function () {
                            $scope.vehicle.price_type = 'hour';
                            $scope.vehicle.price_min = $scope.hourPriceRange.min;
                            $scope.vehicle.price_max = $scope.hourPriceRange.max;
                            $scope.search($scope.vehicle);
                            //reset if day price set
                            $scope.dayPriceRange.min = minDayPrice;
                            $scope.dayPriceRange.max = maxDayPrice;
                        }
                    }
                }
            });
        };
        /**
         * @ngdoc method
         * @name init
         * @methodOf Vehicles.controller:VehicleAllLsitController
         * @description
         *
         * This method will initialize the meta data and functionalities.
         **/
        $scope.init = function () {
            $rootScope.pageTitle = $rootScope.settings['site.name'] + " | " + $filter("translate")("All Vehicles");
            $scope.vehicle.price_type = 'day';
            $scope.setMetaData();
            $scope.currentPage = ($scope.currentPage !== undefined) ? parseInt($scope.currentPage) : 1;
            $scope.getFilterValues();
            $scope.vehicle.page = 1;
            $scope.search($scope.vehicle);

            //Vehicle rating
            $scope.maxRatings = [];
            for (var i = 0; i < $scope.maxRating; i++) {
                $scope.maxRatings.push(i);
            }
        };
        $scope.init();
        $scope.paginate = function(pageno) {
            $scope.currentPage = parseInt($scope.currentPage);
            $scope.search($scope.vehicle);
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
         * @name filterBasedSearch
         * @methodOf Vehicles.controller:VehicleAllLsitController
         * @description
         *
         * This method will list the vehicles based on filters.
         **/

        $scope.filterBasedSearch = function(filter,id) {
            if(filter == 'type') {
                $scope.selected = $scope.vehicle.vehicle_type.indexOf(id);
                if($scope.selected > -1) {
                    $scope.vehicle.vehicle_type.splice($scope.selected, 1);
                } else {
                    $scope.vehicle.vehicle_type.push(id);
                }
            }
            if(filter == 'fuel') {
                $scope.selected = $scope.vehicle.fuel_type.indexOf(id);
                if($scope.selected > -1) {
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
         * @methodOf Vehicles.controller:VehicleAllLsitController
         * @description
         *
         * This method will sort the vehicles.
         **/
        $scope.sortVehicles = function(price_type, type, sortby) {
            if(type == 'price') {
                $scope.sort_name = (sortby == 'desc') ? $filter("translate")("Highest Price") : $filter("translate")("Lowest Price");
                $scope.vehicle.sort_by_price = price_type;
            }
            if(type == 'rating') {
                $scope.sort_name = (sortby == 'desc') ? $filter("translate")("Higher Rating") : $filter("translate")("Lower Rating");
            }
            $scope.vehicle.sort = type;
            $scope.vehicle.sortby = sortby;
            $scope.search($scope.vehicle);
        };

        /**
         * @ngdoc method
         * @name modalOpen
         * @methodOf Vehicles.controller:VehicleAllLsitController
         * @description
         * This method will initialze the page. It pen the modal with vehicle feedbacks.
         *
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
        /**
         * @ngdoc method
         * @name openBookitModal
         * @methodOf Vehicles.controller:VehicleAllLsitController
         * @description
         * This method will initialze the page. It pen the modal with vehicle feedbacks.
         *
         **/
        $scope.openBookitModal = function (size, vehicle_id) {
            GetVehicleFactory.get({'id':vehicle_id}).$promise.then(function(response) {
                $scope.vehicleDetails = response;
                var modalInstance = $uibModal.open({
                    templateUrl: 'Plugins/Vehicles/vehicle_bookit.tpl.html',
                    controller: 'VehicleBookitController',
                    size: size,
                    resolve: {
                        vehicleDetails: function () {
                            return  $scope.vehicleDetails;
                        }
                    }
                });
            });

        };

        $scope.refreshSlider = function () {
            $timeout(function () {
                $scope.$broadcast('rzSliderForceRender');
            });
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
        $scope.VehicleBooking = function (vehicle_id, slug, $valid) {
            if ($valid && ($scope.vehicle.pickup_date > $scope.currentDate) && ($scope.vehicle.drop_date > $scope.vehicle.pickup_date)) {
                if ($rootScope.auth == undefined) {
                    $scope.vehicle = {
                        id: vehicle_id,
                        slug: slug,
                        start_date: $scope.vehicle.pickup_date,
                        end_date: $scope.vehicle.drop_date,
                        pickup_location_id: $scope.vehicle.pickup_location.id,
                        drop_location_id: $scope.vehicle.pickup_location.id
                    };
					if ($scope.vehicle.drop_location) {
                        $scope.vehicle.drop_location_id = $scope.vehicle.drop_location.id;
                    }
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
                        $state.go('vehicle_detail', {'vehicle_rental_id': response.id})
                    }, function (error) {
                        Flash.set($filter("translate")(error.data.message), 'error', false);
                    });
                }
            }
        };
    });
}(angular.module("BookorRent.Vehicles")));

