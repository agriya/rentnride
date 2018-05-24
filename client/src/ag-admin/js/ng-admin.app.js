var ngapp = angular.module('BookorRent', ['ng-admin', 'ng-admin.jwt-auth', 'google.places', 'mwl.calendar']);
var admin_api_url = '/bookorrent/public';
var limit_per_page = 20;
var no_limit_per_page = 100;
var enabledPlugins;
// dashboard page redirect changes
function homeController($scope, $http, $location) {
    $location.path('/dashboard');
}
//Custom directives controlller function called here
//State Provider defined for custom pages.
//Templates created under 'tpl' directory and controller functions defined above.
ngapp.config(function ($stateProvider) {
    $stateProvider
        .state('home', {
            url: '/',
            controller: homeController,
            controllerAs: 'controller'
        })
        .state('pages', {
            parent: 'main',
            url: '/pages/add',
            templateUrl: '../ag-admin/tpl/pages.tpl.html',
            params: {
                id: null
            },
            controller: pagesController,
            controllerAs: 'controller',
            resolve: {
                AuthCheck: function ($http) {
                    var token = localStorage.userToken;
                    if (!token) {
                        return false;
                    } else {
                        $http.defaults.headers.common['Authorization'] = 'Bearer ' + token;
                        return $http.get(admin_api_url + '/api/languages?filter=active&sort=name&sortby=asc');
                    }
                }
            }
        })
        .state('plugins', {
            parent: 'main',
            url: '/plugins',
            templateUrl: '../ag-admin/tpl/plugins.tpl.html',
            controller: pluginsController,
            controllerAs: 'controller',
            resolve: {
                AuthCheck: function ($http) {
                    var token = localStorage.userToken;
                    if (!token) {
                        return false;
                    } else {
                        $http.defaults.headers.common['Authorization'] = 'Bearer ' + token;
                        return $http.get(admin_api_url + '/api/admin/plugins', {});
                    }
                }
            }
        })
        .state('users/change_password', {
            parent: 'main',
            url: '/change_password',
            templateUrl: '../ag-admin/tpl/changePassword.tpl.html',
            params: {
                id: null
            },
            controller: changePasswordController,
            controllerAs: 'controller',
            resolve: {}
        })
        .state('counter_locations/add', {
            parent: 'main',
            url: '/counter_locations/add',
            templateUrl: '../ag-admin/tpl/counterLocationAdd.tpl.html',
            controller: counterLocationController,
            controllerAs: 'controller',
            resolve: {
                AuthCheck: function ($http) {
                    var token = localStorage.userToken;
                    if (!token) {
                        return false;
                    } else {
                        $http.defaults.headers.common['Authorization'] = 'Bearer ' + token;
                    }
                }
            }
        })
        .state('counter_locations/:id/edit', {
            parent: 'main',
            url: '/counter_locations/:id/edit',
            templateUrl: '../ag-admin/tpl/counterLocationEdit.tpl.html',
            controller: counterLocationController,
            controllerAs: 'controller',
            params: {
                id: null
            },
            resolve: {
                AuthCheck: function ($http) {
                    var token = localStorage.userToken;
                    if (!token) {
                        return false;
                    } else {
                        $http.defaults.headers.common['Authorization'] = 'Bearer ' + token;
                    }
                }
            }
        })
        .state('vehicle_type_prices/add', {
            parent: 'main',
            url: '/vehicle_type_prices/add',
            templateUrl: '../ag-admin/tpl/vehicleTypePrice.tpl.html',
            controller: vehicleTypePriceController,
            controllerAs: 'controller',
            resolve: {
                AuthCheck: function ($http) {
                    var token = localStorage.userToken;
                    if (!token) {
                        return false;
                    } else {
                        $http.defaults.headers.common['Authorization'] = 'Bearer ' + token;
                    }
                }
            }
        })
        .state('vehicle_companies/add', {
            parent: 'main',
            url: '/vehicle_companies/add',
            templateUrl: '../ag-admin/tpl/vehicleCompaniesAdd.tpl.html',
            controller: vehicleCompaniesController,
            controllerAs: 'controller',
            resolve: {
                AuthCheck: function ($http) {
                    var token = localStorage.userToken;
                    if (!token) {
                        return false;
                    } else {
                        $http.defaults.headers.common['Authorization'] = 'Bearer ' + token;
                    }
                }
            }
        })
        .state('vehicle_companies/:id/edit', {
            parent: 'main',
            url: '/vehicle_companies/:id/edit',
            templateUrl: '../ag-admin/tpl/vehicleCompaniesEdit.tpl.html',
            controller: vehicleCompaniesController,
            controllerAs: 'controller',
            params: {
                id: null
            },
            resolve: {
                AuthCheck: function ($http) {
                    var token = localStorage.userToken;
                    if (!token) {
                        return false;
                    } else {
                        $http.defaults.headers.common['Authorization'] = 'Bearer ' + token;
                    }
                }
            }
        })
        .state('vehicle/add', {
            parent: 'main',
            url: '/vehicle/add',
            templateUrl: '../ag-admin/tpl/vehicleAdd.tpl.html',
            controller: vehicleController,
            controllerAs: 'controller',
            resolve: {
                AuthCheck: function ($http) {
                    var token = localStorage.userToken;
                    if (!token) {
                        return false;
                    } else {
                        $http.defaults.headers.common['Authorization'] = 'Bearer ' + token;
                    }
                }
            }
        })
        .state('vehicles/:id/edit', {
            parent: 'main',
            url: '/vehicles/:id/edit',
            templateUrl: '../ag-admin/tpl/vehicleEdit.tpl.html',
            controller: vehicleController,
            controllerAs: 'controller',
            params: {
                id: null
            },
            resolve: {
                AuthCheck: function ($http) {
                    var token = localStorage.userToken;
                    if (!token) {
                        return false;
                    } else {
                        $http.defaults.headers.common['Authorization'] = 'Bearer ' + token;
                    }
                }
            }
        })
        .state('vehicles/:id/calendar', {
            parent: 'main',
            url: '/vehicles/:id/calendar',
            templateUrl: '../ag-admin/tpl/vehicle_calendar.tpl.html',
            controller: vehicleCalendarController,
            controllerAs: 'controller',
            params: {
                id: null
            },
            resolve: {
                AuthCheck: function ($http) {
                    var token = localStorage.userToken;
                    if (!token) {
                        return false;
                    } else {
                        $http.defaults.headers.common['Authorization'] = 'Bearer ' + token;
                    }
                }
            }
        })
        .state('vehicle_rentals/:id/checkout', {
            parent: 'main',
            url: '/vehicle_rentals/:id/checkout',
            templateUrl: '../ag-admin/tpl/vehiclecheckOut.tpl.html',
            controller: vehiclecheckOutController,
            controllerAs: 'controller',
            params: {
                id: null
            },
            resolve: {
                AuthCheck: function ($http) {
                    var token = localStorage.userToken;
                    if (!token) {
                        return false;
                    } else {
                        $http.defaults.headers.common['Authorization'] = 'Bearer ' + token;
                    }
                }
            }
        })
        .state('vehicles/view/:id', {
            parent: 'main',
            url: '/vehicles/view/:id',
            templateUrl: '../ag-admin/tpl/vehicleView.tpl.html',
            controller: vehicleViewController,
            controllerAs: 'controller',
            params: {
                id: null
            },
            resolve: {
                AuthCheck: function ($http) {
                    var token = localStorage.userToken;
                    if (!token) {
                        return false;
                    } else {
                        $http.defaults.headers.common['Authorization'] = 'Bearer ' + token;
                    }
                }
            }
        }).state('vehicle_view_page', {
        parent: 'main',
        url: '/vehicle/:id/:slug',
        templateUrl: '../ag-admin/tpl/vehicleView.tpl.html',
        controller: vehicleViewController,
        controllerAs: 'controller',
        params: {
            id: null
        },
        resolve: {
            AuthCheck: function ($http) {
                var token = localStorage.userToken;
                if (!token) {
                    return false;
                } else {
                    $http.defaults.headers.common['Authorization'] = 'Bearer ' + token;
                }
            }
        }
    });
});
//Customize API Mapping
//Referenced Document Link: http://ng-admin-book.marmelab.com/doc/API-mapping.html
ngapp.config(['RestangularProvider', function (RestangularProvider) {
    // use the custom query parameters function to format the API request correctly
    RestangularProvider.addFullRequestInterceptor(function (element, operation, what, url, headers, params) {
        // custom pagination params
        if (params._filters) {
            angular.forEach(params._filters, function (value, i) {
                params[i] = value;
            });
            delete params._filters;
        }
        if (params._sortField) {
            params.sort = params._sortField;
        }
        delete params._sortField;
        if (params._sortDir) {
            params.sortby = params._sortDir;
        }
        delete params._sortDir;
        if (params._perPage !== null && params._perPage !== 'all' && params._page) {
            params._start = (params._page - 1) * params._perPage;
            params._end = params._page * params._perPage;
            //In REST file, we added page and limit query parameters for pagination
            //Get Reference from http://ng-admin-book.marmelab.com/doc/API-mapping.html
            //Keyword - pagination
            params.page = params._page;
            params.limit = params._perPage;
        }
        delete params._start;
        delete params._end;
        if (params._perPage === null) {
            params.limit = limit_per_page;
        }
        /*if (angular.isUndefined(params._perPage)) {
            params.limit = 'all';
        }*/
        //limit('all') is used for dropdown values, our api default limit value is '10', to show all the value we should pass string 'all' in limit parameter.
        if (params._perPage == 'all') {
            params.limit = 'all';
        }
        delete params._page;
        delete params._perPage;
        // custom sort params
        if (params._sortField) {
            delete params._sortField;
            delete params._sortDir;
        }
        return {
            params: params
        };
    });
    //Total Number of Results
    //Our API doesn't return a X-Total-Count header, so we added a totalCount property to the response object using a Restangular response interceptor.
    //Removed metadata info from response
    RestangularProvider.addResponseInterceptor(function (data, operation, what, url, response) {
        if (operation === "getList") {
            var headers = response.headers();
            if (response.data.meta.pagination.total !== null) {
                response.totalCount = response.data.meta.pagination.total;
            }
        }
        return data;
    });
    //To cutomize single view results, we added setResponseExtractor.
    //Our API Edit view results single array with following data format data[{}], Its not working with ng-admin format
    //so we returned data like data[0];
    RestangularProvider.setResponseExtractor(function (response, operation, what, url) {
        if (response.data) {
            if (operation === "getList") {
                // Use results as the return type, and save the result metadata
                var newResponse = response.data;
                newResponse._metadata = response.meta.pagination;
                return newResponse;
            }
            return response.data[0];
        } else {
            return response;
        }
    });
}]);
//Custom Header
//Referenced Link: http://ng-admin-book.marmelab.com/doc/Dashboard.html
//Above link has details about dashboard customization, we follwed the same steps for header customization.
//Created custom directive for header, reference http://ng-admin-book.marmelab.com/doc/Custom-pages.html keyword - directive.
//Template files created under 'tpl' directory.
ngapp.directive('customHeader', ['$location', '$state', '$http', function ($location, $state, $http, $scope) {
    return {
        restrict: 'E',
        scope: {},
        templateUrl: '../ag-admin/tpl/customHeader.tpl.html',
        link: function (scope) {
            scope.siteUrl = admin_api_url;
        },
		controller: customHeaderController,
            controllerAs: 'controller',
            resolve: {}
    };
}]);
//Custom  Dashboard
//Referenced Link: http://ng-admin-book.marmelab.com/doc/Dashboard.html
//Created custom directive for header, reference http://ng-admin-book.marmelab.com/doc/Custom-pages.html keyword - directive.
//Template files created under 'tpl' directory.
ngapp.directive('dashboardSummary', ['$location', '$state', '$http', function ($location, $state, $http) {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entityName: "@",
            entry: "&",
            size: "@",
            label: "@",
            revenueDetails: "&"
        },
        templateUrl: '../ag-admin/tpl/dashboardSummary.tpl.html',
        link: function (scope) {
            scope.rangeVal = [{
                "key": "lastDays",
                "value": "Last 7 Days"
            }, {
                "key": "lastWeeks",
                "value": "Last 4 Weeks"
            }, {
                "key": "lastMonths",
                "value": "Last 3 Months"
            }, {
                "key": "lastYears",
                "value": "Last 3 Years"
            }];
            if (scope.rangeText === undefined) {
                scope.rangeText = "Last 7 Days";
            }
            scope.selectedRangeItem = function (rangeVal, rangeText) {
                $http.get(admin_api_url + '/api/admin/stats', {
                    params: {
                        filter: rangeVal
                    }
                }).success(function (response) {
                    scope.adminstats = response;
                    scope.rangeText = rangeText;
                });
            };
            var token = localStorage.userToken;
            if (!token) {
                $state.go('logout');
            } else {
                $http.defaults.headers.common['Authorization'] = 'Bearer ' + token;
            }
            $http.get(admin_api_url + '/api/admin/stats').success(function (response) {
                scope.adminstats = response;
                scope.adminactivities = response;
            });
            scope.enabled_plugin = localStorage.getItem('enabled_plugins');
            scope.site_version = localStorage.getItem('site_version');
        }
    };
}]);
ngapp.directive('batchDeactive', ['$location', '$state', 'notification', '$q', 'Restangular', function ($location, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@'
        },
        link: function (scope, element, attrs) {
            const status_name = attrs.type == 'deactive' ? 'Deactive' : 'Deactive';
            scope.icon = attrs.type == 'deactive' ? 'glyphicon-remove' : 'glyphicon-remove';
            scope.label = attrs.type == 'deactive' ? 'Deactive' : 'Deactive';
            scope.updateStatus = function () {
                $q.all(scope.selection.map(function (e) {
                        Restangular.one('/users/' + e.values.id + '/deactive').put()
                            .then(function () {
                                $state.reload()
                            })
                    }))
                    .then(function () {
                        notification.log(scope.selection.length + ' User status changed to  ' + status_name, {addnCls: 'humane-flatty-success'});
                    })
            }
        },
        template: '<span ng-click="updateStatus()"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive('batchActive', ['$location', '$state', 'notification', '$q', 'Restangular', function ($location, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@'
        },
        link: function (scope, element, attrs) {
            const status_name = attrs.type == 'active' ? 'Active' : 'Active';
            scope.icon = attrs.type == 'active' ? 'glyphicon-ok' : 'glyphicon-ok';
            scope.label = attrs.type == 'active' ? 'Active' : 'Active';
            scope.updateStatus = function () {
                $q.all(scope.selection.map(function (e) {
                        Restangular.one('/users/' + e.values.id + '/active').put()
                            .then(function () {
                                $state.reload()
                            })
                    }))
                    .then(function () {
                        notification.log(scope.selection.length + ' User status changed to  ' + status_name, {addnCls: 'humane-flatty-success'});
                    })
            }
        },
        template: '<span ng-click="updateStatus()"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive('batchReject', ['$location', '$state', 'notification', '$q', 'Restangular', function ($location, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@'
        },
        link: function (scope, element, attrs) {
            const status_name = attrs.type == 'reject' ? 'Reject' : 'Reject';
            scope.icon = attrs.type == 'reject' ? 'glyphicon-remove' : 'glyphicon-ok';
            scope.label = attrs.type == 'reject' ? 'Reject' : 'Reject';
            scope.updateStatus = function () {
                $q.all(scope.selection.map(function (e) {
                        Restangular.one('/vehicle_companies/' + e.values.id + '/reject').put()
                            .then(function () {
                                $state.reload()
                            })
                    }))
                    .then(function () {
                        notification.log(scope.selection.length + ' Vehicle Comapany status changed to  ' + status_name, {addnCls: 'humane-flatty-success'});
                    })
            }
        },
        template: '<span ng-click="updateStatus()"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive('batchVehicleDeactive', ['$location', '$state', 'notification', '$q', 'Restangular', function ($location, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@'
        },
        link: function (scope, element, attrs) {
            const status_name = attrs.type == 'deactive' ? 'Deactive' : 'Deactive';
            scope.icon = attrs.type == 'deactive' ? 'glyphicon-remove' : 'glyphicon-remove';
            scope.label = attrs.type == 'deactive' ? 'Deactive' : 'Deactive';
            scope.updateStatus = function () {
                $q.all(scope.selection.map(function (e) {
                        Restangular.one('/vehicle_companies/' + e.values.id + '/deactive').put()
                            .then(function () {
                                $state.reload()
                            })
                    }))
                    .then(function () {
                        notification.log(scope.selection.length + ' Vehicle Comapany status changed to  ' + status_name, {addnCls: 'humane-flatty-success'});
                    })
            }
        },
        template: '<span ng-click="updateStatus()"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive('batchVehicleActive', ['$location', '$state', 'notification', '$q', 'Restangular', function ($location, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@'
        },
        link: function (scope, element, attrs) {
            const status_name = attrs.type == 'active' ? 'Active' : 'Active';
            scope.icon = attrs.type == 'active' ? 'glyphicon-ok' : 'glyphicon-ok';
            scope.label = attrs.type == 'active' ? 'Active' : 'Active';
            scope.updateStatus = function () {
                $q.all(scope.selection.map(function (e) {
                        Restangular.one('/vehicle_companies/' + e.values.id + '/active').put()
                            .then(function () {
                                $state.reload()
                            })
                    }))
                    .then(function () {
                        notification.log(scope.selection.length + ' Vehicle Comapany status changed to  ' + status_name, {addnCls: 'humane-flatty-success'});
                    })
            }
        },
        template: '<span ng-click="updateStatus()"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive('batchVehiclesDeactive', ['$location', '$state', 'notification', '$q', 'Restangular', function ($location, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@'
        },
        link: function (scope, element, attrs) {
            const status_name = attrs.type == 'deactive' ? 'Deactive' : 'Deactive';
            scope.icon = attrs.type == 'deactive' ? 'glyphicon-remove' : 'glyphicon-remove';
            scope.label = attrs.type == 'deactive' ? 'Deactive' : 'Deactive';
            scope.updateStatus = function () {
                $q.all(scope.selection.map(function (e) {
                        Restangular.one('/vehicles/' + e.values.id + '/deactive').put()
                            .then(function () {
                                $state.reload()
                            })
                    }))
                    .then(function () {
                        notification.log(scope.selection.length + ' Vehicle status changed to  ' + status_name, {addnCls: 'humane-flatty-success'});
                    })
            }
        },
        template: '<span ng-click="updateStatus()"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);

ngapp.directive('batchStatesDeactive', ['$location', '$state', 'notification', '$q', 'Restangular', function ($location, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@'
        },
        link: function (scope, element, attrs) {
            const status_name = attrs.type == 'deactive' ? 'Deactive' : 'Deactive';
            scope.icon = attrs.type == 'deactive' ? 'glyphicon-remove' : 'glyphicon-remove';
            scope.label = attrs.type == 'deactive' ? 'Deactive' : 'Deactive';
            scope.updateStatus = function () {
                $q.all(scope.selection.map(function (e) {
                        Restangular.one('/states/' + e.values.id + '/deactive').put()
                            .then(function () {
                                $state.reload()
                            })
                    }))
                    .then(function () {
                        notification.log(scope.selection.length + ' State status changed to  ' + status_name, {addnCls: 'humane-flatty-success'});
                    })
            }
        },
        template: '<span ng-click="updateStatus()"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive('batchCitiesDeactive', ['$location', '$state', 'notification', '$q', 'Restangular', function ($location, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@'
        },
        link: function (scope, element, attrs) {
            const status_name = attrs.type == 'deactive' ? 'Deactive' : 'Deactive';
            scope.icon = attrs.type == 'deactive' ? 'glyphicon-remove' : 'glyphicon-remove';
            scope.label = attrs.type == 'deactive' ? 'Deactive' : 'Deactive';
            scope.updateStatus = function () {
                $q.all(scope.selection.map(function (e) {
                        Restangular.one('/cities/' + e.values.id + '/deactive').put()
                            .then(function () {
                                $state.reload()
                            })
                    }))
                    .then(function () {
                        notification.log(scope.selection.length + ' City status changed to  ' + status_name, {addnCls: 'humane-flatty-success'});
                    })
            }
        },
        template: '<span ng-click="updateStatus()"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive('batchCountriesDeactive', ['$location', '$state', 'notification', '$q', 'Restangular', function ($location, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@'
        },
        link: function (scope, element, attrs) {
            const status_name = attrs.type == 'deactive' ? 'Deactive' : 'Deactive';
            scope.icon = attrs.type == 'deactive' ? 'glyphicon-remove' : 'glyphicon-remove';
            scope.label = attrs.type == 'deactive' ? 'Deactive' : 'Deactive';
            scope.updateStatus = function () {
                $q.all(scope.selection.map(function (e) {
                        Restangular.one('/countries/' + e.values.id + '/deactive').put()
                            .then(function () {
                                $state.reload()
                            })
                    }))
                    .then(function () {
                        notification.log(scope.selection.length + ' Country status changed to  ' + status_name, {addnCls: 'humane-flatty-success'});
                    })
            }
        },
        template: '<span ng-click="updateStatus()"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive('batchVehiclesActive', ['$location', '$state', 'notification', '$q', 'Restangular', function ($location, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@'
        },
        link: function (scope, element, attrs) {
            const status_name = attrs.type == 'active' ? 'Active' : 'Active';
            scope.icon = attrs.type == 'active' ? 'glyphicon-ok' : 'glyphicon-ok';
            scope.label = attrs.type == 'active' ? 'Active' : 'Active';
            scope.updateStatus = function () {
                $q.all(scope.selection.map(function (e) {
                        Restangular.one('/vehicles/' + e.values.id + '/active').put()
                            .then(function () {
                                $state.reload()
                            })
                    }))
                    .then(function () {
                        notification.log(scope.selection.length + ' Vehicle status changed to  ' + status_name, {addnCls: 'humane-flatty-success'});
                    })
            }
        },
        template: '<span ng-click="updateStatus()"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive('batchStatesActive', ['$location', '$state', 'notification', '$q', 'Restangular', function ($location, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@'
        },
        link: function (scope, element, attrs) {
            const status_name = attrs.type == 'active' ? 'Active' : 'Active';
            scope.icon = attrs.type == 'active' ? 'glyphicon-ok' : 'glyphicon-ok';
            scope.label = attrs.type == 'active' ? 'Active' : 'Active';
            scope.updateStatus = function () {
                $q.all(scope.selection.map(function (e) {
                        Restangular.one('/states/' + e.values.id + '/active').put()
                            .then(function () {
                                $state.reload()
                            })
                    }))
                    .then(function () {
                        notification.log(scope.selection.length + ' State status changed to  ' + status_name, {addnCls: 'humane-flatty-success'});
                    })
            }
        },
        template: '<span ng-click="updateStatus()"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive('batchCitiesActive', ['$location', '$state', 'notification', '$q', 'Restangular', function ($location, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@'
        },
        link: function (scope, element, attrs) {
            const status_name = attrs.type == 'active' ? 'Active' : 'Active';
            scope.icon = attrs.type == 'active' ? 'glyphicon-ok' : 'glyphicon-ok';
            scope.label = attrs.type == 'active' ? 'Active' : 'Active';
            scope.updateStatus = function () {
                $q.all(scope.selection.map(function (e) {
                        Restangular.one('/cities/' + e.values.id + '/active').put()
                            .then(function () {
                                $state.reload()
                            })
                    }))
                    .then(function () {
                        notification.log(scope.selection.length + ' City status changed to  ' + status_name, {addnCls: 'humane-flatty-success'});
                    })
            }
        },
        template: '<span ng-click="updateStatus()"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive('batchCountriesActive', ['$location', '$state', 'notification', '$q', 'Restangular', function ($location, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@'
        },
        link: function (scope, element, attrs) {
            const status_name = attrs.type == 'active' ? 'Active' : 'Active';
            scope.icon = attrs.type == 'active' ? 'glyphicon-ok' : 'glyphicon-ok';
            scope.label = attrs.type == 'active' ? 'Active' : 'Active';
            scope.updateStatus = function () {
                $q.all(scope.selection.map(function (e) {
                        Restangular.one('/countries/' + e.values.id + '/active').put()
                            .then(function () {
                                $state.reload()
                            })
                    }))
                    .then(function () {
                        notification.log(scope.selection.length + ' Country status changed to  ' + status_name, {addnCls: 'humane-flatty-success'});
                    })
            }
        },
        template: '<span ng-click="updateStatus()"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
