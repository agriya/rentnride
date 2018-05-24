/**
 * BookorRent - v1.0a.01 - 2016-03-28
 *
 * Copyright (c) 2016 Agriya
 */
/**
 * @ngdoc object
 * @name Withdrawals
 * @description
 *
 * This is the module for Withdrawals. It contains the withdrawal functionalities.
 *
 * The withdrawal module act as a state provider, this module get the url and load the template and call the controller instantly.
 *
 * @param {string} withdrawals name of the module
 * @param {!Array.<string>=} dependencies If specified then new module is being created. If unspecified then the module is being retrieved for further configuration.
 *
 *        [
 *            'ui.router',
 *            'ngResource',
 *            'angulartics',
 *            'angulartics.google.analytics',
 *            'angulartics.facebook.pixel'
 *        ]
 * @param {string} stateProvider State provider is used to provide a corresponding model and template.
 * @param {string} analyticsProvider This service lets you integrate google analytics tracker in your AngularJS applications easily.
 * @returns {BookorRent.Withdrawals} new BookorRent.Withdrawals module.
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
        $stateProvider.state('user_cash_withdrawals', {
            url: '/user_cash_withdrawals',
            authenticate: true,
            views: {
                "main": {
                    controller: 'UserCashWithdrawalsController as model',
                    templateUrl: 'Plugins/Withdrawals/user_cashWithdrawals.tpl.html',
                    resolve: ResolveServiceData
                }
            },
            data: {pageTitle: 'User Cash Withdrawals'}
        });
        $stateProvider.state('money_transfer_account', {
            url: '/money_transfer_account',
            authenticate: true,
            views: {
                "main": {
                    controller: 'MoneyTransferAccountsController as model',
                    templateUrl: 'Plugins/Withdrawals/money_transfer_account.tpl.html',
                    resolve: ResolveServiceData
                }
            },
            data: {pageTitle: 'Money Transfer Accounts'}
        });
    });
}(angular.module("BookorRent.Withdrawals", [
    'ui.router',
    'ngResource',
    'angulartics',
    'angulartics.google.analytics',
    'angulartics.facebook.pixel'
])));
