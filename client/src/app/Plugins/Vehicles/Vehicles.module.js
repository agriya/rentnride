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
 * This is the module for Vehicles
 *
 * The Vehicle module act as a state provider, this module get the url and load the template and call the controller temporarily.
 *
 * @param {string} vehicle name of the module
 * @param {!Array.<string>=} dependencies If specified then new module is being created. If unspecified then the module is being retrieved for further configuration.
 *
 *        [
 *            'ui.router',
 *            'ngResource',
 *            'angulartics',
 *            'angulartics.google.analytics',
 *            'angulartics.facebook.pixel',
 *            'ui.bootstrap'
 *            'ui.bootstrap.datetimepicker',
 *            'rzModule',
 *            'ngFileUpload',
 *            'google.places'
 *        ]
 * @param {string} stateProvider State provider is used to provide a corresponding model and template.
 * @param {string} analyticsProvider This service lets you integrate google analytics tracker in your AngularJS applications easily.
 * @returns {BookorRent.Vehicles} new BookorRent.Vehicles module.
 **/
(function (module) {
    /**
     * @ngdoc directive
     * @name Vehicles.directive:vehicleSearch
     * @scope
     * @restrict EA
     * @description
     * vehicleSearch directive used to load the search template.
     * @param {string} vehicleSearch Name of the directive
     **/
    module.directive('vehicleSearch', function () {
        return {
            restrict: 'EA',
            templateUrl: "Plugins/Vehicles/vehicle_search.tpl.html",
            controller: function ($scope, $element, $attrs, $state, CounterLocationFactory, $filter) {
                $scope.counter_location = function () {
                    CounterLocationFactory.get({type: 'list'}).$promise.then(function (response) {
                        $scope.locations = response.data;
                    });
                };
                $scope.init = function () {
                    $scope.currentDate = new Date();
                    $scope.counter_location();
                };
                $scope.init();
                /**
                 * Open Picup and drop off calendar
                 * @param e
                 * @param date
                 */
                $scope.openPickupCalendar = function (e, date) {
                    $scope.open_pickup[date] = true;
                };
                $scope.openDropCalendar = function (e, date) {
                    $scope.open_drop[date] = true;
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
                };
                /**
                 * @ngdoc method
                 * @name SearchSubmit
                 * @methodOf Vehicles.controller:VehiclesController
                 * @description
                 * This method is used to store search details.
                 * @param {Array} vehicel Search details.
                 * @returns {html} Vehicle list page.
                 */
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
                        $state.go('vehicle_list');
                    }
                };
            }

        };
    });
    module.directive('vehicle', function () {
        return {
            restrict: 'EA',
            templateUrl: "Plugins/Vehicles/vehicle.tpl.html"
        };
    });
    module.directive('tripDetail', function () {
        return {
            restrict: 'EA',
            templateUrl: "Plugins/Vehicles/trip_detail.tpl.html"
        };
    });
    module.directive('vehicleList', function() {
       return {
           restrict: 'EA',
           templateUrl:"Plugins/Vehicles/vehicle_list_home.tpl.html",
           controller: function ($scope, $element, $attrs, $state, VehicleTypeFactory, VehicleSearchFactory, $rootScope) {
               //Get vehicles based on vehicle type
			   $scope.active = 0;
               $scope.getVehicles = function(vehicle_type_id) {
					$scope.active = 0;
                   $scope.vehicles = [];
                   VehicleSearchFactory.post({'vehicle_type_id':vehicle_type_id}, function(response) {
                       $scope.vehicleTypes.status_id = vehicle_type_id;
                       var vehicles = response.data;
                       var item = 3;
                       //Split array for show 3 vehicle per slide
                       for(var i=0;i<vehicles.length;i+=item) {
                           $scope.vehicles.push(vehicles.slice(i,i+item));
                       }
                   });
               };
               //Get all vehicle types and store to rootscope
                $scope.getVehicleTypes = function() {
                    if ($rootScope.vehicleTypes == undefined) {
                        VehicleTypeFactory.getAll({'type':'vehicle_count'}).$promise.then(function(response) {
							if(response.data.length > 0 ){
								$scope.vehicleTypes = response.data;
								$scope.getVehicles(response.data[0].id);
								$rootScope.vehicleTypes = response.data;
							}
                            
                        });
                    } else {
                        $scope.vehicleTypes = $rootScope.vehicleTypes;
                        $scope.getVehicles($scope.vehicleTypes[0].id);
                    }
                };
               $scope.init = function() {
                   $scope.noWrapSlides = false;
                   $scope.interval = 5000;
                   $scope.getVehicleTypes();
               };
               $scope.init();

           }
       }
    });
    module.config(function ($stateProvider, $analyticsProvider) {
        var ResolveServiceData = {
            'ResolveServiceData': function (ResolveService, $q) {
                return $q.all({
                    AuthServiceData: ResolveService.promiseAuth,
                    SettingServiceData: ResolveService.promiseSettings
                });
            }
        };
        $stateProvider
            .state('vehicleAdd', {
                url: '/vehicle/add',
                authenticate: true,
                views: {
                    "main": {
                        controller: 'VehicleAddController as model',
                        templateUrl: 'Plugins/Vehicles/vehicle_add.tpl.html',
                        resolve: ResolveServiceData
                    }
                },
                data: {pageTitle: 'Add Vehicle'}
            })
            .state('vehicleEdit', {
                url: '/vehicle/edit/:id',
                authenticate: true,
                views: {
                    "main": {
                        controller: 'VehicleEditController as model',
                        templateUrl: 'Plugins/Vehicles/vehicle_edit.tpl.html',
                        resolve: ResolveServiceData
                    }
                },
                data: {pageTitle: 'Edit Vehicle'}
            })
            .state('myVehicles', {
                url: '/myvehicles',
                authenticate: true,
                views: {
                    "main": {
                        controller: 'MyVehiclesController as model',
                        templateUrl: 'Plugins/Vehicles/my_vehicles.tpl.html',
                        resolve: ResolveServiceData
                    }
                },
                data: {pageTitle: 'My Vehicles'}
            })
            .state('vehicleCompany', {
                url: '/vehicle/company',
                authenticate: true,
                views: {
                    "main": {
                        controller: 'vehicleCompanyController as model',
                        templateUrl: 'Plugins/Vehicles/vehicleCompany.tpl.html',
                        resolve: ResolveServiceData
                    }
                },
                data: {pageTitle: 'Company'}
            })
            .state('vehiclePaynow', {
                url: '/vehicle/{vehicle_id}/paynow',
                authenticate: true,
                views: {
                    "main": {
                        controller: 'VehiclesController as model',
                        templateUrl: 'Plugins/Vehicles/vehicle_payment.tpl.html',
                        resolve: ResolveServiceData
                    }
                },
                data: {pageTitle: 'Vehicles'}
            })
            .state('vehicle_search', {
                url: '/vehicle/search',
                authenticate: false,
                views: {
                    "main": {
                        controller: 'VehicleSearchController as model',
                        templateUrl: 'Plugins/Vehicles/vehicle_search.tpl.html',
                        resolve: ResolveServiceData
                    }
                },
                data: {pageTitle: 'Vehicles'}
            })
            .state('vehicle_list', {
                url: '/vehicles',
                authenticate: false,
                views: {
                    "main": {
                        controller: 'VehicleListController as model',
                        templateUrl: 'Plugins/Vehicles/vehicle_list.tpl.html',
                        resolve: ResolveServiceData
                    }
                },
                data: {pageTitle: 'Vehicles'}
            })
            .state('vehicle_detail', {
                url: '/vehicle_rental/order/{vehicle_rental_id}/update',
                authenticate: true,
                views: {
                    "main": {
                        controller: 'VehicleDetailsController as model',
                        templateUrl: 'Plugins/Vehicles/vehicle_details.tpl.html',
                        resolve: ResolveServiceData
                    }
                },
                data: {pageTitle: 'Vehicles'}
            })
            .state('maintenanceVehicles', {
                url: '/vehicle/{vehicle_id}/maintenance',
                authenticate: true,
                views: {
                    "main": {
                        controller: 'MaintenanceVehiclesController as model',
                        templateUrl: 'Plugins/Vehicles/maintenance_vehicles.tpl.html',
                        resolve: ResolveServiceData
                    }
                },
                data: {pageTitle: 'Vehicles'}
            })
            .state('maintenanceVehicleAdd', {
                url: '/maintenance_vehicle/{vehicle_id}/add',
                authenticate: true,
                views: {
                    "main": {
                        controller: 'MaintenanceVehiclesController as model',
                        templateUrl: 'Plugins/Vehicles/maintenance_vehicle_add.tpl.html',
                        resolve: ResolveServiceData
                    }
                },
                data: {pageTitle: 'Vehicles'}
            })
            .state('maintenanceVehicleEdit', {
                url: '/maintenance_vehicle/{id}/edit',
                authenticate: true,
                views: {
                    "main": {
                        controller: 'MaintenanceVehiclesController as model',
                        templateUrl: 'Plugins/Vehicles/maintenance_vehicle_edit.tpl.html',
                        resolve: ResolveServiceData
                    }
                },
                data: {pageTitle: 'Vehicles'}
            })
            .state('vehicleView', {
                url: '/vehicle/{id}/{slug}',
                authenticate: false,
                views: {
                    "main": {
                        controller: 'VehicleViewController as model',
                        templateUrl: 'Plugins/Vehicles/vehicle_view.tpl.html',
                        resolve: ResolveServiceData
                    }
                },
                data: {pageTitle: 'Vehicles'}
            })
            .state('vehicleStatus', {
                url: '/vehicle/{status}',
                authenticate: true,
                views: {
                    "main": {
                        controller: 'VehicleAddController as model',
                        templateUrl: 'Plugins/Vehicles/vehicle_view.tpl.html',
                        resolve: ResolveServiceData
                    }
                },
                data: {pageTitle: 'Vehicles'}
            })
            .state('all_vehicles', {
            url: '/vehicles/all',
            authenticate: false,
            views: {
                "main": {
                    controller: 'VehicleAllLsitController as model',
                    templateUrl: 'Plugins/Vehicles/all_vehicles.tpl.html',
                    resolve: ResolveServiceData
                }
            },
            data: {pageTitle: 'Vehicles'}
        })
    });
}(angular.module("BookorRent.Vehicles", [
    'ui.router',
    'ngResource',
    'angulartics',
    'angulartics.google.analytics',
    'angulartics.facebook.pixel',
    'ui.bootstrap',
    'ui.bootstrap.datetimepicker',
    'rzModule',
    'ngFileUpload',
    'google.places',
    '720kb.socialshare'
])));