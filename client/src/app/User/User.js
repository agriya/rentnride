(function (module) {
    /**
     * @ngdoc controller
     * @name User.controller:UserController
     * @description
     *
     * This is user controller having the methods setmMetaData, init, upload and user_profile.
     **/
    module.controller('UserController', function ($scope, $auth, $state, UsersFactory, $rootScope, $filter, UserActivateFactory, Flash, AuthFactory, $location, ConstSocialLogin ) {
        var model = this;
        model.maxSize = 5;
        /**
         * @ngdoc method
         * @name setMetaData
         * @methodOf User.controller:UserController
         * @description
         *
         * This method will set the meta data dynamically by using the angular.element function.
         *
         * It defines the angular elements
         **/
        model.setMetaData = function () {
            var pageTitle = $filter("translate")("User");
            var fullUrl = $location.absUrl();
            var appUrl = $rootScope.settings['scheme_name'] + ":/" + $location.url();
            angular.element('html head meta[property="og:title"], html head meta[name="twitter:title"]').attr("content", $rootScope.settings['site.name'] + " | " + pageTitle);
            angular.element('meta[property="al:ios:url"], meta[property="al:ipad:url"], meta[property="al:android:url"], meta[property="al:windows_phone:url"], html head meta[name="twitter:app:url:iphone"], html head meta[name="twitter:app:url:ipad"], html head meta[name="twitter:app:url:googleplay"]').attr('content', appUrl);
            angular.element('meta[property="og:url"]').attr('content', fullUrl);
        };
        /**
         * @ngdoc method
         * @name init
         * @methodOf User.controller:UserController
         * @description
         * This method will initialze the page. It returns the page title.
         *
         **/
        model.init = function () {
            model.setMetaData();
            $scope.currentPage = ($scope.currentPage !== undefined) ? parseInt($scope.currentPage) : 1;
            model.ConstSocialLogin = ConstSocialLogin;
            $rootScope.pageTitle = $rootScope.settings['site.name'] + " | " + $filter("translate")("User") + ":" + $state.params.username;
            UsersFactory.get({username: $state.params.username}).$promise.then(function (response) {
                model.user = response;
            });
        };
        model.init();

    });
}(angular.module("BookorRent.user")));