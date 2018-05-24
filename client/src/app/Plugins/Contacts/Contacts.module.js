/**
 * BookorRent - v1.0a.01 - 2016-03-28
 *
 * Copyright (c) 2016 Agriya
 */
/**
 * @ngdoc object
 * @name Contacts
 * @description
 *
 * This is the module for Contacts. It contains the contact us functionalities.
 *
 * The contact module act as a state provider, this module get the url and load the template and call the controller instantly.
 *
 * @param {string} Contacts name of the module
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
 * @returns {BookorRent.Contacts} new BookorRent.Contacts module.
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
        $stateProvider
            .state('contact', {
                url: '/contactus',
                authenticate: false,
                views: {
                    'main': {
                        controller: 'ContactUsController as model',
                        templateUrl: 'Plugins/Contacts/contacts.tpl.html',
                        resolve: ResolveServiceData
                    }
                }
            });

    });

}(angular.module('BookorRent.Contacts', [
    'ui.router',
    'ngResource',
    'angulartics',
    'angulartics.google.analytics',
    'angulartics.facebook.pixel',
    'vcRecaptcha'
])));
