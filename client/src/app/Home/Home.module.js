/**
 * @ngdoc object
 * @name Home
 * @description
 *
 * This is the home module. It provides the service for the home controller and it's methods
 *
 * The angular.module is a global place for creating, registering and retrieving Angular modules.
 * All modules that should be available to an application must be registered using this mechanism.
 * Passing one argument retrieves an existing angular.Module, whereas passing more than one argument creates a new angular.Module
 *
 * @param {string} BookorRent.home name of the module
 * @param {!Array.<string>=} dependencies If specified then new module is being created. If unspecified then the module is being retrieved for further configuration.
 *        [
 *            'ui.router',
 *            'angulartics',
 *            'angulartics.google.analytics',
 *            'angulartics.facebook.pixel'
 *        ]
 * @param {Function=} configFn Optional configuration function for the module.
 * @returns {angular.Module} new BookorRent.home module with the angular.Module api.
 **/
(function (module) {
    module.config(function ($stateProvider, $analyticsProvider) {
        var ResolveServiceData = {
            'ResolveServiceData': function (ResolveService, $q) {
                return $q.all({
                    AuthServiceData: ResolveService.promiseAuth,
                    SettingServiceData: ResolveService.promiseSettings
                });
            }
        };
        $stateProvider.state('home', {
            url: '/',
            authenticate: false,
            views: {
                "main": {
                    controller: 'HomeController as model',
                    templateUrl: 'Home/home.tpl.html',
                    resolve: ResolveServiceData
                }
            },
            data: {pageTitle: 'Home'}
        });
    });

}(angular.module("BookorRent.home", [
    'ui.router',
    'angulartics',
    'angulartics.google.analytics',
    'angulartics.facebook.pixel'
])));
