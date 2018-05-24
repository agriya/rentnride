(function (module) {
    /**
     * @ngdoc directive
     * @name Pages.directive:footerLinks
     * @scope
     * @restrict AE
     *
     * @description
     * footerLinks directive creates a footerLinks tag. We can use this as an element.
     *
     * @param {string} googleAnalytics Name of the directive
     *
     **/
    module.directive('footerLinks', function () {
        var linker = function (scope, element, attrs) {
            // do DOM Manipulation here
        };
        return {
            restrict: 'A',
            templateUrl: 'Plugins/Pages/page_links.tpl.html',
            link: linker,
            controller: 'PagesController as model',
            bindToController: true
        };
    });
    /**
     * @ngdoc controller
     * @name Pages.controller:PagesController
     * @description
     *
     * This is pages controller having the methods init(), setMetaData(). It controls the static pages.
     **/
    module.controller('PagesController', function ($scope, $http, $filter, $state, $rootScope, $location, PageFactory, $translate, $translateLocalStorage) {
        var model = this;
        /**
         * @ngdoc method
         * @name setMetaData
         * @methodOf Pages.controller:PagesController
         * @description
         *
         * This method will set the meta data dynamically by using the angular.element
         **/
        model.setMetaData = function (title) {
            var fullUrl = $location.absUrl();
            var appUrl = $rootScope.settings['scheme_name'] + ":/" + $location.url();
            angular.element('html head meta[property="og:title"], html head meta[name="twitter:title"]').attr("content", $rootScope.settings['site.name'] + " | " + title);
            angular.element('meta[property="al:ios:url"], meta[property="al:ipad:url"], meta[property="al:android:url"], meta[property="al:windows_phone:url"], html head meta[name="twitter:app:url:iphone"], html head meta[name="twitter:app:url:ipad"], html head meta[name="twitter:app:url:googleplay"]').attr('content', appUrl);
            angular.element('meta[property="og:url"]').attr('content', fullUrl);
        };
        /**
         * @ngdoc method
         * @name init
         * @methodOf Pages.controller:PagesController
         * @description
         * This method will initialze the page. It returns the page title
         *
         **/
        model.init = function () {
            var currentLocale = $translate.preferredLanguage();
            if ($translate.use() !== undefined) {
                currentLocale = $translate.use();
            } else if ($translateLocalStorage.get('NG_TRANSLATE_LANG_KEY') !== undefined || $translateLocalStorage.get('NG_TRANSLATE_LANG_KEY') !== null) {
                currentLocale = $translateLocalStorage.get('NG_TRANSLATE_LANG_KEY');
            }
            if ($state.params.slug !== undefined && $state.params.slug !== null) {
                PageFactory.get({slug: $state.params.slug, iso2: currentLocale}).$promise
                    .then(function (response) {
                        $scope.page = response;
                        model.setMetaData(response.title);
                        $rootScope.pageTitle = $rootScope.settings['site.name'] + " | " + response.title;
                    });
            }
        };
        model.init();
    });
}(angular.module("BookorRent.Pages")));
