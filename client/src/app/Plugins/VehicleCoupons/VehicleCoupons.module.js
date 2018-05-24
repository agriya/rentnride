/**
 * BookorRent - v1.0a.01 - 2016-03-28
 *
 * Copyright (c) 2016 Agriya
 */
/**
 * @ngdoc object
 * @name VehicleCoupons
 * @description
 *
 * This is the module for VehicleCoupons. It contains the VehicleCoupons functionalities.
 *
 * @param {string} VehicleCoupons name of the module
 * @param {!Array.<string>=} dependencies If specified then new module is being created. If unspecified then the module is being retrieved for further configuration.
 *
 *        [
 *            'ui.router',
 *            'ngResource',
 *            'angulartics',
 *            'angulartics.google.analytics',
 *            'angulartics.facebook.pixel' *
 *        ]
 * @returns {BookorRent.VehicleCoupons} new BookorRent.VehicleCoupons module.
 **/
(function (module) {

    /**
     * @ngdoc directive
     * @name VehicleCoupons.directive:coupon
     * @module VehicleCoupons
     * @scope
     * @restrict E
     * @description
     * This directive used to define the coupon detail section.
     */
    module.directive('coupon', function () {
        return {
            restrict: 'E',
            templateUrl: 'Plugins/VehicleCoupons/vehicle_coupon.tpl.html'
            // scope:{}
        };
    });
}(angular.module('BookorRent.VehicleCoupons', [
    'ui.router',
    'ngResource',
    'angulartics',
    'angulartics.google.analytics',
    'angulartics.facebook.pixel'
])));