//Custom button Creation
//Synchronize button to synchronize payment gateways
//Referenced Link: http://ng-admin-book.marmelab.com/doc/reference/View.html
ngapp.directive('addSync', ['$location', '$state', '$http', 'notification', function ($location, $state, $http, notification) {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entityName: "@",
            entry: "&",
            size: "@",
            label: "@"
        },
        template: "<a class=\"btn btn-success\" ng-disabled=\"disableButton\" ng-class=\"{ 'btn-{{size}} hide': syncSudopay === false }\" ng-click=\"sync()\">\n<span class=\"glyphicon glyphicon-resize-small sync-icon\" aria-hidden=\"true\"></span>&nbsp;<span class=\"sync hidden-xs\"> {{label}}</span> <span ng-show=\"disableButton\"><i class=\"fa fa-spinner fa-pulse fa-lg\"></i></span>\n</a>",
        link: function (scope, element) {
            scope.syncSudopay = false;
            if (scope.entry().values.name === 'ZazPay') {
                scope.syncSudopay = true;
            }
            scope.sync = function () {
                scope.disableButton = true;
                $http.get(admin_api_url + '/api/admin/sudopay/synchronize').success(function (response) {
                    scope.disableButton = false;
                    notification.log('Synchronized Successfully', {addnCls: 'humane-flatty-success'});
                });
            };
        }
    };
}]);
//Cancel button to cancel vehicle_rentals.
//Referenced Link: http://ng-admin-book.marmelab.com/doc/reference/View.html
ngapp.directive('addCancel', ['$location', '$state', '$http', 'notification', function ($location, $state, $http, notification) {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entityName: "@",
            entry: "&",
            size: "@",
            label: "@",
            post: '&'
        },
        template: "<a class=\"btn btn-default btn-xs\" ng-disabled=\"disableButton\" ng-class=\"{ 'btn-{{size}} hide': removeCancel === false }\" ng-click=\"cancel()\">\n<span class=\"glyphicon glyphicon-ban-circle sync-icon\" title=\"Cancel\" aria-hidden=\"true\"></span>&nbsp;<span class=\"sync hidden-xs\"> {{label}}</span> <span ng-show=\"disableButton\"><i class=\"fa fa-spinner fa-pulse fa-lg\"></i></span>\n</a>",
        link: function (scope, element) {
            scope.removeCancel = false;
            if (scope.entry().values.item_user_status_id === 2 || scope.entry().values.item_user_status_id === 7) {
                scope.removeCancel = true;
            }
            var id = scope.entry().values.id;
            scope.cancel = function () {
                $http.put(admin_api_url + '/api/admin/vehicle_rentals/' + id + '/cancelled-by-admin').success(function (response) {
                    notification.log(response.Success, {addnCls: 'humane-flatty-success'});
                    $state.reload();
                    scope.removeCancel = false;
                }).catch(function (error) {
                    notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
                });
            };
        }

    };
}]);
ngapp.directive('viewActivity', ['$location', '$state', function ($location, $state) {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entityName: "@",
            entry: "&",
            size: "@",
            label: "@"
        },
        template: '<a class="btn btn-primary navbar-btn" title="View activity" target="_blank" ng-class="size ? \'btn-\' + size : \'\'" href="../#/activity/{{vehicle_rental_id}}" >\n<span class="glyphicon glyphicon-tasks" aria-hidden="true"></span>&nbsp;<span class="hidden-xs">{{label}}</span>\n</a>',
        link: function (scope) {
            if (scope.entry()._entityName == 'vehicle_rentals') {
                scope.vehicle_rental_id = scope.entry().values.id + '/all';
            } else {
                scope.vehicle_rental_id = scope.entry().values['item_user_disputable_id'] + '/dispute';
            }
        }
    };
}]);
ngapp.directive('editCompany', ['$location', '$state', function ($location, $state) {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entityName: "@",
            entry: "&",
            size: "@",
            label: "@"
        },
        template: '<a class="btn btn-default btn-xs" href="../ag-admin/#/vehicle_companies/{{vehicle_company_id}}/edit" title="Edit" ng-class="size ? \'btn-\' + size : \'\'" >\n<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>&nbsp;<span class="hidden-xs">{{label}}</span>\n</a>',
        link: function (scope, element) {
            scope.vehicle_company_id = scope.entry().values.id;
        }
    };
}]);
ngapp.directive('editCounterLocation', ['$location', '$state', function ($location, $state) {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entityName: "@",
            entry: "&",
            size: "@",
            label: "@"
        },
        template: '<a class="btn btn-default btn-xs" href="../ag-admin/#/counter_locations/{{counter_location_id}}/edit" title="Edit" ng-class="size ? \'btn-\' + size : \'\'" >\n<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>&nbsp;<span class="hidden-xs">{{label}}</span>\n</a>',
        link: function (scope, element) {
            scope.counter_location_id = scope.entry().values.id;
        }
    };
}]);
ngapp.directive('editVehicle', ['$location', '$state', function ($location, $state) {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entityName: "@",
            entry: "&",
            size: "@",
            label: "@"
        },
        template: '<a class="btn btn-default btn-xs" href="../ag-admin/#/vehicles/{{vehicle_id}}/edit" title="Edit" ng-class="size ? \'btn-\' + size : \'\'" >\n<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>&nbsp;<span class="hidden-xs">{{label}}</span>\n</a>',
        link: function (scope, element) {
            scope.vehicle_id = scope.entry().values.id;
        }
    };
}]);
ngapp.directive('vehicleCalendar', ['$location', '$state', function ($location, $state) {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entityName: "@",
            entry: "&",
            size: "@",
            label: "@"
        },
        template: '<a class="btn btn-default btn-xs" href="../ag-admin/#/vehicles/{{vehicle_id}}/calendar" title="Calendar" ng-class="size ? \'btn-\' + size : \'\'" >\n<i class="fa fa-calendar" aria-hidden="true"></i>&nbsp;<span class="hidden-xs">{{label}}</span>\n</a>',
        link: function (scope, element) {
            scope.vehicle_id = scope.entry().values.id;
        }
    };
}]);

ngapp.directive('showVehicle', ['$location', '$state', function ($location, $state) {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entityName: "@",
            entry: "&",
            size: "@",
            label: "@"
        },
        template: '<a class="btn btn-default btn-xs" title="Show" href="../ag-admin/#/vehicle/{{vehicle_id}}/{{vehicle_slug}}" ng-class="size ? \'btn-\' + size : \'\'" >\n<span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>&nbsp;<span class="hidden-xs">{{label}}</span>\n</a>',
        link: function (scope, element) {
            scope.vehicle_id = scope.entry().values.id;
            scope.vehicle_slug = scope.entry().values.slug;
        }
    };
}]);
ngapp.directive('checkIn', ['$location', '$state', '$http', 'notification', function ($location, $state, $http, notification) {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entityName: "@",
            entry: "&",
            size: "@",
            label: "@",
            post: '&'
        },
        template: "<a class=\"btn btn-default btn-xs\" ng-disabled=\"disableButton\" ng-class=\"{ 'btn-{{size}} hide': removecheckIn === false }\" ng-click=\"checkIn()\">\n<span class=\"fa fa-arrow-circle-down sync-icon\" aria-hidden=\"true\"></span>&nbsp;<span class=\"sync hidden-xs\"> {{label}}</span> <span ng-show=\"disableButton\"><i class=\"fa fa-check-circle fa-check-circle fa-lg\"></i></span>\n</a>",
        link: function (scope, element) {
            scope.removecheckIn = false;
            if (scope.entry().values.checkin === 1) {
                scope.removecheckIn = true;
            }
            var rental_id = scope.entry().values.id;
            scope.checkIn = function () {
                $http.get(admin_api_url + '/api/admin/vehicle_rentals/' + rental_id + '/checkin').success(function (response) {
                    notification.log(response.Success, {addnCls: 'humane-flatty-success'});
                    $state.reload();
                    scope.removecheckIn = false;
                }).catch(function (error) {
                    notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
                });
            };
        }

    };
}]);
ngapp.directive('checkOut', ['$location', '$state', function ($location, $state) {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entityName: "@",
            entry: "&",
            size: "@",
            label: "@"
        },
        template: '<a class="btn btn-default btn-xs" ng-disabled=\"disableButton\"  ng-class=\'{ "btn-{{size}} hide": removecheckOut === false }\'  href="../ag-admin/#/vehicle_rentals/{{rental_id}}/checkout" ng-class="size ? \'btn-\' + size : \'\'" >\n<span class="fa fa-arrow-circle-up" aria-hidden="true"></span>&nbsp;<span class="hidden-xs">{{label}}</span>\n</a>',
        link: function (scope, element) {
            scope.removecheckOut = false;
            if (scope.entry().values.checkout === 1) {
                scope.rental_id = scope.entry().values.id;
                scope.removecheckOut = true;
            }
        }
    };
}]);
ngapp.directive('tripDetail', ['$location', '$state', '$http', function ($location, $state, $http, $scope) {
    return {
        restrict: 'E',
        templateUrl: '../ag-admin/tpl/trip_detail.tpl.html',
    };
}]);
ngapp.directive('vehicleDetail', ['$location', '$state', '$http', function ($location, $state, $http, $scope) {
    return {
        restrict: 'E',
        templateUrl: '../ag-admin/tpl/vehicle.tpl.html',
    };
}]);
//Custom button for change password
//Referenced Link: http://ng-admin-book.marmelab.com/doc/reference/View.html
ngapp.directive('addPassword', ['$location', '$state', '$http', 'notification', function ($location, $state, $http, notification) {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entityName: "@",
            entry: "&",
            size: "@",
            label: "@"
        },
        template: '<a class=\"btn btn-default btn-xs\" title="Change Password" ng-click=\"password()\" >\n<span class=\"glyphicon glyphicon-lock sync-icon\" aria-hidden=\"true\"></span>&nbsp;<span class=\"sync hidden-xs\"> {{label}}</span> <span ng-show=\"disableButton\"><i class=\"fa fa-spinner fa-pulse fa-lg\"></i></span>\n</a>',
        link: function (scope, element) {
            var id = scope.entry().values.id;
            scope.password = function () {
                $state.go('users/change_password', {id: id});
            };
        }
    };
}]);
//Refrech link on settings page
ngapp.directive('refreshPage', function() {
    return {
        restrict: 'E',
        template: '<div class="pull-right mob-dc">To reflect settings changes, you need to <a class="btn btn-xs btn-primary" href="" ng-click="fullRefresh();">refresh</a></div>',
        controller: function ($scope, $window) {
            $scope.fullRefresh = function () {
                $window.location.reload();
            };
        }
    };
});

//custom header  controller defined here.
function customHeaderController($state, $scope, $http, $location, notification) {
	id = 1;
	$http.get(admin_api_url + '/api/admin/users/' + id).success(function (response) {
		$scope.adminDetail = response;
    });
}


