var lLAsyncSync = function (libs, cb) {
    var counter = 0;
    var loadPart1, loadPart2, loaderTemplate;

    loaderTemplate = function (funcName) {
        if (counter < libs.length) {
            var lib = libs[counter];
            $.getScript(lib, function () {
                counter++;
                if (counter == libs.length) {
                    // Angular trigger after all js load
                    angular.bootstrap('html', ['BookorRent']);
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
    libraries.push('vendor/moment/min/moment-with-locales.min.js');
    libraries.push('vendor/angular/angular.js');
    libraries.push('vendor/angular-moment/angular-moment.js');
    libraries.push('vendor/angular-sanitize/angular-sanitize.js');
    libraries.push('vendor/angular-resource/angular-resource.js');
    libraries.push('vendor/angular-bootstrap/ui-bootstrap-tpls.min.js');
    libraries.push('vendor/ng-file-upload-shim/angular-file-upload-shim.min.js');
    libraries.push('vendor/ng-file-upload/ng-file-upload.min.js');
    libraries.push('vendor/angular-ui-router/release/angular-ui-router.js');
    libraries.push('vendor/angular-animate/angular-animate.js');
    libraries.push('vendor/angular-translate/angular-translate.min.js');
    libraries.push('vendor/satellizer/satellizer.js');
    libraries.push('vendor/angular-growl-v2/build/angular-growl.js');
    libraries.push('vendor/angular-translate-loader-static-files/angular-translate-loader-static-files.min.js');
    libraries.push('vendor/angular-dynamic-locale/tmhDynamicLocale.min.js');
    libraries.push('vendor/angular-translate-storage-cookie/angular-translate-storage-cookie.js');
    libraries.push('vendor/angular-translate-handler-log/angular-translate-handler-log.js');
    libraries.push('vendor/angular-translate-storage-local/angular-translate-storage-local.min.js');
    libraries.push('vendor/angular-cookies/angular-cookies.js');
    libraries.push('vendor/angular-slugify/angular-slugify.js');
    libraries.push('vendor/bootstrap/dist/js/bootstrap.min.js');
    libraries.push('vendor/angulartics/dist/angulartics.min.js');
    libraries.push('vendor/angulartics-google-analytics/dist/angulartics-google-analytics.min.js');
    libraries.push('vendor/angulartics-facebook-pixel/dist/angulartics-facebook-pixel.min.js');
    libraries.push('vendor/angular-recaptcha/release/angular-recaptcha.min.js');
    libraries.push('vendor/angular-credit-cards/release/angular-credit-cards.js');
    libraries.push('vendor/angular-loading-bar/build/loading-bar.min.js');
    libraries.push('vendor/bootstrap-ui-datetime-picker/dist/datetime-picker.js');
    libraries.push('vendor/accounting/accounting.js');
    libraries.push('vendor/angularjs-slider/dist/rzslider.js');
    libraries.push('vendor/angular-google-places-autocomplete/src/autocomplete.js');
    libraries.push('vendor/angular-bootstrap-calendar/dist/js/angular-bootstrap-calendar-tpls.min.js');
    libraries.push('vendor/angularjs-socialshare/dist/angular-socialshare.js');
    libraries.push('client/src/app/App.js');
    libraries.push('client/src/app/Common/Common.module.js');
    libraries.push('client/src/app/Home/Home.module.js');
    libraries.push('client/src/app/User/User.module.js');
    libraries.push('client/src/app/Wallets/Wallet.module.js');
    libraries.push('client/src/app/Message/Message.module.js');
    libraries.push('client/src/app/Transactions/Transaction.module.js');
    libraries.push('client/src/app/Common/Footer.js');
    libraries.push('client/src/app/Common/Header.js');
    libraries.push('client/src/app/Constant.js');
    libraries.push('client/src/app/Home/Home.js');
    libraries.push('client/src/app/User/ChangePassword.js');
    libraries.push('client/src/app/User/Dashboard.js');
    libraries.push('client/src/app/User/ForgetPassword.js');
    libraries.push('client/src/app/User/Login.js');
    libraries.push('client/src/app/User/Register.js');
    libraries.push('client/src/app/User/User.js');
    libraries.push('client/src/app/User/UserActivate.js');
    libraries.push('client/src/app/User/UserProfile.js');
    libraries.push('client/src/app/User/UsersService.js');
    libraries.push('client/src/app/Wallets/Wallet.js');
    libraries.push('client/src/app/Wallets/WalletService.js');
    libraries.push('client/src/app/Transactions/Transaction.js');
    libraries.push('client/src/app/Transactions/TransactionService.js');
    libraries.push('client/src/app/Message/Message.js');
    libraries.push('client/src/app/Message/MessageService.js');
    libraries.push('api/assets/js/plugins.js');
    libraries.push('assets/js/templates-app.js');

    lLAsyncSync(libraries, function () {
        // Creates the Locale component
    });

})(jQuery, window, document);	