/**
 * @ngdoc object
 * @name Transaction
 * @description
 *
 * This is the module for transaction. It controls all transaction related functions.
 *
 * The angular.module is a global place for creating, registering and retrieving Angular modules.
 * All modules that should be available to an application must be registered using this mechanism.
 * Passing one argument retrieves an existing angular.Module, whereas passing more than one argument creates a new angular.Module
 *
 * @param {string} transaction name of the module
 * @param {!Array.<string>=} dependencies If specified then new module is being created. If unspecified then the module is being retrieved for further configuration.
 *
 *        [
 *            'ui.router',
 *            'angulartics',
 *            'angulartics.google.analytics',
 *            'angulartics.facebook.pixel',
 *            'satellizer',
 *            'ngFileUpload',
 *            'vcRecaptcha'
 *        ]
 * @param {Function=} configFn Optional configuration function for the module.
 * @returns {angular.Module} new BookorRent.transaction module with the angular.Module api.
 **/
(function (module) {
    module.config(function ($stateProvider, $authProvider, GENERAL_CONFIG, $analyticsProvider) {
        var ResolveServiceData = {
            'ResolveServiceData': function (ResolveService, $q) {
                return $q.all({
                    AuthServiceData: ResolveService.promiseAuth,
                    SettingServiceData: ResolveService.promiseSettings
                });
            }
        };
        //$authProvider.tokenPrefix = "";
        //$authProvider.tokenName = "userToken";
        //$authProvider.loginUrl = GENERAL_CONFIG.api_url + '/users/login';
        //$authProvider.signupUrl = GENERAL_CONFIG.api_url + '/users/register';
        $stateProvider.state('transactions', {
            url: '/transactions',
            authenticate: false,
            views: {
                "main": {
                    controller: 'TransactionController as model',
                    templateUrl: 'Transactions/transaction_list.tpl.html',
                    resolve: ResolveServiceData
                }
            }
        });
    });
}(angular.module("BookorRent.Transactions", [
    'ui.router',
    'angulartics',
    'angulartics.google.analytics',
    'angulartics.facebook.pixel',
    'satellizer',
    'ngFileUpload',
    'vcRecaptcha'
])));

