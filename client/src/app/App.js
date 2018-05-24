/**
 * BookorRent - v1.0a.01 - 2016-03-28
 *
 * Copyright (c) 2016 Agriya
 */
/**
 * @ngdoc object
 * @name App
 * @description
 *
 * This is the base module. It defines the base configuration and appcontroller and run function etc.,
 *
 * The angular.module is a global place for creating, registering and retrieving Angular modules.
 * All modules that should be available to an application must be registered using this mechanism.
 * Passing one argument retrieves an existing angular.Module, whereas passing more than one argument creates a new angular.Module
 *
 *
 * @param {!string} App name of the module
 * @param {!Array.<string>=} dependencies If specified then new module is being created. If unspecified then the module is being retrieved for further configuration.
 *
 *        [
 *            'BookorRent.Constant',
 *            'BookorRent.home',
 *            'BookorRent.Constant',
 *            'BookorRent.user',
 *            'templates-app'
 *            'ui.router.state',
 *            'ui.router',
 *            'angulartics',
 *            'angulartics.google.analytics',
 *            'angulartics.facebook.pixel',
 *            'slugifier'
 *        ]
 * @param {Function=} configFn Optional configuration function for the module.
 * @returns {angular.Module} new BookorRent module with the angular.Module api.
 **/
(function (app) {
    app.config(function ($stateProvider, $urlRouterProvider, $authProvider, $translateProvider, $analyticsProvider) {
        $translateProvider.useStaticFilesLoader({
            prefix: 'assets/js/l10n/',
            suffix: '.json'
        });
        $translateProvider.preferredLanguage('en');
        $translateProvider.useLocalStorage(); // saves selected language to localStorage
        // Enable escaping of HTML
        $translateProvider.useSanitizeValueStrategy('escape');
        $urlRouterProvider.otherwise('/');
    });
    /**
     * @ngdoc controller
     * @name App.controller:AppController
     * @module App
     * @description
     *
     * This is AppController which is the base controller for all the controllers
     **/
    app.controller('AppController', function ($scope, $auth, $rootScope) {
        // Search block close
        /**
         * @ngdoc method
         * @name scrollMoveTop
         * @methodOf App.controller:AppController
         * @description
         * This method is used to scroll the window for top.
         **/
        $scope.scrollMoveTop = function () {
            $('html, body').stop(true, true).animate({
                scrollTop: 0
            }, 600);
        };
        /**
         * @ngdoc method
         * @name getFormatCurrency
         * @methodOf App.controller:AppController
         * @description
         * This method will provide currency format.
         * @param {integer} price Currency symbol changed into other country currency(converted currency).
         * @returns {integer} New converted currency with price.
         **/
        $scope.getFormatCurrency = function (price, value) {
            var currency;
            if ($rootScope.settings['site.enabled_plugins'].indexOf('CurrencyConversions') > -1) {
                if (value == 'site') {
                    currency = $rootScope.default_currency;
                } else {
                    currency = localStorage.getItem('convertedCurrency');
                    currency_obj = JSON.parse(currency);
                    if (currency_obj === null) {
                        currency = $rootScope.default_currency;
                    } else {
                        currency = (currency_obj) ? currency_obj : $rootScope.default_currency;
                        $scope.currency = currency.id;
                        price = price * currency.rate;
                    }
                }
            } else {
                currency = $rootScope.default_currency;
            }
            if (currency.is_prefix_display_on_left) {
                return accounting.formatMoney(price, currency.symbol, 2, currency.thousands_sep, currency.dec_point);
            } else {
                return accounting.formatMoney(price, currency.symbol, 2, currency.thousands_sep, currency.dec_point);
            }
        };
    });
    app.run(function ($rootScope, $location, $http, $auth) {
        $rootScope.is_fresh_call = 1;
        var url_array = ['/users/register', '/users/login'];
        $rootScope.$on('$stateChangeStart', function (event, toState, toParams, fromState, fromParams) {
            if ($.inArray(toState.url, url_array) !== -1) {
                if ($auth.isAuthenticated()) {
                    $location.path('/');
                }
            }
            if (toState.authenticate && !$auth.isAuthenticated()) {
                $rootScope.returnToState = toState.url;
                $rootScope.returnToStateParams = toParams.Id;
                $location.path('/users/login');
            }
        });
        $rootScope.$on('$viewContentLoaded', function () {
            if (!$('#preloader').hasClass('loadAG')) {
                $('#status').fadeOut(600);
                $('#preloader').delay(600).fadeOut(600 / 2);
            }
        });
        $rootScope.$on('$stateChangeSuccess', function () {
            document.body.scrollTop = document.documentElement.scrollTop = 0;
        });
    });
    // Flash message set & get
    /**
     * @ngdoc service
     * @name App.service:Flash
     * @module App
     * @description
     * Flash is a factory service used to set and get the flash messages
     * @param {string} Flash The name of the factory service
     * @returns {string} New flash message.
     */
    app.factory('Flash', ['$rootScope', 'growl', function ($rootScope, growl) {
        return {
            set: function (message, type, isStateChange) {
                if (type === 'success') {
                    growl.success(message);
                } else if (type === 'error') {
                    growl.error(message);
                    /*if (isStateChange === true) {
                     growl.error(message, {
                     ttl: -1
                     });
                     } else {
                     growl.error(message);
                     }*/
                } else if (type === 'info') {
                    growl.info(message);
                } else if (type === 'Warning') {
                    growl.warning(message);
                }
            }
        };
    }]);
    //Header
    /**
     * @ngdoc directive
     * @name App.directive:header
     * @module App
     * @scope
     * @restrict A
     *
     * @description
     * This directive used to define the header section.
     *
     * @param {string} header directive name
     *
     */
    app.directive('header', function () {
        /** @type {function} */
        var linker = function (scope, element, attrs) {
            // do DOM Manipulation hereafter
        };
        return {
            restrict: 'A',
            templateUrl: 'Common/header.tpl.html',
            link: linker,
            controller: 'HeaderController as model',
            scope: {
                header: '=',
                currency: '@',
                defaultcurrency: '@'
            }
        };
    });
    // Footer
    /**
     * @ngdoc directive
     * @name App.directive:footer
     * @module App
     * @scope
     * @restrict A
     *
     * @description
     * This directive used to define the footer section.
     *
     * @param {string}  footer  directive name
     *
     */
    app.directive('footer', function () {
        var linker = function (scope, element, attrs) {
            // do DOM Manipulation hereafter	
        };
        return {
            restrict: 'A',
            templateUrl: 'Common/footer.tpl.html',
            link: linker,
            controller: 'FooterController as model',
            scope: {
                footer: '='
            }
        };
    });
    /**
     * @ngdoc filter
     * @name App.filter:html
     * @description
     * It returns the filtered html data.
     */
    app.filter('html', function ($sce) {
        return function (val) {
            return $sce.trustAsHtml(val);
        };
    });
    /**
     * @ngdoc service
     * @name App.service.ResolveService
     * @module App
     * @description
     * It maintains the authentication services.
     */
    app.service('ResolveService', function ($auth, $rootScope, GENERAL_CONFIG, AuthFactory, $q, $location, $state, Flash, $filter) {
        var promiseSettings;
        var promiseAuth;
        var deferred = $q.defer();
        if ($auth.isAuthenticated() && $rootScope.auth === undefined) {
            promiseAuth = AuthFactory.fetch().$promise.then(function (user) {
                if(!user.is_active || !user.is_email_confirmed) {
                    delete $rootScope.auth;
                    localStorage.removeItem('userRole');
                    localStorage.removeItem('userToken');
                    $state.go('home');
                } else {
                    $rootScope.auth = user;
                }
            }, function(error) {
                Flash.set($filter("translate")("User not found"), 'error', false);
                delete $rootScope.auth;
                localStorage.removeItem('userRole');
                localStorage.removeItem('userToken');
                $state.go('home');
            });
        } else {
            promiseAuth = true;
        }
        if ($rootScope.is_fresh_call) {
            if (angular.isUndefined($rootScope.settings)) {
                $rootScope.settings = {};
            }
            if (angular.isUndefined($rootScope.currencies)) {
                $rootScope.currencies = [];
            }
            promiseSettings = $.get(GENERAL_CONFIG.api_url + '/settings?type=public_settings', function (response) {
                if (response.settings.original) {
                    $.each(response.settings.original, function (i, settingData) {
                        $rootScope.settings[settingData.name] = settingData.value;
                    });
                }
                if (response.currencies) {
                    $.each(response.currencies, function (i, currencyData) {
                        $rootScope.currencies.push(currencyData);
                    });
                }
                angular.forEach($rootScope.currencies, function (data) {
                    if (data !== null && data.code !== undefined) {
                        if (data.code == response.default_currency_code) {
                            $rootScope.default_currency = data;
                        }
                    }
                });
            });
        } else {
            promiseSettings = true;
        }		
        return {
            promiseAuth: promiseAuth,
            promiseSettings: promiseSettings
        };
    });
    /**
     * @ngdoc function
     * @name App.function:growlProvider
     * @module App
     * @description
     * Automatic closing of notifications (timeout, ttl)
     */
    app.config(['growlProvider', function (growlProvider) {
        growlProvider.onlyUniqueMessages(true);
        growlProvider.globalTimeToLive(5000);
        growlProvider.globalPosition('top-center');
        growlProvider.globalDisableCountDown(true);
        //growlProvider.globalEnableHtml(true);
    }]);
    /**
     * @ngdoc function
     * @name App.function:cfpLoadingBarProvider
     * @module App
     * @description
     * Loading bar display while page refresh.
     */
    app.config(function (cfpLoadingBarProvider) {
        // true is the default, but I left this here as an example:
        cfpLoadingBarProvider.includeSpinner = false;
    });
}(angular.module("BookorRent", [
    'BookorRent.home',
    'BookorRent.Constant',
    'BookorRent.user',
    'BookorRent.Wallets',
    'templates-app',
    'ui.router.state',
    'ui.router',
    'ui.bootstrap',
    'ngSanitize',
    'ngAnimate',
    'angular-growl',
    'pascalprecht.translate',
    'ngCookies',
    'BookorRent.common',
    'BookorRent.Messages',
    'BookorRent.Transactions',
    'chieffancypants.loadingBar',
    'angularMoment'
])));

