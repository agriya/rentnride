/**
 * bookorrent - v0.0.1 - 2016-04-22
 *
 * Copyright (c) 2016 Agriya
 */
/**
 * @ngdoc object
 * @name Translations
 * @description
 *
 * This is the module for translations. It contains the translations functionalities.
 *
 * @param {string} Translations name of the module
 * @param {!Array.<string>=} dependencies If specified then new module is being created. If unspecified then the module is being retrieved for further configuration.
 *
 *         [
 *            'ngResource',
 *            'pascalprecht.translate',
 *            'tmh.dynamicLocale',
 *            'ngSanitize',
 *            'ngCookies',
 *        ]
 * @returns {BookorRent.Translations} new BookorRent.Translations module.
 **/
(function (module) {
}(angular.module('BookorRent.Translations', [
    'ngResource',
    'pascalprecht.translate',
    'tmh.dynamicLocale',
    'ngSanitize',
    'ngCookies'
])));
(function (module) {
    module.config(function (tmhDynamicLocaleProvider) {
        tmhDynamicLocaleProvider.localeLocationPattern('assets/js/angular-i18n/angular-locale_{{locale}}.js');
        tmhDynamicLocaleProvider.localeLocationPattern('assets/js/moment/locale/{{locale}}.js');
    });
    /**
     * @ngdoc service
     * @name Translations.LanguageList
     * @function
     * @description
     * LanguageList is used to list the languages.
     * @param {string} LanguageList The name of the factory service
     * @param {function()} function It used to list the languages.
     * @param {string} url Base url accessed in GENERAL_CONFIG.
     * @returns {object} The service return language list.
     */
    module.service('LanguageList', function ($sce, $rootScope, $q, GENERAL_CONFIG) {
        promise = $.get(GENERAL_CONFIG.api_url + '/languages?filter=active&sort=name&sortby=asc', function (response) {
        });
        return {
            promise: promise
        };
    });
    /**
     * @ngdoc service
     * @name Translations.LocaleService
     * @function
     * @description
     * LocaleService is used to maintains the locale service of the languages.
     * @param {string} LocaleService The name of the factory service
     * @param {function()} function It used to maintains the locale service of the languages.
     * @param {string} url Base url accessed in GENERAL_CONFIG.
     */
    module.service('LocaleService', function ($translate, $rootScope, tmhDynamicLocale, GENERAL_CONFIG, LanguageList, ResolveService, $translateLocalStorage, amMoment) {
        'use strict';
        var localesObj;
        var loadedlocalesObj = {};
        var _LOCALES_DISPLAY_NAMES = [];
        var _LOCALES;
        var promiseLanguages = LanguageList.promise;
        promiseLanguages.then(function (response) {
            $.each(response.data, function (i, data) {
                loadedlocalesObj[data.iso2] = data.name;
            });
            localesObj = loadedlocalesObj;
            // locales and locales display names
            _LOCALES = Object.keys(localesObj);
            if (!_LOCALES || _LOCALES.length === 0) {
                console.error('There are no _LOCALES provided');
            }
            _LOCALES.forEach(function (locale) {
                _LOCALES_DISPLAY_NAMES.push(localesObj[locale]);
            });
        });
        // STORING CURRENT LOCALE
        var currentLocale = $translate.preferredLanguage();
        if ($translate.use() !== undefined) {
            currentLocale = $translate.use();
        } else if ($translateLocalStorage.get('NG_TRANSLATE_LANG_KEY') !== undefined && $translateLocalStorage.get('NG_TRANSLATE_LANG_KEY') !== null) {
            currentLocale = $translateLocalStorage.get('NG_TRANSLATE_LANG_KEY');
        }
        // METHODS
        var checkLocaleIsValid = function (locale) {
            return _LOCALES.indexOf(locale) !== -1;
        };
        var setLocale = function (locale) {
            if (!checkLocaleIsValid(locale)) {
                console.error('Locale name "' + locale + '" is invalid');
                return;
            }
            // updating current locale
            currentLocale = locale;
            // asking angular-translate to load and apply proper translations
            $translate.use(locale);
        };
        // EVENTS
        // on successful applying translations by angular-translate
        $rootScope.$on('$translateChangeSuccess', function (event, data) {
            amMoment.changeLocale(data.language);
            document.documentElement.setAttribute('lang', data.language); // sets "lang" attribute to html
            // asking angular-dynamic-locale to load and apply proper AngularJS $locale setting
			var dyn_locale = data.language.toLowerCase().replace(/_/g, '-');
			if(dyn_locale == 'en'){
				dyn_locale = 'en-au';
			}
            tmhDynamicLocale.set(dyn_locale);
        });
        return {
            getLocaleName: function(locale) {
                var lang_code = '';
                angular.forEach(localesObj, function(value, key){
                      if(value == locale) {
                          lang_code = key;
                      }
                  });
                return lang_code;
            },
            getLocaleDisplayName: function () {
                return localesObj[currentLocale];
            },
            setLocaleByDisplayName: function (localeDisplayName) {
                setLocale(
                    _LOCALES[
                        _LOCALES_DISPLAY_NAMES.indexOf(localeDisplayName) // get locale index
                        ]
                );
            },
            getLocalesDisplayNames: function () {
                return _LOCALES_DISPLAY_NAMES;
            }
        };
    });
    /**
     * @ngdoc directive
     * @name Translations.directive:ngTranslateLanguageSelect
     * @module Translations
     * @scope
     * This directive used to load translated languages.
     * @restrict A
     * @description
     * This directive used to load translated languages.
     */
    module.directive('ngTranslateLanguageSelect', function (LocaleService, LanguageList) {
        'use strict';
        return {
            restrict: 'A',
            replace: true,
            templateUrl: 'Plugins/Translations/language_translate.tpl.html',
            controller: function ($scope, $rootScope, $timeout, LanguageList, amMoment) {
                var promiseSettings = LanguageList.promise;
                promiseSettings.then(function (response) {
                    $scope.currentLocaleDisplayName = LocaleService.getLocaleDisplayName();
                    $scope.localesDisplayNames = LocaleService.getLocalesDisplayNames();
                    $scope.visible = $scope.localesDisplayNames && $scope.localesDisplayNames.length > 1;
                });
                $scope.changeLanguage = function (locale) {
                    var code = LocaleService.getLocaleName(locale);
                    amMoment.changeLocale(code);
                    LocaleService.setLocaleByDisplayName(locale);
                };
            }
        };
    });
}(angular.module("BookorRent.Translations")));
