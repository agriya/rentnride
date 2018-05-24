(function (module) {
    module.config(function ($authProvider, GENERAL_CONFIG) {
        var ResolveServiceData = {
            'ResolveServiceData': function (ResolveService, $q) {
                return $q.all({
                    AuthServiceData: ResolveService.promiseAuth,
                    SettingServiceData: ResolveService.promiseSettings
                });
            }
        };
        var url = GENERAL_CONFIG.api_url + '/providers';
        var params = {};
        $.get(url, params, function (response) {
            var credentials = {};
            var url = GENERAL_CONFIG.api_url + '/auth/';
            if (location.hostname == 'localhost') {
                url = window.location.protocol + '//' + window.location.host + url;
            }
            angular.forEach(response.data, function (res, i) {
                credentials = {
                    clientId: res.api_key,
                    redirectUri: url + angular.lowercase(res.name),
                    url: GENERAL_CONFIG.api_url + '/auth/' + angular.lowercase(res.name)
                };
                if (res.name === 'Facebook') {
                    $authProvider.facebook(credentials);
                }
                if (res.name === 'Google') {
                    $authProvider.google(credentials);
                }
                if (res.name === 'Twitter') {
                    $authProvider.twitter(credentials);
                }
                if (res.name === 'Github') {
                    credentials = {
                        redirectUri: url + 'github',
                        url: url + 'github',
                        clientId: res.api_key
                    };
                    $authProvider.github(credentials);
                }
            });
        });
    });
    /**
     * @ngdoc directive
     * @name SocialLogins.directive:socialLogin
     * @module SocialLogins
     * @scope
     * This directive used to load the social login page url link.
     * @restrict E
     * @description
     * This directive used to load the social login page template.
     */
    module.directive('socialLogin', function () {
        var linker = function (scope, element, attrs) {
            // do DOM Manipulation here
        };
        return {
            restrict: 'E',
            templateUrl: 'Plugins/SocialLogins/social_login.tpl.html',
            link: linker,
            controller: 'SocialLoginController as model',
            bindToController: true,
            scope: {
                pageType: '@pageType'
            }
        };
    });
    /**
     * @ngdoc controller
     * @name SocialLogins.controller:SocialLoginEmailController
     * @description
     * This is SocialLoginEmailController having the methods init(), setMetaData(), loginnow(). It controls the email related functions.
     **/
    module.controller('SocialLoginEmailController', function ($state, ProvidersFactory, $auth, $scope, Flash, SocialLoginFactory, $rootScope, $filter, $location, AuthFactory) {
        /**
         * @ngdoc method
         * @name setMetaData
         * @methodOf SocialLogins.controller:SocialLoginEmailController
         * @description
         * This method will set the meta data's dynamically by using the angular.element
         * @returns {Element} New meta data element.
         **/
        $scope.setMetaData = function () {
            var pageTitle = $filter("translate")("Get Social API Email");
            var fullUrl = $location.absUrl();
            var appUrl = $rootScope.settings['scheme_name'] + ":/" + $location.url();
            angular.element('html head meta[property="og:title"], html head meta[name="twitter:title"]').attr("content", $rootScope.settings['site.name'] + " | " + pageTitle);
            angular.element('meta[property="al:ios:url"], meta[property="al:ipad:url"], meta[property="al:android:url"], meta[property="al:windows_phone:url"], html head meta[name="twitter:app:url:iphone"], html head meta[name="twitter:app:url:ipad"], html head meta[name="twitter:app:url:googleplay"]').attr('content', appUrl);
            angular.element('meta[property="og:url"]').attr('content', fullUrl);
        };
        /**
         * @ngdoc method
         * @name init
         * @methodOf SocialLogins.controller:SocialLoginEmailController
         * @description
         * This method will initialize the page. It returns the page title.
         **/
        $scope.init = function () {
            if (!$rootScope.thrid_party_profile) {
                Flash.set($filter("translate")("Unable to get provider info, please try again."), 'error', false);
                $state.go('login');
            }
            // $scope.setMetaData();
            // $rootScope.pageTitle = $rootScope.settings['site.name'] + " | " + $filter("translate")("Get Social API Email");
        };
        $scope.init();
        /**
         * @ngdoc method
         * @name loginnow
         * @methodOf SocialLogins.controller:SocialLoginEmailController
         * @description
         * This method will be used in authenticating and logging in the user.
         * @param {integer} user User details.
         * @returns {Array} Success or failure message.
         **/
        $scope.loginnow = function (user) {
            $scope.user = user;
            $scope.user.thrid_party_profile = $rootScope.thrid_party_profile;
            SocialLoginFactory.login($scope.user, function (response) {
                if (response.userToken) {
                    $auth.setToken(response.userToken);
                    localStorage.userRole = response.role;
                    AuthFactory.fetch().$promise.then(function (user) {
                        $rootScope.auth = user;
                        $state.go('dashboard');
                    });
                } else {
                    var errorMessage;
                    if (response.error.code === 1) {
                        errorMessage = $filter("translate")("Already registered email");
                    } else {
                        errorMessage = response.error.message;
                    }
                    Flash.set(errorMessage, 'error', false);
                }
            });
        };
    });
    /**
     * @ngdoc controller
     * @name SocialLogins.controller:SocialLoginController
     * @description
     * This is SocialLoginController having the methods init(), setMetaData() and it controls the login functionalities using social websites.
     **/
    module.controller('SocialLoginController', function ($state, ProvidersFactory, $auth, $scope, $rootScope, $location, AuthFactory, $filter, Flash) {
        var model = this;
        /**
         * @ngdoc method
         * @name setMetaData
         * @methodOf SocialLogins.controller:SocialLoginController
         * @description
         * This method will set the meta data's dynamically by using the angular.element
         * @returns {Element} New meta data element.
         **/
        model.setMetaData = function () {            
            var fullUrl = $location.absUrl();
            var appUrl = $rootScope.settings['scheme_name'] + ":/" + $location.url();
            angular.element('html head meta[property="og:title"], html head meta[name="twitter:title"]').attr("content", $rootScope.settings['site.name'] + " | " + $rootScope.pageTitle);
            angular.element('meta[property="al:ios:url"], meta[property="al:ipad:url"], meta[property="al:android:url"], meta[property="al:windows_phone:url"], html head meta[name="twitter:app:url:iphone"], html head meta[name="twitter:app:url:ipad"], html head meta[name="twitter:app:url:googleplay"]').attr('content', appUrl);
            angular.element('meta[property="og:url"]').attr('content', fullUrl);
        };
        /**
         * @ngdoc method
         * @name init
         * @methodOf SocialLogins.controller:SocialLoginController
         * @description
         * This method will initialize the page. It returns the page title.
         **/
        model.init = function () {
            model.setMetaData();
            ProvidersFactory.get({'filter': 'active', 'sortby': 'asc', 'sort': 'display_order'}).$promise
                .then(function (response) {
                    model.sociallogin = response;
                });
        };
        model.init();
        $scope.contentInIframe = false;
        if (self !== top) {
            $scope.contentInIframe = true;
        }
        model.sociallogin = {};
        /**
         * @ngdoc method
         * @name authenticate
         * @methodOf SocialLogins.controller:SocialLoginController
         * @description
         * This method will be used in authenticating the user.
         * @returns {Array} Success or failure message.
         **/
        model.authenticate = function (provider) {
            $auth.authenticate(provider).then(function (response) {
                if (response.data.userToken) {
                    localStorage.userRole = response.data.role;
                    AuthFactory.fetch().$promise.then(function (user) {
                        $rootScope.auth = user;
                        $state.go('dashboard', {});
                    });
                } else if (response.data.thrid_party_profile) {
                    $rootScope.thrid_party_profile = response.data.thrid_party_profile;
                    $state.go('socialLoginEmail');
                }
            }).catch(function (error) {
            });
        };
    });
})(angular.module('BookorRent.SocialLogins'));
