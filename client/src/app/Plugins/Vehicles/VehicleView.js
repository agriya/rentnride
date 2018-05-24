(function (module) {
    /**
     * @ngdoc controller
     * @name Vehicles.controller:VehicleViewController
     * @description
     * This is modal controller. It contains all the details about the vehicle view.
     **/
    module.controller('VehicleViewController', function ($state, $scope, $http, Flash, $location, AuthFactory, GENERAL_CONFIG, $filter, $rootScope, GetVehicleFactory, GetVehicleFeedbackFactory, $uibModal, ConstDurationTypes, ConstDiscountTypes, moment) {
        var model = this;
        $scope.maxSize = 5;
        $scope.ConstDiscountTypes = ConstDiscountTypes;
        $scope.ConstDurationTypes = ConstDurationTypes;
        $scope.socialShareDetails = {};
        /**
         * @ngdoc method
         * @name getVehicle
         * @methodOf Vehicles.controller:VehicleViewController
         * @description
         * This method is used to get vehicle details.
         * @param {integer} vehicle_id Vehicle identifier.
         * @returns {Object} Vehicle details.
         */
        $scope.getVehicle = function () {
            GetVehicleFactory.get({id: $state.params.id}).$promise.then(function (response) {
                $scope.vehicle = response;
                $scope.vehicle.roundedRating = response.feedback_rating | 0;
                $scope.pickup_locations = response.pickup_locations;
                $scope.drop_locations = response.drop_locations;
                if (response.vehicle_type && response.vehicle_type.vehicle_type_extra_accessory) {
                    $scope.vehicle_extra_accessories = response.vehicle_type.vehicle_type_extra_accessory.data;
                }
                if (response.vehicle_type && response.vehicle_type.vehicle_type_insurance) {
                    $scope.vehicle_insurance = response.vehicle_type.vehicle_type_insurance.data;
                }
                if (response.vehicle_type && response.vehicle_type.vehicle_type_fuel_option) {
                    $scope.vehicle_fuel_option = response.vehicle_type.vehicle_type_fuel_option.data;
                }
                if (response.vehicle_type && response.vehicle_type.vehicle_type_tax) {
                    $scope.vehicle_taxes = response.vehicle_type.vehicle_type_tax.data;
                }
                if (response.vehicle_type && response.vehicle_type.vehicle_type_surcharge) {
                    $scope.vehicle_surcharges = response.vehicle_type.vehicle_type_surcharge.data;
                }
                $scope.socialShareDetails = {
                    name : $scope.vehicle.name,
                    image: $scope.vehicle.attachments.thumb.large,
                    rating: $scope.vehicle.feedback_rating,
                    url: $scope.currentUrl
                };
            });
        };
        /**
         * @ngdoc method
         * @name getVehicleFeedbacks
         * @methodOf Vehicles.controller:VehicleViewController
         * @description
         * This method is used to get vehicle feedback details.
         * @param {integer} vehicle_id Vehicle identifier.
         * @returns {Object} Vehicle feedback details.
         */
        $scope.getVehicleFeedbacks = function () {
            GetVehicleFeedbackFactory.get({vehicle_id: $state.params.id, page: model.currentPage}).$promise.then(function (response) {
                $scope.vehicleFeedbacks = response.data;
                $scope.vehicle_feedback_metadata = response.meta.pagination;
            });
        };
        /**
         * @ngdoc method
         * @name init
         * @methodOf Vehicles.controller:VehicleViewController
         * @description
         * This method will initialize the functionalities
         **/
        $scope.init = function () {
            $scope.vehicle_search_value = localStorage.getItem('vehicle_search_value');
            localStorage.removeItem('vehicle_search_value');
            $scope.currentUrl = $location.absUrl();
            model.currentPage = (model.currentPage !== undefined) ? parseInt(model.currentPage) : 1;
            $scope.maxRatings = [];
            $scope.maxRating = 5;
            for (var i = 0; i < $scope.maxRating; i++) {
                $scope.maxRatings.push(i);
            }
            $scope.getVehicle();
            $scope.getVehicleFeedbacks();
        };
        $scope.init();
        $scope.paginate = function (pageno) {
            model.currentPage = parseInt(model.currentPage);
            $scope.getVehicleFeedbacks();
        };
        /**
         * @ngdoc method
         * @name openBookitModal
         * @methodOf Vehicles.controller:VehicleViewController
         * @description
         * This method will initialze the page. It pen the modal with vehicle.
         **/
        $scope.openBookitModal = function (size) {
            var modalInstance = $uibModal.open({
                templateUrl: 'Plugins/Vehicles/vehicle_bookit.tpl.html',
                controller: 'VehicleBookitController',
                size: size,
                resolve: {
                    vehicleDetails: function () {
                        return $scope.vehicle;
                    },
                    searchValue: function () {
                        return $scope.vehicle_search_value;
                    }
                }
            });
        };
    });
}(angular.module("BookorRent.Vehicles")));