//Change password controller defined here.
function changePasswordController($state, $scope, $http, $location, notification) {
    var id = $state.params.id;
    $scope.ChangePassword = function () {
        $http.put(admin_api_url + '/api/admin/users/' + id + '/change_password', $scope.passwordArr).success(function (response) {
            if (response.Success !== undefined) {
                notification.log(response.Success, {addnCls: 'humane-flatty-success'});
                $location.path('/users/list');
            }
        }).catch(function (error) {
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
        });
    };
}
//Pages controller defined here.
function pagesController($scope, $http, $location, notification, AuthCheck) {
    $scope.languageArr = [];
    $scope.init = function () {
        if (!AuthCheck) {
            $location.path('/logout');
        } else {
            $scope.languageList = AuthCheck.data;
        }
    }
    $scope.pageAdd = function () {
        angular.forEach($scope.languageArr.pages, function (value, key) {
            $scope.languageArr.pages[key]['language_id'] = key;
            $scope.languageArr.pages[key]['slug'] = $scope.languageArr.pages.slug;
        }, $scope.languageArr.pages);
        $http.post(admin_api_url + '/api/admin/pages', $scope.languageArr.pages).success(function (response) {
            if (response.Success !== undefined) {
                notification.log(response.Success, {addnCls: 'humane-flatty-success'});
                $location.path('/pages/list');
            } else {
                notification.log('Page could not be updated. Please, try again.', {addnCls: 'humane-flatty-error'});
            }
        });
    };
    $scope.init();
}
//plugins controller function
function pluginsController($scope, $http, notification, $state, $window, AuthCheck) {
    getPluginDetails();
    function getPluginDetails() {
        if (!AuthCheck) {
            $location.path('/logout');
        } else {
            $scope.item_plugin = AuthCheck.data.item_plugin;
            $scope.property_plugin = AuthCheck.data.property_plugin;
            $scope.payment_gateway_plugin = AuthCheck.data.payment_gateway_plugin;
            $scope.other_plugin = AuthCheck.data.other_plugin;
            $scope.enabled_plugin = AuthCheck.data.enabled_plugin;
            localStorage.setItem('enabled_plugins', JSON.stringify(AuthCheck.data.enabled_plugin));
        }
    };
    $scope.checkStatus = function (plugin, enabled_plugins) {
        if ($.inArray(plugin, enabled_plugins) > -1) {
            return true;
        } else {
            return false;
        }
    }
    $scope.updatePluginStatus = function (e, plugin_name, status) {
        e.preventDefault();
        var target = angular.element(e.target);
        checkDisabled = target.parent().hasClass('disabled');
        if (checkDisabled === true) {
            return false;
        }
        var params = {};
        var confirm_msg = '';
        params.plugin_name = plugin_name;
        params.is_enabled = status;
        confirm_msg = (status === 0) ? "Are you sure want to disable?" : "Are you sure want to enable?";
        notification_msg = (status === 0) ? "disabled" : "enabled";
        if (confirm(confirm_msg)) {
            $http.put(admin_api_url + '/api/admin/plugins', params).success(function (response) {
                if (response.Success !== undefined) {
                    notification.log(plugin_name + ' Plugin update successfully.', {addnCls: 'humane-flatty-success'});
                    $state.reload();
                }
            }, function (error) {
                notification.log(plugin_name + ' Plugin could not be update', {addnCls: 'humane-flatty-error'});
                $state.reload();
            });
        }
    }
    $scope.fullRefresh = function () {
        $window.location.reload();
    }
}
//Counter location add controller defined here.
function counterLocationController($state, $scope, $http, $location, notification) {
    var counter_location_id = 0;
    $scope.init = function () {
        if ($state.params.id) {
            counter_location_id = $state.params.id;
            $http.get(admin_api_url + '/api/admin/counter_locations/' + counter_location_id).success(function (response) {
                $scope.counterLocation = response;
                $scope.counterLocation.full_address = response.address;
            });
        }
        $scope.$on('g-places-autocomplete:select', function (event) {
            if (event.targetScope.model.formatted_address.indexOf(event.targetScope.model.name)) {
                $scope.counterLocation.address = event.targetScope.model.name + ', ' + event.targetScope.model.formatted_address;
            } else {
                $scope.counterLocation.address = event.targetScope.model.formatted_address;
            }
            $scope.counterLocation.latitude = event.targetScope.model.geometry.location.lat();
            $scope.counterLocation.longitude = event.targetScope.model.geometry.location.lng();
        });
    };
    $scope.CounterLocationAdd = function (isvalid) {
        if (isvalid) {
            $scope.LocationAdd.$setPristine();
            $scope.LocationAdd.$setUntouched();
            $http.post(admin_api_url + '/api/admin/counter_locations', $scope.counterLocation).success(function (response) {
                if (response.Success !== undefined) {
                    notification.log(response.Success, {addnCls: 'humane-flatty-success'});
                    $location.path('/counter_locations/list');
                }
            }).error(function (error) {
                notification.log(error.message, {addnCls: 'humane-flatty-error'});
            });
        }
    };
    $scope.CounterLocationEdit = function (isvalid) {
        if (isvalid) {
            $scope.counterLocation.id = counter_location_id;
            $http.put(admin_api_url + '/api/admin/counter_locations/' + counter_location_id, $scope.counterLocation).success(function (response) {
                if (response.Success !== undefined) {
                    notification.log(response.Success, {addnCls: 'humane-flatty-success'});
                    $location.path('/counter_locations/list');
                }
            }).error(function(error) {
                notification.log(error.message, {addnCls: 'humane-flatty-error'});
            });
        }
    };
    $scope.init();
}
//Vehicle company add controller defined here.
function vehicleCompaniesController($state, $scope, $http, $location, notification) {
    var model = this;
    var vehicle_company_id = 0;
    $scope.init = function () {
        if ($state.params.id) {
            vehicle_company_id = $state.params.id;
            $http.get(admin_api_url + '/api/admin/vehicle_companies/' + vehicle_company_id).success(function (response) {
                $scope.vehicleCompanies = response;
                $scope.vehicleCompanies.full_address = response.address;
            });
        }
        $http.get(admin_api_url + '/api/admin/users?limit=all').success(function (response) {
            $scope.users = response.data;
        });

        $scope.$on('g-places-autocomplete:select', function (event) {
            if (event.targetScope.model.formatted_address.indexOf(event.targetScope.model.name)) {
                $scope.vehicleCompanies.address = event.targetScope.model.name + ', ' + event.targetScope.model.formatted_address;
            } else {
                $scope.vehicleCompanies.address = event.targetScope.model.formatted_address;
            }
            $scope.vehicleCompanies.latitude = event.targetScope.model.geometry.location.lat();
            $scope.vehicleCompanies.longitude = event.targetScope.model.geometry.location.lng();
        });
    };
    $scope.VehicleCompaniesAdd = function (isvalid) {
        if (isvalid) {
            $http.post(admin_api_url + '/api/admin/vehicle_companies', $scope.vehicleCompanies).success(function (response) {
                if (response.Success !== undefined) {
                    notification.log(response.Success, {addnCls: 'humane-flatty-success'});
                    $location.path('/vehicle_companies/list');
                }
            }).catch(function (message) {
                angular.forEach(message.data.errors, function (value, key) {
                    if (key == 'address') {
                        model.full_addressErr = value;
                    }
                });
                notification.log(message.data.message, {addnCls: 'humane-flatty-error'});
            });
        }
    };
    $scope.VehicleCompaniesEdit = function (isvalid) {
        if (isvalid) {
            $scope.vehicleCompanies.id = vehicle_company_id;
            $http.put(admin_api_url + '/api/admin/vehicle_companies/' + vehicle_company_id, $scope.vehicleCompanies).success(function (response) {
                if (response.Success !== undefined) {
                    notification.log(response.Success, {addnCls: 'humane-flatty-success'});
                    $location.path('/vehicle_companies/list');
                }
            }, function (error) {
                notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            });
        }
    };
    $scope.init();
}
//Vehicle type price controller defined here.
function vehicleTypePriceController($state, $scope, $http, $location, notification) {
    $http.get(admin_api_url + '/api/admin/vehicle_types').success(function (response) {
        $scope.vehicleTypes = response.data;
    });
    $scope.choices = [{group: 1}];
    $scope.addNewChoice = function () {
        var newItemNo = $scope.choices.length + 1;
        $scope.choices.push({group: newItemNo});
    };
    $scope.removeChoice = function () {
        var lastItem = $scope.choices.length - 1;
        $scope.choices.splice(lastItem);
    };
    $scope.VehicleTypePrices = function (isvalid) {
        if (isvalid) {
            $http.post(admin_api_url + '/api/admin/vehicle_type_prices', $scope.vehicleTypePrices).success(function (response) {
                if (response.Success !== undefined) {
                    notification.log(response.Success, {addnCls: 'humane-flatty-success'});
                    $location.path('/vehicle_type_prices/list');
                }
            }).catch(function (error) {
                notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            });
        }
    };
}
//Default pages/create changed here to pages/add.
ngapp.directive('createPage', ['$location', '$state', function ($location, $state) {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entityName: "@",
            entry: "&",
            size: "@",
            label: "@"
        },
        template: '<a class="btn btn-create-green" ng-class="size ? \'btn-\' + size : \'\'" href="#/pages/add" >\n<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>&nbsp;<span class="hidden-xs">Create</span>\n</a>',
        link: function (scope) {
        }
    };
}]);
function vehicleViewController($state, $scope, $http, $location, notification) {
    var model = this;
    var vehicle_id = 0;
    $scope.enabled_plugin = localStorage.getItem('enabled_plugins');
    $scope.siteCurrency = localStorage.getItem('site_currency');
    $scope.init = function () {
        $scope.currentPage = ($scope.currentPage !== undefined) ? parseInt($scope.currentPage) : 1;
        $scope.maxRatings = [];
        $scope.maxRating = 5;
        for (var i = 0; i < $scope.maxRating; i++) {
            $scope.maxRatings.push(i);
        }
        if ($state.params.id) {
            vehicle_id = $state.params.id;
            $http.get(admin_api_url + '/api/admin/vehicles/' + vehicle_id).success(function (response) {
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
            });
            $http.get(admin_api_url + '/api/admin/vehicle_feedbacks?vehicle_id=' + vehicle_id + '&page=' + $scope.currentPage).success(function (response) {
                $scope.vehicleFeedbacks = response.data;
                $scope.vehicle_metadata = response.meta.pagination;
            });
        }
    };
    $scope.init();
    $scope.paginate = function (pageno) {
        $scope.currentPage = parseInt(pageno);
        $scope.init();
    };
}
//Vehicle Add
function vehicleController($state, $scope, $http, Upload, $location, notification) {
    var vehicle_id = 0;
    $scope.settings = localStorage.getItem('settings');
    angular.forEach(JSON.parse($scope.settings), function (setting, key) {
        if (setting.name == 'vehicle.driver_min_age') {
            $scope.driver_min_age = parseInt(setting.value);
        }
        if (setting.name == 'vehicle.driver_max_age') {
            $scope.driver_max_age = parseInt(setting.value);
        }
    });
    $scope.vehicle = {};
    $scope.vehicle.pickup_counter_locations = [];
    $scope.vehicle.drop_counter_locations = [];
    $scope.init = function () {
        if ($state.params.id) {
            vehicle_id = $state.params.id;
            $http.get(admin_api_url + '/api/admin/vehicles/' + vehicle_id).success(function (response) {
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
            });
        }
    };
    $http.get(admin_api_url + '/api/admin/vehicle/add').success(function (response) {
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
    $scope.getVehicleModel = function (vehicle_make_id) {
        $http.get(admin_api_url + '/api/admin/vehicle_models?vehicle_make_id=' + vehicle_make_id).success(function (response) {
            $scope.vehicleModels = response.data;
        });
    };
    $scope.getVehicleTypePrice = function (vehicle_type_id) {
        $http.get(admin_api_url + '/api/admin/vehicle_types/' + vehicle_type_id).success(function (response) {
            $scope.vehicleType = response;
        });
    };
    $scope.getNumber = function (num) {
        return new Array(num);
    };
    $scope.Range = function (min, max) {
        var result = [];
        for (var i = parseFloat(min); i <= parseFloat(max); i++) {
            result.push(i);
        }
        return result;
    };
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
    $scope.vehicleSubmit = function ($valid, file) {
        if ($valid) {
            $scope.vehicle.file = file;
            Upload.upload({
                url: admin_api_url + '/api/admin/vehicles',
                data: $scope.vehicle
            }).then(function (response) {
                if (response.data.Success !== undefined) {
                    notification.log(response.data.Success, {addnCls: 'humane-flatty-success'});
                    $location.path('/vehicles/list');
                } else {
                    notification.log('Vehicle could not be updated. Please, try again.', {addnCls: 'humane-flatty-error'});
                }
            });
        }
    };
    $scope.checkStatus = function (id, selected_list) {
        if ($.inArray(parseInt(id), selected_list) > -1) {
            return true;
        } else {
            return false;
        }
    }
    $scope.vehicleEdit = function ($valid, file) {
        if ($valid) {
            if ($scope.file !== undefined) {
                Upload.upload({
                    url: admin_api_url + '/api/admin/vehicles/' + vehicle_id,
                    data: {
                        file: $scope.file,
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
                        'fuel_type_id': $scope.vehicle.fuel_type_id,
                        'vehicle_company_id': $scope.vehicle.vehicle_company_id,
                        'is_active': $scope.vehicle.is_active
                    }
                }).then(function (response) {
                    if (response.data.Success !== undefined) {
                        notification.log(response.data.Success, {addnCls: 'humane-flatty-success'});
                        $location.path('/vehicles/list');
                    } else {
                        notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
                    }
                });

            } else {
                $scope.vehicle.id = vehicle_id;
                $http.post(admin_api_url + '/api/admin/vehicles/' + vehicle_id, $scope.vehicle).success(function (response) {
                    if (response.Success !== undefined) {
                        notification.log(response.Success, {addnCls: 'humane-flatty-success'});
                        $location.path('/vehicles/list');
                    }
                }, function (error) {
                    notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
                });
            }
        }
    };
    $scope.init();
}
//vehicle calendar controller
function vehicleCalendarController ($state, $scope, $http, $location, notification, $filter) {
    $scope.getEvents = function() {
        $http.get(admin_api_url + '/api/admin/vehicle_rentals?type=booking&vehicle_id='+$state.params.id).success(function(response) {
            if(response.data) {
                $scope.events = $scope.setEvents(response.data);
            }
        });
    };
    $scope.init = function () {
        $scope.calendarView = 'month';
        $scope.viewDate = new Date();
        $scope.isCellOpen = true;
        $scope.calendarTitle = 'Vehicle Calendar';
        $scope.getEvents();
    };
    $scope.setEvents = function(vehicles) {
        var eventsLists = [];
        var types = ['info', 'warning', 'primary', 'danger', 'success'];
        angular.forEach(vehicles, function(value, key) {
            var start_date = $filter("date")(new Date(value.item_booking_start_date), "MMM d, y h:mm a");
            var end_date = $filter('date')(new Date(value.item_booking_end_date), 'MMM d, y h:mm a');
            eventsList = {
                title: value.item_userable.name+' '+start_date+' - '+end_date,
                type: types[key % 5],
                startsAt: value.item_booking_start_date,
                endsAt: value.item_booking_end_date,
                draggable: false,
                resizable: false,
                editable: false,
                deletable: false,
                event: value
            };
            eventsLists.push(eventsList);
        });
        return eventsLists;
    };
    $scope.eventClicked = function(cal_event) {
        window.location = '../#/activity/'+cal_event.event.id+'/note';
    };
    $scope.init();
}
//Vehicle Add
function vehiclecheckOutController($state, $scope, $http, $location, notification, $window) {
    var vehicle_rental_id = 0;
    $scope.check_out = {};
    $scope.init = function () {
        $scope.enabled_plugin = localStorage.getItem('enabled_plugins');
        $scope.siteCurrency = localStorage.getItem('site_currency');
        if ($state.params.id) {
            vehicle_rental_id = $state.params.id;
            $http.get(admin_api_url + '/api/admin/vehicle_rentals/' + vehicle_rental_id).success(function (response) {
                $scope.vehicleDetails = response.item_userable;
                $scope.VehicleRentalDetails = response;
            });
        }
    };
    $scope.calculateAmount = function (amount) {
        if ($scope.VehicleRentalDetails.late_checkout_total_fee) {
            amount = amount + $scope.VehicleRentalDetails.late_checkout_total_fee;
        }
        if (($scope.VehicleRentalDetails.deposit_amount > 0)) {
            if ($scope.VehicleRentalDetails.deposit_amount > amount) {
                $scope.manualPay = 0;
                $scope.claimToDeposit = amount;
            } else {
                $scope.manualPay = amount - $scope.VehicleRentalDetails.deposit_amount;
                $scope.claimToDeposit = $scope.VehicleRentalDetails.deposit_amount;
            }
        } else {
            $scope.manualPay = amount;
        }
    };
    $scope.checkout = function ($valid) {
        var checkout = $window.confirm('Are you sure want to checkout?');
        $scope.check_out.id = $state.params.id;
        $scope.check_out.claim_request_amount = $scope.claim_amount;
        $http.post(admin_api_url + '/api/admin/vehicle_rentals/' + vehicle_rental_id + '/checkout', $scope.check_out).success(function (response) {
            if (response.Success !== undefined) {
                notification.log(response.Success, {addnCls: 'humane-flatty-success'});
                $location.path('/vehicle_rentals/list');
            }
        }, function (error) {
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
        });
    };
    $scope.init();
}
ngapp.config(['NgAdminConfigurationProvider', 'RestangularProvider', 'ngAdminJWTAuthConfiguratorProvider', function (NgAdminConfigurationProvider, RestangularProvider, ngAdminJWTAuthConfigurator) {
    var enabledPlugins = localStorage.getItem('enabled_plugins');
    var siteCurrency = localStorage.getItem('site_currency');
    var nga = NgAdminConfigurationProvider;
    ngAdminJWTAuthConfigurator.setJWTAuthURL(admin_api_url + '/api/users/login');
    ngAdminJWTAuthConfigurator.setCustomLoginTemplate('tpl/customLoginTemplate.html');
    ngAdminJWTAuthConfigurator.setCustomAuthHeader({
        name: 'Authorization',
        template: 'Bearer {{token}}'
    });
    //trunctate function to shorten text length.
    function truncate(value) {
        if (!value) {
            return '';
        }
        return value.length > 50 ? value.substr(0, 50) + '...' : value;
    }

    // create an admin application
    var admin = nga.application('BookorRent') // application main title
        .debug(true) // debug disabled
        .baseApiUrl(admin_api_url + '/api/admin/'); // main API endpoint
    // Creating all entities
    var users = nga.entity('users');
    var user_logins = nga.entity('user_logins');
    var cities = nga.entity('cities');
    var states = nga.entity('states');
    var countries = nga.entity('countries');
    var pages = nga.entity('pages');
    var email_templates = nga.entity('email_templates');
    var languages = nga.entity('languages');
    var settings = nga.entity('settings');
    var setting_categories = nga.entity('setting_categories');
    var ips = nga.entity('ips');
    var providers = nga.entity('providers');
    var roles = nga.entity('roles');
    var pages = nga.entity('pages');
    var contacts = nga.entity('contacts');
    var user_cash_withdrawals = nga.entity('user_cash_withdrawals');
    var user_logins = nga.entity('user_logins');
    var plugins = nga.entity('plugins');
    var vehicles = nga.entity('vehicles');
    var vehicle_coupons = nga.entity('vehicle_coupons');
    var vehicle_disputes = nga.entity('vehicle_disputes');
    var transaction_types = nga.entity('transaction_types');
    var transactions = nga.entity('transactions');
    var sudopay_transaction_logs = nga.entity('sudopay_transaction_logs');
    var paypal_transaction_logs = nga.entity('paypal_transaction_logs');
    var wallet_transaction_logs = nga.entity('wallet_transaction_logs');
    var sudopay_ipn_logs = nga.entity('sudopay_ipn_logs');
    var cancellation_types = nga.entity('cancellation_types');
    var vehicle_rentals = nga.entity('vehicle_rentals');
    var messages = nga.entity('messages');
    var vehicle_feedbacks = nga.entity('vehicle_feedbacks');
    var currencies = nga.entity('currencies');
    var currency_conversions = nga.entity('currency_conversions');
    var currency_conversion_histories = nga.entity('currency_conversion_histories');
    var api_requests = nga.entity('api_requests');
    var vehicle_dispute_types = nga.entity('vehicle_dispute_types');
    var vehicle_dispute_closed_types = nga.entity('vehicle_dispute_closed_types');
    var provider_users = nga.entity('provider_users');
    var vehicle_companies = nga.entity('vehicle_companies');
    var vehicle_makes = nga.entity('vehicle_makes');
    var vehicle_models = nga.entity('vehicle_models');
    var vehicle_types = nga.entity('vehicle_types');
    var vehicle_taxes = nga.entity('vehicle_taxes');
    var vehicle_type_taxes = nga.entity('vehicle_type_taxes');
    var vehicle_surcharges = nga.entity('vehicle_surcharges');
    var vehicle_type_surcharges = nga.entity('vehicle_type_surcharges');
    var vehicle_insurances = nga.entity('vehicle_insurances');
    var vehicle_type_insurances = nga.entity('vehicle_type_insurances');
    var vehicle_extra_accessories = nga.entity('vehicle_extra_accessories');
    var vehicle_type_extra_accessories = nga.entity('vehicle_type_extra_accessories');
    var vehicle_fuel_options = nga.entity('vehicle_fuel_options');
    var vehicle_type_fuel_options = nga.entity('vehicle_type_fuel_options');
    var vehicle_special_prices = nga.entity('vehicle_special_prices');
    var counter_locations = nga.entity('counter_locations');
    var vehicle_type_prices = nga.entity('vehicle_type_prices');
    var vehicle_search = nga.entity('vehicle_search');
    var unavailable_vehicles = nga.entity('unavailable_vehicles');
    //Adding all the entities to the admin application.
    admin.addEntity(users);
    admin.addEntity(user_logins);
    admin.addEntity(cities);
    admin.addEntity(states);
    admin.addEntity(countries);
    admin.addEntity(pages);
    admin.addEntity(email_templates);
    admin.addEntity(languages);
    admin.addEntity(settings);
    admin.addEntity(setting_categories);
    admin.addEntity(ips);
    admin.addEntity(providers);
    admin.addEntity(roles);
    admin.addEntity(pages);
    admin.addEntity(contacts);
    admin.addEntity(user_cash_withdrawals);
    admin.addEntity(user_logins);
    admin.addEntity(plugins);
    admin.addEntity(vehicles);
    admin.addEntity(transaction_types);
    admin.addEntity(vehicle_coupons);
    admin.addEntity(vehicle_disputes);
    admin.addEntity(transactions);
    admin.addEntity(sudopay_transaction_logs);
    admin.addEntity(paypal_transaction_logs);
    admin.addEntity(wallet_transaction_logs);
    admin.addEntity(sudopay_ipn_logs);
    admin.addEntity(cancellation_types);
    admin.addEntity(vehicle_rentals);
    admin.addEntity(messages);
    admin.addEntity(vehicle_feedbacks);
    admin.addEntity(currencies);
    admin.addEntity(currency_conversions);
    admin.addEntity(currency_conversion_histories);
    admin.addEntity(api_requests);
    admin.addEntity(vehicle_dispute_types);
    admin.addEntity(vehicle_dispute_closed_types);
    admin.addEntity(provider_users);
    admin.addEntity(vehicle_companies);
    admin.addEntity(vehicle_makes);
    admin.addEntity(vehicle_models);
    admin.addEntity(vehicle_types);
    admin.addEntity(vehicle_taxes);
    admin.addEntity(vehicle_type_taxes);
    admin.addEntity(vehicle_surcharges);
    admin.addEntity(vehicle_type_surcharges);
    admin.addEntity(vehicle_insurances);
    admin.addEntity(vehicle_type_insurances);
    admin.addEntity(vehicle_extra_accessories);
    admin.addEntity(vehicle_type_extra_accessories);
    admin.addEntity(vehicle_fuel_options);
    admin.addEntity(vehicle_type_fuel_options);
    admin.addEntity(vehicle_special_prices);
    admin.addEntity(counter_locations);
    admin.addEntity(vehicle_type_prices);
    admin.addEntity(vehicle_search);
    admin.addEntity(unavailable_vehicles);
    roles.listView().title('Roles')
        .fields([
            nga.field('id').label('ID'),
            nga.field('name').label('Name')
        ])
        .perPage(limit_per_page)
        .listActions(['edit', 'delete'])
        .filters([
            nga.field('q').label('Search', 'template')
                .pinned(true)
                .template('<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"/><span class="input-group-addon"><i class="glyphicon glyphicon-search text-primary"></i></span></div>')
        ]);
    var countries_custom_tmp = '<ma-filter-button filters="filters()" enabled-filters="enabledFilters" enable-filter="enableFilter()"></ma-filter-button>';
    countries.listView().title('Countries')
        .fields([
            nga.field('name').label('Name'),
            nga.field('iso2').label('iso2')
        ])
        .infinitePagination(false)
        .perPage(limit_per_page)
        .listActions(['edit', 'delete'])
        .filters([
            nga.field('q').label('Search', 'template')
                .pinned(true)
                .template('<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"/><span class="input-group-addon"><i class="glyphicon glyphicon-search text-primary"></i></span></div>')
        ])
        .actions(['create', 'batch', countries_custom_tmp])
		.batchActions([
            '<batch-countries-deactive type="deactive" selection="selection"></batch-countries-deactive>',
            '<batch-countries-active type="active" selection="selection"></batch-countries-active>',
            'delete'
        ]);
    countries.creationView().title('Add Country')
        .fields([
            nga.field('name').label('Country')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Country'
                }),
            nga.field('iso2').label('iso2')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'iso2'
                }),
            nga.field('iso3').label('iso3')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'iso3'
                })
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            // stop the progress bar
            progression.done();
            // add a notification
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            // redirect to the list view
            $state.go($state.get('list'), {entity: entity.name()});
            // cancel the default action (redirect to the add view)
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            // Error return to form fields.
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            // stop the progress bar
            progression.done();
            // add a notification
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);
    countries.editionView().title('Edit Country')
        .fields([
            nga.field('name').label('Name'),
            nga.field('iso2').label('iso2'),
            nga.field('iso3').label('iso3')
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);
    var states_custom_tmp = '<ma-filter-button filters="filters()" enabled-filters="enabledFilters" enable-filter="enableFilter()"></ma-filter-button>';
    states.listView().title('States')
        .fields([
            nga.field('name').label('Name'),
            nga.field('Country.name').label('Country'),
            nga.field('is_active', 'boolean').label('Active?')
        ])
        .perPage(limit_per_page)
        .listActions(['edit', 'delete'])
        .filters([
            nga.field('q').label('Search', 'template')
                .pinned(true)
                .template('<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"/><span class="input-group-addon"><i class="glyphicon glyphicon-search text-primary"></i></span></div>'),
            nga.field('filter', 'choice').label('Status').attributes({
                    placeholder: 'Status?'
                })
                .choices([{
                    label: 'Active',
                    value: 'active'
                }, {
                    label: 'Inactive',
                    value: 'inactive'
                }])
        ])
        .actions(['create', 'batch', states_custom_tmp])
		.batchActions([
            '<batch-states-deactive type="deactive" selection="selection"></batch-states-deactive>',
            '<batch-states-active type="active" selection="selection"></batch-states-active>',
            'delete'
        ]);
    states.creationView().title('Add State')
        .fields([
            nga.field('name')
                .label('State')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'State'
                }),
            nga.field('country_id', 'reference')
                .label('Country')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Country'
                })
                .targetEntity(nga.entity('countries'))
                .perPage('all') // For getting all list
                .targetField(nga.field('name').map(truncate)),
            nga.field('is_active', 'choice').label('Active?')
                .attributes({
                    placeholder: 'Active?'
                })
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: true
                }, {
                    label: 'No',
                    value: false
                }])
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);
    states.editionView().title('Edit State')
        .fields([
            nga.field('name')
                .validation({
                    required: true
                })
                .label('State'),
            nga.field('country_id', 'reference')
                .label('Country')
                .validation({
                    required: true
                })
                .perPage('all') // For getting all list
                .targetEntity(nga.entity('countries'))
                .targetField(nga.field('name').map(truncate)),
            nga.field('is_active', 'choice').label('Active?')
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: true
                }, {
                    label: 'No',
                    value: false
                }])
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);
    var cities_custom_tmp = '<ma-filter-button filters="filters()" enabled-filters="enabledFilters" enable-filter="enableFilter()"></ma-filter-button>';
    cities.listView().title('Cities')
        .fields([
            nga.field('name').label('Name'),
            nga.field('State.name').label('State'),
            nga.field('Country.name').label('Country'),
            nga.field('is_active', 'boolean').label('Active?')
        ])
        .perPage(limit_per_page)
        .listActions(['edit', 'delete'])
        .filters([
            nga.field('q').label('Search', 'template')
                .pinned(true)
                .template('<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"/><span class="input-group-addon"><i class="glyphicon glyphicon-search text-primary"></i></span></div>'),
            nga.field('filter', 'choice').label('Status').attributes({
                    placeholder: 'Status?'
                })
                .choices([{
                    label: 'Active',
                    value: 'active'
                }, {
                    label: 'Inactive',
                    value: 'inactive'
                }])
        ])
        .actions(['create', 'batch', cities_custom_tmp])
		.batchActions([
            '<batch-cities-deactive type="deactive" selection="selection"></batch-cities-deactive>',
            '<batch-cities-active type="active" selection="selection"></batch-cities-active>',
            'delete'
        ]);
    cities.creationView().title('Add City')
        .fields([
            nga.field('name').label('Name')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Name'
                }),
            nga.field('state_id', 'reference')
                .label('State')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'State'
                })
                .targetEntity(nga.entity('states'))
                .perPage('all') // For getting all list
                .targetField(nga.field('name').map(truncate)),
            nga.field('country_id', 'reference')
                .label('Country')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Country'
                })
                .perPage('all') // For getting all list
                .targetEntity(nga.entity('countries'))
                .targetField(nga.field('name').map(truncate)),
            nga.field('latitude').label('Latitude')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Latitude'
                }),
            nga.field('longitude').label('Longitude')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Longitude'
                }),
            nga.field('is_active', 'choice').label('Active?')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Active?'
                })
                .choices([{
                    label: 'Yes',
                    value: true
                }, {
                    label: 'No',
                    value: false
                }])
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);
    cities.editionView().title('Edit City')
        .fields([
            nga.field('name')
                .label('Name')
                .validation({
                    required: true
                }),
            nga.field('state_id', 'reference')
                .label('State')
                .validation({
                    required: true
                })
                .targetEntity(nga.entity('states'))
                .perPage('all') // For getting all list
                .targetField(nga.field('name').map(truncate)),
            nga.field('country_id', 'reference')
                .label('Country')
                .validation({
                    required: true
                })
                .targetEntity(nga.entity('countries'))
                .perPage('all') // For getting all list
                .targetField(nga.field('name').map(truncate)),
            nga.field('latitude')
                .label('Latitude')
                .validation({
                    required: true
                }),
            nga.field('longitude')
                .label('longitude')
                .validation({
                    required: true
                }),
            nga.field('is_active', 'choice').label('Active?')
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: true
                }, {
                    label: 'No',
                    value: false
                }])
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);
    var providers_custom_tmp = '<ma-filter-button filters="filters()" enabled-filters="enabledFilters" enable-filter="enableFilter()"></ma-filter-button>';
    providers.listView().title('Providers')
        .fields([
            nga.field('id').label('ID'),
            nga.field('name').label('Name'),
            nga.field('api_key').label('Client ID').map(truncate),
            nga.field('secret_key').label('Secret Key').map(truncate),
            nga.field('display_order').label('Display Order'),
            nga.field('is_active', 'boolean').label('Active?'),
        ]).listActions(['delete', 'edit'])
        .sortField('id')
        .sortDir('ASC')
        .filters([
            nga.field('q').label('Search', 'template')
                .pinned(true)
                .template('<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"/><span class="input-group-addon"><i class="glyphicon glyphicon-search text-primary"></i></span></div>')
        ])
        .actions(['batch', providers_custom_tmp]);
    providers.editionView().title('Edit Providers') // "Social Login" plugins
        .fields([
            nga.field('name')
                .label('Name')
                .validation({
                    required: true
                }),
            nga.field('api_key')
                .label('Client ID'),
            nga.field('secret_key')
                .label('Secret Key'),
            nga.field('display_order')
                .label('Display Order'),
            nga.field('is_active', 'choice').label('Active?')
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: true
                }, {
                    label: 'No',
                    value: false
                }])
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);
    email_templates.listView().title('Email Templates')
        .fields([
            nga.field('name').label('Name'),
            nga.field('from_name').label('From Name'),
            nga.field('subject').label('Subject').map(truncate),
            nga.field('body_content').label('Content').map(truncate)
        ])
        .perPage(limit_per_page)
        .listActions(['edit'])
        .batchActions([])
        .filters([
            nga.field('q').label('Search', 'template')
                .pinned(true)
                .template('<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"/><span class="input-group-addon"><i class="glyphicon glyphicon-search text-primary"></i></span></div>')
        ])
        .actions([]);
    email_templates.editionView().title('Edit Email Template')
        .fields([
            nga.field('name').editable(false).label('Name'),
            nga.field('from_name')
                .label('From Name')
                .validation({
                    required: true
                }),
            nga.field('subject')
                .label('Subject')
                .validation({
                    required: true
                }),
            nga.field('body_content', 'wysiwyg')
                .validation({
                    required: true
                })
                .stripTags(true),
            nga.field('info').editable(false).label('Constant for Subject and Content'),
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);
    var languages_custom_tmp = '<ma-filter-button filters="filters()" enabled-filters="enabledFilters" enable-filter="enableFilter()"></ma-filter-button>';
    languages.listView().title('Languages')
        .fields([
            nga.field('name').label('Name'),
            nga.field('iso2').label('ISO2'),
            nga.field('is_active', 'boolean').label('Active?')
        ])
        .perPage(limit_per_page)
        .listActions(['edit', 'delete'])
        .filters([
            nga.field('q').label('Search', 'template')
                .pinned(true)
                .template('<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"/><span class="input-group-addon"><i class="glyphicon glyphicon-search text-primary"></i></span></div>'),
            nga.field('filter', 'choice').label('Status').attributes({
                    placeholder: 'Status?'
                })
                .choices([{
                    label: 'Active',
                    value: 'active'
                }, {
                    label: 'Inactive',
                    value: 'inactive'
                },])
        ])
        .actions(['create', 'batch', languages_custom_tmp]);
    languages.creationView().title('Add Language')
        .fields([
            nga.field('name').label('Name')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Name'
                }),
            nga.field('iso2').label('ISO2')
                .validation({
                    required: true,
                    minlength: 2,
                    maxlength: 2
                })
                .attributes({
                    placeholder: 'ISO2'
                }),
            nga.field('iso3').label('ISO3')
                .validation({
                    required: true,
                    minlength: 3,
                    maxlength: 3
                })
                .attributes({
                    placeholder: 'ISO3'
                }),
            nga.field('is_active', 'choice').label('Active?')
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: true
                }, {
                    label: 'No',
                    value: false
                }])
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);
    languages.editionView().title('Edit Language')
        .fields([
            nga.field('name')
                .label('Name')
                .validation({
                    required: true
                }),
            nga.field('iso2').label('ISO2')
                .validation({
                    required: true
                }),
            nga.field('iso3').label('ISO3')
                .validation({
                    required: true
                }),
            nga.field('is_active', 'choice').label('Active?')
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: true
                }, {
                    label: 'No',
                    value: false
                }])
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);
	var users_custom_tmp = '<ma-filter-button filters="filters()" enabled-filters="enabledFilters" enable-filter="enableFilter()"></ma-filter-button>';
    users.listView()
        .fields([
            nga.field('created_at').label('Registered On'),
            nga.field('role_id', 'choice').label('User Type')
                .choices([{
                    label: 'Admin',
                    value: '1'
                }, {
                    label: 'User',
                    value: '2'
                }]),
            nga.field('username').label('Name'),
            nga.field('email').label('Email'),
            nga.field('available_wallet_amount', 'number').format('0.00').label('Available Balance (' + siteCurrency + ')'),
            nga.field('user_login_count', 'number').label('Login Count'),
            nga.field('is_active', 'boolean').label('Active?'),
            nga.field('is_email_confirmed', 'boolean').label('Email Confirmed?'),
        ])		
        .filters([
            nga.field('q').label('Search')
                .pinned(true)
                .template('<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"/><span class="input-group-addon"><i class="glyphicon glyphicon-search text-primary"></i></span></div>'),
            nga.field('filter', 'choice').label('Status').attributes({
                    placeholder: 'Status?'
                })
                .choices([{
                    label: 'Active',
                    value: 'active'
                }, {
                    label: 'Inactive',
                    value: 'inactive'
                },]),
            nga.field('role_id', 'choice').label('User Type').attributes({
                    placeholder: 'Select User Type'
                })
                .choices([{
                    label: 'User',
                    value: 'userpass'
                }, {
                    label: 'Admin',
                    value: 'admin'
                }
                ]),
            nga.field('is_email_confirmed', 'choice').label('Email Verified Status').attributes({
                    placeholder: 'Email Verified?'
                })
                .choices([{
                    label: 'Yes',
                    value: 'yes'
                }, {
                    label: 'No',
                    value: 'no'
                }])
        ])
		.actions(['create', 'batch', users_custom_tmp]);
    // set the fields of the user entity list view
    users.listView().title('Users') // default title is "[Entity_name] list"
        .infinitePagination(false) // load pages as the user scrolls		
        .perPage(limit_per_page)
        .batchActions([
            '<batch-deactive type="deactive" selection="selection"></batch-deactive>',
            '<batch-active type="active" selection="selection"></batch-active>',
            'delete'
        ])
        .listActions(['<add-password entry="entry" entity="entity" size="sm" label="Change Password" ></add-password>', 'show', 'edit', 'delete']);
    users.creationView().title('Add User')
        .fields([
            nga.field('role_id', 'choice').label('User Type')
                .choices([{
                    label: 'Admin',
                    value: '1'
                }, {
                    label: 'User',
                    value: '2'
                }])
                .attributes({
                    placeholder: 'User Type'
                })
                .validation({
                    required: true
                }),
            nga.field('username').label('Name').map(truncate)
                .attributes({
                    placeholder: 'Name'
                })
                .validation({
                    required: true
                }),
            nga.field('email', 'email').label('Email')
                .attributes({
                    placeholder: 'Email'
                })
                .validation({
                    required: true
                }),
            nga.field('password', 'password').label('Password')
                .attributes({
                    placeholder: 'Password'
                })
                .validation({
                    required: true
                }),
            nga.field('is_active', 'choice').label('Active?')
                .attributes({
                    placeholder: 'Active?'
                })
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: true
                }, {
                    label: 'No',
                    value: false
                },]),
            nga.field('is_email_confirmed', 'choice').label('Email Confirmed?')
                .attributes({
                    placeholder: 'Email Confirmed?'
                })
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: 1
                }, {
                    label: 'No',
                    value: 0
                }]),
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);
    users.showView()
        .title('Show #{{ entry.values.username }}')
        .fields([
            nga.field('created_at').label('Registered On'),
            nga.field('role_id', 'choice').label('User Type')
                .choices([{
                    label: 'Admin',
                    value: '1'
                }, {
                    label: 'User',
                    value: '2'
                }]),
            nga.field('username').label('Name'),
            nga.field('email').label('Email'),
            nga.field('available_wallet_amount', 'number').format('0.00').label('Available Balance (' + siteCurrency + ')'),
            nga.field('user_login_count', 'number').label('Login Count'),
            nga.field('vehicle_rental_count', 'number').label('Vehicle Rental Count'),
            nga.field('vehicle_rental_order_count', 'number').label('Vehicle Rental Order Count'),
            nga.field('is_active', 'boolean').label('Active?'),
            nga.field('is_email_confirmed', 'boolean').label('Email Confirmed?'),
            nga.field('register_ip.ip').label('Register IP'),
            nga.field('last_login_ip.ip').label('Last Login IP'),
            nga.field('', 'template').label('Related User Logins')
                .template('<span class="pull-right"><ma-filtered-list-button entity-name="user_logins" filter="{ user_id: entry.values.id }" size="sm"></ma-filtered-list-button></span>'),
        ]);
    users.editionView().title('Edit User')
        .fields([
            nga.field('role_id', 'choice').label('User Type')
                .choices([{
                    label: 'Admin',
                    value: 1
                }, {
                    label: 'User',
                    value: 2
                }]).validation({
                required: true
            }),
            nga.field('username').label('Name').map(truncate)
                .validation({
                    required: true
                }),
            nga.field('email', 'email').label('Email')
                .validation({
                    required: true
                }),
            nga.field('is_active', 'choice').label('Active?')
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: 1
                }, {
                    label: 'No',
                    value: 0
                },]),
            nga.field('is_email_confirmed', 'choice').label('Email Confirmed?')
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: 1
                }, {
                    label: 'No',
                    value: 0
                }])
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);
    var setting_category_list_tpl = '<ma-edit-button entry="entry" entity="entity" size="sm" label="Configure"></ma-edit-button>';
    var setting_category_action_tpl = '<ma-filter-button filters="filters()" enabled-filters="enabledFilters" enable-filter="enableFilter()"></ma-filter-button>'+'<refresh-page></refresh-page>';
    setting_categories.listView().title('Site Settings')
        .fields([
            nga.field('name').label('Name'),
            nga.field('description').label('Description')
        ])
        .sortField('display_order')
        .sortDir('ASC')
        .batchActions([])
        .perPage(limit_per_page)
        .actions(setting_category_action_tpl)
        .listActions(setting_category_list_tpl)

    settings_category_edit_template = '<ma-list-button entry="entry" entity="entity" size="sm"></ma-list-button>';
    setting_categories.editionView().title('Edit Settings')
        .fields([
            nga.field('name').editable(false).label('Name'),
            nga.field('description').editable(false).label('Description'),
            nga.field('Related Settings', 'referenced_list') // display list of related settings
                .targetEntity(nga.entity('settings'))
                .targetReferenceField('setting_category_id')
                .targetFields([
                    nga.field('label').label('Name'),
                    nga.field('value').label('Value')
                ])
                .listActions(['edit']),
            nga.field('', 'template').label('').template('<add-sync entry="entry" entity="entity" size="sm" label="Synchronize with ZazPay" ></add-sync>'),
        ])
        .actions(settings_category_edit_template)

    var setting_edit_template = '<ma-back-button></ma-back-button>';
    settings.editionView().title('Edit - {{entry.values.label}}')
        .fields([
            nga.field('label').editable(false).label('Name'),
            nga.field('description', 'wysiwyg').editable(false).label('Description'),
            nga.field('value', 'text').label('Value')
                .validation({
                    validator: function (value, entry) {
                        if (entry.name === "payment.is_live_mode" || entry.name === "paypal.is_live_mode" || entry.name === "facebook.is_enabled_facebook_comment" || entry.name === "analytics.is_enabled_facebook_pixel" || entry.name === "analytics.is_enabled_google_analytics" || entry.name === "paypal.is_paypal_enabled_for_payments" || entry.name === "payment.is_sudopay_enabled_for_payments") {
                            if (value !== "0" && value !== "1") {
                                throw new Error('Value must be either 0 or 1');
                            }
                        }
                    }
                })
        ])
        .actions(setting_edit_template);
    var ips_custom_tmp = '<ma-filter-button filters="filters()" enabled-filters="enabledFilters" enable-filter="enableFilter()"></ma-filter-button>';
    ips.listView().title('IPs')
        .fields([
            nga.field('ip').label('IP'),
            nga.field('City.name')
                .label('City')
                .map(truncate),
            nga.field('State.name')
                .label('State')
                .map(truncate),
            nga.field('Country.name')
                .label('Country')
                .map(truncate),
            nga.field('latitude').label('Latitude'),
            nga.field('longitude').label('Longitude')
        ]).listActions(['delete'])
        .perPage(limit_per_page)
        .filters([
            nga.field('q').label('Search', 'template')
                .pinned(true)
                .template('<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"/><span class="input-group-addon"><i class="glyphicon glyphicon-search text-primary"></i></span></div>')
        ])
        .actions(['batch', ips_custom_tmp]);
    var pages_custom_tmp = '<ma-filter-button filters="filters()" enabled-filters="enabledFilters" enable-filter="enableFilter()"></ma-filter-button>' +
        '<create-page entry="entry" entity="entity" size="sm" lable=""></create-page>';
    pages.listView().title('Pages')
        .fields([
            nga.field('id').label('ID'),
            nga.field('language_id', 'reference')
                .editable(false)
                .label('Language')
                .targetEntity(nga.entity('languages'))
                .perPage('all') // For getting all list
                .targetField(nga.field('name'))
                .validation({
                    required: true
                }),
            nga.field('title').label('Title').map(truncate),
            nga.field('page_content').label('Content').map(truncate),
            nga.field('slug').label('Page Slug'),
        ])
        .perPage(limit_per_page)
        .listActions(['show', 'edit', 'delete'])
        .filters([
            nga.field('q').label('Search', 'template')
                .pinned(true)
                .template('<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"/><span class="input-group-addon"><i class="fa fa-search text-primary"></i></span></div>')
        ])
        .actions(['batch', pages_custom_tmp]);
    pages.showView().title('Pages - {{ entry.values.id }}')
        .fields([
            nga.field('language_id', 'reference')
                .label('Language')
                .targetEntity(nga.entity('languages'))
                .perPage('all') // For getting all list
                .targetField(nga.field('name')),
            nga.field('title').label('Title'),
            nga.field('page_content', 'wysiwyg').label('Content'),
            nga.field('slug').label('Page Slug')
        ]);
    pages.editionView().title('Pages - {{ entry.values.title }}')
        .fields([
            nga.field('title')
                .validation({
                    required: true
                })
                .label('Title'),
            nga.field('page_content', 'wysiwyg')
                .stripTags(true)
                .validation({
                    required: true
                }),
            nga.field('slug')
                .label('Page Slug')
                .validation({
                    required: true
                })
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);
    var contacts_custom_tmp = '<ma-filter-button filters="filters()" enabled-filters="enabledFilters" enable-filter="enableFilter()"></ma-filter-button>';
    contacts.listView().title('Contacts')
        .fields([
            nga.field('user_type').label('Submitter'),
            nga.field('first_name').label('First Name'),
            nga.field('last_name').label('Last Name'),
            nga.field('email').label('Email'),
            nga.field('subject').label('Subject').map(truncate),
            nga.field('message').label('Message').map(truncate),
            nga.field('ip.ip').label('IP')
        ]).listActions(['show', 'delete'])
        .filters([
            nga.field('q').label('Search', 'template')
                .pinned(true)
                .template('<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"/><span class="input-group-addon"><i class="fa fa-search text-primary"></i></span></div>')
        ])
        .actions(['batch', contacts_custom_tmp]);
    contacts.showView().title('Contact - {{ entry.values.id }}')
        .fields([
            nga.field('user.username').label('User'),
            nga.field('first_name').label('First Name'),
            nga.field('last_name').label('Last Name'),
            nga.field('email').label('Email'),
            nga.field('subject').label('Subject'),
            nga.field('message').label('Message'),
            nga.field('ip.ip').label('IP')
        ]);
    var user_cash_withdrawals_custom_tmp = '<ma-filter-button filters="filters()" enabled-filters="enabledFilters" enable-filter="enableFilter()"></ma-filter-button>';
    user_cash_withdrawals.listView().title('Withdraw Requests')
        .infinitePagination(false) // load pages as the user scrolls
        .perPage(limit_per_page)
        .fields([
            nga.field('created_at').label('Created'),
            nga.field('user.username').label('User'),
            nga.field('amount', 'number').format('0.00').label('Amount'),
            nga.field('withdrawal_status.name').label('Status'),
            nga.field('money_transfer_account.account', 'wysiwyg').label('Money Transfer Account').map(truncate)
        ])
        .listActions(['edit', 'delete'])
        .filters([
            nga.field('q').label('Search')
                .pinned(true)
                .template('<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"/><span class="input-group-addon"><i class="fa fa-search text-primary"></i></span></div>'),
            nga.field('filter', 'choice').label('Status').attributes({
                    placeholder: 'Status?'
                })
                .choices([{
                    label: 'Pending',
                    value: '1'
                }, {
                    label: 'Rejected',
                    value: '2'
                }, {
                    label: 'Success',
                    value: '3'
                }])
        ])
        .actions(['batch', user_cash_withdrawals_custom_tmp]);
    user_cash_withdrawals.editionView().title('Edit Withdraw Requests')
        .fields([
            nga.field('amount')
                .label('Amount')
                .validation({
                    required: true
                }),
            nga.field('withdrawal_status_id', 'choice').label('Status')
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Pending',
                    value: '1'
                }, {
                    label: 'Rejected',
                    value: '2'
                }, {
                    label: 'Success',
                    value: '3'
                }])
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);
    var user_logins_custom_tmp = '<ma-filter-button filters="filters()" enabled-filters="enabledFilters" enable-filter="enableFilter()"></ma-filter-button>';
    user_logins.listView().title('User Logins')
        .fields([
            nga.field('created_at').label('Created'),
            nga.field('user.username').label('User'),
            nga.field('user_login_ip.ip').label('IP'),
            nga.field('user_agent').label('User Agent').map(truncate)
        ])
        .perPage(limit_per_page)
        .listActions(['show', 'delete'])
        .filters([
            nga.field('q').label('Search', 'template')
                .pinned(true)
                .template('<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"/><span class="input-group-addon"><i class="fa fa-search text-primary"></i></span></div>')
        ])
        .actions(['batch', user_logins_custom_tmp]);

    user_logins.showView().title('View User Login')
        .fields([
            nga.field('created_at').label('Created'),
            nga.field('user.username').label('User'),
            nga.field('role_id', 'choice').label('User Type')
                .choices([{
                    label: 'Admin',
                    value: '1'
                }, {
                    label: 'User',
                    value: '2'
                }]),
            nga.field('user_login_ip.ip').label('IP'),
            nga.field('user_agent').label('User Agent')
        ]);

    var transaction_types_custom_tmp = '<ma-filter-button filters="filters()" enabled-filters="enabledFilters" enable-filter="enableFilter()"></ma-filter-button>';
    transaction_types.listView().title('Transaction Types')
        .fields([
            nga.field('name').label('Name'),
            nga.field('transaction_type_group_id', 'choice').label('Type')
                .choices([{
                    label: 'Wallet',
                    value: '1'
                }, {
                    label: 'Cash Withdrawal',
                    value: '2'
                }, {
                    label: 'VehicleRental',
                    value: '3'
                }]),
            nga.field('message').label('Message'),
            nga.field('is_credit', 'boolean').label('Credit'),
        ])
        .sortField('name')
        .sortDir('ASC')
        .infinitePagination(false)
        .perPage(limit_per_page)
        .listActions(['edit'])
        .filters([
            nga.field('q').label('Search')
                .pinned(true)
                .template('<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"/><span class="input-group-addon"><i class="fa fa-search text-primary"></i></span></div>'),
            nga.field('transaction_type_group_id', 'choice').label('Type').attributes({
                    placeholder: 'Select Type'
                })
                .choices([{
                    label: 'Wallet',
                    value: '1'
                }, {
                    label: 'Cash Withdrawal',
                    value: '2'
                }, {
                    label: 'VehicleRental',
                    value: '3'
                }])
        ])
        .batchActions([])
        .actions([transaction_types_custom_tmp]);

    transaction_types.editionView().title('Edit Transaction Type')
        .fields([
            nga.field('name').label('Name'),
            nga.field('is_credit', 'choice').label('Is credit?')
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: true
                }, {
                    label: 'No',
                    value: false
                }]),
            nga.field('message', 'text').label('Message')
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);
    var vehicles_custom_tmp = '<a class="btn btn-create" ng-class="size ? \'btn-\' + size : \'\'" href="#/vehicle/add" >\n<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>&nbsp;<span class="hidden-xs">Create</span>\n</a><ma-filter-button filters="filters()" enabled-filters="enabledFilters" enable-filter="enableFilter()"></ma-filter-button>';
    vehicles.listView().title('Vehicles')
        .fields([
            nga.field('created_at').label('Created'),
            nga.field('vehicle_company.name').label('Company Name'),
            nga.field('name').label('')
                .template('<a href="#/vehicle/{{entry.values.id}}/{{entry.values.slug}}">{{entry.values.name}}</a>')
                .label('Vehicle Name'),
            nga.field('vehicle_make.name').label('Make'),
            nga.field('vehicle_model.name').label('Model'),
            nga.field('vehicle_type.name').label('Type'),
            nga.field('per_hour_amount', 'number').format('0.00').label('Hour Amount(' + siteCurrency + ')'),
            nga.field('per_day_amount', 'number').format('0.00').label('Day Amount(' + siteCurrency + ')'),
            nga.field('feedback_count', 'number').label('Feedback Count').cssClasses(function () {
                if (enabledPlugins.indexOf("VehicleFeedbacks") === -1) {
                    return "ng-hide";
                }
            }),
            nga.field('is_active', 'boolean').label('Active?'),
        ])
        .filters([
            nga.field('q').label('Search')
                .pinned(true)
                .template('<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"/><span class="input-group-addon"><i class="glyphicon glyphicon-search text-primary"></i></span></div>'),
            nga.field('filter', 'choice').label('Status').attributes({
                    placeholder: 'Status?'
                })
                .choices([{
                    label: 'Active',
                    value: 'active'
                }, {
                    label: 'Inactive',
                    value: 'inactive'
                },]),
            nga.field('vehicle_company_id', 'reference')
                .attributes({
                    placeholder: 'Company'
                })
                .targetEntity(nga.entity('vehicle_companies?filter=active'))
                .targetField(nga.field('name'))
                .label('Company'),
            nga.field('vehicle_make_id', 'reference')
                .attributes({
                    placeholder: 'Make'
                })
                .targetEntity(nga.entity('vehicle_makes?filter=active'))
                .targetField(nga.field('name'))
                .label('Make'),
            nga.field('vehicle_model_id', 'reference')
                .attributes({
                    placeholder: 'Model'
                })
                .targetEntity(nga.entity('vehicle_models?filter=active'))
                .targetField(nga.field('name'))
                .label('Model'),
            nga.field('vehicle_type_id', 'reference')
                .attributes({
                    placeholder: 'Type'
                })
                .targetEntity(nga.entity('vehicle_types?filter=active'))
                .targetField(nga.field('name'))
                .label('Type')
        ])
        .infinitePagination(false)
        .perPage(limit_per_page)
        .listActions(['<show-vehicle selection="selection" entry="entry" entity="entity" size="sm" label="Show" ></show-vehicle>', '<edit-vehicle selection="selection" entry="entry" entity="entity" size="sm" label="Edit" ></edit-vehicle>', '<vehicle-calendar selection="selection" entry="entry" entity="entity" size="sm"></vehicle-calendar>', 'delete'])
        .actions(['batch', vehicles_custom_tmp])
        .batchActions([
            '<batch-vehicles-deactive type="deactive" selection="selection"></batch-vehicles-deactive>',
            '<batch-vehicles-active type="active" selection="selection"></batch-vehicles-active>',
            'delete'
        ]);
    vehicles.creationView().title('Add Item')
        .fields([
            nga.field('user_id', 'reference')
                .label('User')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'User'
                })
                .perPage('all')
                .targetEntity(nga.entity('users'))
                .targetField(nga.field('username').map(truncate)),
            nga.field('name').label('Item Name').map(truncate)
                .attributes({
                    placeholder: 'Item Name'
                })
                .validation({
                    required: true
                }),
            nga.field('description').label('Description')
                .attributes({
                    placeholder: 'Description'
                }),
            nga.field('amount').label('Amount')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Amount'
                })
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);
    vehicles.showView().title('Show Vehicle')
        .fields([
            nga.field('created_at').label('Created'),
            nga.field('vehicle_company.name').label('Company Name'),
            nga.field('name').label('Vehicle Name'),
            nga.field('vehicle_make.name').label('Make'),
            nga.field('vehicle_model.name').label('Model'),
            nga.field('vehicle_type.name').label('Type'),
            nga.field('vehicle_no').label('Vehicle No'),
            nga.field('no_of_seats').label('Seats'),
            nga.field('no_of_doors').label('Doors'),
            nga.field('no_of_gears').label('Gears'),
            nga.field('no_small_bags').label('Small bags'),
            nga.field('no_large_bags').label('Large bags'),
            nga.field('is_manual_transmission', 'boolean').label('Manual Transmission?'),
            nga.field('is_ac', 'boolean').label('AC'),
            nga.field('is_abs', 'boolean').label('Abs'),
            nga.field('is_airbag', 'boolean').label('Airbag?'),
            nga.field('no_of_airbags').label('Airbags'),
            nga.field('minimum_age_of_driver').label('Minimum age of driver'),
            nga.field('mileage').label('Mileage'),
            nga.field('per_hour_amount', 'number').format('0.00').label('Per hour amount(' + siteCurrency + ')'),
            nga.field('per_day_amount', 'number').format('0.00').label('Per day amount(' + siteCurrency + ')'),
            nga.field('fuel_type.name').label('Fuel Type'),
            nga.field('feedback_count', 'number').label('Feedback Count').cssClasses(function () {
                if (enabledPlugins.indexOf("VehicleFeedbacks") === -1) {
                    return "ng-hide";
                }
            }),
            nga.field('vehicle_rental_count').label('Vehicle Rental Count'),
            nga.field('is_active', 'boolean').label('Active?'),
            nga.field('Counter Locations', 'referenced_list')
                .targetEntity(nga.entity('counter_locations'))
                .targetReferenceField('vehicle_id')
                .targetFields([
                    nga.field('address').label('Address').map(truncate)
                ])
        ])
        .actions(['batch', '<a class="btn btn-default" ng-class="size ? \'btn-\' + size : \'\'" href="#/vehicles/list" >\n<span class="glyphicon glyphicon-list" aria-hidden="true"></span>&nbsp;<span class="hidden-xs">Create</span>\n</a>']);
    vehicles.editionView().title('Edit Item')
        .fields([
            nga.field('user_id', 'reference')
                .label('User')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'User'
                })
                .perPage('all')
                .targetEntity(nga.entity('users'))
                .targetField(nga.field('username').map(truncate)),
            nga.field('name').label('Item Name').map(truncate)
                .attributes({
                    placeholder: 'Item Name'
                })
                .validation({
                    required: true
                }),
            nga.field('description').label('Description')
                .attributes({
                    placeholder: 'Description'
                }),
            nga.field('amount').label('Amount')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Amount'
                }),
            nga.field('is_active', 'choice').label('Active?')
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: 1
                }, {
                    label: 'No',
                    value: 0
                },]),
            nga.field('is_paid', 'choice').label('Paid?')
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: 1
                }, {
                    label: 'No',
                    value: 0
                }])
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);
    var vehicle_coupons_custom_tmp = '<ma-filter-button filters="filters()" enabled-filters="enabledFilters" enable-filter="enableFilter()"></ma-filter-button>';
    vehicle_coupons.listView().title('Vehicle Coupons')
        .fields([
            nga.field('couponable.name').label('Vehicle'),
            nga.field('name').label('Coupon'),
            nga.field('discount', 'number').format('0.00').label('Discount'),
            nga.field('discount_type_id', 'choice').label('Discount Type(' + siteCurrency + '/%)')
                .choices([{
                    label: 'Percentage',
                    value: '1'
                }, {
                    label: 'Amount',
                    value: '2'
                }]),
            nga.field('validity_start_date').label('Start Date'),
            nga.field('validity_end_date').label('End Date'),
            nga.field('no_of_quantity').label('Quantity'),
            nga.field('no_of_quantity_used', 'number').label('Quantity Used'),
            nga.field('is_active', 'boolean').label('Active?'),
        ])
        .filters([
            nga.field('q').label('Search')
                .pinned(true)
                .template('<div class="input-group"><input type="text" ng-model="value" placeholder="Search Coupon" class="form-control"/><span class="input-group-addon"><i class="glyphicon glyphicon-search text-primary"></i></span></div>'),
            nga.field('filter', 'choice').label('Status').attributes({
                    placeholder: 'Status?'
                })
                .choices([{
                    label: 'Active',
                    value: 'active'
                }, {
                    label: 'Inactive',
                    value: 'inactive'
                },]),
            nga.field('start_date', 'date').format("MM/dd/yyyy").attributes({'placeholder': 'Select Start date'}).label("Start Date"),
            nga.field('end_date', 'date').format("MM/dd/yyyy").attributes({'placeholder': 'Select End date'}).label("End Date")
        ])
        .actions(['batch', 'create'])
        .listActions(['edit', 'delete'])
        .actions(['create', 'batch', vehicle_coupons_custom_tmp]);

    vehicle_coupons.creationView().title('Add Vehicle Coupon')
        .fields([
            nga.field('vehicle_id', 'reference')
                .label('Vehicle')
                .attributes({
                    placeholder: 'Vehicle'
                })
                .perPage('all')
                .targetEntity(nga.entity('vehicles'))
                .targetField(nga.field('name').map(truncate)),
            nga.field('name')
                .label('Coupon')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Coupon'
                }),
            nga.field('description')
                .label('Description')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Description'
                }),
            nga.field('discount')
                .label('Discount')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Discount'
                }),
            nga.field('discount_type_id', 'choice').label('Discount Type(' + siteCurrency + '/%)')
                .choices([{
                    label: 'Percentage',
                    value: '1'
                }, {
                    label: 'Amount',
                    value: '2'
                }])
                .attributes({
                    placeholder: 'Discount Type'
                })
                .validation({
                    required: true
                }),
            nga.field('no_of_quantity', 'number')
                .label('Quantity')
                .attributes({
                    placeholder: 'Quantity'
                })
                .validation({
                    required: true,
                    validator: function (value) {
                        if (value <= 0) throw new Error('Enter Valid Quantity');
                    }
                }),
            nga.field('maximum_discount_amount')
                .label('Maximum Discount Amount')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Maximum Discount Amount'
                }),
            nga.field('validity_start_date', 'date').label('Start Date'),
            nga.field('validity_end_date', 'date').label('End Date'),
            nga.field('is_active', 'choice').label('Active?')
                .attributes({
                    placeholder: 'Active?'
                })
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: '1'
                }, {
                    label: 'No',
                    value: '0'
                }])
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);

    vehicle_coupons.editionView().title('Edit Vehicle Coupon')
        .fields([
            nga.field('vehicle_id', 'reference')
                .label('Vehicle')
                .attributes({
                    placeholder: 'Vehicle'
                })
                .perPage('all')
                .targetEntity(nga.entity('vehicles'))
                .targetField(nga.field('name').map(truncate)),
            nga.field('name')
                .label('Coupon')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Coupon'
                }),
            nga.field('description')
                .label('Description')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Description'
                }),
            nga.field('discount')
                .label('Discount')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Discount'
                }),
            nga.field('discount_type_id', 'choice')
                .label('Discount Type(' + siteCurrency + '/%)')
                .choices([{
                    label: 'Percentage',
                    value: '1'
                }, {
                    label: 'Amount',
                    value: '2'
                }]),
            nga.field('no_of_quantity', 'number')
                .label('Quantity')
                .attributes({
                    placeholder: 'Quantity'
                })
                .validation({
                    required: true,
                    validator: function (value) {
                        if (value <= 0) throw new Error('Enter Valid Quantity');
                    }
                }),
            nga.field('maximum_discount_amount')
                .label('Maximum Discount Amount')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Maximum Discount Amount'
                }),
            nga.field('validity_start_date', 'datetime').label('Start Date'),
            nga.field('validity_end_date', 'datetime').label('End Date'),
            nga.field('is_active', 'choice').label('Active?')
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: '1'
                }, {
                    label: 'No',
                    value: '0'
                }])
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);
    sudopay_ipn_logs.listView().title('ZazPay Ipn Logs')
        .fields([
            nga.field('id').label('ID'),
            nga.field('created_at').label('Created'),
            nga.field('ip').label('IP'),
            nga.field('post_variable').label('Post Variable')
        ])
        .infinitePagination(false)
        .perPage(limit_per_page)
        .batchActions([])
        .actions([]);

    var transactions_custom_tmp = '<ma-filter-button filters="filters()" enabled-filters="enabledFilters" enable-filter="enableFilter()"></ma-filter-button>';
    transactions.listView().title('Transactions')
        .fields([
            nga.field('id').label('ID'),
            nga.field('created_at').label('Date'),
            nga.field('from_user.username').label('From'),
            nga.field('to_user.username').label('To'),
            nga.field('description', 'wysiwyg').label('Description'),
            nga.field('credit_amount', 'number').format('0.00').label('Credit(' + siteCurrency + ')'),
            nga.field('debit_amount', 'number').format('0.00').label('Debit(' + siteCurrency + ')')
        ])
        .filters([
            nga.field('q').label('Search')
                .pinned(true)
                .template('<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"/><span class="input-group-addon"><i class="glyphicon glyphicon-search text-primary"></i></span></div>'),
            nga.field('start_date', 'date').format("MM/dd/yyyy").attributes({'placeholder': 'Select From Date'}).label("From Date"),
            nga.field('end_date', 'date').format("MM/dd/yyyy").attributes({'placeholder': 'Select To Date'}).label("To Date"),
            nga.field('from_user', 'reference')
                .attributes({
                    placeholder: 'From User'
                })
                .perPage('all')
                .targetEntity(nga.entity('users'))
                .targetField(nga.field('username'))
                .label('From User'),
            nga.field('to_user', 'reference')
                .attributes({
                    placeholder: 'To User'
                })
                .perPage('all')
                .targetEntity(nga.entity('users'))
                .targetField(nga.field('username'))
                .label('To User')
        ])
        .infinitePagination(false)
        .perPage(limit_per_page)
        .batchActions([])
        .actions(['batch', transactions_custom_tmp]);

    sudopay_transaction_logs.listView().title('ZazPay Transaction Logs')
        .fields([
            nga.field('created_at').label('Created'),
            nga.field('sudopay_transaction_logable_type').label('Class'),
            nga.field('sudopay_pay_key').label('Pay Key'),
            nga.field('gateway_name').label('Gateway'),
            nga.field('status').label('Status'),
            nga.field('payment_type').label('Payment Type'),
            nga.field('sudopay_transaction_fee').label('Transaction Fee(' + siteCurrency + ')'),
            nga.field('amount', 'number').format('0.00').label('Amount(' + siteCurrency + ')'),
        ])
        .filters([
            nga.field('q').label('Search')
                .pinned(true)
                .template('<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"/><span class="input-group-addon"><i class="glyphicon glyphicon-search text-primary"></i></span></div>')
        ])
        .infinitePagination(false)
        .perPage(limit_per_page)
        .batchActions([])
        .actions([])
        .listActions(['show']);

    sudopay_transaction_logs.showView().title('View ZazPay Transaction Log')
        .fields([
            nga.field('created_at').label('Created'),
            nga.field('sudopay_transaction_logable_type').label('Class'),
            nga.field('sudopay_pay_key').label('Pay Key'),
            nga.field('gateway_name').label('Gateway'),
            nga.field('status').label('Status'),
            nga.field('payment_type').label('Payment Type'),
            nga.field('sudopay_transaction_fee').label('Transaction Fee(' + siteCurrency + ')'),
            nga.field('amount', 'number').format('0.00').label('Amount(' + siteCurrency + ')'),
            nga.field('buyer_email').label('Buyer email'),
            nga.field('buyer_address').label('Buyer Address')
        ]);

    paypal_transaction_logs.listView().title('Paypal Transaction Logs')
        .fields([
            nga.field('created_at').label('Created'),
            nga.field('paypal_transaction_logable_type').label('Class'),
            nga.field('paypal_pay_key').label('Pay Key'),
            nga.field('payer_id').label('Payer Id'),
            nga.field('authorization_id').label('Authorization Id'),
            nga.field('capture_id').label('Capture Id'),
            nga.field('void_id').label('Void Id'),
            nga.field('status').label('Status'),
            nga.field('payment_type').label('Payment Type'),
            nga.field('paypal_transaction_fee', 'number').format('0.00').label('Transaction Fee(' + siteCurrency + ')'),
            nga.field('amount', 'number').format('0.00').label('Amount(' + siteCurrency + ')')
        ])
        .filters([
            nga.field('q').label('Search')
                .pinned(true)
                .template('<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"/><span class="input-group-addon"><i class="glyphicon glyphicon-search text-primary"></i></span></div>')
        ])
        .infinitePagination(false)
        .perPage(limit_per_page)
        .batchActions([])
        .actions([])
        .listActions(['show']);

    paypal_transaction_logs.showView().title('view Paypal Transaction Log')
        .fields([
            nga.field('created_at').label('Created'),
            nga.field('paypal_transaction_logable_type').label('Class'),
            nga.field('paypal_pay_key').label('Pay Key'),
            nga.field('payer_id').label('Payer Id'),
            nga.field('authorization_id').label('Authorization Id'),
            nga.field('capture_id').label('Capture Id'),
            nga.field('void_id').label('Void Id'),
            nga.field('refund_id').label('Refund Id'),
            nga.field('status').label('Status'),
            nga.field('payment_type').label('Payment Type'),
            nga.field('paypal_transaction_fee', 'number').format('0.00').label('Transaction Fee(' + siteCurrency + ')'),
            nga.field('amount', 'number').format('0.00').label('Amount(' + siteCurrency + ')'),
            nga.field('buyer_email').label('Buyer email'),
            nga.field('buyer_address').label('Buyer Address')
        ]);

    wallet_transaction_logs.listView().title('Wallet Transaction Logs')
        .fields([
            nga.field('created_at').label('Created'),
            nga.field('wallet_transaction_logable_type').label('Class'),
            nga.field('status').label('Status'),
            nga.field('payment_type').label('Payment Type'),
            nga.field('amount', 'number').format('0.00').label('Amount(' + siteCurrency + ')')
        ])
        .filters([
            nga.field('q').label('Search')
                .pinned(true)
                .template('<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"/><span class="input-group-addon"><i class="glyphicon glyphicon-search text-primary"></i></span></div>')
        ])
        .infinitePagination(false)
        .perPage(limit_per_page)
        .batchActions([])
        .actions([])
        .listActions(['show']);

    wallet_transaction_logs.showView().title('View Wallet Transaction Log')
        .fields([
            nga.field('created_at').label('Created'),
            nga.field('wallet_transaction_logable_type').label('Class'),
            nga.field('status').label('Status'),
            nga.field('payment_type').label('Payment Type'),
            nga.field('amount', 'number').format('0.00').label('Amount(' + siteCurrency + ')')
        ]);

    cancellation_types.listView().title('Cancellation Types')
        .fields([
            nga.field('name').label('Name'),
            nga.field('description').label('Description').map(truncate),
            nga.field('minimum_duration').label('Minimum Duration'),
            nga.field('maximum_duration').label('Maximum Duration'),
            nga.field('deduct_rate', 'number').format('0.00').label('Deduct Rate(' + siteCurrency + ')')
        ])
        .filters([
            nga.field('q').label('Search')
                .pinned(true)
                .template('<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"/><span class="input-group-addon"><i class="glyphicon glyphicon-search text-primary"></i></span></div>')
        ])
        .infinitePagination(false)
        .perPage(limit_per_page)
        .listActions(['edit'])
        .actions(['create']);

    cancellation_types.creationView().title('Add Cancellation Type')
        .fields([
            nga.field('name').label('Name')
                .attributes({
                    placeholder: 'Name'
                })
                .validation({
                    required: true
                }),
            nga.field('description', 'text').label('Description')
                .attributes({
                    placeholder: 'description'
                })
                .validation({
                    required: true
                }),
            nga.field('minimum_duration').label('Minimum Duration')
                .attributes({
                    placeholder: 'Minimum Duration'
                })
                .validation({
                    required: true
                }),
            nga.field('maximum_duration').label('Maximum Duration')
                .attributes({
                    placeholder: 'Maximum Duration'
                })
                .validation({
                    required: true
                }),
            nga.field('deduct_rate').label('Deduct Rate')
                .attributes({
                    placeholder: 'Deduct Rate'
                })
                .validation({
                    required: true
                })
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);
    cancellation_types.editionView().title('Edit Cancellation Type')
        .fields([
            nga.field('name').label('Name')
                .attributes({
                    placeholder: 'Name'
                })
                .validation({
                    required: true
                }),
            nga.field('description', 'text').label('Description')
                .attributes({
                    placeholder: 'description'
                })
                .validation({
                    required: true
                }),
            nga.field('minimum_duration').label('Minimum Duration')
                .attributes({
                    placeholder: 'Minimum Duration'
                })
                .validation({
                    required: true
                }),
            nga.field('maximum_duration').label('Maximum Duration')
                .attributes({
                    placeholder: 'Maximum Duration'
                })
                .validation({
                    required: true
                }),
            nga.field('deduct_rate').label('Deduct Rate')
                .attributes({
                    placeholder: 'Deduct Rate'
                })
                .validation({
                    required: true
                })
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);
    var vehicle_rentals_custom_tmp = '<ma-filter-button filters="filters()" enabled-filters="enabledFilters" enable-filter="enableFilter()"></ma-filter-button>';
    vehicle_rentals.listView().title('Vehicle Rentals')
        .fields([
            nga.field('created_at').label('Created'),
            nga.field('user.username').label('User'),
            nga.field('item_userable.name').label('Vehicle Name'),
            nga.field('item_user_status.name').label('Status'),
            nga.field('item_booking_start_date').label('Start Date'),
            nga.field('item_booking_end_date').label('End Date'),
            nga.field('booking_amount', 'number').format('0.00').label('Rental Amount(' + siteCurrency + ')'),
            nga.field('deposit_amount', 'number').format('0.00').label('Deposit Amount(' + siteCurrency + ')'),
            nga.field('coupon_discount_amount', 'number').format('0.00').label('Coupon Discount(' + siteCurrency + ')').cssClasses(function () {
                if (enabledPlugins.indexOf("VehicleCoupons") === -1) {
                    return "ng-hide";
                }
            }),
            nga.field('total_amount', 'number').format('0.00').label('Total(' + siteCurrency + ')')
        ])
        .filters([
            nga.field('q').label('Search', 'template')
                .pinned(true)
                .template('<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"/><span class="input-group-addon"><i class="glyphicon glyphicon-search text-primary"></i></span></div>'),
            nga.field('filter', 'choice').label('Status').attributes({
                    placeholder: 'Status?'
                })
                .choices([{
                    label: 'Payment Pending',
                    value: 'payment_pending'
                }, {
                    label: 'Waiting For Acceptance',
                    value: 'waiting_for_acceptance'
                }, {
                    label: 'Rejected',
                    value: 'rejected'
                }, {
                    label: 'Cancelled',
                    value: 'cancelled'
                }, {
                    label: 'Expired',
                    value: 'expired'
                }, {
                    label: 'Confirmed',
                    value: 'confirmed'
                }, {
                    label: 'Waiting For Review',
                    value: 'waiting_for_review'
                }, {
                    label: 'Completed',
                    value: 'completed'
                }]),
            nga.field('start_date', 'date').format("MM/dd/yyyy").attributes({'placeholder': 'Select Start date'}).label("Start Date"),
            nga.field('end_date', 'date').format("MM/dd/yyyy").attributes({'placeholder': 'Select End date'}).label("End Date")
        ])
        .infinitePagination(false)
        .perPage(limit_per_page)
        .listActions(['<view-activity selection="selection" entry="entry" entity="entity" size="sm" label="View Activities" ></view-activity>', 'show', '<add-cancel entry="entry" entity="entity" size="sm" label="Cancel" ></add-cancel>', '<check-in entry="entry" entity="entity" size="sm" label="Check In" ></check-in>', '<check-out selection="selection" entry="entry" entity="entity" size="sm" label="Check Out" ></check-out>'])
        .batchActions([])
        .actions(['batch', vehicle_rentals_custom_tmp]);
    vehicle_rentals.showView().title('View Vehicle Rental')
        .fields([
            nga.field('created_at').label('Created'),
            nga.field('user.username').label('User'),
            nga.field('item_userable.name').label('Vehicle Name'),
            nga.field('item_user_status.name').label('Status'),
            nga.field('item_booking_start_date').label('Start Date'),
            nga.field('item_booking_end_date').label('End Date'),
            nga.field('is_dispute', 'boolean').label('Is Dispute?'),
            nga.field('is_payment_cleared', 'boolean').label('Is Payment Cleared'),
            nga.field('additional_fee', 'number').format('0.00').label('Additional Fee(' + siteCurrency + ')'),
            nga.field('admin_commission_amount', 'number').format('0.00').label('Admin Commission Amount(' + siteCurrency + ')'),
            nga.field('claim_request_amount', 'number').format('0.00').label('Claim Request Amount(' + siteCurrency + ')'),
            nga.field('coupon_discount_amount', 'number').format('0.00').label('Coupon Discount Amount(' + siteCurrency + ')'),
            nga.field('drop_location_differ_charges', 'number').format('0.00').label('Drop Location Differ Charges(' + siteCurrency + ')'),
            nga.field('extra_accessory_amount', 'number').format('0.00').label('Extra Accessory Amount(' + siteCurrency + ')'),
            nga.field('fuel_option_amount', 'number').format('0.00').label('Fuel Option Amount(' + siteCurrency + ')'),
            nga.field('special_discount_amount', 'number').format('0.00').label('Special Discount Amount(' + siteCurrency + ')'),
            nga.field('surcharge_amount', 'number').format('0.00').label('Surcharge Amount(' + siteCurrency + ')'),
            nga.field('tax_amount', 'number').format('0.00').label('Tax Amount(' + siteCurrency + ')'),
            nga.field('type_discount_amount', 'number').format('0.00').label('Type Discount Amount(' + siteCurrency + ')'),
            nga.field('insurance_amount', 'number').format('0.00').label('Insurance Amount(' + siteCurrency + ')'),
            nga.field('booking_amount', 'number').format('0.00').label('Rental Amount(' + siteCurrency + ')'),
            nga.field('deposit_amount', 'number').format('0.00').label('Deposit Amount(' + siteCurrency + ')'),
            nga.field('coupon_discount_amount', 'number').format('0.00').label('Coupon Discount(' + siteCurrency + ')').cssClasses(function () {
                if (enabledPlugins.indexOf("VehicleCoupons") === -1) {
                    return "ng-hide";
                }
            }),
            nga.field('total_amount', 'number').format('0.00').label('Total(' + siteCurrency + ')')
        ])
        .actions(['batch', 'list']);
    var messages_custom_tmp = '<ma-filter-button filters="filters()" enabled-filters="enabledFilters" enable-filter="enableFilter()"></ma-filter-button>';
    messages.listView().title('Messages')
        .fields([
            nga.field('created_at').label('Created'),
            nga.field('from_user.username').label('From'),
            nga.field('to_user.username').label('To'),
            nga.field('message_content.subject', 'wysiwyg').label('Subject').map(truncate)
        ])
        .filters([
            nga.field('q').label('Search')
                .pinned(true)
                .template('<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"/><span class="input-group-addon"><i class="glyphicon glyphicon-search text-primary"></i></span></div>'),
            nga.field('filter', 'choice').label('Filter').attributes({
                    placeholder: 'Select Filter'
                })
                .choices([{
                    label: 'VehicleRental messages',
                    value: 'vehicle_rental_messages'
                }, {
                    label: 'User messages',
                    value: 'user_messages'
                },])
        ])
        .infinitePagination(false)
        .perPage(limit_per_page)
        .listActions(['show', 'delete'])
        .actions(['batch', messages_custom_tmp]);
    messages.showView().title('Messages')
        .fields([
            nga.field('created_at').label('Created'),
            nga.field('from_user.username').label('From'),
            nga.field('to_user.username').label('To'),
            nga.field('message_content.subject', 'wysiwyg').label('Subject'),
            nga.field('message_content.message', 'wysiwyg').label('Message')
        ]);
    var vehicle_feedbacks_custom_tmp = '<ma-filter-button filters="filters()" enabled-filters="enabledFilters" enable-filter="enableFilter()"></ma-filter-button>';
    vehicle_feedbacks.listView().title('Feedbacks')
        .fields([
            nga.field('created_at').label('Created'),
            nga.field('user.username').label('From'),
            nga.field('to_user.username').label('To'),
            nga.field('feedbackable.name').label('Item'),
            nga.field('feedback','wysiwyg').label('Feedback').map(truncate),
            nga.field('rating').label('Rating'),
            nga.field('ip.ip').label('IP')
        ])
        .filters([
            nga.field('q').label('Search')
                .pinned(true)
                .template('<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"/><span class="input-group-addon"><i class="glyphicon glyphicon-search text-primary"></i></span></div>'),
            nga.field('filter', 'choice').label('Filter').attributes({
                    placeholder: 'Filter'
                })
                .choices([{
                    label: 'Feedback To Host',
                    value: 'feedback_to_host'
                }, {
                    label: 'Feedback To Booker',
                    value: 'feedback_to_booker'
                },])
        ])
        .infinitePagination(false)
        .perPage(limit_per_page)
        .listActions(['edit', 'delete'])
        .actions(['batch', vehicle_feedbacks_custom_tmp]);
    vehicle_feedbacks.editionView().title('Edit Feedback')
        .fields([
            nga.field('feedback', 'text').label('Message'),
            nga.field('rating', 'choice').label('Rating')
                .validation({
                    required: true
                })
                .choices([{
                    label: '0',
                    value: 0
                }, {
                    label: '1',
                    value: 1
                }, {
                    label: '2',
                    value: 2
                }, {
                    label: '3',
                    value: 3
                }, {
                    label: '4',
                    value: 4
                }, {
                    label: '5',
                    value: 5
                },]),
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);
    var currencies_custom_tmp = '<ma-filter-button filters="filters()" enabled-filters="enabledFilters" enable-filter="enableFilter()"></ma-filter-button>';
    currencies.listView().title('Currencies')
        .fields([
            nga.field('created_at').label('Created'),
            nga.field('name').label('Name'),
            nga.field('code').label('Code'),
            nga.field('symbol').label('Symbol'),
            nga.field('decimals').label('Decimals'),
            nga.field('dec_point').label('Dec Point'),
            nga.field('thousands_sep').label('Thousand Sep'),
            nga.field('is_active', 'boolean').label('Active?')
        ])
        .filters([
            nga.field('q').label('Search')
                .pinned(true)
                .template('<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"/><span class="input-group-addon"><i class="glyphicon glyphicon-search text-primary"></i></span></div>'),
            nga.field('filter', 'choice').label('Status').attributes({
                    placeholder: 'Status?'
                })
                .choices([{
                    label: 'Active',
                    value: 'active'
                }, {
                    label: 'Inactive',
                    value: 'inactive'
                },])
        ])
        .perPage(limit_per_page)
        .actions(['batch', 'create'])
        .listActions(['show', 'edit', 'delete'])
        .actions(['create', 'batch', currencies_custom_tmp]);

    currencies.creationView().title('Add Currency')
        .fields([
            nga.field('name')
                .label('Name')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Name'
                }),
            nga.field('code')
                .label('Code')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Code'
                }),
            nga.field('symbol')
                .label('Symbol')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Symbol'
                }),
            nga.field('decimals')
                .label('Decimals')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Decimals'
                }),
            nga.field('dec_point')
                .label('Decimal Point')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Decimal Point'
                }),
            nga.field('thousands_sep')
                .label('Thousand Separate')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Thousand Separate'
                }),
            nga.field('prefix')
                .label('Prefix')
                .attributes({
                    placeholder: 'Prefix'
                }),
            nga.field('suffix')
                .label('Suffix')
                .attributes({
                    placeholder: 'Suffix'
                }),
            nga.field('is_prefix_display_on_left', 'choice').label('Is prefix display on left?')
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: 1
                }, {
                    label: 'No',
                    value: 0
                }]),
            nga.field('is_use_graphic_symbol', 'choice').label('Is use graphic symbol?')
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: 1
                }, {
                    label: 'No',
                    value: 0
                }])
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);

    currencies.editionView().title('Edit Currency')
        .fields([
            nga.field('name')
                .label('Name')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Name'
                }),
            nga.field('code')
                .label('Code')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Code'
                }),
            nga.field('symbol')
                .label('Symbol')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Symbol'
                }),
            nga.field('decimals')
                .label('Decimals')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Decimals'
                }),
            nga.field('dec_point')
                .label('Decimal Point')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Decimal Point'
                }),
            nga.field('thousands_sep')
                .label('Thousand Separate')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Thousand Separate'
                }),
            nga.field('prefix')
                .label('Prefix')
                .attributes({
                    placeholder: 'Prefix'
                }),
            nga.field('suffix')
                .label('Suffix')
                .attributes({
                    placeholder: 'Suffix'
                }),
            nga.field('is_prefix_display_on_left', 'choice').label('Is prefix display on left?')
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: 1
                }, {
                    label: 'No',
                    value: 0
                }]),
            nga.field('is_use_graphic_symbol', 'choice').label('Is use graphic symbol?')
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: 1
                }, {
                    label: 'No',
                    value: 0
                }]),
            nga.field('is_active', 'choice').label('Active?')
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: 1
                }, {
                    label: 'No',
                    value: 0
                }])
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);

    currencies.showView().title('View Currency')
        .fields([
            nga.field('created_at').label('Created'),
            nga.field('name').label('Name'),
            nga.field('code').label('Code'),
            nga.field('symbol').label('Symbol'),
            nga.field('decimals').label('Decimals'),
            nga.field('dec_point').label('Dec Point'),
            nga.field('thousands_sep').label('Thousand Sep'),
            nga.field('prefix').label('Prefix'),
            nga.field('suffix').label('Suffix'),
            nga.field('is_prefix_display_on_left', 'choice').label('Is prefix display on left?')
                .choices([{
                    label: 'Yes',
                    value: '1'
                }, {
                    label: 'no',
                    value: '0'
                }]),
            nga.field('is_use_graphic_symbol', 'choice').label('Is use graphic symbol?')
                .choices([{
                    label: 'Yes',
                    value: '1'
                }, {
                    label: 'No',
                    value: '0'
                }]),
            nga.field('is_active', 'boolean').label('Active?')
        ]);

    var currency_conversions_custom_tmp = '<ma-filter-button filters="filters()" enabled-filters="enabledFilters" enable-filter="enableFilter()"></ma-filter-button>';
    currency_conversions.listView().title('Currency Conversions')
        .fields([
            nga.field('updated_at').label('Created'),
            nga.field('currency.name').label('Currency'),
            nga.field('converted_currency.name').label('Converted Currency'),
            nga.field('rate', 'number').format('0.000000').label('Rate')
        ])
        .perPage(limit_per_page)
        .filters([
            nga.field('from_currency', 'reference')
                .attributes({
                    placeholder: 'From Currency'
                })
                .targetEntity(nga.entity('currencies?type=list'))
                .targetField(nga.field('name'))
                .label('From Currency'),
            nga.field('to_currency', 'reference')
                .attributes({
                    placeholder: 'Converted Currency'
                })
                .targetEntity(nga.entity('currencies?type=list'))
                .targetField(nga.field('name'))
                .label('Converted Currency')
        ])
        .batchActions([])
        .actions(['batch', currency_conversions_custom_tmp]);

    var currency_conversion_histories_custom_tmp = '<ma-filter-button filters="filters()" enabled-filters="enabledFilters" enable-filter="enableFilter()"></ma-filter-button>';
    currency_conversion_histories.listView().title('Currency Conversion History')
        .fields([
            nga.field('created_at').label('Created'),
            nga.field('currency_conversion.currency.name').label('Currency'),
            nga.field('currency_conversion.converted_currency.name').label('Converted Currency'),
            nga.field('rate_before_change', 'number').format('0.00').label('Rate before change'),
            nga.field('rate', 'number').format('0.00').label('Rate')
        ])
        .perPage(limit_per_page)
        .filters([
            nga.field('from_currency', 'reference')
                .attributes({
                    placeholder: 'From Currency'
                })
                .targetEntity(nga.entity('currencies?type=list'))
                .targetField(nga.field('name'))
                .label('From Currency'),
            nga.field('to_currency', 'reference')
                .attributes({
                    placeholder: 'Converted Currency'
                })
                .targetEntity(nga.entity('currencies?type=list'))
                .targetField(nga.field('name'))
                .label('Converted Currency'),
            nga.field('start_date', 'date').format("MM/dd/yyyy").attributes({'placeholder': 'Select From Date'}).label("From Date"),
            nga.field('end_date', 'date').format("MM/dd/yyyy").attributes({'placeholder': 'Select To Date'}).label("To Date")
        ])
        .batchActions([])
        .actions(['batch', currency_conversion_histories_custom_tmp]);

    var api_requests_custom_tmp = '<ma-filter-button filters="filters()" enabled-filters="enabledFilters" enable-filter="enableFilter()"></ma-filter-button>';
    api_requests.listView().title('Api Requests')
        .fields([
            nga.field('created_at').label('Created'),
            nga.field('User.username').label('User'),
            nga.field('path').label('Path').map(truncate),
            nga.field('method').label('Request'),
            nga.field('http_response_code').label('Response Code'),
            nga.field('Ip.ip').label('IP')
        ])
        .perPage(limit_per_page)
        .listActions(['show', 'delete'])
        .filters([
            nga.field('q').label('Search', 'template')
                .pinned(true)
                .template('<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"/><span class="input-group-addon"><i class="fa fa-search text-primary"></i></span></div>'),
            nga.field('filter', 'choice').label('Request').attributes({
                    placeholder: 'Select Request'
                })
                .choices([{
                    label: 'GET',
                    value: 'GET'
                }, {
                    label: 'POST',
                    value: 'POST'
                }, {
                    label: 'PUT',
                    value: 'PUT'
                }, {
                    label: 'DELETE',
                    value: 'DELETE'
                },])
        ])
        .actions(['batch', api_requests_custom_tmp]);

    // booking dispute related pages
    var vehicle_dispute_types_custom_tmp = '<ma-filter-button filters="filters()" enabled-filters="enabledFilters" enable-filter="enableFilter()"></ma-filter-button>';
    vehicle_dispute_types.listView().title('Rental Dispute Types')
        .fields([
            nga.field('id').label('ID'),
            nga.field('name').label('Name'),
            nga.field('is_booker', 'boolean').label('Booker?'),
            nga.field('is_active', 'boolean').label('Active?')
        ])
        .perPage(limit_per_page)
        .filters([
            nga.field('q').label('Search', 'template')
                .pinned(true)
                .template('<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"/><span class="input-group-addon"><i class="glyphicon glyphicon-search text-primary"></i></span></div>'),
            nga.field('filter', 'choice').label('Status').attributes({
                    placeholder: 'Status?'
                })
                .choices([{
                    label: 'Active',
                    value: 'active'
                }, {
                    label: 'Inactive',
                    value: 'inactive'
                }])
        ])
        .actions(['batch', vehicle_dispute_types_custom_tmp]);
    vehicle_dispute_types.creationView()
        .fields([
            nga.field('name').label('Name')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Name'
                }),
            nga.field('is_booker', 'choice').label('Booker?')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Booker?'
                })
                .choices([{
                    label: 'Yes',
                    value: true
                }, {
                    label: 'No',
                    value: false
                }]),
            nga.field('is_active', 'choice').label('Active?')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Active?'
                })
                .choices([{
                    label: 'Yes',
                    value: true
                }, {
                    label: 'No',
                    value: false
                }])
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);
    // TODO: is_booker should not be edited. so edit function commented
    /*vehicle_dispute_types.editionView()
        .fields([
            nga.field('name')
                .label('Name')
                .validation({
                    required: true
                }),
            nga.field('is_booker', 'choice').label('Booker?')
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: true
                }, {
                    label: 'No',
                    value: false
                }]),
            nga.field('is_active', 'choice').label('Active?')
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: true
                }, {
                    label: 'No',
                    value: false
                }])
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);*/

    var vehicle_dispute_closed_types_custom_tmp = '<ma-filter-button filters="filters()" enabled-filters="enabledFilters" enable-filter="enableFilter()"></ma-filter-button>';
    vehicle_dispute_closed_types.listView().title('Rental Dispute Closed Types')
        .fields([
            nga.field('id').label('ID'),
            nga.field('name').label('Name'),
            nga.field('dispute_type.name').label('Dispute Type'),
            nga.field('resolved_type').label('Resolve Type'),
            nga.field('reason').label('Reason'),
            nga.field('is_booker', 'boolean').label('Booker?')
        ])
        .perPage(limit_per_page).filters([
            nga.field('q').label('Search', 'template')
                .pinned(true)
                .template('<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"/><span class="input-group-addon"><i class="glyphicon glyphicon-search text-primary"></i></span></div>'),
            nga.field('filter', 'choice').label('User Type').attributes({
                    placeholder: 'User Type?'
                })
                .choices([{
                    label: 'Is Booker',
                    value: 'booker'
                }, {
                    label: 'Is Host',
                    value: 'host'
                }])
        ])
        .batchActions([])
        .actions(['batch', vehicle_dispute_closed_types_custom_tmp]);
    vehicle_dispute_closed_types.creationView()
        .fields([
            nga.field('name').label('Name')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Name'
                }),
            nga.field('dispute_type_id', 'reference')
                .label('Dispute Type')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Dispute Type'
                })
                .targetEntity(nga.entity('vehicle_dispute_types'))
                .perPage('all') // For getting all list
                .targetField(nga.field('name').map(truncate)),
            nga.field('resolved_type')
                .label('Resolve Type')
                .validation({
                    required: true
                }),
            nga.field('reason')
                .label('Reason')
                .validation({
                    required: true
                }),
            nga.field('is_booker', 'choice').label('Booker?')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Booker?'
                })
                .choices([{
                    label: 'Yes',
                    value: 1
                }, {
                    label: 'No',
                    value: 0
                }])
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);
    // TODO: is_booker should not be edited. so edit function commented
    /*vehicle_dispute_closed_types.editionView()
        .fields([
            nga.field('name')
                .label('Name')
                .validation({
                    required: true
                }),
            nga.field('dispute_type_id', 'reference')
                .label('Dispute Type')
                .validation({
                    required: true
                })
                .targetEntity(nga.entity('vehicle_dispute_types'))
                .perPage('all') // For getting all list
                .targetField(nga.field('name').map(truncate)),
            nga.field('resolved_type')
                .label('Resolve Type')
                .validation({
                    required: true
                }),
            nga.field('reason')
                .label('Reason')
                .validation({
                    required: true
                }),
            nga.field('is_booker', 'choice').label('Booker?')
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: 1
                }, {
                    label: 'No',
                    value: 0
                }])
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);*/
    // dispute listings
    var vehicle_disputes_custom_tmp = '<ma-filter-button filters="filters()" enabled-filters="enabledFilters" enable-filter="enableFilter()"></ma-filter-button>';
    var activity_custom_tmp = ''
    vehicle_disputes.listView().title('Vehicle Disputes')
        .fields([
            nga.field('created_at').label('Created'),
            nga.field('user.username').label('User'),
            nga.field('item_user_disputable.item_userable.name').label('Vehicle'),
            nga.field('item_user_disputable.item_user_status.name').label('Status'),
            nga.field('dispute_type.name').label('Dispute Type'),
            nga.field('dispute_status.name').label('Dispute Status'),
            nga.field('last_replied_date').label('Last Replied'),
            nga.field('dispute_conversation_count').label('Conversation Count'),
            nga.field('is_favor_booker').label('Favour to'),
            nga.field('resolved_date').label('Resolved'),
        ])
        .filters([
            nga.field('q').label('Search')
                .pinned(true)
                .template('<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"/><span class="input-group-addon"><i class="glyphicon glyphicon-search text-primary"></i></span></div>'),
            nga.field('filter', 'choice').label('Status').attributes({
                    placeholder: 'Status?'
                })
                .choices([{
                    label: 'Open',
                    value: 'Open'
                }, {
                    label: 'Under Discussion',
                    value: 'Under Discussion'
                }, {
                    label: 'Waiting Administrator Decision',
                    value: 'Waiting Administrator Decision'
                }, {
                    label: 'Closed',
                    value: 'Closed'
                }])

        ])
        .actions([])
        .listActions(['<view-activity selection="selection" entry="entry" entity="entity" size="sm" label="View Activities" ></view-activity>']);

    api_requests.showView().title('View Api Request')
        .fields([
            nga.field('created_at').label('Created'),
            nga.field('User.username').label('User'),
            nga.field('path').label('Path'),
            nga.field('method').label('Request'),
            nga.field('http_response_code').label('Response Code'),
            nga.field('Ip.ip').label('IP')
        ]);

    var vehicle_makes_custom_tmp = '<ma-filter-button filters="filters()" enabled-filters="enabledFilters" enable-filter="enableFilter()"></ma-filter-button>';
    vehicle_makes.listView().title('Vehicle Makes')
        .fields([
            nga.field('created_at').label('Created'),
            nga.field('name').label('Name'),
            nga.field('vehicle_count').label('Vehicle Count'),
            nga.field('vehicle_model_count').label('Vehicle Model Count'),
            nga.field('is_active', 'boolean').label('Active?')
        ])
        .infinitePagination(false)
        .perPage(limit_per_page)
        .listActions(['delete', 'edit','show'])
        .filters([
            nga.field('q').label('Search')
                .pinned(true)
                .template('<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"/><span class="input-group-addon"><i class="fa fa-search text-primary"></i></span></div>'),
            nga.field('filter', 'choice').label('Status').attributes({
                    placeholder: 'Status?'
                })
                .choices([{
                    label: 'Active',
                    value: 'active'
                }, {
                    label: 'Inactive',
                    value: 'inactive'
                },])
        ])
        .batchActions([])
        .actions(['create', 'batch', vehicle_makes_custom_tmp]);
    vehicle_makes.creationView().title('Add Vehicle Make')
        .fields([
            nga.field('name').label('Name')
                .attributes({
                    placeholder: 'Name'
                })
                .validation({
                    required: true
                }),
            nga.field('is_active', 'choice').label('Active?')
                .attributes({
                    placeholder: 'Active?'
                })
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: 1
                }, {
                    label: 'No',
                    value: 0
                },])
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);
		
	vehicle_makes.showView().title('View Vehicle Make')
        .fields([
            nga.field('created_at').label('Created'),
            nga.field('name').label('Name'),
            nga.field('vehicle_count').label('Vehicle Count'),
            nga.field('vehicle_model_count').label('Vehicle Model Count'),
            nga.field('is_active','boolean').label('Active?'),
			nga.field('', 'template').label('Related Vehicles')
                .template('<span class="pull-right"><ma-filtered-list-button entity-name="vehicles" filter="{ vehicle_make_id: entry.values.id }" size="sm"></ma-filtered-list-button></span>'),
        ]);	
		
    vehicle_makes.editionView().title('Edit Vehicle Make')
        .fields([
            nga.field('name').label('Name'),
            nga.field('is_active', 'choice').label('Active?')
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: 1
                }, {
                    label: 'No',
                    value: 0
                },])
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);
    var vehicle_models_custom_tmp = '<ma-filter-button filters="filters()" enabled-filters="enabledFilters" enable-filter="enableFilter()"></ma-filter-button>';
    vehicle_models.listView().title('Vehicle Models')
        .fields([
            nga.field('created_at').label('Created'),
            nga.field('name').label('Name'),
            nga.field('vehicle_make.name').label('Vehicle Make'),
            nga.field('vehicle_count').label('Vehicle Count'),
            nga.field('is_active', 'boolean').label('Active?')
        ])
        .infinitePagination(false)
        .perPage(limit_per_page)
        .listActions(['delete', 'edit','show'])
        .filters([
            nga.field('q').label('Search')
                .pinned(true)
                .template('<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"/><span class="input-group-addon"><i class="fa fa-search text-primary"></i></span></div>'),
            nga.field('filter', 'choice').label('Status').attributes({
                    placeholder: 'Status?'
                })
                .choices([{
                    label: 'Active',
                    value: 'active'
                }, {
                    label: 'Inactive',
                    value: 'inactive'
                },])
        ])
        .batchActions([])
        .actions(['create', 'batch', vehicle_models_custom_tmp]);
		
	vehicle_models.showView().title('View Vehicle Model')
        .fields([
            nga.field('created_at').label('Created'),
            nga.field('name').label('Name'),
            nga.field('vehicle_make.name').label('Vehicle Make'),
            nga.field('vehicle_count').label('Vehicle Count'),
            nga.field('is_active','boolean').label('Active?'),
			nga.field('', 'template').label('Related Vehicles')
                .template('<span class="pull-right"><ma-filtered-list-button entity-name="vehicles" filter="{ vehicle_model_id: entry.values.id }" size="sm"></ma-filtered-list-button></span>'),
        ]);
		
    vehicle_models.creationView().title('Add Vehicle Model')
        .fields([
            nga.field('name').label('Name')
                .attributes({
                    placeholder: 'Name'
                })
                .validation({
                    required: true
                }),
            nga.field('vehicle_make_id', 'reference')
                .label('Vehicle Make')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Vehicle Make'
                })
                .targetEntity(nga.entity('vehicle_makes?filter=active'))
                .perPage('all')
                .targetField(nga.field('name').map(truncate)),
            nga.field('is_active', 'choice').label('Active?')
                .attributes({
                    placeholder: 'Active?'
                })
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: 1
                }, {
                    label: 'No',
                    value: 0
                },])
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);
    vehicle_models.editionView().title('Edit Vehicle Model')
        .fields([
            nga.field('name').label('Name'),
            nga.field('vehicle_make_id', 'reference')
                .label('Vehicle Make')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Vehicle Make'
                })
                .targetEntity(nga.entity('vehicle_makes'))
                .perPage('all')
                .targetField(nga.field('name').map(truncate)),
            nga.field('is_active', 'choice').label('Active?')
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: 1
                }, {
                    label: 'No',
                    value: 0
                },])
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);

    var vehicle_types_custom_tmp = '<ma-filter-button filters="filters()" enabled-filters="enabledFilters" enable-filter="enableFilter()"></ma-filter-button>';
    vehicle_types.listView().title('Vehicle Types')
        .fields([
            nga.field('created_at').label('Created'),
            nga.field('name').label('Name'),
            nga.field('minimum_hour_price', 'number').format('0.00').label('Minimum Hour Price'),
            nga.field('maximum_hour_price', 'number').format('0.00').label('Maximum Hour Price'),
            nga.field('minimum_day_price', 'number').format('0.00').label('Minimum Day Price'),
            nga.field('maximum_day_price', 'number').format('0.00').label('Maximum Day Price'),
            nga.field('drop_location_differ_unit_price', 'number').format('0.00').label('Drop location differ unit price'),
            nga.field('drop_location_differ_additional_fee', 'number').format('0.00').label('Drop location additional fee'),
            nga.field('deposit_amount', 'number').format('0.00').label('Deposit Amount(' + siteCurrency + ')'),
            nga.field('vehicle_count').label('Vehicle Count'),
            nga.field('is_active', 'boolean').label('Active?')
        ])
        .infinitePagination(false)
        .perPage(limit_per_page)
        .listActions(['show', 'edit', 'delete'])
        .filters([
            nga.field('q').label('Search')
                .pinned(true)
                .template('<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"/><span class="input-group-addon"><i class="fa fa-search text-primary"></i></span></div>'),
            nga.field('filter', 'choice').label('Status').attributes({
                    placeholder: 'Status?'
                })
                .choices([{
                    label: 'Active',
                    value: 'active'
                }, {
                    label: 'Inactive',
                    value: 'inactive'
                },])
        ])
        .batchActions([])
        .actions(['create', 'batch', vehicle_types_custom_tmp]);
		
    vehicle_types.showView().title('View Vehicle Type')
        .fields([
            nga.field('created_at').label('Created'),
            nga.field('name').label('Name'),
            nga.field('minimum_hour_price', 'number').format('0.00').label('Minimum Hour Price(' + siteCurrency + ')'),
            nga.field('maximum_hour_price', 'number').format('0.00').label('Maximum Hour Price(' + siteCurrency + ')'),
            nga.field('minimum_day_price', 'number').format('0.00').label('Minimum Day Price(' + siteCurrency + ')'),
            nga.field('maximum_day_price', 'number').format('0.00').label('Maximum Day Price(' + siteCurrency + ')'),
            nga.field('drop_location_differ_unit_price', 'number').format('0.00').label('Drop location differ unit price(' + siteCurrency + ')'),
            nga.field('drop_location_differ_additional_fee', 'number').format('0.00').label('Drop location additional fee(' + siteCurrency + ')'),
            nga.field('deposit_amount', 'number').format('0.00').label('Deposit Amount(' + siteCurrency + ')'),
            nga.field('vehicle_count','number').label('Vehicle Count'),
            nga.field('is_active', 'boolean').label('Active?'),
            nga.field('Discount Prices', 'referenced_list')
                .targetEntity(nga.entity('vehicle_type_prices'))
                .targetReferenceField('vehicle_type_id')
                .targetFields([
                    nga.field('created_at').label('Created'),
                    nga.field('minimum_no_of_day').label('Rental Min no of day'),
                    nga.field('maximum_no_of_day').label('Rental Max no of day'),
                    nga.field('discount_percentage', 'number').format('0.00').label('Discount(%)'),
                    nga.field('is_active', 'boolean').label('Active?')
                ]),
            nga.field('Special Prices', 'referenced_list')
                .targetEntity(nga.entity('vehicle_special_prices'))
                .targetReferenceField('vehicle_type_id')
                .targetFields([
                    nga.field('created_at').label('Created'),
                    nga.field('start_date').label('Start Date'),
                    nga.field('end_date').label('End Date'),
                    nga.field('discount_percentage', 'number').format('0.00').label('Discount(%)'),
                    nga.field('is_active', 'boolean').label('Active?')
                ]),
			nga.field('', 'template').label('Related Vehicles')
                .template('<span class="pull-right"><ma-filtered-list-button entity-name="vehicles" filter="{ vehicle_type_id: entry.values.id }" size="sm"></ma-filtered-list-button></span>'),	
        ])
    vehicle_types.editionView().title('Edit Vehicle Type')
        .fields([
            nga.field('name').label('Name')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Name'
                }),
            nga.field('minimum_hour_price').label('Minimum Hour Price(' + siteCurrency + ')')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Minimum Hour Price'
                }),
            nga.field('maximum_hour_price').label('Maximum Hour Price(' + siteCurrency + ')')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Maximum Hour Price'
                }),
            nga.field('minimum_day_price').label('Minimum Day Price(' + siteCurrency + ')')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Minimum Day Price'
                }),
            nga.field('maximum_day_price').label('Maximum Day Price(' + siteCurrency + ')')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Maximum Day Price'
                }),
            nga.field('drop_location_differ_unit_price').label('Drop location differ unit price(' + siteCurrency + ')')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Drop location differ unit price'
                }),
            nga.field('drop_location_differ_additional_fee').label('Drop location additional fee(' + siteCurrency + ')')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Drop location additional fee'
                }),
            nga.field('deposit_amount').label('Deposit Amount(' + siteCurrency + ')')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Deposit Amount'
                }),
            nga.field('is_active', 'choice').label('Active?')
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: 1
                }, {
                    label: 'No',
                    value: 0
                },])
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);

    vehicle_types.creationView().title('Add Vehicle Type')
        .fields([
            nga.field('name').label('Name')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Name'
                }),
            nga.field('minimum_hour_price').label('Minimum Hour Price(' + siteCurrency + ')')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Minimum Hour Price'
                }),
            nga.field('maximum_hour_price').label('Maximum Hour Price(' + siteCurrency + ')')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Maximum Hour Price'
                }),
            nga.field('minimum_day_price').label('Minimum Day Price(' + siteCurrency + ')')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Minimum Day Price'
                }),
            nga.field('maximum_day_price').label('Maximum Day Price(' + siteCurrency + ')')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Maximum Day Price'
                }),
            nga.field('drop_location_differ_unit_price').label('Drop location differ unit price(' + siteCurrency + ')')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Drop location differ unit price'
                }),
            nga.field('drop_location_differ_additional_fee').label('Drop location additional fee(' + siteCurrency + ')')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Drop location additional fee'
                }),
            nga.field('deposit_amount').label('Deposit Amount(' + siteCurrency + ')')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Deposit Amount'
                }),
            nga.field('is_active', 'choice').label('Active?')
                .attributes({
                    placeholder: 'Active?'
                })
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: 1
                }, {
                    label: 'No',
                    value: 0
                },])
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);

    var vehicle_taxes_custom_tmp = '<ma-filter-button filters="filters()" enabled-filters="enabledFilters" enable-filter="enableFilter()"></ma-filter-button>';
    vehicle_taxes.listView().title('Taxes')
        .fields([
            nga.field('created_at').label('Created'),
            nga.field('name','wysiwyg').label('Name').map(truncate),
            nga.field('short_description','wysiwyg').label('Short Description').map(truncate),
            nga.field('is_active', 'boolean').label('Active?')
        ])
        .infinitePagination(false)
        .perPage(limit_per_page)
        .listActions(['show', 'edit', 'delete'])
        .filters([
            nga.field('q').label('Search')
                .pinned(true)
                .template('<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"/><span class="input-group-addon"><i class="fa fa-search text-primary"></i></span></div>'),
            nga.field('filter', 'choice').label('Status').attributes({
                    placeholder: 'Status?'
                })
                .choices([{
                    label: 'Active',
                    value: 'active'
                }, {
                    label: 'Inactive',
                    value: 'inactive'
                },])
        ])
        .batchActions([])
        .actions(['create', 'batch', vehicle_taxes_custom_tmp]);
    vehicle_taxes.showView().title('View Tax')
        .fields([
            nga.field('created_at').label('Created'),
            nga.field('name').label('Name'),
            nga.field('short_description').label('Short Description'),
            nga.field('description', 'wysiwyg').label('Description'),
            nga.field('is_active', 'boolean').label('Active?')
        ]);
    vehicle_taxes.creationView().title('Add Tax')
        .fields([
            nga.field('name').label('Name')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Name'
                }),
            nga.field('short_description', 'text').label('Short Description')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Short Description'
                }),
            nga.field('description', 'wysiwyg').label('Description')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Short Description'
                }),
            nga.field('is_active', 'choice').label('Active?')
                .attributes({
                    placeholder: 'Active?'
                })
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: 1
                }, {
                    label: 'No',
                    value: 0
                },])
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);

    vehicle_taxes.editionView().title('Edit Tax')
        .fields([
            nga.field('name').label('Name')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Name'
                }),
            nga.field('short_description', 'text').label('Short Description')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Short Description'
                }),
            nga.field('description', 'wysiwyg').label('Description')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Short Description'
                }),
            nga.field('is_active', 'choice').label('Active?')
                .attributes({
                    placeholder: 'Active?'
                })
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: true
                }, {
                    label: 'No',
                    value: false
                },])
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);

    var vehicle_type_taxes_custom_tmp = '<ma-filter-button filters="filters()" enabled-filters="enabledFilters" enable-filter="enableFilter()"></ma-filter-button>';
    vehicle_type_taxes.listView().title('Vehicle Type Taxes')
        .fields([
            nga.field('created_at').label('Created'),
            nga.field('vehicle_type.name').label('Vehicle Type'),
            nga.field('vehicle_tax.name','wysiwyg').label('Tax'),
            nga.field('rate', 'number').format('0.00').label('Rate'),
            nga.field('discount_type.type').label('Type(' + siteCurrency + '/%)'),
            nga.field('duration_type.name').label('Duration Type'),
            nga.field('max_allowed_amount', 'number').format('0.00').label('Max Amount Allowed(' + siteCurrency + ')'),
            nga.field('is_active', 'boolean').label('Active?')
        ])
        .infinitePagination(false)
        .perPage(limit_per_page)
        .listActions(['edit', 'delete'])
        .filters([
            nga.field('q').label('Search')
                .pinned(true)
                .template('<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"/><span class="input-group-addon"><i class="fa fa-search text-primary"></i></span></div>'),
            nga.field('filter', 'choice').label('Status').attributes({
                    placeholder: 'Status?'
                })
                .choices([{
                    label: 'Active',
                    value: 'active'
                }, {
                    label: 'Inactive',
                    value: 'inactive'
                },])
        ])
        .batchActions([])
        .actions(['create', 'batch', vehicle_type_taxes_custom_tmp]);

    vehicle_type_taxes.creationView().title('Add Vehicle Type Tax')
        .fields([
            nga.field('vehicle_type_id', 'reference')
                .label('Vehicle Type')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Vehicle Type'
                })
                .targetEntity(nga.entity('vehicle_types'))
                .perPage('all')
                .targetField(nga.field('name').map(truncate)),
            nga.field('tax_id', 'reference')
                .label('Tax')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Tax'
                })
                .perPage('all')
                .targetEntity(nga.entity('vehicle_taxes'))
                .targetField(nga.field('name').map(truncate)),
            nga.field('rate').label('Rate')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Rate'
                }),
            nga.field('max_allowed_amount').label('Maximum amount allowed(' + siteCurrency + ')')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Maximum amount allowed'
                }),
            nga.field('discount_type_id', 'choice').label('Tax Type(' + siteCurrency + '/%)')
                .choices([{
                    label: 'Percentage',
                    value: '1'
                }, {
                    label: 'Amount',
                    value: '2'
                }])
                .attributes({
                    placeholder: 'Tax Type'
                })
                .validation({
                    required: true
                }),
            nga.field('duration_type_id', 'choice').label('Duration Type')
                .choices([{
                    label: 'Per day',
                    value: '1'
                }, {
                    label: 'Per rental',
                    value: '2'
                }])
                .attributes({
                    placeholder: 'Duration Type'
                })
                .validation({
                    required: true
                }),
            nga.field('is_active', 'choice').label('Active?')
                .attributes({
                    placeholder: 'Active?'
                })
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: true
                }, {
                    label: 'No',
                    value: false
                },])
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);

    vehicle_type_taxes.editionView().title('Edit Vehicle Type Tax')
        .fields([
            nga.field('vehicle_type_id', 'reference')
                .label('Vehicle Type')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Vehicle Type'
                })
                .targetEntity(nga.entity('vehicle_types'))
                .perPage('all')
                .targetField(nga.field('name').map(truncate)),
            nga.field('tax_id', 'reference')
                .label('Tax')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Tax'
                })
                .perPage('all')
                .targetEntity(nga.entity('vehicle_taxes'))
                .targetField(nga.field('name').map(truncate)),
            nga.field('rate').label('Rate')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Rate'
                }),
            nga.field('max_allowed_amount').label('Maximum amount allowed(' + siteCurrency + ')')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Maximum amount allowed'
                }),
            nga.field('discount_type_id', 'choice').label('Tax Type(' + siteCurrency + '/%)')
                .choices([{
                    label: 'Percentage',
                    value: '1'
                }, {
                    label: 'Amount',
                    value: '2'
                }])
                .attributes({
                    placeholder: 'Tax Type'
                })
                .validation({
                    required: true
                }),
            nga.field('duration_type_id', 'choice').label('Duration Type')
                .choices([{
                    label: 'Per day',
                    value: '1'
                }, {
                    label: 'Per rental',
                    value: '2'
                }])
                .attributes({
                    placeholder: 'Duration Type'
                })
                .validation({
                    required: true
                }),
            nga.field('is_active', 'choice').label('Active?')
                .attributes({
                    placeholder: 'Active?'
                })
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: true
                }, {
                    label: 'No',
                    value: false
                },])
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);

    var vehicle_insurances_custom_tmp = '<ma-filter-button filters="filters()" enabled-filters="enabledFilters" enable-filter="enableFilter()"></ma-filter-button>';
    vehicle_insurances.listView().title('Insurance')
        .fields([
            nga.field('created_at').label('Created'),
            nga.field('name','wysiwyg').label('Name').map(truncate),
            nga.field('short_description','wysiwyg').label('Short Description').map(truncate),
            nga.field('is_active', 'boolean').label('Active?')
        ])
        .infinitePagination(false)
        .perPage(limit_per_page)
        .listActions(['show', 'edit', 'delete'])
        .filters([
            nga.field('q').label('Search')
                .pinned(true)
                .template('<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"/><span class="input-group-addon"><i class="fa fa-search text-primary"></i></span></div>'),
            nga.field('filter', 'choice').label('Status').attributes({
                    placeholder: 'Status?'
                })
                .choices([{
                    label: 'Active',
                    value: 'active'
                }, {
                    label: 'Inactive',
                    value: 'inactive'
                },])
        ])
        .batchActions([])
        .actions(['create', 'batch', vehicle_insurances_custom_tmp]);
    vehicle_insurances.showView().title('View Insurance')
        .fields([
            nga.field('created_at').label('Created'),
            nga.field('name').label('Name'),
            nga.field('short_description').label('Short Description'),
            nga.field('description', 'wysiwyg').label('Description'),
            nga.field('is_active', 'boolean').label('Active?')            
        ]);
    vehicle_insurances.creationView().title('Add Insurance')
        .fields([
            nga.field('name').label('Name')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Name'
                }),
            nga.field('short_description', 'text').label('Short Description')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Short Description'
                }),
            nga.field('description', 'wysiwyg').label('Description')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Short Description'
                }),
            nga.field('is_active', 'choice').label('Active?')
                .attributes({
                    placeholder: 'Active?'
                })
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: 1
                }, {
                    label: 'No',
                    value: 0
                },])
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);

    vehicle_insurances.editionView().title('Edit Insurance')
        .fields([
            nga.field('name').label('Name')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Name'
                }),
            nga.field('short_description', 'text').label('Short Description')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Short Description'
                }),
            nga.field('description', 'wysiwyg').label('Description')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Short Description'
                }),
            nga.field('is_active', 'choice').label('Active?')
                .attributes({
                    placeholder: 'Active?'
                })
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: true
                }, {
                    label: 'No',
                    value: false
                },])
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);

    var vehicle_type_surcharges_custom_tmp = '<ma-filter-button filters="filters()" enabled-filters="enabledFilters" enable-filter="enableFilter()"></ma-filter-button>';
    vehicle_type_surcharges.listView().title('Vehicle Type Surcharges')
        .fields([
            nga.field('created_at').label('Created'),
            nga.field('vehicle_type.name').label('Vehicle Type'),
            nga.field('vehicle_surcharge.name','wysiwyg').label('Surcharge'),
            nga.field('rate', 'number').format('0.00').label('Rate'),
            nga.field('discount_type.type').label('Type(' + siteCurrency + '/%)'),
            nga.field('duration_type.name').label('Duration Type'),
            nga.field('max_allowed_amount', 'number').format('0.00').label('Max Amount Allowed(' + siteCurrency + ')'),
            nga.field('is_active', 'boolean').label('Active?')
        ])
        .infinitePagination(false)
        .perPage(limit_per_page)
        .listActions(['edit', 'delete'])
        .filters([
            nga.field('q').label('Search')
                .pinned(true)
                .template('<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"/><span class="input-group-addon"><i class="fa fa-search text-primary"></i></span></div>'),
            nga.field('filter', 'choice').label('Status').attributes({
                    placeholder: 'Status?'
                })
                .choices([{
                    label: 'Active',
                    value: 'active'
                }, {
                    label: 'Inactive',
                    value: 'inactive'
                },])
        ])
        .batchActions([])
        .actions(['create', 'batch', vehicle_type_surcharges_custom_tmp]);

    vehicle_type_surcharges.creationView().title('Add Vehicle Type Surcharge')
        .fields([
            nga.field('vehicle_type_id', 'reference')
                .label('Vehicle Type')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Vehicle Type'
                })
                .targetEntity(nga.entity('vehicle_types'))
                .perPage('all')
                .targetField(nga.field('name').map(truncate)),
            nga.field('surcharge_id', 'reference')
                .label('Surcharge')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Surcharge'
                })
                .perPage('all')
                .targetEntity(nga.entity('vehicle_surcharges'))
                .targetField(nga.field('name').map(truncate)),
            nga.field('rate').label('Rate')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Rate'
                }),
            nga.field('max_allowed_amount').label('Maximum amount allowed(' + siteCurrency + ')')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Maximum amount allowed'
                }),
            nga.field('discount_type_id', 'choice').label('Surcharge Type(' + siteCurrency + '/%)')
                .choices([{
                    label: 'Percentage',
                    value: '1'
                }, {
                    label: 'Amount',
                    value: '2'
                }])
                .attributes({
                    placeholder: 'Surcharge Type'
                })
                .validation({
                    required: true
                }),
            nga.field('duration_type_id', 'choice').label('Duration Type')
                .choices([{
                    label: 'Per day',
                    value: '1'
                }, {
                    label: 'Per rental',
                    value: '2'
                }])
                .attributes({
                    placeholder: 'Duration Type'
                })
                .validation({
                    required: true
                }),
            nga.field('is_active', 'choice').label('Active?')
                .attributes({
                    placeholder: 'Active?'
                })
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: 1
                }, {
                    label: 'No',
                    value: 0
                },])
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);

    vehicle_type_surcharges.editionView().title('Edit Vehicle Type Surcharge')
        .fields([
            nga.field('vehicle_type_id', 'reference')
                .label('Vehicle Type')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Vehicle Type'
                })
                .targetEntity(nga.entity('vehicle_types'))
                .perPage('all')
                .targetField(nga.field('name').map(truncate)),
            nga.field('surcharge_id', 'reference')
                .label('Surcharge')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Surcharge'
                })
                .perPage('all')
                .targetEntity(nga.entity('vehicle_surcharges'))
                .targetField(nga.field('name').map(truncate)),
            nga.field('rate').label('Rate')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Rate'
                }),
            nga.field('max_allowed_amount').label('Maximum amount allowed(' + siteCurrency + ')')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Maximum amount allowed'
                }),
            nga.field('discount_type_id', 'choice').label('Surcharge Type(' + siteCurrency + '/%)')
                .choices([{
                    label: 'Percentage',
                    value: '1'
                }, {
                    label: 'Amount',
                    value: '2'
                }])
                .attributes({
                    placeholder: 'Surcharge Type'
                })
                .validation({
                    required: true
                }),
            nga.field('duration_type_id', 'choice').label('Duration Type')
                .choices([{
                    label: 'Per day',
                    value: '1'
                }, {
                    label: 'Per rental',
                    value: '2'
                }])
                .attributes({
                    placeholder: 'Duration Type'
                })
                .validation({
                    required: true
                }),
            nga.field('is_active', 'choice').label('Active?')
                .attributes({
                    placeholder: 'Active?'
                })
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: 1
                }, {
                    label: 'No',
                    value: 0
                },])
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);

    var vehicle_surcharges_custom_tmp = '<ma-filter-button filters="filters()" enabled-filters="enabledFilters" enable-filter="enableFilter()"></ma-filter-button>';
    vehicle_surcharges.listView().title('Surcharges')
        .fields([
            nga.field('created_at').label('Created'),
            nga.field('name','wysiwyg').label('Name').map(truncate),
            nga.field('short_description','wysiwyg').label('Short Description').map(truncate),
            nga.field('is_active', 'boolean').label('Active?')
        ])
        .infinitePagination(false)
        .perPage(limit_per_page)
        .listActions(['show', 'edit', 'delete'])
        .filters([
            nga.field('q').label('Search')
                .pinned(true)
                .template('<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"/><span class="input-group-addon"><i class="fa fa-search text-primary"></i></span></div>'),
            nga.field('filter', 'choice').label('Status').attributes({
                    placeholder: 'Status?'
                })
                .choices([{
                    label: 'Active',
                    value: 'active'
                }, {
                    label: 'Inactive',
                    value: 'inactive'
                },])
        ])
        .batchActions([])
        .actions(['create', 'batch', vehicle_surcharges_custom_tmp]);
    vehicle_surcharges.showView().title('View Surcharge')
        .fields([
            nga.field('created_at').label('Created'),
            nga.field('name','wysiwyg').label('Name'),
            nga.field('short_description','wysiwyg').label('Short Description'),
            nga.field('description', 'wysiwyg').label('Description'),
            nga.field('is_active', 'boolean').label('Active?')
        ]);
    vehicle_surcharges.creationView().title('Add Surcharge')
        .fields([
            nga.field('name').label('Name')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Name'
                }),
            nga.field('short_description', 'text').label('Short Description')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Short Description'
                }),
            nga.field('description', 'wysiwyg').label('Description')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Short Description'
                }),
            nga.field('is_active', 'choice').label('Active?')
                .attributes({
                    placeholder: 'Active?'
                })
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: 1
                }, {
                    label: 'No',
                    value: 0
                },])
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);

    vehicle_surcharges.editionView().title('Edit Surcharge')
        .fields([
            nga.field('name').label('Name')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Name'
                }),
            nga.field('short_description', 'text').label('Short Description')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Short Description'
                }),
            nga.field('description', 'wysiwyg').label('Description')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Short Description'
                }),
            nga.field('is_active', 'choice').label('Active?')
                .attributes({
                    placeholder: 'Active?'
                })
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: true
                }, {
                    label: 'No',
                    value: false
                },])
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);

    var vehicle_type_insurances_custom_tmp = '<ma-filter-button filters="filters()" enabled-filters="enabledFilters" enable-filter="enableFilter()"></ma-filter-button>';
    vehicle_type_insurances.listView().title('Vehicle Type Insurances')
        .fields([
            nga.field('created_at').label('Created'),
            nga.field('vehicle_type.name').label('Vehicle Type'),
            nga.field('vehicle_insurance.name','wysiwyg').label('Insurance'),
            nga.field('rate', 'number').format('0.00').label('Rate'),
            nga.field('discount_type.type').label('Type(' + siteCurrency + '/%)'),
            nga.field('duration_type.name').label('Duration Type'),
            nga.field('max_allowed_amount', 'number').format('0.00').label('Max Amount Allowed(' + siteCurrency + ')'),
            nga.field('is_active', 'boolean').label('Active?')
        ])
        .infinitePagination(false)
        .perPage(limit_per_page)
        .listActions(['edit', 'delete'])
        .filters([
            nga.field('q').label('Search')
                .pinned(true)
                .template('<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"/><span class="input-group-addon"><i class="fa fa-search text-primary"></i></span></div>'),
            nga.field('filter', 'choice').label('Status').attributes({
                    placeholder: 'Status?'
                })
                .choices([{
                    label: 'Active',
                    value: 'active'
                }, {
                    label: 'Inactive',
                    value: 'inactive'
                },])
        ])
        .batchActions([])
        .actions(['create', 'batch', vehicle_type_insurances_custom_tmp]);

    vehicle_type_insurances.creationView().title('Add Vehicle Type Insurance')
        .fields([
            nga.field('vehicle_type_id', 'reference')
                .label('Vehicle Type')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Vehicle Type'
                })
                .targetEntity(nga.entity('vehicle_types'))
                .perPage('all')
                .targetField(nga.field('name').map(truncate)),
            nga.field('insurance_id', 'reference')
                .label('Insurance')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Insurance'
                })
                .perPage('all')
                .targetEntity(nga.entity('vehicle_insurances'))
                .targetField(nga.field('name').map(truncate)),
            nga.field('rate').label('Rate')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Rate'
                }),
            nga.field('max_allowed_amount').label('Maximum amount allowed(' + siteCurrency + ')')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Maximum amount allowed'
                }),
            nga.field('discount_type_id', 'choice').label('Insurance Type(' + siteCurrency + '/%)')
                .choices([{
                    label: 'Percentage',
                    value: '1'
                }, {
                    label: 'Amount',
                    value: '2'
                }])
                .attributes({
                    placeholder: 'Insurance Type'
                })
                .validation({
                    required: true
                }),
            nga.field('duration_type_id', 'choice').label('Duration Type')
                .choices([{
                    label: 'Per day',
                    value: '1'
                }, {
                    label: 'Per rental',
                    value: '2'
                }])
                .attributes({
                    placeholder: 'Duration Type'
                })
                .validation({
                    required: true
                }),
            nga.field('is_active', 'choice').label('Active?')
                .attributes({
                    placeholder: 'Active?'
                })
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: 1
                }, {
                    label: 'No',
                    value: 0
                },])
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);

    vehicle_type_insurances.editionView().title('Edit Vehicle Type Insurance')
        .fields([
            nga.field('vehicle_type_id', 'reference')
                .label('Vehicle Type')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Vehicle Type'
                })
                .targetEntity(nga.entity('vehicle_types'))
                .perPage('all')
                .targetField(nga.field('name').map(truncate)),
            nga.field('insurance_id', 'reference')
                .label('Insurance')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Insurance'
                })
                .perPage('all')
                .targetEntity(nga.entity('vehicle_insurances'))
                .targetField(nga.field('name').map(truncate)),
            nga.field('rate').label('Rate')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Rate'
                }),
            nga.field('max_allowed_amount').label('Maximum amount allowed(' + siteCurrency + ')')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Maximum amount allowed'
                }),
            nga.field('discount_type_id', 'choice').label('Insurance Type(' + siteCurrency + '/%)')
                .choices([{
                    label: 'Percentage',
                    value: '1'
                }, {
                    label: 'Amount',
                    value: '2'
                }])
                .attributes({
                    placeholder: 'Insurance Type'
                })
                .validation({
                    required: true
                }),
            nga.field('duration_type_id', 'choice').label('Duration Type')
                .choices([{
                    label: 'Per day',
                    value: '1'
                }, {
                    label: 'Per rental',
                    value: '2'
                }])
                .attributes({
                    placeholder: 'Duration Type'
                })
                .validation({
                    required: true
                }),
            nga.field('is_active', 'choice').label('Active?')
                .attributes({
                    placeholder: 'Active?'
                })
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: 1
                }, {
                    label: 'No',
                    value: 0
                },])
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);

    var vehicle_extra_accessories_custom_tmp = '<ma-filter-button filters="filters()" enabled-filters="enabledFilters" enable-filter="enableFilter()"></ma-filter-button>';
    vehicle_extra_accessories.listView().title('Extra Accessories')
        .fields([
            nga.field('created_at').label('Created'),
            nga.field('name','wysiwyg').label('Name').map(truncate),
            nga.field('short_description','wysiwyg').label('Short Description').map(truncate),
            nga.field('is_active', 'boolean').label('Active?')
        ])
        .infinitePagination(false)
        .perPage(limit_per_page)
        .listActions(['show', 'edit', 'delete'])
        .filters([
            nga.field('q').label('Search')
                .pinned(true)
                .template('<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"/><span class="input-group-addon"><i class="fa fa-search text-primary"></i></span></div>'),
            nga.field('filter', 'choice').label('Status').attributes({
                    placeholder: 'Status?'
                })
                .choices([{
                    label: 'Active',
                    value: 'active'
                }, {
                    label: 'Inactive',
                    value: 'inactive'
                },])
        ])
        .batchActions([])
        .actions(['create', 'batch', vehicle_extra_accessories_custom_tmp]);
    vehicle_extra_accessories.showView().title('View Extra Accessory')
        .fields([
            nga.field('created_at').label('Created'),
            nga.field('name').label('Name'),
            nga.field('short_description').label('Short Description'),
            nga.field('description', 'wysiwyg').label('Description'),
            nga.field('is_active', 'boolean').label('Active?')
        ]);
    vehicle_extra_accessories.creationView().title('Add Extra Accessory')
        .fields([
            nga.field('name').label('Name')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Name'
                }),
            nga.field('short_description', 'text').label('Short Description')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Short Description'
                }),
            nga.field('description', 'wysiwyg').label('Description')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Short Description'
                }),
            nga.field('is_active', 'choice').label('Active?')
                .attributes({
                    placeholder: 'Active?'
                })
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: true
                }, {
                    label: 'No',
                    value: false
                },])
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);
    vehicle_extra_accessories.editionView().title('Edit Extra Accessory')
        .fields([
            nga.field('name').label('Name')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Name'
                }),
            nga.field('short_description', 'text').label('Short Description')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Short Description'
                }),
            nga.field('description', 'wysiwyg').label('Description')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Short Description'
                }),
            nga.field('is_active', 'choice').label('Active?')
                .attributes({
                    placeholder: 'Active?'
                })
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: true
                }, {
                    label: 'No',
                    value: false
                },])
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);

    var vehicle_type_extra_accessories_custom_tmp = '<ma-filter-button filters="filters()" enabled-filters="enabledFilters" enable-filter="enableFilter()"></ma-filter-button>';
    vehicle_type_extra_accessories.listView().title('Vehicle Type Extra Accessories')
        .fields([
            nga.field('created_at').label('Created'),
            nga.field('vehicle_type.name').label('Vehicle Type'),
            nga.field('vehicle_extra_accessory.name','wysiwyg').label('Extra Accessory'),
            nga.field('rate', 'number').format('0.00').label('Rate'),
            nga.field('discount_type.type').label('Type(' + siteCurrency + '/%)'),
            nga.field('duration_type.name').label('Duration Type'),
            nga.field('max_allowed_amount', 'number').format('0.00').label('Max Amount Allowed(' + siteCurrency + ')'),
            nga.field('deposit_amount', 'number').format('0.00').label('Deposit Amount(' + siteCurrency + ')'),
            nga.field('is_active', 'boolean').label('Active?')
        ])
        .infinitePagination(false)
        .perPage(limit_per_page)
        .listActions(['edit', 'delete'])
        .filters([
            nga.field('q').label('Search')
                .pinned(true)
                .template('<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"/><span class="input-group-addon"><i class="fa fa-search text-primary"></i></span></div>'),
            nga.field('filter', 'choice').label('Status').attributes({
                    placeholder: 'Status?'
                })
                .choices([{
                    label: 'Active',
                    value: 'active'
                }, {
                    label: 'Inactive',
                    value: 'inactive'
                },])
        ])
        .batchActions([])
        .actions(['create', 'batch', vehicle_type_extra_accessories_custom_tmp]);

    vehicle_type_extra_accessories.creationView().title('Add Vehicle Type Extra Accessory')
        .fields([
            nga.field('vehicle_type_id', 'reference')
                .label('Vehicle Type')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Vehicle Type'
                })
                .targetEntity(nga.entity('vehicle_types'))
                .perPage('all')
                .targetField(nga.field('name').map(truncate)),
            nga.field('extra_accessory_id', 'reference')
                .label('Extra Accessory')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Extra Accessory'
                })
                .perPage('all')
                .targetEntity(nga.entity('vehicle_extra_accessories'))
                .targetField(nga.field('name').map(truncate)),
            nga.field('rate').label('Rate')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Rate'
                }),
            nga.field('max_allowed_amount').label('Maximum amount allowed(' + siteCurrency + ')')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Maximum amount allowed'
                }),
            nga.field('deposit_amount').label('Deposit Amount(' + siteCurrency + ')')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Deposit Amount'
                }),
            nga.field('discount_type_id', 'choice').label('Type(' + siteCurrency + '/%)')
                .choices([{
                    label: 'Percentage',
                    value: '1'
                }, {
                    label: 'Amount',
                    value: '2'
                }])
                .attributes({
                    placeholder: 'Type'
                })
                .validation({
                    required: true
                }),
            nga.field('duration_type_id', 'choice').label('Duration Type')
                .choices([{
                    label: 'Per day',
                    value: '1'
                }, {
                    label: 'Per rental',
                    value: '2'
                }])
                .attributes({
                    placeholder: 'Duration Type'
                })
                .validation({
                    required: true
                }),
            nga.field('is_active', 'choice').label('Active?')
                .attributes({
                    placeholder: 'Active?'
                })
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: 1
                }, {
                    label: 'No',
                    value: 0
                },])
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);

    vehicle_type_extra_accessories.editionView().title('Edit Vehicle Type Extra Accessory')
        .fields([
            nga.field('vehicle_type_id', 'reference')
                .label('Vehicle Type')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Vehicle Type'
                })
                .targetEntity(nga.entity('vehicle_types'))
                .perPage('all')
                .targetField(nga.field('name').map(truncate)),
            nga.field('extra_accessory_id', 'reference')
                .label('Extra Accessory')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Extra Accessory'
                })
                .perPage('all')
                .targetEntity(nga.entity('vehicle_extra_accessories'))
                .targetField(nga.field('name').map(truncate)),
            nga.field('rate').label('Rate')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Rate'
                }),
            nga.field('max_allowed_amount').label('Maximum amount allowed(' + siteCurrency + ')')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Maximum amount allowed'
                }),
            nga.field('deposit_amount').label('Deposit Amount(' + siteCurrency + ')')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Deposit Amount'
                }),
            nga.field('discount_type_id', 'choice').label('Type(' + siteCurrency + '/%)')
                .choices([{
                    label: 'Percentage',
                    value: '1'
                }, {
                    label: 'Amount',
                    value: '2'
                }])
                .attributes({
                    placeholder: 'Type'
                })
                .validation({
                    required: true
                }),
            nga.field('duration_type_id', 'choice').label('Duration Type')
                .choices([{
                    label: 'Per day',
                    value: '1'
                }, {
                    label: 'Per rental',
                    value: '2'
                }])
                .attributes({
                    placeholder: 'Duration Type'
                })
                .validation({
                    required: true
                }),
            nga.field('is_active', 'choice').label('Active?')
                .attributes({
                    placeholder: 'Active?'
                })
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: true
                }, {
                    label: 'No',
                    value: false
                }])
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);

    var vehicle_fuel_options_custom_tmp = '<ma-filter-button filters="filters()" enabled-filters="enabledFilters" enable-filter="enableFilter()"></ma-filter-button>';
    vehicle_fuel_options.listView().title('Fuel Options')
        .fields([
            nga.field('created_at').label('Created'),
            nga.field('name','wysiwyg').label('Name').map(truncate),
            nga.field('short_description','wysiwyg').label('Short Description').map(truncate),
            nga.field('is_active', 'boolean').label('Active?')
        ])
        .infinitePagination(false)
        .perPage(limit_per_page)
        .listActions(['show', 'edit', 'delete'])
        .filters([
            nga.field('q').label('Search')
                .pinned(true)
                .template('<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"/><span class="input-group-addon"><i class="fa fa-search text-primary"></i></span></div>'),
            nga.field('filter', 'choice').label('Status').attributes({
                    placeholder: 'Status?'
                })
                .choices([{
                    label: 'Active',
                    value: 'active'
                }, {
                    label: 'Inactive',
                    value: 'inactive'
                },])
        ])
        .batchActions([])
        .actions(['create', 'batch', vehicle_fuel_options_custom_tmp]);
    vehicle_fuel_options.showView().title('View Fuel Option')
        .fields([
            nga.field('created_at').label('Created'),
            nga.field('name').label('Name'),
            nga.field('short_description').label('Short Description'),
            nga.field('description', 'wysiwyg').label('Description'),
            nga.field('is_active', 'boolean').label('Active?')
        ]);
    vehicle_fuel_options.creationView().title('Add Fuel Option')
        .fields([
            nga.field('name').label('Name')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Name'
                }),
            nga.field('short_description', 'text').label('Short Description')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Short Description'
                }),
            nga.field('description', 'wysiwyg').label('Description')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Short Description'
                }),
            nga.field('is_active', 'choice').label('Active?')
                .attributes({
                    placeholder: 'Active?'
                })
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: true
                }, {
                    label: 'No',
                    value: false
                },])
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);
    vehicle_fuel_options.editionView().title('Edit Fuel Option')
        .fields([
            nga.field('name').label('Name')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Name'
                }),
            nga.field('short_description', 'text').label('Short Description')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Short Description'
                }),
            nga.field('description', 'wysiwyg').label('Description')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Short Description'
                }),
            nga.field('is_active', 'choice').label('Active?')
                .attributes({
                    placeholder: 'Active?'
                })
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: true
                }, {
                    label: 'No',
                    value: false
                },])
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);

    var vehicle_type_fuel_options_custom_tmp = '<ma-filter-button filters="filters()" enabled-filters="enabledFilters" enable-filter="enableFilter()"></ma-filter-button>';
    vehicle_type_fuel_options.listView().title('Vehicle Type Fuel Options')
        .fields([
            nga.field('created_at').label('Created'),
            nga.field('vehicle_type.name').label('Vehicle Type'),
            nga.field('vehicle_fuel_option.name','wysiwyg').label('Fuel Option'),
            nga.field('rate', 'number').format('0.00').label('Rate'),
            nga.field('discount_type.type').label('Type(' + siteCurrency + '/%)'),
            nga.field('duration_type.name').label('Duration Type'),
            nga.field('max_allowed_amount', 'number').format('0.00').label('Max Amount Allowed(' + siteCurrency + ')'),
            nga.field('is_active', 'boolean').label('Active?')
        ])
        .infinitePagination(false)
        .perPage(limit_per_page)
        .listActions(['edit', 'delete'])
        .filters([
            nga.field('q').label('Search')
                .pinned(true)
                .template('<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"/><span class="input-group-addon"><i class="fa fa-search text-primary"></i></span></div>'),
            nga.field('filter', 'choice').label('Status').attributes({
                    placeholder: 'Status?'
                })
                .choices([{
                    label: 'Active',
                    value: 'active'
                }, {
                    label: 'Inactive',
                    value: 'inactive'
                },])
        ])
        .batchActions([])
        .actions(['create', 'batch', vehicle_type_fuel_options_custom_tmp]);

    vehicle_type_fuel_options.creationView().title('Add Vehicle Type Fuel Option')
        .fields([
            nga.field('vehicle_type_id', 'reference')
                .label('Vehicle Type')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Vehicle Type'
                })
                .targetEntity(nga.entity('vehicle_types'))
                .perPage('all')
                .targetField(nga.field('name').map(truncate)),
            nga.field('fuel_option_id', 'reference')
                .label('Fuel Option')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Fuel Option'
                })
                .perPage('all')
                .targetEntity(nga.entity('vehicle_fuel_options'))
                .targetField(nga.field('name').map(truncate)),
            nga.field('rate').label('Rate')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Rate'
                }),
            nga.field('max_allowed_amount').label('Maximum amount allowed(' + siteCurrency + ')')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Maximum amount allowed'
                }),
            nga.field('discount_type_id', 'choice').label('Type(' + siteCurrency + '/%)')
                .choices([{
                    label: 'Percentage',
                    value: '1'
                }, {
                    label: 'Amount',
                    value: '2'
                }])
                .attributes({
                    placeholder: 'Type'
                })
                .validation({
                    required: true
                }),
            nga.field('duration_type_id', 'choice').label('Duration Type')
                .choices([{
                    label: 'Per day',
                    value: '1'
                }, {
                    label: 'Per rental',
                    value: '2'
                }])
                .attributes({
                    placeholder: 'Duration Type'
                })
                .validation({
                    required: true
                }),
            nga.field('is_active', 'choice').label('Active?')
                .attributes({
                    placeholder: 'Active?'
                })
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: 1
                }, {
                    label: 'No',
                    value: 0
                },])
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);

    vehicle_type_fuel_options.editionView().title('Edit Vehicle Type Fuel Option')
        .fields([
            nga.field('vehicle_type_id', 'reference')
                .label('Vehicle Type')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Vehicle Type'
                })
                .targetEntity(nga.entity('vehicle_types'))
                .perPage('all')
                .targetField(nga.field('name').map(truncate)),
            nga.field('fuel_option_id', 'reference')
                .label('Fuel Option')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Fuel Option'
                })
                .perPage('all')
                .targetEntity(nga.entity('vehicle_fuel_options'))
                .targetField(nga.field('name').map(truncate)),
            nga.field('rate').label('Rate')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Rate'
                }),
            nga.field('max_allowed_amount').label('Maximum amount allowed(' + siteCurrency + ')')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Maximum amount allowed'
                }),
            nga.field('discount_type_id', 'choice').label('Type(' + siteCurrency + '/%)')
                .choices([{
                    label: 'Percentage',
                    value: '1'
                }, {
                    label: 'Amount',
                    value: '2'
                }])
                .attributes({
                    placeholder: 'Type'
                })
                .validation({
                    required: true
                }),
            nga.field('duration_type_id', 'choice').label('Duration Type')
                .choices([{
                    label: 'Per day',
                    value: '1'
                }, {
                    label: 'Per rental',
                    value: '2'
                }])
                .attributes({
                    placeholder: 'Duration Type'
                })
                .validation({
                    required: true
                }),
            nga.field('is_active', 'choice').label('Active?')
                .attributes({
                    placeholder: 'Active?'
                })
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: true
                }, {
                    label: 'No',
                    value: false
                }])
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);

    var vehicle_special_prices_custom_tmp = '<ma-filter-button filters="filters()" enabled-filters="enabledFilters" enable-filter="enableFilter()"></ma-filter-button>';
    vehicle_special_prices.listView().title('Special Discount Prices')
        .fields([
            nga.field('created_at').label('Created'),
            nga.field('vehicle_type.name').label('Vehicle Type'),
            nga.field('start_date').label('Start Date'),
            nga.field('end_date').label('End Date'),
            nga.field('discount_percentage', 'number').format('0.00').label('Discount(%)'),
            nga.field('is_active', 'boolean').label('Active?')
        ])
        .infinitePagination(false)
        .perPage(limit_per_page)
        .listActions(['edit', 'delete'])
        .filters([
            nga.field('filter', 'choice').label('Status').attributes({
                    placeholder: 'Status?'
                })
                .choices([{
                    label: 'Active',
                    value: 'active'
                }, {
                    label: 'Inactive',
                    value: 'inactive'
                }]),
            nga.field('vehicle_type_id', 'reference')
                .attributes({
                    placeholder: 'Type'
                })
                .targetEntity(nga.entity('vehicle_types'))
                .targetField(nga.field('name'))
                .label('Type'),
            nga.field('start_date', 'date').format("MM/dd/yyyy").attributes({'placeholder': 'Select Start date'}).label("Start Date"),
            nga.field('end_date', 'date').format("MM/dd/yyyy").attributes({'placeholder': 'Select End date'}).label("End Date")
        ])
        .batchActions([])
        .actions(['create', 'batch', vehicle_special_prices_custom_tmp]);

    vehicle_special_prices.creationView().title('Add Special Discount Price')
        .fields([
            nga.field('vehicle_type_id', 'reference')
                .label('Vehicle Type')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Vehicle Type'
                })
                .targetEntity(nga.entity('vehicle_types?filter=active'))
                .perPage('all')
                .targetField(nga.field('name').map(truncate)),
            nga.field('discount_percentage').label('Discount(%)')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Discount Percentage'
                }),
            nga.field('start_date', 'date')
			    .label('Start date')
				.attributes({
                    placeholder: 'Start Date'
                }),
            nga.field('end_date', 'date')
			    .label('End date')
				.attributes({
                    placeholder: 'End Date'
                }),
            nga.field('is_active', 'choice').label('Active?')
                .attributes({
                    placeholder: 'Active?'
                })
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: true
                }, {
                    label: 'No',
                    value: false
                }])
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);

    vehicle_special_prices.editionView().title('Edit Special Discount Price')
        .fields([
            nga.field('vehicle_type_id', 'reference')
                .label('Vehicle Type')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Vehicle Type'
                })
                .targetEntity(nga.entity('vehicle_types'))
                .perPage('all')
                .targetField(nga.field('name').map(truncate)),
            nga.field('discount_percentage').label('Discount(%)')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Discount Percentage'
                }),
            nga.field('start_date', 'date').label('Start date'),
            nga.field('end_date', 'date').label('End date'),
            nga.field('is_active', 'choice').label('Active?')
                .attributes({
                    placeholder: 'Active?'
                })
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: true
                }, {
                    label: 'No',
                    value: false
                }])
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);

    var vehicle_companies_custom_tmp = '<ma-filter-button filters="filters()" enabled-filters="enabledFilters" enable-filter="enableFilter()"></ma-filter-button>';
    vehicle_companies.listView().title('Vehicle Companies')
        .fields([
            nga.field('created_at').label('Created'),
            nga.field('user.username').label('User'),
            nga.field('name').label('Name'),
            nga.field('address').label('Address').map(truncate),
            nga.field('latitude').label('Latitude'),
            nga.field('longitude').label('Longitude'),
            nga.field('email').label('Email'),
            nga.field('mobile').label('Mobile'),
            nga.field('vehicle_count').label('Vehicle Count'),
            nga.field('status').label('Status?'),
        ])
        .filters([
            nga.field('q').label('Search')
                .pinned(true)
                .template('<div class="input-group"><input type="text" ng-model="value" placeholder="Search Company" class="form-control"/><span class="input-group-addon"><i class="glyphicon glyphicon-search text-primary"></i></span></div>'),
            nga.field('is_active', 'choice').label('Status')
                .attributes({
                    placeholder: 'Status?'
                })
                .choices([{
                    label: 'Active',
                    value: 1
                }, {
                    label: 'Deactive',
                    value: 0
                }, {
                    label: 'Rejected',
                    value: 2
                }])
        ])
        .perPage(limit_per_page)
        .actions(['batch', 'create'])
        .listActions(['show', '<edit-company selection="selection" entry="entry" entity="entity" size="sm" label="Edit" ></edit-company>', 'delete'])
        .actions(['<a class="btn btn-create" ng-class="size ? \'btn-\' + size : \'\'" href="#/vehicle_companies/add" >\n<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>&nbsp;<span class="hidden-xs">Create</span>\n</a>', 'batch', vehicle_companies_custom_tmp])
        .batchActions([
            '<batch-vehicle-active type="reject" selection="selection"></batch-vehicle-active>',
            '<batch-vehicle-deactive type="reject" selection="selection"></batch-vehicle-deactive>',
            '<batch-reject type="reject" selection="selection"></batch-reject>',
            'delete'
        ]);
    vehicle_companies.showView().title('View Company Details')
        .fields([
            nga.field('created_at').label('Created'),
            nga.field('user.username').label('User'),
            nga.field('name').label('Name'),
            nga.field('address').label('Address'),
            nga.field('latitude').label('Latitude'),
            nga.field('longitude').label('Longitude'),
            nga.field('email').label('Email'),
            nga.field('mobile').label('Mobile'),
            nga.field('phone').label('Phone'),
            nga.field('fax').label('Fax'),
            nga.field('vehicle_count').label('Vehicle Count'),
            nga.field('status').label('Status?')            
        ]);
    counter_locations.listView().title('Counter Locations')
        .fields([
            nga.field('created_at').label('Created'),
            nga.field('address').label('Address').map(truncate),
            nga.field('latitude').label('Latitude'),
            nga.field('longitude').label('Longitude'),
            nga.field('mobile').label('Mobile'),
            nga.field('phone').label('Phone'),
            nga.field('fax').label('Fax'),
            nga.field('email').label('Email')
        ])
        .filters([
            nga.field('q').label('Search')
                .pinned(true)
                .template('<div class="input-group"><input type="text" ng-model="value" placeholder="Search Location" class="form-control"/><span class="input-group-addon"><i class="glyphicon glyphicon-search text-primary"></i></span></div>'),
            nga.field('filter', 'choice').label('Status').attributes({
                    placeholder: 'Status?'
                })
                .choices([{
                    label: 'Active',
                    value: 'active'
                }, {
                    label: 'Inactive',
                    value: 'inactive'
                },])
        ])
        .perPage(limit_per_page)
        .actions(['batch', 'create'])
        .listActions(['<edit-counter-location selection="selection" entry="entry" entity="entity" size="sm" label="Edit" ></edit-counter-location>', 'delete'])
        .actions(['<a class="btn btn-default" ng-class="size ? \'btn-\' + size : \'\'" href="#/counter_locations/add" >\n<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>&nbsp;<span class="hidden-xs">Create</span>\n</a>'])
        .batchActions([]);
    var vehicle_type_prices_custom_tmp = '<ma-filter-button filters="filters()" enabled-filters="enabledFilters" enable-filter="enableFilter()"></ma-filter-button>';
    vehicle_type_prices.listView().title('Discount Prices')
        .fields([
            nga.field('created_at').label('Created'),
            nga.field('vehicle_type.name').label('Vehicle Type'),
            nga.field('minimum_no_of_day').label('Rental Min no of day'),
            nga.field('maximum_no_of_day').label('Rental Max no of day'),
            nga.field('discount_percentage', 'number').format('0.00').label('Discount(%)'),
            nga.field('is_active', 'boolean').label('Active?')
        ])
        .infinitePagination(false)
        .perPage(limit_per_page)
        .listActions(['edit', 'delete'])
        .filters([
            nga.field('q').label('Search')
                .pinned(true)
                .template('<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"/><span class="input-group-addon"><i class="fa fa-search text-primary"></i></span></div>'),
            nga.field('filter', 'choice').label('Status').attributes({
                    placeholder: 'Status?'
                })
                .choices([{
                    label: 'Active',
                    value: 'active'
                }, {
                    label: 'Inactive',
                    value: 'inactive'
                }])
        ])
        .batchActions([])
        .actions(['create', 'batch', vehicle_type_prices_custom_tmp]);

    vehicle_type_prices.creationView().title('Add Discount Price')
        .fields([
            nga.field('vehicle_type_id', 'reference')
                .label('Vehicle Type')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Vehicle Type'
                })
                .targetEntity(nga.entity('vehicle_types?filter=active'))
                .perPage('all')
                .targetField(nga.field('name').map(truncate)),
            nga.field('minimum_no_of_day').label('Rental Min no of day')
                .attributes({
                    placeholder: 'Rental Min no of day'
                })
                .validation({
                    required: true
                }),
            nga.field('maximum_no_of_day').label('Rental Max no of day')
                .attributes({
                    placeholder: 'Rental Max no of day'
                })
                .validation({
                    required: true
                }),
            nga.field('discount_percentage').label('Discount Percentage')
                .attributes({
                    placeholder: 'Discount Percentage'
                })
                .validation({
                    required: true
                }),
            nga.field('is_active', 'choice').label('Active?')
                .attributes({
                    placeholder: 'Active?'
                })
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: true
                }, {
                    label: 'No',
                    value: false
                },])
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);

    vehicle_type_prices.editionView().title('Edit Discount Price')
        .fields([
            nga.field('vehicle_type_id', 'reference')
                .label('Vehicle Type')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Vehicle Type'
                })
                .targetEntity(nga.entity('vehicle_types'))
                .perPage('all')
                .targetField(nga.field('name').map(truncate)),
            nga.field('minimum_no_of_day').label('Rental Min no of day')
                .validation({
                    required: true
                }),
            nga.field('maximum_no_of_day').label('Rental Max no of day')
                .validation({
                    required: true
                }),
            nga.field('discount_percentage').label('Discount Percentage')
                .validation({
                    required: true
                }),
            nga.field('is_active', 'choice').label('Active?')
                .validation({
                    required: true
                })
                .choices([{
                    label: 'Yes',
                    value: 1
                }, {
                    label: 'No',
                    value: 0
                },])
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);
    var vehicle_search_custom_tmp = '<ma-filter-button filters="filters()" enabled-filters="enabledFilters" enable-filter="enableFilter()"></ma-filter-button>';
    vehicle_search.listView().title('Vehicle Search')
        .fields([
            nga.field('vehicle_type.name').label('Vehicle Type'),
            nga.field('start_date').label('Start Date'),
            nga.field('end_date').label('End Date'),
        ])
        .infinitePagination(false)
        .perPage(limit_per_page)
        .listActions(['edit', 'delete'])
        .filters([
            nga.field('q').label('Search')
                .pinned(true)
                .template('<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"/><span class="input-group-addon"><i class="fa fa-search text-primary"></i></span></div>'),
            nga.field('start_date', 'date').format("MM/dd/yyyy").attributes({'placeholder': 'Select Start date'}).label("Start Date"),
            nga.field('end_date', 'date').format("MM/dd/yyyy").attributes({'placeholder': 'Select End date'}).label("End Date")
        ])
        .batchActions([])
        .actions(['create', 'batch', vehicle_search_custom_tmp]);

    var unavailable_vehicles_custom_tmp = '<ma-filter-button filters="filters()" enabled-filters="enabledFilters" enable-filter="enableFilter()"></ma-filter-button>';
    unavailable_vehicles.listView().title('Maintenance')
        .fields([
            nga.field('vehicle.name').label('Vehicle'),
            nga.field('start_date').label('Start Date'),
            nga.field('end_date').label('End Date'),
        ])
        .infinitePagination(false)
        .perPage(limit_per_page)
        .listActions(['edit', 'delete'])
        .filters([
            nga.field('q').label('Search')
                .pinned(true)
                .template('<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"/><span class="input-group-addon"><i class="fa fa-search text-primary"></i></span></div>'),
            nga.field('start_date', 'date').format("MM/dd/yyyy").attributes({'placeholder': 'Select Start date'}).label("Start Date"),
            nga.field('end_date', 'date').format("MM/dd/yyyy").attributes({'placeholder': 'Select End date'}).label("End Date"),
            nga.field('vehicle_id', 'reference')
                .label('Vehicle')
                .attributes({
                    placeholder: 'Vehicle'
                })
                .targetEntity(nga.entity('vehicles'))
                .perPage('all')
                .targetField(nga.field('name').map(truncate))
        ])
        .batchActions([])
        .actions(['create', 'batch', unavailable_vehicles_custom_tmp]);

    unavailable_vehicles.creationView().title('Add Maintenance')
        .fields([
            nga.field('vehicle_id', 'reference')
                .label('Vehicle')
                .validation({
                    required: true
                })
                .attributes({
                    placeholder: 'Vehicle'
                })
                .targetEntity(nga.entity('vehicles'))
                .perPage('all')
                .targetField(nga.field('name').map(truncate)),
            nga.field('start_date', 'datetime').label('Start date'),
            nga.field('end_date', 'datetime').label('End date')
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);
    unavailable_vehicles.editionView().title('Edit Maintenance')
        .fields([
            nga.field('start_date', 'datetime').label('Start date'),
            nga.field('end_date', 'datetime').label('End date')
        ])
        .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function (progression, notification, $state, entry, entity) {
            progression.done();
            notification.log(entry.values.Success, {addnCls: 'humane-flatty-success'});
            $state.go($state.get('list'), {entity: entity.name()});
            return false;
        }])
        .onSubmitError(['error', 'form', 'progression', 'notification', function (error, form, progression, notification) {
            angular.forEach(error.data.errors, function (value, key) {
                if (this[key]) {
                    this[key].$valid = false;
                }
            }, form);
            progression.done();
            notification.log(error.data.message, {addnCls: 'humane-flatty-error'});
            return false;
        }]);
    // attach the admin application to the DOM and run it
    nga.configure(admin);
    //Menu configuration
    admin.menu(nga.menu()
        .addChild(nga.menu().title('Users').icon('<span class="fa fa-users"></span>')
            .addChild(nga.menu(users).title('Users').icon('<span class="glyphicon glyphicon-user userprofileChildMenuClass"></span>'))
        )
        .addChild(nga.menu().title('Vehicles').icon('<span class="fa fa-taxi"></span>'))
        .addChild(nga.menu().title('Payments').icon('<span class="fa fa-credit-card"></span>')
            .addChild(nga.menu(transactions).title('Transactions').icon('<span class="glyphicon glyphicon-usd"></span>'))
        )
        .addChild(nga.menu().title('Settings').icon('<span class="glyphicon glyphicon-cog"></span>')
            .addChild(nga.menu(setting_categories).title('Site Settings').icon('<span class="glyphicon glyphicon-wrench"></span>'))
        )
        .addChild(nga.menu().title('Activities').icon('<span class="glyphicon glyphicon-time"></span>')
            .addChild(nga.menu(api_requests).title('Api Requests').icon('<span class="glyphicon glyphicon-transfer"></span>'))
            .addChild(nga.menu(user_logins).title('User Logins').icon('<span class="glyphicon glyphicon-log-in"></span>'))
            .addChild(nga.menu(messages).title('Messages').icon('<span class="glyphicon glyphicon-envelope"></span>'))
        )
        .addChild(nga.menu(plugins).title('Plugins').icon('<span class="glyphicon glyphicon-th-large"></span>').link("/plugins"))
        .addChild(nga.menu().title('Master').icon('<span class="glyphicon glyphicon-dashboard"></span>')
            .addChild(nga.menu(cities).title('Cities').icon('<span class="glyphicon glyphicon-globe"></span>'))
            .addChild(nga.menu(states).title('States').icon('<span class="glyphicon glyphicon-globe"></span>'))
            .addChild(nga.menu(countries).title('Countries').icon('<span class="glyphicon glyphicon-globe"></span>'))
            .addChild(nga.menu(email_templates).title('Email Templates').icon('<span class="glyphicon glyphicon-inbox"></span>'))
            .addChild(nga.menu(languages).title('Languages').icon('<span class="glyphicon glyphicon-font"></span>'))
            .addChild(nga.menu(ips).title('IPs').icon('<span class="glyphicon glyphicon-barcode"></span>'))
            .addChild(nga.menu(transaction_types).title('Transaction Types').icon('<span class="fa fa-credit-card-alt"></span>'))
            .addChild(nga.menu(currencies).title('Currencies').icon('<span class="fa fa-money"></span>'))
            //.addChild(nga.menu(cancellation_types).title('Cancellation Types').icon('<span class="glyphicon glyphicon-remove-sign"></span>'))
        )
        .addChild(nga.menu().title('Vehicle Master').icon('<span class="fa fa-taxi"></span>'))
        .addChild(nga.menu().title('Vehicle Rental Master').icon('<span class="fa fa-hourglass"></span>'))
        .addChild(nga.menu().title('Diagnostics').icon('<span class="glyphicon glyphicon-tasks"></span>')
            .addChild(nga.menu(wallet_transaction_logs).title('Wallet Transaction Log').icon('<span class="glyphicon glyphicon-calendar"></span>'))
        )
        .addChild(nga.menu().title('View Site').template('<span class="fa fa-desktop"></span> <a href="'+admin_api_url+'" target="_blank">View Site </a>'))
		.addChild(nga.menu().title('Logout').icon('<span class="glyphicon glyphicon-log-out"></span>').link('/logout'))
    );
    if (angular.isDefined(enabledPlugins)) {
        //Add menu item if Plugin Enabled.
        if (enabledPlugins.indexOf("Contacts") > -1) {
            admin.menu().getChildByTitle('Users')
                .addChild(nga.menu(contacts).title('Contacts').icon('<span class="fa fa-support"></span>'))
        }
        if (enabledPlugins.indexOf("Withdrawal") > -1) {
            admin.menu().getChildByTitle('Users')
                .addChild(nga.menu(user_cash_withdrawals).title('Withdraw Requests').icon('<span class="fa fa-money"></span>'))
        }
        if (enabledPlugins.indexOf("Pages") > -1) {
            admin.menu().getChildByTitle('Master')
                .addChild(nga.menu(pages).title('Pages').icon('<span class="glyphicon glyphicon-list-alt"></span>'))
        }
        if (enabledPlugins.indexOf("SocialLogins") > -1) {
            admin.menu().getChildByTitle('Master')
                .addChild(nga.menu(providers).title('Providers').icon('<span class="fa fa-share-alt socialloginChildMenuClass"></span>'))
        }
        if (enabledPlugins.indexOf("Vehicles") > -1) {
            admin.menu().getChildByTitle('Vehicles')
                .addChild(nga.menu(vehicles).title('Vehicles').icon('<span class="fa fa-taxi"></span>'))
                .addChild(nga.menu(counter_locations).title('Counter Locations').icon('<span class="fa fa-map-marker"></span>'))
                .addChild(nga.menu(vehicle_type_prices).title('Discount Prices').icon('<span class="fa fa-percent"></span>'))
                .addChild(nga.menu(vehicle_special_prices).title('Special Discount Prices').icon('<span class="fa fa-percent"></span>'))
                .addChild(nga.menu(unavailable_vehicles).title('Maintenance').icon('<span class="fa fa-bug"></span>'))
            admin.menu().getChildByTitle('Vehicle Master')
                .addChild(nga.menu(vehicle_companies).title('Vehicle Companies').icon('<span class="fa fa-building"></span>'))
                .addChild(nga.menu(vehicle_makes).title('Vehicle Makes').icon('<span class="fa fa-automobile"></span>'))
                .addChild(nga.menu(vehicle_models).title('Vehicle Models').icon('<span class="fa fa-automobile"></span>'))
                .addChild(nga.menu(vehicle_types).title('Vehicle Types').icon('<span class="fa fa-automobile"></span>'))
        }
        if (enabledPlugins.indexOf("VehicleRentals") > -1) {
            admin.menu().getChildByTitle('Vehicles')
                .addChild(nga.menu(vehicle_rentals).title('Rentals').icon('<span class="fa fa-hourglass"></span>'))
        }
        if (enabledPlugins.indexOf("VehicleCoupons") > -1) {
            admin.menu().getChildByTitle('Vehicles')
                .addChild(nga.menu(vehicle_coupons).title('Coupons').icon('<span class="glyphicon glyphicon-gift"></span>'))
        }
        if (enabledPlugins.indexOf("VehicleFeedbacks") > -1) {
            admin.menu().getChildByTitle('Vehicles')
                .addChild(nga.menu(vehicle_feedbacks).title('Feedbacks').icon('<span class="glyphicon glyphicon-thumbs-up"></span>'))
        }
        if (enabledPlugins.indexOf("VehicleDisputes") > -1) {
            admin.menu().getChildByTitle('Vehicles')
                .addChild(nga.menu(vehicle_disputes).title('Disputes').icon('<span class="fa fa-gavel"></span>'))
            admin.menu().getChildByTitle('Vehicle Rental Master')
                .addChild(nga.menu(vehicle_dispute_types).title('Rental Dispute Types').icon('<span class="glyphicon glyphicon-retweet"></span>'))
                .addChild(nga.menu(vehicle_dispute_closed_types).title('Rental Dispute Closed Types').icon('<span class="glyphicon glyphicon-retweet"></span>'))
        }
        if (enabledPlugins.indexOf("VehicleTaxes") > -1) {
            admin.menu().getChildByTitle('Vehicle Rental Master')
                .addChild(nga.menu(vehicle_taxes).title('Taxes').icon('<span class="fa fa-text-width"></span>'))
                .addChild(nga.menu(vehicle_type_taxes).title('Vehicle Type Taxes').icon('<span class="fa fa-text-width"></span>'))
        }
        if (enabledPlugins.indexOf("VehicleSurcharges") > -1) {
            admin.menu().getChildByTitle('Vehicle Rental Master')
                .addChild(nga.menu(vehicle_surcharges).title('Surcharges').icon('<span class="fa fa-download"></span>'))
                .addChild(nga.menu(vehicle_type_surcharges).title('Vehicle Type Surcharges').icon('<span class="fa fa-download"></span>'))
        }
        if (enabledPlugins.indexOf("VehicleInsurances") > -1) {
            admin.menu().getChildByTitle('Vehicle Rental Master')
                .addChild(nga.menu(vehicle_insurances).title('Insurances').icon('<span class="fa fa-university"></span>'))
                .addChild(nga.menu(vehicle_type_insurances).title('Vehicle Type Insurances').icon('<span class="fa fa-university"></span>'))
        }
        if (enabledPlugins.indexOf("VehicleExtraAccessories") > -1) {
            admin.menu().getChildByTitle('Vehicle Rental Master')
                .addChild(nga.menu(vehicle_extra_accessories).title('Extra Accessories').icon('<span class="fa fa-external-link-square"></span>'))
                .addChild(nga.menu(vehicle_type_extra_accessories).title('Vehicle Type Extra Accessory').icon('<span class="fa fa-external-link-square"></span>'))
        }
        if (enabledPlugins.indexOf("VehicleFuelOptions") > -1) {
            admin.menu().getChildByTitle('Vehicle Rental Master')
                .addChild(nga.menu(vehicle_fuel_options).title('Fuel Options').icon('<span class="fa fa-tint"></span>'))
                .addChild(nga.menu(vehicle_type_fuel_options).title('Vehicle Type Fuel Options').icon('<span class="fa fa-tint"></span>'))
        }
        if (enabledPlugins.indexOf("Sudopays") > -1) {
            admin.menu().getChildByTitle('Diagnostics')
                .addChild(nga.menu(sudopay_transaction_logs).title('ZazPay Transaction Log').icon('<span class="glyphicon glyphicon-calendar"></span>'))
                .addChild(nga.menu(sudopay_ipn_logs).title('ZazPay Ipn Log').icon('<span class="glyphicon glyphicon-calendar"></span>'))
        }
        if (enabledPlugins.indexOf("Paypal") > -1) {
            admin.menu().getChildByTitle('Diagnostics')
                .addChild(nga.menu(paypal_transaction_logs).title('Paypal Transaction Log').icon('<span class="glyphicon glyphicon-calendar"></span>'))
        }
        if (enabledPlugins.indexOf("CurrencyConversions") > -1) {
            admin.menu().getChildByTitle('Activities')
                .addChild(nga.menu(currency_conversions).title('Currency Conversion').icon('<span class="fa fa-money"></span>'))
                .addChild(nga.menu(currency_conversion_histories).title('Currency Conversion History').icon('<span class="fa fa-money"></span>'))
        }
        //disable Listing parent menu if Item plugin disabled
        if (enabledPlugins.indexOf("Vehicles") === -1) {
            admin.menu().getChildByTitle('Vehicles')
                .title('')
                .template('')
                .icon('');
            admin.menu().getChildByTitle('Vehicle Master')
                .title('')
                .template('')
                .icon('');
        }
        //disable Listing parent menu if Tax plugin disabled
        if (enabledPlugins.indexOf("VehicleTaxes") === -1 && enabledPlugins.indexOf("VehicleSurcharges") === -1 && enabledPlugins.indexOf("VehicleInsurances") === -1 && enabledPlugins.indexOf("VehicleExtraAccessories") === -1 && enabledPlugins.indexOf("VehicleFuelOptions") === -1 && enabledPlugins.indexOf("VehicleDisputes") === -1) {
            admin.menu().getChildByTitle('Vehicle Rental Master')
                .title('')
                .template('')
                .icon('');
        }
    }
    // customize header
    var customHeaderTemplate = '<div class="navbar-header">' +
        '<button type="button" class="navbar-toggle" ng-click="isCollapsed = !isCollapsed">' +
        '<span class="icon-bar"></span>' +
        '<span class="icon-bar"></span>' +
        '<span class="icon-bar"></span>' +
        '</button>' +
        '<a class="navbar-brand" ui-sref="dashboard"><img src="../assets/img/logo.png" alt="[Image: Bookorrent]" title="Bookorrent" /></a>' +
        '</div>' + '<custom-header></custom-header>'; //<custom-header></custom-header> this is custom directive				
    admin.header(customHeaderTemplate);
    // customize dashboard
    var dashboardTpl = '<div class="row list-header"><div class="col-lg-12"><div class="page-header">' +
        '<h4><span>Dashboard</span></h4></div></div></div>' + '<dashboard-summary></dashboard-summary>' + '<div class="row dashboard-content"><div class="col-xs-12"><div class="panel panel-default"><ma-dashboard-panel collection="dashboardController.collections.users" entries="dashboardController.entries.users" datastore="dashboardController.datastore"></ma-dashboard-panel></div><div class="pull-right"><a class="btn btn-warning btn-block" href="#/users/list">View List <i class="fa fa-angle-right"></i></a><br></div></div><div class="col-xs-12"><div class="panel panel-default"><ma-dashboard-panel collection="dashboardController.collections.vehicles" entries="dashboardController.entries.vehicles" datastore="dashboardController.datastore"></ma-dashboard-panel></div><div class="pull-right "><a class="btn btn-warning btn-block" href="#/vehicles/list">View List <i class="fa fa-angle-right"></i></a><br></div></div></div>';
	admin.dashboard(nga.dashboard()
		.addCollection(nga.collection(users)
			.name('users')
			.title('Users')
			.perPage(5)
			.fields([
				nga.field('created_at').label('Register On'),
				nga.field('role_id', 'choice').label('User Type')
					.choices([{
						label: 'Admin',
						value: '1'
					}, {
						label: 'User',
						value: '2'
					}]),
				nga.field('username').label('Name'),
				nga.field('email').label('Email'),
				nga.field('available_wallet_amount', 'number').format('0.00').label('Available Balance (' + siteCurrency + ')'),
				nga.field('user_login_count', 'number').label('Login Count'),
				nga.field('is_active', 'boolean').label('Active?'),
				nga.field('is_email_confirmed', 'boolean').label('Email Confirmed?'),
			])
			.listActions(['<add-password entry="entry" entity="entity" size="sm" label="Change Password" ></add-password>', 'show', 'edit', 'delete'])			
			.order(1))
		.addCollection(nga.collection(vehicles)
			.name('vehicles')
			.title('Vehicles')
			.perPage(5)
			.fields([
				nga.field('created_at').label('Created'),
				nga.field('vehicle_company.name').label('Company Name'),
				nga.field('name').label('')
					.template('<a href="#/vehicle/{{entry.values.id}}/{{entry.values.slug}}">{{entry.values.name}}</a>')
					.label('Vehicle Name'),
				nga.field('vehicle_make.name').label('Make'),
				nga.field('vehicle_model.name').label('Model'),
				nga.field('vehicle_type.name').label('Type'),
				nga.field('per_hour_amount', 'number').format('0.00').label('Hour Amount(' + siteCurrency + ')'),
				nga.field('per_day_amount', 'number').format('0.00').label('Day Amount(' + siteCurrency + ')'),
				nga.field('feedback_count', 'number').label('Feedback Count').cssClasses(function () {
					if (enabledPlugins.indexOf("VehicleFeedbacks") === -1) {
						return "ng-hide";
					}
				}),
				nga.field('is_active', 'boolean').label('Active?'),
			])
			.listActions(['<show-vehicle selection="selection" entry="entry" entity="entity" size="sm" label="Show" ></show-vehicle>', '<edit-vehicle selection="selection" entry="entry" entity="entity" size="sm" label="Edit" ></edit-vehicle>', '<vehicle-calendar selection="selection" entry="entry" entity="entity" size="sm"></vehicle-calendar>', 'delete'])	
			.order(2))
		.template(dashboardTpl)
	);	
}]);
//Checking Admin Authentication by checking auth credentials, Redirected to site page if not admin logged in.
ngapp.run(function ($rootScope, $location) {
    $rootScope.$on('$viewContentLoaded', function () {
        if (!$('#preloader').hasClass('loadAG')) {
            $('#status').fadeOut(600);
            $('#preloader').delay(600).fadeOut(600 / 2);
        }
        var url_array = ['/login', '/logout', ''];
        var path = $location.path();
        if ($.inArray(path, url_array) === -1) {
            var token = localStorage.userToken;
            var role = localStorage.userRole;
            if (!token || parseInt(role) != 1) {
                $location.path('/logout');
            }
        }
    });
});
