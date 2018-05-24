/**
 * BookorRent - v1.0a.01 - 2016-03-28
 *
 * Copyright (c) 2016 Agriya
 */
/**
 * @ngdoc object
 * @name VehicleRentals
 * @description
 *
 * This is the module for VehicleRentals. It contains the contact us functionalities.
 *
 * The VehicleRentals module act as a state provider, this module get the url and load the template and call the controller temporarily.
 *
 * @param {string} VehicleRentals name of the module
 * @param {!Array.<string>=} dependencies If specified then new module is being created. If unspecified then the module is being retrieved for further configuration.
 *
 *        [
 *            'ui.router',
 *            'ngResource',
 *            'angulartics',
 *            'angulartics.google.analytics',
 *            'angulartics.facebook.pixel',
 *            'ui.bootstrap',
 *            'ui.bootstrap.datetimepicker',
 *            'credit-cards'
 *        ]
 * @param {string} stateProvider State provider is used to provide a corresponding model and template.
 * @param {string} analyticsProvider This service lets you integrate google analytics tracker in your AngularJS applications easily.
 * @returns {BookorRent.VehicleRentals} new BookorRent.VehicleRentals module.
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
        $stateProvider.state('vehicle_rental_list_status', {
                url: '/booking/:statusID/:slug',
                authenticate: true,
                views: {
                    "main": {
                        controller: 'VehicleRentalController as model',
                        templateUrl: 'Plugins/VehicleRentals/vehicle_rental_list.tpl.html',
                        resolve: ResolveServiceData
                    }
                },
                data: {pageTitle: 'VehicleRental'}
            })
            
        .state('orders', {
            url: '/orders/:statusID/:slug',
            authenticate: true,
            views: {
                'main': {
                    controller: 'OrderListsController as model',
                    templateUrl: 'Plugins/VehicleRentals/order_lists.tpl.html',
                    resolve: ResolveServiceData
                }
            }
        })
        .state('order', {
            url: '/vehicle_rental/order/{vehicle_rental_id}',
            authenticate: true,
            views: {
                "main": {
                    controller: 'VehicleRentalOrderController as model',
                    templateUrl: 'Plugins/VehicleRentals/vehicle_rental_order.tpl.html',
                    resolve: ResolveServiceData
                }
            },
            data: {pageTitle: 'VehicleRental'}
        })
        .state('activity', {
            url: '/activity/{vehicle_rental_id}/{action}',
            authenticate: true,
            views: {
                "main": {
                    controller: 'VehicleRentalActivityController as model',
                    templateUrl: 'Plugins/VehicleRentals/vehicle_rental_activity.tpl.html',
                    resolve: ResolveServiceData
                }
            },
            data: {pageTitle: 'VehicleRental'}
        })
        .state('bookingAdd', {
            url: '/items/bookit/{item_id}/add',
            authenticate: true,
            views: {
                "main": {
                    controller: 'VehicleRentalController as model',
                    templateUrl: 'Plugins/VehicleRentals/vehicle_rental_add.tpl.html',
                    resolve: ResolveServiceData
                }
            },
            data: {pageTitle: 'VehicleRental'}
        })
        .state('checkout', {
            url: '/vehicle_rental/{order_id}/checkout',
            authenticate: true,
            views: {
                "main": {
                    controller: 'VehicleCheckoutController as model',
                    templateUrl: 'Plugins/VehicleRentals/vehicle_checkout.tpl.html',
                    resolve: ResolveServiceData
                }
            },
            data: {pageTitle: 'VehicleRental'}
        })
        .state('vehicleRentalStatus', {
            url: '/vehicle_rental/{statusID}/{slug}',
            authenticate: true,
            views: {
                "main": {
                    controller: 'VehicleRentalController as model',
                }
            },
            data: {pageTitle: 'Vehicles'}
        })
        .state('hostCalendar', {
            url: '/vehicle_rentals/orders/calendar',
            authenticate: true,
            views: {
                "main": {
                    controller: 'OrderCalendarController as model',
                    templateUrl: 'Plugins/VehicleRentals/vehicle_rental_calendar.tpl.html',
                    resolve: ResolveServiceData
                }
            },
            data: {pageTitle: 'VehicleRental'}
        })
        .state('bookingCalendar', {
            url: '/vehicle_rentals/bookings/calendar',
            authenticate: true,
            views: {
                "main": {
                    controller: 'BookingCalendarController as model',
                    templateUrl: 'Plugins/VehicleRentals/vehicle_rental_calendar.tpl.html',
                    resolve: ResolveServiceData
                }
            },
            data: {pageTitle: 'VehicleRental'}
        })
        .state('vehicleRentalAction', {
            url: '/vehicle_rentals/{vehicle_rental_id}/{action}',
            authenticate: true,
            views: {
                "main": {
                    controller: 'VehicleRentalController as model',
                    templateUrl: 'Plugins/VehicleRentals/vehicle_rental_list.tpl.html',
                    resolve: ResolveServiceData
                }
            },
            data: {pageTitle: 'VehicleRental'}
        })
        .state('vehicleOrderAction', {
            url: '/vehicle_orders/{vehicle_order_id}/{action}',
            authenticate: true,
            views: {
                "main": {
                    controller: 'OrderListsController as model',
                    templateUrl: 'Plugins/VehicleRentals/order_lists.tpl.html',
                    resolve: ResolveServiceData
                }
            },
            data: {pageTitle: 'VehicleRental'}
        });
    });
}(angular.module("BookorRent.VehicleRentals", [
    'ui.router',
    'ngResource',
    'angulartics',
    'angulartics.google.analytics',
    'angulartics.facebook.pixel',
    'mwl.calendar',
    'ui.bootstrap',
    'ui.bootstrap.datetimepicker',
    'credit-cards'
])));
