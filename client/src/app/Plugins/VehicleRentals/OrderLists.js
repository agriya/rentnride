(function (module) {
    /**
     * @ngdoc controller
     * @name VehicleRentals.controller:OrderListsController
     * @description
     * This is items controller having the methods init(), setMetaData().
     * It controls the functionality of items.
     **/
    module.controller('OrderListsController', function ($scope, $rootScope, $filter, Flash, $state, $location, ItemsOrderFactory, VehicleRentalStatusFactory, ConstItemUserStatus, ConfirmVehicleRental, RejectVehicleRental, $stateParams, checkInFactory) {
        model = this;
        $scope.maxSize = 5;
        $scope.ConstItemUserStatus = ConstItemUserStatus;
        var params = {};
        $scope.statusID = 0;
        $scope.status_slug = 'all';

        /**
         * @ngdoc method
         * @name setMetaData
         * @methodOf VehicleRentals.controller:OrderListsController
         * @description
         * This method will set the meta data dynamically by using the angular.element.
         * @returns {Element} New meta data element.
         **/
        $scope.setMetaData = function () {
            var pageTitle = $filter("translate")("Item orders");
            var fullUrl = $location.absUrl();
            var appUrl = $rootScope.settings['scheme_name'] + ":/" + $location.url();
            angular.element('html head meta[property="og:title"], html head meta[name="twitter:title"]').attr("content", $rootScope.settings['site.name'] + " | " + pageTitle);
            angular.element('meta[property="al:ios:url"], meta[property="al:ipad:url"], meta[property="al:android:url"], meta[property="al:windows_phone:url"], html head meta[name="twitter:app:url:iphone"], html head meta[name="twitter:app:url:ipad"], html head meta[name="twitter:app:url:googleplay"]').attr('content', appUrl);
            angular.element('meta[property="og:url"]').attr('content', fullUrl);
        };
        /**
         * @ngdoc method
         * @name getItemOrders
         * @methodOf VehicleRentals.controller:OrderListsController
         * @description
         * This method will initialze the page. It returns the item orders.
         * @param {integer} params Page no.
         * @returns {Array} Order Order details.
         */
        $scope.getItemOrders = function () {
            params = {'page': $scope.currentPage};
            if ($scope.statusID !== undefined && $scope.statusID !== 0) {
                params = {'item_user_status_id': $scope.statusID, 'page': $scope.currentPage};
            }
            ItemsOrderFactory.get(params).$promise.then(function (response) {
                $scope.itemOrders = response.data;
                $scope._metadata = response.meta.pagination;
            });
        };
        /**
         * @ngdoc method
         * @name init
         * @methodOf VehicleRentals.controller:OrderListsController
         * @description
         * This method will initialze the page. It returns the page title.
         **/
        $scope.init = function () {
            $scope.setMetaData();
            $scope.currentPage = (model.currentPage !== undefined) ? parseInt(model.currentPage) : 1;
            $rootScope.pageTitle = $rootScope.settings['site.name'] + " | " + $filter("translate")("Item Orders");
            $scope.statusID = ($stateParams.statusID !== undefined) ? parseInt($stateParams.statusID) : 0;
            $scope.status_slug = ($stateParams.slug !== undefined) ? $stateParams.slug : 'all';
            // get orders listing
            $scope.getItemOrders();
            //Get booking status
            $scope.getRentalStatus();

            //from email, click accept or reject
            if($stateParams.vehicle_order_id !== undefined) {
                var order_id = $stateParams.vehicle_order_id;
                if( $stateParams.action == 'confirm') {
                    $scope.VehicleRentalConfirm(order_id);
                }
                if($stateParams.action == 'reject') {
                    $scope.VehicleRentalReject(order_id);
                }
            }
        };
        /**
         * @ngdoc method
         * @name getRentalStatus
         * @methodOf VehicleRentals.controller:VehicleRentalController
         * @description
         * This method will be load rental status.
         * @param {string} user_type User type.
         * @returns {Array} Status Order status.
         **/
        $scope.getRentalStatus = function () {
            if ($rootScope.OrderItemUserStatus == undefined) {
                VehicleRentalStatusFactory.get({'filter': 'host'}).$promise.then(function (response) {
                    $scope.itemUserStatus = response.item_user_statuses;
                    $rootScope.OrderItemUserStatus = response.item_user_statuses;
                });
            } else {
                $scope.itemUserStatus = $rootScope.OrderItemUserStatus;
            }
        };
        /**
         * @ngdoc method
         * @name paginate
         * @methodOf VehicleRentals.controller:OrderListsController
         * @description
         * This method will be load pagination the pages.
         **/
        $scope.paginate = function (pageno) {
            $scope.currentPage = parseInt($scope.currentPage);
            $scope.getItemOrders();
        };

        /**
         * @ngdoc method
         * @name filterOrder
         * @methodOf VehicleRentals.controller:OrderListsController
         * @description
         * This method will initialze the page. It returns item orders with filters
         *
         **/
        $scope.filterOrder = function (id, slug) {
            $state.go('orders', {statusID: id, slug: slug});
        };
        /**
         * @ngdoc method
         * @name VehicleRentalConfirm
         * @methodOf VehicleRentals.controller:OrderListsController
         * @description
         * This method is used to confirm the booked item.
         * @param {integer} vehicle_rental_id Rental identifier.
         * @returns {Array} Success or failure message.
         */
        $scope.VehicleRentalConfirm = function (vehicle_rental_id) {
            ConfirmVehicleRental.confirm({id: vehicle_rental_id}).$promise.then(function (response) {
                Flash.set($filter("translate")("VehicleRental Confirmed Successfully!"), 'success', true);
                $state.go('orders', {statusID: $scope.ConstItemUserStatus.Confirmed, slug: 'confirmed'});
            }, function (error) {
                Flash.set($filter("translate")("VehicleRental Could not be updated!"), 'error', false);
            });
        };

        /**
         * @ngdoc method
         * @name VehicleRentalReject
         * @methodOf VehicleRentals.controller:OrderListsController
         * @description
         * This method is used to reject the booked item.
         * @param {integer} vehicle_rental_id Rental identifier.
         * @returns {Array} Success or failure message.
         */
        $scope.VehicleRentalReject = function (id) {
            RejectVehicleRental.reject({
                id: id
            }).$promise.then(function (data) {
                Flash.set($filter("translate")("VehicleRental Rejected Successfully!"), 'success', true);
                $state.go('orders', {statusID: $scope.ConstItemUserStatus.Rejected, slug: 'rejected'});
            }, function (error) {
                errmsg = (error.data.message != undefined) ? error.data.message : "VehicleRental could not be rejected";
                Flash.set($filter("translate")(errmsg), 'error', false);
            });
        };

        $scope.init();
        /**
         * @ngdoc method
         * @name orderCheckin
         * @methodOf VehicleRentals.controller:OrderListsController
         * @description
         * This method is used to check in.
         * @param {integer} order_id Order identifier.
         * @returns {Array} Success or failure message.
         */
        $scope.orderCheckin = function (order_id) {
            checkInFactory.checkin({'id': order_id}, function (response) {
                Flash.set($filter("translate")("VehicleRental has been checked-in successfully!"), 'success', true);
                $state.go('orders', {statusID: $scope.ConstItemUserStatus.Attended, slug: 'attended'});
            }, function (error) {
                Flash.set($filter("translate")("VehicleRental could not be updated. Please, try again"), 'error', false);
            });
        };
    });
}(angular.module("BookorRent.VehicleRentals")));
