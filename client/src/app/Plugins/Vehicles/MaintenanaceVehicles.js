(function (module) {
    /**
     * @ngdoc controller
     * @name Vehicles.controller:MaintenanceVehiclesController
     * @description
     * This is MaintenanceVehiclesController having the methods init(), setMetaData(). It controls the functionality of vehicle maintenance.
     **/
    module.controller('MaintenanceVehiclesController', function ($window, $scope, $rootScope, $filter, Flash, $state, $location, MaintenanceVehicles, EditMaintenanceVehicles) {
        var model = this;
        model.maxSize = 5;
        model._metadata = [];
        /**
         * @ngdoc method
         * @name setMetaData
         * @methodOf Vehicles.controller:MaintenanceVehiclesController
         * @description
         * This method will set the meta data dynamically by using the angular.element.
         * @returns {Element} New meta data element.
         **/
        model.setMetaData = function () {
            var pageTitle = $filter("translate")("Maintenance Vehicles");
            var fullUrl = $location.absUrl();
            var appUrl = $rootScope.settings['scheme_name'] + ":/" + $location.url();
            angular.element('html head meta[property="og:title"], html head meta[name="twitter:title"]').attr("content", $rootScope.settings['site.name'] + " | " + pageTitle);
            angular.element('meta[property="al:ios:url"], meta[property="al:ipad:url"], meta[property="al:android:url"], meta[property="al:windows_phone:url"], html head meta[name="twitter:app:url:iphone"], html head meta[name="twitter:app:url:ipad"], html head meta[name="twitter:app:url:googleplay"]').attr('content', appUrl);
            angular.element('meta[property="og:url"]').attr('content', fullUrl);
        };
        /**
         * @ngdoc method
         * @name init
         * @methodOf Vehicles.controller:MaintenanceVehiclesController
         * @description
         * This method will initialze the page. It returns the page title.
         **/
        model.init = function () {
            model.setMetaData();
            model.currentPage = (model.currentPage !== undefined) ? parseInt(model.currentPage) : 1;
            $rootScope.pageTitle = $rootScope.settings['site.name'] + " | " + $filter("translate")("Maintenance Vehicles");
            //For Listing
            if ($state.params.vehicle_id != undefined) {
                MaintenanceVehicles.get({'vehicle_id': $state.params.vehicle_id}).$promise.then(function (response) {
                    model.maintenanceVehicles = response.data;
                    model._metadata = response.meta.pagination;
                });
            }
            //For edit
            if ($state.params.id != undefined) {
                EditMaintenanceVehicles.get({id: $state.params.id}).$promise.then(function (response) {
                    model.start_date = new Date(response.start_date);
                    model.end_date = new Date(response.end_date);
                });
            }
        };
        model.init();
        /**
         * @ngdoc method
         * @name paginate
         * @methodOf Vehicles.controller:MaintenanceVehiclesController
         * @description
         * This method will be load pagination the pages.
         **/
        model.paginate = function (pageno) {
            model.currentPage = parseInt(model.currentPage);
            $scope.init();
        };
        /**
         * Open Picup and drop off calendar
         * @param e
         * @param date
         */
        model.openStartCalendar = function (e, date) {
            $scope.open_start[date] = true;
        };
        model.openEndCalendar = function (e, date) {
            $scope.open_end[date] = true;
        };

        $scope.open_start = {
            date: false
        };
        $scope.open_end = {
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
        $scope.addMaintenanceDate = function () {
            $state.go('maintenanceVehicleAdd', {'vehicle_id': $state.params.vehicle_id})
        };
        /**
         * @ngdoc method
         * @name manitenanceVehicle
         * @methodOf Vehicles.controller:MaintenanceVehiclesController
         * @description
         * This method is used to store vehicle maintenance.
         * @param {Array} vehicle_id Vehicle identifier.
         * @returns {Array} Success or failure message.
         */
        model.manitenanceVehicle = function ($valid) {
            if ($valid) {
                var vehicle = {
                    vehicle_id: $state.params.vehicle_id,
                    start_date: model.start_date,
                    end_date: model.end_date
                };
                MaintenanceVehicles.save(vehicle, function (response) {
                    Flash.set($filter("translate")("Maintenance date added"), 'success', true);
                    $state.go('maintenanceVehicles', {vehicle_id: $state.params.vehicle_id});
                }, function (error) {
                    Flash.set($filter("translate")(error.data.message), 'error', false);
                });
            }
        };
        /**
         * @ngdoc method
         * @name editManitenanceDates
         * @methodOf Vehicles.controller:MaintenanceVehiclesController
         * @description
         * This method is used to edit vehicle maintenance.
         * @param {Array} maintenance_id Vehicle maintenance identifier.
         * @returns {Array} Success or failure message.
         */
        model.editManitenanceDates = function ($valid) {
            if ($valid) {
                var vehicle = {
                    id:$state.params.id,
                    start_date: model.start_date,
                    end_date: model.end_date
                };
                MaintenanceVehicles.update(vehicle, function (response) {
                    Flash.set($filter("translate")("Maintenance date updated"), 'success', true);
                    $state.go('maintenanceVehicles', {vehicle_id: response.vehicle_id});
                }, function (error) {
                    Flash.set($filter("translate")(error.data.message), 'error', false);
                })
            }
        }
        /**
         * @ngdoc method
         * @name removeVehicle
         * @methodOf Vehicles.controller:MaintenanceVehiclesController
         * @description
         * This method is used to remove vehicle maintenance.
         * @param {Array} maintenance_id Vehicle maintenance identifier.
         * @returns {Array} Success or failure message.
         */
        model.removeVehicle = function (id) {
            var deleteItem = $window.confirm('Are you sure want to delete?');
            if (deleteItem) {
                MaintenanceVehicles.delete({'id': id}, function (response) {
                    Flash.set($filter("translate")("Maintenanace Date Deleted successfully"), 'success', true);
                    $state.reload();
                }, function (error) {
                    Flash.set($filter("translate")("Maintenanace Date Could not be deleted"), 'error', false);
                });
            }
        };
    });
}(angular.module("BookorRent.Vehicles")));
