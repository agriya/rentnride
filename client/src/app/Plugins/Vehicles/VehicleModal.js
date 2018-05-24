(function (module) {
    /**
     * @ngdoc controller
     * @name Vehicles.controller:VehicleModalController
     * @description
     * This is modal controller. It contains all the details about the user. It fetches the data of the feedbacks
     **/
    module.controller('VehicleModalController', function ($state, $scope, $http, Flash, AuthFactory, GENERAL_CONFIG, $filter, $rootScope, $uibModalInstance, vehicle_id, GetVehicleFeedbackFactory, moment) {
        var model = this;
        $scope.maxSize = 5;
        /**
         * @ngdoc method
         * @name getVehicleFeedbacks
         * @methodOf Vehicles.controller:VehicleModalController
         * @description
         * This method is used to get vehicle feedbacks.
         * @param {integer} vehicle_id Vehicle identifier.
         * @returns {Object} Vehicle feedbacks.
         */
        $scope.getVehicleFeedbacks = function () {
            GetVehicleFeedbackFactory.get({vehicle_id: vehicle_id, page: $scope.currentPage}).$promise.then(function (response) {
                $scope.vehicleFeedbacks = response.data;
                $scope.vehicle_metadata = response.meta.pagination;
            });
        };
        /**
         * @ngdoc method
         * @name init
         * @methodOf Vehicles.controller:VehicleModalController
         * @description
         * This method will initialze the page. It returns the page title.
         **/
        model.init = function () {
            $scope.currentPage = ($scope.currentPage !== undefined) ? parseInt($scope.currentPage) : 1;
            $scope.maxRatings = [];
            $scope.maxRating = 5;
            for (var i = 1; i <= $scope.maxRating; i++) {
                $scope.maxRatings.push(i);
            }
            $scope.getVehicleFeedbacks();
        };
        model.init();
        $scope.modalClose = function () {
            $uibModalInstance.dismiss('close');
        };
        //Go to user page
        $scope.userDashboard = function (name) {
            $uibModalInstance.dismiss('close');
            $state.go('userView', {'username': name});
        };
        /**
         * @ngdoc method
         * @name paginate
         * @methodOf Vehicles.controller:VehicleModalController
         * @description
         * This method will be load pagination the pages.
         **/
        $scope.paginate = function () {
            $scope.currentPage = parseInt($scope.currentPage);
            $scope.getVehicleFeedbacks();
        };
    });
}(angular.module("BookorRent.Vehicles")));
