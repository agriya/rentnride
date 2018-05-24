/**
 * @ngdoc object
 * @name User
 * @description
 *
 * This is the module for user. It controls all user related functions.
 *
 * The angular.module is a global place for creating, registering and retrieving Angular modules.
 * All modules that should be available to an application must be registered using this mechanism.
 * Passing one argument retrieves an existing angular.Module, whereas passing more than one argument creates a new angular.Module
 *
 * @param {string} user name of the module
 * @param {!Array.<string>=} dependencies If specified then new module is being created. If unspecified then the module is being retrieved for further configuration.
 *
 *        [
 *            'ui.router',
 *            'angulartics',
 *            'angulartics.google.analytics',
 *            'angulartics.facebook.pixel',
 *            'satellizer',
 *            'ngFileUpload',
 *            'vcRecaptcha'
 *        ]
 * @param {Function=} configFn Optional configuration function for the module.
 * @returns {angular.Module} new BookorRent.user module with the angular.Module api.
 **/
(function (module) {
    module.directive('passwordMatch', function() {
        return {
            require: 'ngModel',
            scope: {
                otherModelValue: '=passwordMatch'
            },
            link: function(scope, element, attributes, ngModel) {
                ngModel.$validators.compareTo = function(modelValue) {
                    return modelValue === scope.otherModelValue;
                };
                scope.$watch('otherModelValue', function() {
                    ngModel.$validate();
                });
            }
        };
    });
    module.config(function ($stateProvider, $authProvider, GENERAL_CONFIG, $analyticsProvider) {
        var ResolveServiceData = {
            'ResolveServiceData': function (ResolveService, $q) {
                return $q.all({
                    AuthServiceData: ResolveService.promiseAuth,
                    SettingServiceData: ResolveService.promiseSettings
                });
            }
        };
        $authProvider.tokenPrefix = "";
        $authProvider.tokenName = "userToken";
        $authProvider.loginUrl = GENERAL_CONFIG.api_url + '/users/login';
        $authProvider.signupUrl = GENERAL_CONFIG.api_url + '/users/register';
        $stateProvider.state('login', {
            url: '/users/login',
            authenticate: false,
            views: {
                "main": {
                    controller: 'UserLoginController as model',
                    templateUrl: 'User/login.tpl.html',
                    resolve: ResolveServiceData
                }
            }
        }).state('register', {
            url: '/users/register',
            authenticate: false,
            views: {
                "main": {
                    controller: 'UserRegisterController as model',
                    templateUrl: 'User/register.tpl.html',
                    resolve: ResolveServiceData
                }
            }
        }).state('activate', {
            url: '/users/:id/activate/:hash',
            views: {
                "main": {
                    controller: 'UserActivateController as model',
                    resolve: ResolveServiceData
                }
            }
        }).state('ChangePassword', {
            url: '/users/change_password',
            authenticate: true,
            views: {
                "main": {
                    controller: 'ChangePasswordController as model',
                    templateUrl: 'User/change_password.tpl.html',
                    resolve: ResolveServiceData
                }
            }
        }).state('UserProfile', {
            url: '/users/user_profile',
            authenticate: true,
            views: {
                "main": {
                    controller: 'UserProfileController as model',
                    templateUrl: 'User/user_profile.tpl.html',
                    resolve: ResolveServiceData
                }
            }
        }).state('userView', {
            url: '/user/:username',
            authenticate: false,
            views: {
                "main": {
                    controller: 'UserController as model',
                    templateUrl: 'User/user_view.tpl.html',
                    resolve: ResolveServiceData
                }
            }
        }).state('dashboard', {
            url: '/users/dashboard',
            authenticate: true,
            views: {
                "main": {
                    controller: 'DashboardController as model',
                    templateUrl: 'User/dashboard.tpl.html',
                    resolve: ResolveServiceData
                }
            }
        }).state('ForgotPassword', {
            url: '/users/forgot_password',
            authenticate: false,
            views: {
                "main": {
                    controller: 'ForgotPasswordController as model',
                    templateUrl: 'User/forgot_password.tpl.html',
                    resolve: ResolveServiceData
                }
            }
        });
    });
    /**
     * @ngdoc directive
     * @name User.directive:dashboardSettings
     * @module User
     * @scope
     * @restrict E
     *
     * @description
     * This directive used to maintain the dashboard settings.
     *
     * @param {string}  dashboardSettings  directive name
     *
     */
    module.directive('dashboardSettings', function () {
        return {
            restrict: 'E',
            templateUrl: 'User/dashboard_settings.tpl.html'
        };
    });
}(angular.module("BookorRent.user", [
    'ui.router',
    'angulartics',
    'angulartics.google.analytics',
    'angulartics.facebook.pixel',
    'satellizer',
    'ngFileUpload',
    'vcRecaptcha',
    'slugifier'
])));

