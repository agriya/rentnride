/**
 * BookorRent - v1.0a.01 - 2016-03-28
 *
 * Copyright (c) 2016 Agriya
 */
/**
 * @ngdoc object
 * @name SocialLogins
 * @description
 *
 * This is the module for SocialLogins. It contains the social login functionalities.
 *
 * The social login module act as a state provider, this module get the url and load the template and call the controller instantly.
 *
 * @param {string} SocialLogin name of the module
 * @param {!Array.<string>=} dependencies If specified then new module is being created. If unspecified then the module is being retrieved for further configuration.
 *
 *         [
 *            'ui.router',
 *            'ngResource',
 *            'satellizer'
 *        ]
 * @param {string} stateProvider State provider is used to provide a corresponding model and template.
 * @param {string} analyticsProvider This service lets you integrate google analytics tracker in your AngularJS applications easily.
 * @returns {BookorRent.SocialLogins} new BookorRent.SocialLogins module.
 **/
(function (module) {
    /**
     * @ngdoc directive
     * @name Vehicles.directive:socialShare
     * @scope
     * @restrict EA
     * @description
     * socialShare directive used to load the social login share.
     * @param {string} socialShare Name of the directive
     **/
    module.directive('socialShare', function () {
        return {
            restrict: 'EA',
            templateUrl: "Plugins/SocialLogins/social_login_share.tpl.html"
        };
    });
    module.config(function ($stateProvider, $authProvider, GENERAL_CONFIG) {
        $authProvider.unlinkUrl = GENERAL_CONFIG.api_url + '/auth/unlink';
        var ResolveServiceData = {
            'ResolveServiceData': function (ResolveService, $q) {
                return $q.all({
                    AuthServiceData: ResolveService.promiseAuth,
                    SettingServiceData: ResolveService.promiseSettings
                });
            }
        };
        $stateProvider.state('social', {
            url: '/social',
            authenticate: true,
            views: {
                "main": {
                    controller: 'SocialConnectionController as model',
                    templateUrl: 'Plugins/SocialLogins/my_connection.tpl.html',
                    resolve: ResolveServiceData
                }
            }
        }).state('profileImage', {
            url: '/profile_image',
            authenticate: true,
            views: {
                "main": {
                    controller: 'SocialProfileController as model',
                    templateUrl: 'Plugins/SocialLogins/profile_image.tpl.html',
                    resolve: ResolveServiceData
                }
            }
        }).state('socialLoginEmail', {
            url: '/social-login/email',
            authenticate: false,
            views: {
                "main": {
                    controller: 'SocialLoginEmailController as model',
                    templateUrl: 'Plugins/SocialLogins/get_email_from_user.tpl.html',
                    resolve: ResolveServiceData
                }
            }
        });
    });
}(angular.module('BookorRent.SocialLogins', [
    'ui.router',
    'ngResource',
    'satellizer'
])));
