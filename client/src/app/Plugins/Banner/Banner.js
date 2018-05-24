/**
 * BookorRent - v1.0a.01 - 2016-03-28
 *
 * Copyright (c) 2016 Agriya
 */
/**
 * @ngdoc object
 * @name Banner
 * @description
 *
 * This is the module for banner. It contains the banner functionalities.
 *
 * The banner module have directive and controller. The directive which is used to load the template and call the controller instantly.
 *
 * @param {string} banner name of the module
 * @param {!Array.<string>=} dependencies The dependencies are included in main BookorRent.Banner module.
 *
 *        [
 *            'ui.router',
 *            'ngResource'
 *        ]
 * @returns {BookorRent.Banner} new BookorRent.Banner module.
 **/

(function (module) {
    /**
     * @ngdoc directive
     * @name Banner.directive:Banner
     * @scope
     * @restrict A
     * @description
     * Banner directive creates a banner tag. We can use this only as an attribute.
     * @param {string} banner Name of the directive
     **/
    module.directive('banner', function () {
        var linker = function (scope, element, attrs) {
            // do DOM Manipulation here
        };
        return {
            restrict: 'A',
            templateUrl: 'Plugins/Banner/banner.tpl.html',
            link: linker,
            controller: 'BannerController as model',
            bindToController: true,
            scope: {
                position: '@position'
            }
        };
    });
    /**
     * @ngdoc controller
     * @name Banner.controller:BannerController
     * @description
     * This is BannerController. All banner related functionalities will be declared here.
     **/
    module.controller('BannerController', function () {
        var model = this;
    });

}(angular.module("BookorRent.Banner", [
    'ui.router',
    'ngResource'
])));
