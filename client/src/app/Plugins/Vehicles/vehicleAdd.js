(function (module) {
    /**
     * @ngdoc controller
     * @name Vehicles.controller:VehicleAddController
     * @description
     * This is VehicleAddController having the methods init(), setMetaData(). It controls the functionality of add vehicle.
     **/
    module.controller('VehicleAddController', function ($scope, $rootScope, $filter, Flash, $state, $location, Upload, GENERAL_CONFIG, VehicleRelatedFactory, VehicleModelFactory, VehicleTypeFactory, VehicleCompanyFactory) {
        model = this;
        $scope.vehicle = {};
        $scope.vehicle.pickup_counter_locations = [];
        $scope.vehicle.drop_counter_locations = [];
        /**
         * @ngdoc method
         * @name setMetaData
         * @methodOf Vehicles.controller:VehicleAddController
         * @description
         * This method will set the meta data dynamically by using the angular.element.
         * @returns {Element} New meta data element.
         **/
        model.setMetaData = function () {
            var pageTitle = $filter("translate")("Add Vehicle");
            var fullUrl = $location.absUrl();
            var appUrl = $rootScope.settings['scheme_name'] + ":/" + $location.url();
            angular.element('html head meta[property="og:title"], html head meta[name="twitter:title"]').attr("content", $rootScope.settings['site.name'] + " | " + pageTitle);
            angular.element('meta[property="al:ios:url"], meta[property="al:ipad:url"], meta[property="al:android:url"], meta[property="al:windows_phone:url"], html head meta[name="twitter:app:url:iphone"], html head meta[name="twitter:app:url:ipad"], html head meta[name="twitter:app:url:googleplay"]').attr('content', appUrl);
            angular.element('meta[property="og:url"]').attr('content', fullUrl);
        };
        /**
         * @ngdoc method
         * @name init
         * @methodOf Vehicles.controller:VehicleAddController
         * @description
         * This method will initialze the page. It returns the page title.
         **/
        model.init = function () {
            model.setMetaData();
            $scope.driver_min_age = $rootScope.settings['vehicle.driver_min_age'];
            $scope.driver_max_age = $rootScope.settings['vehicle.driver_max_age'];
            $rootScope.pageTitle = $rootScope.settings['site.name'] + " | " + $filter("translate")("Add Vehicle");
            VehicleRelatedFactory.get().$promise.then(function (response) {
                $scope.vehicleCompanies = response.vehicle_company_list;
                $scope.vehicleTypes = response.vehicle_type_list;
                $scope.vehicleMakes = response.vehicle_make_list;
                $scope.counter_locations = response.counter_location_list;
                $scope.fuelTypes = response.fuel_type_list;
                $scope.seats = parseInt(response.settings.seats);
                $scope.doors = parseInt(response.settings.doors);
                $scope.small_bags = parseInt(response.settings.small_bags);
                $scope.large_bags = parseInt(response.settings.large_bags);
                $scope.gears = parseInt(response.settings.gears);
                $scope.air_bags = parseInt(response.settings.airbags);

                //covert counter location object to array
                $scope.vehicle_counter_locations = [];
                angular.forEach ($scope.counter_locations, function(value, key){
                    var obj = {};
                    obj.id = value;
                    obj.name = key;
                    $scope.vehicle_counter_locations.push(obj);
                });
            });

            $scope.status = ($state.params.status !== undefined) ? $state.params.status : 0;
            if ($scope.status == 'fail') {
                Flash.set($filter("translate")("Vehicle Add could not be completed, please try again."), 'error', false);
                $state.go("myVehicles");

            } else if ($scope.status == 'success') {
                if($rootScope.settings['vehicle.auto_approve'] == 0) {
                    Flash.set($filter("translate")("Admin need to approve the vehicle."), 'success', true);
                } else {
                    Flash.set($filter("translate")("Vehicle Add successfully completed."), 'success', true);
                }
                $state.go("myVehicles");
            }
        };
        model.init();
        /**
         * @ngdoc method
         * @name getVehicleModel
         * @methodOf Vehicles.controller:VehicleAddController
         * @description
         * This method will get vehicle models.
         * @param {integer} vehicle_make_id Vehicle make identifier.
         * @returns {Object} Vehicle models list.
         **/
        $scope.getVehicleModel = function (vehicle_make_id) {
            VehicleModelFactory.get({'vehicle_make_id': vehicle_make_id}).$promise.then(function (response) {
                $scope.vehicleModels = response.data;
            });
        };
        /**
         * @ngdoc method
         * @name getVehicleTypePrice
         * @methodOf Vehicles.controller:VehicleAddController
         * @description
         * This method will get vehicle types.
         * @param {integer} vehicle_type_id Vehicle type identifier.
         * @returns {Object} Vehicle type list.
         **/
        $scope.getVehicleTypePrice = function (vehicle_type_id) {
            VehicleTypeFactory.get({'id': vehicle_type_id}).$promise.then(function (response) {
                $scope.vehicleType = response;
            });
        };
        /**
         * @ngdoc method
         * @name getNumber
         * @methodOf Vehicles.controller:VehicleAddController
         * @description
         * This method user to create new array.
         */
        $scope.getNumber = function (num) {
            return new Array(num);
        };
        /**
         * @ngdoc method
         * @name Range
         * @methodOf Vehicles.controller:VehicleAddController
         * @description
         * This method used create numbers between two number.
         */
        $scope.Range = function (min, max) {
            var result = [];
            for (var i = parseFloat(min); i <= parseFloat(max); i++) {
                result.push(i);
            }
            return result;
        };
        /**
         * @ngdoc method
         * @name pickupSelection
         * @methodOf Vehicles.controller:VehicleAddController
         * @description
         * This method used to collect all pickup location from counter location.
         */
        $scope.pickupSelection = function pickupSelection(location_id) {
            var selected_id = $scope.vehicle.pickup_counter_locations.indexOf(location_id);
            if (selected_id > -1) {
                $scope.vehicle.pickup_counter_locations.splice(selected_id, 1);
                $scope.all_pickup_location = false;
            } else {
                $scope.vehicle.pickup_counter_locations.push(location_id);
                if($scope.vehicle.pickup_counter_locations.length == $scope.vehicle_counter_locations.length) {
                    $scope.all_pickup_location = true;
                }
            }
        };
        /**
         * @ngdoc method
         * @name dropSelection
         * @methodOf Vehicles.controller:VehicleAddController
         * @description
         * This method used to collect all drop location from counter location.
         */
        $scope.dropSelection = function dropSelection(location_id) {
            var selected_id = $scope.vehicle.drop_counter_locations.indexOf(location_id);
            if (selected_id > -1) {
                $scope.vehicle.drop_counter_locations.splice(selected_id, 1);
                $scope.all_drop_location = false;
            } else {
                $scope.vehicle.drop_counter_locations.push(location_id);
                if($scope.vehicle.drop_counter_locations.length == $scope.vehicle_counter_locations.length) {
                    $scope.all_drop_location = true;
                }
            }
        };
        /**
         * @ngdoc method
         * @name select all pickuplocation
         * @methodOf Vehicles.controller:VehicleAddController
         * @description
         * This method used to collect all pickup location from counter location.
         */
        $scope.selectAllPickupLocation = function() {
            $scope.vehicle.pickup_counter_locations = [];
            if ($scope.all_pickup_location) {
                $scope.all_pickup_location = true;
            } else {
                $scope.all_pickup_location = false;
            }
            angular.forEach($scope.vehicle_counter_locations, function (value, key) {
                value.selected = $scope.all_pickup_location;
                if($scope.all_pickup_location) {
                    $scope.vehicle.pickup_counter_locations.push(value.id);
                }
            });
        };
        /**
         * @ngdoc method
         * @name select all droplocation
         * @methodOf Vehicles.controller:VehicleAddController
         * @description
         * This method used to collect all drop location from counter location.
         */
        $scope.selectAllDropLocation = function() {
            $scope.vehicle.drop_counter_locations = [];
            if ($scope.all_drop_location) {
                $scope.all_drop_location = true;
            } else {
                $scope.all_drop_location = false;
            }
            angular.forEach($scope.vehicle_counter_locations, function (value, key) {
                value.checked = $scope.all_drop_location;
                if($scope.all_drop_location) {
                    $scope.vehicle.drop_counter_locations.push(value.id);
                }
            });
        };
        /**
         * @ngdoc method
         * @name vehicleSubmit
         * @methodOf Vehicles.controller:VehicleAddController
         * @description
         * This method will store vehicle.
         * @param {Object} vehicle Vehicle detaila.
         * @returns {Array} Success or failure message.
         **/
        $scope.vehicleSubmit = function ($valid, file) {
            if ($valid) {
                $scope.vehicle.file = file;
                Upload.upload({
                    url: GENERAL_CONFIG.api_url + '/vehicles',
                    data: $scope.vehicle
                }).then(function (response) {
                    if (response.data.Success !== undefined) {
                        Flash.set($filter("translate")("Vehicle Added successfully"), 'success', true);
                        if ($rootScope.settings['vehicle.listing_fee'] > 0) {
                            $state.go('vehiclePaynow', {'vehicle_id': response.data.id});
                        } else {
                            $state.go('myVehicles');
                        }
                    } else {
                        Flash.set($filter("translate")("Vehicle could not be added"), 'error', false);
                    }
                });
            }
        }
    });
}(angular.module("BookorRent.Vehicles")));
