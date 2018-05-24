(function (module) {
    /**
     * @ngdoc controller
     * @name Home.controller:HomeController
     * @description
     *
     * This is Home Controller having the methods init(), setMetaData() and it defines the index page.
     **/
    module.controller('HomeController', function ($filter, $rootScope, $location) {
        var model = this;
        /**
         * @ngdoc method
         * @name setMetaData
         * @methodOf Home.controller:HomeController
         * @description
         *
         * This method will set the meta data dynamically by using the method angular.element.
         **/
        model.setMetaData = function () {
            var pageTitle = $filter("translate")("Home");
            var fullUrl = $location.absUrl();
            var appUrl = $rootScope.settings['scheme_name'] + ":/" + $location.url();
            angular.element('html head meta[property="og:title"], html head meta[name="twitter:title"]').attr("content", $rootScope.settings['site.name'] + " | " + pageTitle);
            angular.element('meta[property="al:ios:url"], meta[property="al:ipad:url"], meta[property="al:android:url"], meta[property="al:windows_phone:url"], html head meta[name="twitter:app:url:iphone"], html head meta[name="twitter:app:url:ipad"], html head meta[name="twitter:app:url:googleplay"]').attr('content', appUrl);
            angular.element('meta[property="og:url"]').attr('content', fullUrl);
        };
        /**
         * @ngdoc method
         * @name init
         * @methodOf Home.controller:HomeController
         * @description
         * This method will initialze the page and meta data.
         *
         **/
        model.init = function () {
            model.setMetaData();
            $rootScope.pageTitle = $rootScope.settings['site.name'] + " | " + $filter("translate")("Home");
        };
        model.init();
    });

}(angular.module("BookorRent.home")));