/**
 * LumenBase - v1.0a.01 - 2016-03-28
 *
 * Copyright (c) 2016 Agriya
 */
/**
 * @ngdoc object
 * @name Pages
 * @description
 *
 * This is the module for pages. It contains the pages functionalities.
 *
 * The contact module act as a state provider, this module get the url and load the template and call the controller instantly.
 *
 * @param {string} pages name of the module
 * @param {!Array.<string>=} dependencies If specified then new module is being created. If unspecified then the module is being retrieved for further configuration.
 *
 *        [
 *            'ui.router',
 *            'ngResource',
 *            'angulartics',
 *            'angulartics.google.analytics',
 *            'angulartics.facebook.pixel',
 *            'vcRecaptcha'
 *        ]
 * @param {string} stateProvider State provider is used to provide a corresponding model and template.
 * @param {string} analyticsProvider This service lets you integrate google analytics tracker in your AngularJS applications easily.
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
        $stateProvider.state('pages', {
            url: '/page/{slug}',
            authenticate: false,
            views: {
                "main": {
                    controller: 'PagesController as model',
                    templateUrl: 'Plugins/Pages/pages.tpl.html',
                    resolve: ResolveServiceData
                }
            },
            data: {pageTitle: 'Pages'}
        });
    });

}(angular.module("BookorRent.Pages", [
    'ui.router',
    'ngResource',
    'angulartics',
    'angulartics.google.analytics',
    'angulartics.facebook.pixel'
])));
