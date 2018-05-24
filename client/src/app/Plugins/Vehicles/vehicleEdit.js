(function (module) {
    /**
     * @ngdoc controller
     * @name Vehicles.controller:VehicleEditController
     * @description
     * This is VehicleEditController having the methods init(), setMetaData(). It controls the functionality of edit vehicle.
     **/
    module.controller('VehicleEditController', function ($scope, $rootScope, $filter, Flash, $state, $location, Upload, GENERAL_CONFIG, VehicleRelatedFactory, VehicleModelFactory, VehicleTypeFactory, VehicleFactory) {
        model = this;
        $scope.vehicle = {};
        $scope.vehicle.pickup_counter_locations = [];
        $scope.vehicle.drop_counter_locations = [];
        var vehicle_id = $state.params.id;
        /**
         * @ngdoc method
         * @name setMetaData
         * @methodOf Vehicles.controller:VehicleEditController
         * @description
         * This method will set the meta data dynamically by using the angular.element.
         * @returns {Element} New meta data element.
         **/
        model.setMetaData = function () {
            var pageTitle = $filter("translate")("Edit Vehicle");
            var fullUrl = $location.absUrl();
            var appUrl = $rootScope.settings['scheme_name'] + ":/" + $location.url();
            angular.element('html head meta[property="og:title"], html head meta[name="twitter:title"]').attr("content", $rootScope.settings['site.name'] + " | " + pageTitle);
            angular.element('meta[property="al:ios:url"], meta[property="al:ipad:url"], meta[property="al:android:url"], meta[property="al:windows_phone:url"], html head meta[name="twitter:app:url:iphone"], html head meta[name="twitter:app:url:ipad"], html head meta[name="twitter:app:url:googleplay"]').attr('content', appUrl);
            angular.element('meta[property="og:url"]').attr('content', fullUrl);
        };
        /**
         * @ngdoc method
         * @name init
         * @methodOf Vehicles.controller:VehicleEditController
         * @description
         * This method will initialze the page. It returns the page title.
         **/
        model.init = function () {
            $scope.driver_min_age = $rootScope.settings['vehicle.driver_min_age'];
            $scope.driver_max_age = $rootScope.settings['vehicle.driver_max_age'];
            model.setMetaData();
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
            VehicleFactory.get({'id': $state.params.id}).$promise.then(function (response) {
                $scope.vehicle = response;
                pickup_counter_locations = [];
                drop_counter_locations = [];
                angular.forEach(response.pickup_locations, function (value, key) {
                    pickup_counter_locations.push(value.id);
                });
                angular.forEach(response.drop_locations, function (value, key) {
                    drop_counter_locations.push(value.id);
                });
                $scope.vehicle.minimum_age_of_driver = parseInt(response.minimum_age_of_driver);
                $scope.vehicle.pickup_counter_locations = pickup_counter_locations;
                $scope.vehicle.drop_counter_locations = drop_counter_locations;
                $scope.getVehicleModel(response.vehicle_make_id);
                $scope.getVehicleTypePrice(response.vehicle_type_id);
            }, function (error) {
            });
        };
        model.init();
        /**
         * @ngdoc method
         * @name getVehicleModel
         * @methodOf Vehicles.controller:VehicleEditController
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
         * @methodOf Vehicles.controller:VehicleEditController
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
         * @methodOf Vehicles.controller:VehicleEditController
         * @description
         * This method user to create new array.
         */
        $scope.getNumber = function (num) {
            return new Array(num);
        };
        /**
         * @ngdoc method
         * @name Range
         * @methodOf Vehicles.controller:VehicleEditController
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
         * @methodOf Vehicles.controller:VehicleEditController
         * @description
         * This method used to collect all pickup location from counter location.
         */
        $scope.pickupSelection = function pickupSelection(location_id) {
            location_id = parseInt(location_id);
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
         * @methodOf Vehicles.controller:VehicleEditController
         * @description
         * This method used to collect all drop location from counter location.
         */
        $scope.dropSelection = function dropSelection(location_id) {
            location_id = parseInt(location_id);
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
         * @name checkStatus
         * @methodOf Vehicles.controller:VehicleEditController
         * @description
         * This method used to show the selected drop and pickup locations.
         */
        $scope.checkStatus = function (id, selected_list) {
            if ($.inArray(parseInt(id), selected_list) > -1) {
                return true;
            } else {
                return false;
            }
        };
        /**
         * @ngdoc method
         * @name vehicleSubmit
         * @methodOf Vehicles.controller:VehicleEditController
         * @description
         * This method will store vehicle.
         * @param {Object} vehicle Vehicle detaila.
         * @returns {Array} Success or failure message.
         **/
        $scope.vehicleSubmit = function ($valid, file) {
            if ($valid) {
                if (file !== undefined) {
                    Upload.upload({
                        url: GENERAL_CONFIG.api_url + '/vehicles/' + vehicle_id,
                        data: {
                            file: file,
                            'id': vehicle_id,
                            'vehicle_make_id': $scope.vehicle.vehicle_make_id,
                            'vehicle_model_id': $scope.vehicle.vehicle_model_id,
                            'vehicle_type_id': $scope.vehicle.vehicle_type_id,
                            'pickup_counter_locations': $scope.vehicle.pickup_counter_locations,
                            'drop_counter_locations': $scope.vehicle.drop_counter_locations,
                            'driven_kilometer': $scope.vehicle.driven_kilometer,
                            'vehicle_no': $scope.vehicle.vehicle_no,
                            'no_of_seats': $scope.vehicle.no_of_seats,
                            'no_of_doors': $scope.vehicle.no_of_doors,
                            'no_of_gears': $scope.vehicle.no_of_gears,
                            'is_manual_transmission': $scope.vehicle.is_manual_transmission,
                            'no_small_bags': $scope.vehicle.no_small_bags,
                            'no_large_bags': $scope.vehicle.no_large_bags,
                            'is_ac': $scope.vehicle.is_ac,
                            'minimum_age_of_driver': $scope.vehicle.minimum_age_of_driver,
                            'mileage': $scope.vehicle.mileage,
                            'is_airbag': $scope.vehicle.mileage,
                            'no_of_airbags': $scope.vehicle.no_of_airbags,
                            'is_abs': $scope.vehicle.is_abs,
                            'per_hour_amount': $scope.vehicle.per_hour_amount,
                            'per_day_amount': $scope.vehicle.per_day_amount,
                            'fuel_type_id': $scope.vehicle.fuel_type_id
                        }
                    }).then(function (response) {
                        if (response.data.Success !== undefined) {
                            Flash.set($filter("translate")("Vehicle Updated successfully"), 'success', true);
                            $state.go('myVehicles');
                        } else {
                            Flash.set($filter("translate")("Vehicle could not be updated"), 'error', false);
                        }
                    });
                } else {
                    VehicleFactory.update({id: vehicle_id}, $scope.vehicle, function (response) {
                        Flash.set($filter("translate")("Vehicle Updated successfully"), 'success', true);
                        $state.go('myVehicles');
                    }, function (error) {
                        Flash.set($filter("translate")("Vehicle could not be updated"), 'error', false);
                    });
                }
            }
        }
    });
}(angular.module("BookorRent.Vehicles")));
