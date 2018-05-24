(function (module) {
    /**
     * @ngdoc controller
     * @name User.controller:DashboardController
     * @description
     *
     * This is dashboard controller. It contains all the details about the user. It fetches the data of the user by using AuthFactory.
     **/
    module.controller('DashboardController', function ($state, $scope, $http, Flash, AuthFactory, GENERAL_CONFIG, $filter, $rootScope, ConstThumb, ConstSocialLogin, MyVehiclesFactory, statsFactory, $uibModal) {
        var model = this;
        /**
         * @ngdoc method
         * @name init
         * @methodOf User.controller:DashboardController
         * @description
         * This method will initialze the page. It returns the user's details.
         *
         **/
        model.init = function () {
            $rootScope.pageTitle = $rootScope.settings['site.name'] + " | " + $filter("translate")("User Dashboard");
            //Get user details
            AuthFactory.fetch({}).$promise
                .then(function (response) {
                    $scope.user = response;
                    $rootScope.vehicle_company = response.vehicle_company;
                });
            model.ConstSocialLogin = ConstSocialLogin;
            model.thumb = ConstThumb.user;
            model.getStats();
            model.myVehicles();
            model.myvehicleTpl = "Plugins/Vehicles/my_vehicles.tpl.html";

        };
		
		model.maintenance = function (vehicle_id) {    // This function is used in Plugins/Vehicles/my_vehicles.tpl.html for maintenance
            $state.go("maintenanceVehicles", {'vehicle_id': vehicle_id});
        };
		
        model.myVehicles = function() {
            MyVehiclesFactory.get({'user_id': $rootScope.auth.id, 'page':model.currentPage}).$promise.then(function (response) {
                model.vehicles = response.data;
                angular.forEach(model.vehicles , function(value, key) {
                    value.roundedRating = value.feedback_rating | 0;
                });
                model._metadata = response.meta.pagination;
            });
            //Vehicle rating
            $scope.maxRatings = [];
            $scope.maxRating = 5;
            for (var i = 0; i < $scope.maxRating; i++) {
                $scope.maxRatings.push(i);
            }
        };
        model.paginate = function (pageno) {
            model.currentPage = parseInt(model.currentPage);
            model.myVehicles();
        };
        model.getStats = function() {
            statsFactory.get().$promise.then(function(data) {
                if(data.response) {
                    model.bookingStats = data.response.booking;
                    model.hostStats = data.response.host;
                    model.orderCount = data.response.total_order_count;
                    model.bookingCount = data.response.total_booking_count;
                }
            });
        };
        model.init();
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
        model.payNow = function(vehicle_id) {
            $state.go("vehiclePaynow", {'vehicle_id':vehicle_id});
        };
    });
}(angular.module("BookorRent.user")));
