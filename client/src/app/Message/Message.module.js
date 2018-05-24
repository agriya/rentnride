/**
 * @ngdoc object
 * @name Message
 * @description
 *
 * This is the module for Message
 *
 * The angular.module is a global place for creating, registering and retrieving Angular modules.
 * All modules that should be available to an application must be registered using this mechanism.
 * Passing one argument retrieves an existing angular.Module, whereas passing more than one argument creates a new angular.Module
 *
 * @param {string} Message name of the module
 * @param {!Array.<string>=} dependencies If specified then new module is being created. If unspecified then the module is being retrieved for further configuration.
 *
 *        [
 *            'ui.router',
 *            'ngResource',
 *            'angulartics',
 *            'angulartics.google.analytics',
 *            'angulartics.facebook.pixel'
 *        ]
 * @param {Function=} configFn Optional configuration function for the module.
 * @returns {angular.Module} new BookorRent.Message module with the angular.Module api.
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
        $stateProvider.state('message', {
                url: '/messages/{type}',
                authenticate: true,
                views: {
                    "main": {
                        controller: 'MessageController as model',
                        templateUrl: 'Message/message_list.tpl.html',
                        resolve: ResolveServiceData
                    }
                },
                data: {pageTitle: 'Inbox'}
            })
            .state('view_message', {
                url: '/message/{id}/{action}',
                authenticate: true,
                views: {
                    "main": {
                        controller: 'MessageController as model',
                        templateUrl: 'Message/message_view.tpl.html',
                        resolve: ResolveServiceData
                    }
                },
                data: {pageTitle: 'View Message'}
            });

    });
}(angular.module("BookorRent.Messages", [
    'ui.router',
    'ngResource',
    'angulartics',
    'angulartics.google.analytics',
    'angulartics.facebook.pixel'
])));
