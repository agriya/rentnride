/**
 * @ngdoc object
 * @name Wallets
 * @description
 *
 * This is the module for wallet
 *
 * The angular.module is a global place for creating, registering and retrieving Angular modules.
 * All modules that should be available to an application must be registered using this mechanism.
 * Passing one argument retrieves an existing angular.Module, whereas passing more than one argument creates a new angular.Module
 *
 * @param {string} wallets name of the module
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
 * @returns {angular.Module} new BookorRent.wallet module with the {@link angular.Module} api.
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
        $stateProvider.state('wallets', {
            url: '/wallets',
            authenticate: true,
            views: {
                "main": {
                    controller: 'WalletsController as model',
                    templateUrl: 'Wallets/wallet.tpl.html',
                    resolve: ResolveServiceData
                }
            },
            data: {pageTitle: 'Add to Wallet'}
        }).state('walletsRedirect', {
            url: '/wallets/:status',
            authenticate: true,
            views: {
                "main": {
                    controller: 'WalletsStatusController as model',
                    resolve: ResolveServiceData
                }
            },
            data: {pageTitle: 'Add to Wallet'}
        });
    });
}(angular.module("BookorRent.Wallets", [
    'ui.router',
    'ngResource',
    'angulartics',
    'angulartics.google.analytics',
    'angulartics.facebook.pixel',
    'credit-cards'
])));
