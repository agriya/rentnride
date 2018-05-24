var lLAsyncSync = function (libs, cb) {
    var counter = 0;
    var loadPart1, loadPart2, loaderTemplate;

    loaderTemplate = function (funcName) {
        if (counter < libs.length) {
            var lib = libs[counter];
            $.getScript(lib, function () {
                counter++;
                if (counter == libs.length) {
                    $.get(admin_api_url + '/api/settings?type=public_settings').success(function (response) {
                        if (response) {
                            $.each(response.currencies, function (i, currencyData) {
                                if (response.default_currency_code == currencyData.code) {
                                    localStorage.setItem('site_currency', currencyData.symbol);
                                }
                            });
                            localStorage.setItem('settings', JSON.stringify(response.settings.original));
                            $.each(response.settings.original, function (i, settingData) {
                                if (settingData.name == "site.enabled_plugins") {
                                    localStorage.setItem('enabled_plugins', settingData.value);
                                }
                                if (settingData.name == "site.version") {
                                    localStorage.setItem('site_version', settingData.value);
                                }
                            });
                        }
                        // Angular trigger after all js load
                        angular.bootstrap('html', ['BookorRent']);
                    });
                }
                // Go next
                funcName();
            }).fail(function () {
                console.log("*********************", arguments);
                if (arguments[0].readyState === 0) {
                    console.error('It wasnt possible to load the script: ' + lib);
                } else {
                    console.error('A problem occurred while loading the script: ' + lib);
                    //script loaded but failed to parse
                    console.error(arguments[2].toString());
                    console.error(arguments);
                }
            });
        } else {
            if (cb) {
                cb();
            }
        }
    };

    loadPart1 = function () {
        loaderTemplate(loadPart2);
    };
    loadPart2 = function () {
        loaderTemplate(loadPart1);
    };

    // Start the iteration of the libraries
    if (libs && libs.length > 0) {
        loadPart1();
    } else if (cb) {
        console.error('No libraries to be loaded');
        cb();
    }
};
(function ($, window, document, undefined) {
    'use strict';
    var libraries = [];
    libraries.push('../assets/js/RentnRide-admin-0.0.1.js');
    lLAsyncSync(libraries, function () {
        // Creates the Locale component
    });

})(jQuery, window, document);	