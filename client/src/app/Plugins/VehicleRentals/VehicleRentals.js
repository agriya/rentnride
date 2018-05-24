/**
 * BookorRent - v1.0a.01 - 2016-03-28
 *
 * Copyright (c) 2016 Agriya
 */
/**
 * @ngdoc object
 * @name VehicleRentals
 * @description
 *
 * This is the module for VehicleRentals.
 *
 * The VehicleRentals module have initialize, directive and controllers. The booking module is used to booking the item with date and quantity.
 *
 * @param {Array.<string>=} dependencies The dependencies are included in main BookorRent.Banner module.
 *
 *        [
 *            'ui.router',
 *            'ngResource'
 *        ]
 * @returns {BookorRent.VehicleRentals} new BookorRent.VehicleRentals module.
 **/
(function (module) {
    /**
     * @ngdoc directive
     * @name VehicleRentals.directive:coupon
     * @scope
     * @restrict EA
     * @description
     * coupon directive used to load the coupon template.
     * @param {string} coupon Name of the directive
     **/
    module.directive('coupon', function () {
        var linker = function (scope, element, attrs) {
            // do DOM Manipulation here
        };
        return {
            restrict: 'A',
            templateUrl: 'Plugins/VehicleRentals/coupon.tpl.html',
            link: linker,
            controller: 'CouponController as model',
            bindToController: true,
            scope: {
                filter: '@filter'
            }
        };
    });
    /**
     * @ngdoc controller
     * @name VehicleRentals.controller:VehicleRentalController
     * @description
     * This is VehicleRentalController having the methods init(), setMetaData(), and it defines the vehicle rental related funtions.
     **/
    module.controller('VehicleRentalController', function ($state, $scope, $http, Flash, $filter, VehicleRentalFactory, VehicleRentalCancelFactory, AuthFactory, $rootScope, $location, ConstItemUserStatus, VehicleRentalStatusFactory, $stateParams) {
        var model = this;
        $scope.maxSize = 5;
        $scope.ConstItemUserStatus = ConstItemUserStatus;
        var params = {};
        $scope.statusID = 0;
        $scope.status_slug = 'all';
        $scope.booking = {};
        $scope.isPayment = false;
        /**
         * @ngdoc method
         * @name setMetaData
         * @methodOf VehicleRentals.controller:VehicleRentalController
         * @description
         * This method will set the meta data dynamically by using the angular.element.
         * @returns {Element} New meta data element.
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
         * @name getVehicleRentalList
         * @methodOf VehicleRentals.controller:VehicleRentalController
         * @description
         * This method will get vehicle rental list.
         * @param {integer} rental_status_id Rental status identifier.
         * @returns {Object} Vehicle rental list.
         **/
        $scope.getVehicleRentalList = function () {
            param = {'page': $scope.currentPage};
            $rootScope.pageTitle = $rootScope.settings['site.name'] + " | " + $filter("translate")("VehicleRental");
            if ($scope.statusID !== undefined && $scope.statusID !== 0) {
                param = {'item_user_status_id': $scope.statusID, 'page': $scope.currentPage};
            }
            VehicleRentalFactory.filter(param).$promise.then(function (response) {
                $.each(response.data, function (i, record) {
                    if(response.data[i].item_user_status_id == ConstItemUserStatus.BookerReviewed){
                        response.data[i].item_user_status.name = 'completed';
                    }
                });
                $scope.VehicleRentalLists = response.data;
                $scope._metadata = response.meta.pagination;
            });
        };
        /**
         * @ngdoc method
         * @name VehicleRentalCancel
         * @methodOf VehicleRentals.controller:VehicleRentalController
         * @description
         * This method is used to cancel the booked item.
         * @param {integer} rental_id Rental identifier.
         * @returns {Array} Success or failure message.
         */
        $scope.VehicleRentalCancel = function (id) {
            VehicleRentalCancelFactory.cancel({
                id: id
            }).$promise.then(function (data) {
                Flash.set($filter("translate")("VehicleRental Cancelled Successfully!"), 'success', true);
                $state.go('vehicle_rental_list_status', {statusID: $scope.ConstItemUserStatus.Cancelled, slug: 'cancelled'});
            }, function (error) {
                errmsg = (error.data.message != undefined) ? error.data.message : "VehicleRental could not be cancelled";
                Flash.set($filter("translate")(errmsg), 'error', false);
            });
        };

        /**
         * @ngdoc method
         * @name init
         * @methodOf VehicleRentals.controller:VehicleRentalController
         * @description
         * This method will initialize the meta data and functionalities.
         **/
        $scope.init = function () {
            $scope.setMetaData();
            $scope.currentPage = ($scope.currentPage !== undefined) ? parseInt($scope.currentPage) : 1;
            $scope.statusID = ($stateParams.statusID !== undefined) ? $stateParams.statusID : 0;
            $scope.status_slug = ($stateParams.slug !== undefined) ? $stateParams.slug : 'all';
            if ($scope.statusID == 'status') {
                if ($scope.status_slug == 'fail') {
                    Flash.set($filter("translate")("VehicleRental could not be completed, please try again."), 'error', false);
                } else if ($scope.status_slug == 'success') {
                    Flash.set($filter("translate")("Vehicle booked successfully"), 'success', true);
                }
                $state.go('vehicle_rental_list_status', {statusID: 0, slug: 'all'});
            } else {
                $scope.statusID = parseInt($scope.statusID);
            }
            //Get booking status
            $scope.getRentalStatus();
            $scope.getVehicleRentalList();
            //from email, click cancel
            if($stateParams.vehicle_rental_id !== undefined && $stateParams.action == 'cancel') {
                var rental_id = $stateParams.vehicle_rental_id;
                $scope.VehicleRentalCancel(rental_id);
            }

        };
        /**
         * @ngdoc method
         * @name getRentalStatus
         * @methodOf VehicleRentals.controller:VehicleRentalController
         * @description
         * This method will be load rental status.
         * @returns {Array} Rental Status.
         **/
        $scope.getRentalStatus = function () {
            if ($rootScope.BookingItemUserStatus == undefined) {
                VehicleRentalStatusFactory.get({'filter': 'booker'}).$promise.then(function (response) {
                    $scope.itemUserStatus = response.item_user_statuses;
                    $rootScope.BookingItemUserStatus = response.item_user_statuses;
                });
            } else {
                $scope.itemUserStatus = $rootScope.BookingItemUserStatus;
            }
        };
        /**
         * @ngdoc method
         * @name paginate
         * @methodOf VehicleRentals.controller:VehicleRentalController
         * @description
         * This method will be load pagination the pages.
         **/
        $scope.paginate = function (pageno) {
            $scope.currentPage = parseInt($scope.currentPage);
            $scope.getVehicleRentalList();
        };
        /**
         * @ngdoc method
         * @name filterVehicleRental
         * @methodOf VehicleRentals.controller:VehicleRentalController
         * @description
         * This method will be redirect to status based.
         **/
        $scope.filterVehicleRental = function (id, slug) {
            $state.go('vehicle_rental_list_status', {statusID: id, slug: slug});
        };
        $scope.init();

        /**
         * @ngdoc method
         * @name openCalendar
         * @methodOf VehicleRentals.controller:VehicleRentalController
         * @description
         * This method used to open a calendar.
         **/
        $scope.openCalendar = function (e, date) {
            $scope.open[date] = true;
        };

        $scope.open = {
            date: false
        };
        /**
         * @ngdoc method
         * @name BookFormSubmit
         * @methodOf VehicleRentals.controller:VehicleRentalController
         * @description
         * This method is used to store rental details.
         * @param {Array} rental Rental details.
         * @returns {Array} Success or failure message.
         */
        $scope.BookFormSubmit = function ($valid) {
            if ($valid) {
                $scope.booking.item_id = $state.params.item_id;
                VehicleRentalFactory.save($scope.booking, function (response) {
                    $scope.booking = {};
                    $scope.BookAddForm.$setPristine();
                    $scope.BookAddForm.$setUntouched();
                    Flash.set($filter("translate")("VehicleRental Added Successfully!"), 'success', true);
                    $state.go('order', {id: response.item_userable_id, vehicle_rental_id: response.id});
                }, function (error) {
                    $scope.dateErr = '';
                    $scope.quantityErr = '';
                    var errorResponse = error.data.errors;
                    if (errorResponse.item_booking_start_date) {
                        $scope.dateErr = $filter("translate")(errorResponse.item_booking_start_date[0]);
                    }
                    if (errorResponse.quantity) {
                        $scope.quantityErr = $filter("translate")(errorResponse.quantity[0]);
                    }
                    Flash.set($filter("translate")("VehicleRental Could not be added!"), 'error', false);
                });
            }
        };
        /**
         * @ngdoc method
         * @name vehicleRentalPaynow
         * @methodOf VehicleRentals.controller:VehicleRentalController
         * @description
         * This method is used to pay rental amount.
         * @param {integer} order_id Order details.
         * @returns {Array} Success or failure message.
         */
            //if booker details not updated move to vehicle rental update page
        $scope.vehicleRentalPaynow = function (order_id) {
            VehicleRentalFactory.get({id: order_id}).$promise.then(function (response) {
                if (response.booker_detail) {
                    $state.go('order', {'vehicle_rental_id': order_id});
                } else {
                    $state.go('vehicle_detail', {'vehicle_rental_id': order_id});
                }
            }, function (error) {
				Flash.set($filter("translate")(error.data.message), 'error', true);
			});
        };

    });
}(angular.module("BookorRent.VehicleRentals")));

