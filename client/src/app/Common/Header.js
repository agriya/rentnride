(function (module) {
    /**
     * @ngdoc controller
     * @name Common.controller:HeaderController
     * @description
     *
     * This is HeaderController having the methods init(), setMetaData() and logout() and it defines the header section of all the pages.
     *
     * The header will change according to the user login. If the user logged in, header conotains the user settings, and logout options.
     *
     * If not logged in, signin and signup options will be available in header.
     **/
    module.controller('HeaderController', function ($state, $scope, $rootScope, $auth, ResolveService, AuthFactory, ConstSocialLogin, ConstThumb, $location, $filter) {
        var model = this;
        /**
         * @ngdoc method
         * @name setMetaData
         * @methodOf Common.controller:HeaderController
         * @description
         *
         * This method will set the meta data dynamically by using the angular.element function
         **/
        model.setMetaData = function () {
            angular.element('html head meta[name=description]').attr("content", $rootScope.settings['meta.description']);
            angular.element('html head meta[name=keywords]').attr("content", $rootScope.settings['meta.keywords']);
            angular.element('html head meta[property="og:description"], html head meta[name="twitter:description"]').attr("content", $rootScope.settings['meta.description']);
            angular.element('html head meta[name="twitter:creator"]').attr('content', $rootScope.settings['twitter.creator']);
            angular.element('html head meta[name="twitter:site"]').attr('content', $rootScope.settings['twitter.site']);
            angular.element('html head meta[name="twitter:card"]').attr('content', $rootScope.settings['twitter.card']);
            angular.element('html head meta[name="twitter:app:id:iphone"]').attr('content', $rootScope.settings['ios_app_store_id']);
            angular.element('html head meta[name="twitter:app:id:ipad"]').attr('content', $rootScope.settings['ipad_app_store_id']);
            angular.element('html head meta[property="al:ios:app_store_id"]').attr("content", $rootScope.settings['ios_app_store_id']);
            angular.element('html head meta[property="al:ipad:app_store_id"]').attr("content", $rootScope.settings['ipad_app_store_id']);
            angular.element('html head meta[property="al:android:package"]').attr("content", $rootScope.settings['android_app_store_id']);
            angular.element('html head meta[property="al:windows_phone:app_id"]').attr("content", $rootScope.settings['windows_phone_app_id']);
            angular.element('html head meta[property="og:image"], html head meta[name="twitter:image:src"]').attr("content", '');
            angular.element('html head meta[property="og:type"]').attr("content", '');
        };
        /**
         * @ngdoc method
         * @name init
         * @methodOf Common.controller:HeaderController
         * @description
         * This method will initialze the page and it initializes the settings for header.
         *
         **/
        model.init = function () {
            var promise = ResolveService.promiseAuth;
            var promiseSettings = ResolveService.promiseSettings;
            promiseSettings.then(function (data) {
                model.setMetaData();
            });
            model.ConstSocialLogin = ConstSocialLogin;
            model.thumb = ConstThumb.user;
            AuthFactory.fetch({}).$promise
                .then(function (response) {
                    $rootScope.vehicle_company = response.vehicle_company;
                });
            $scope.$watch(function() {
                if($rootScope.settings['site.name'] !== undefined) {
                    if($location.path() == '/how-it-works') {
                        $rootScope.pageTitle = $rootScope.settings['site.name'] + " | " + $filter("translate")("How it works");
                    }
                }
            });
        };
        model.init();
        /**
         * @ngdoc method
         * @name isAuthenticated
         * @methodOf Common.controller:HeaderController
         * @description
         * This mehtod is use to check whether the user logged in or not.
         * According to the returned value the site header will be changed.
         * @returns {function()} It returns the $auth.isAuthenticated() function
         **/
        model.isAuthenticated = function () {
            return $auth.isAuthenticated();
        };
        /**
         * @ngdoc method
         * @name logout
         * @methodOf Common.controller:HeaderController
         * @description
         * This method will be used in logging out the user.
         *
         **/
        model.logout = function () {
            delete $rootScope.auth;
            localStorage.removeItem('userRole');
            localStorage.removeItem('auth');
            localStorage.removeItem('enabled_plugins');
            $auth.logout();
        };


    });
}(angular.module("BookorRent.common")));
