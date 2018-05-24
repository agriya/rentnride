(function (module) {
    /**
     * @ngdoc controller
     * @name Vehicles.controller:VehicleSearchController
     * @description
     * This is VehicleSearchController having the methods init(), setMetaData(), and it defines the vehicle search related funtions.
     **/
    module.controller('VehicleSearchController', function ($state, $scope, $http, Flash, $filter, AuthFactory, $rootScope, $location, VehicleSearchFactory, CounterLocationFactory) {
        $scope.vehicle = {};
        /**
         * @ngdoc method
         * @name setMetaData
         * @methodOf Vehicles.controller:VehicleSearchController
         * @description
         * This method will set the meta data dynamically by using the angular.element.
         * @returns {Element} New meta data element.
         **/
        $scope.setMetaData = function () {
            var pageTitle = $filter("translate")("Vehicle Search");
            var fullUrl = $location.absUrl();
            var appUrl = $rootScope.settings['scheme_name'] + ":/" + $location.url();
            angular.element('html head meta[property="og:title"], html head meta[name="twitter:title"]').attr("content", $rootScope.settings['site.name'] + " | " + pageTitle);
            angular.element('meta[property="al:ios:url"], meta[property="al:ipad:url"], meta[property="al:android:url"], meta[property="al:windows_phone:url"], html head meta[name="twitter:app:url:iphone"], html head meta[name="twitter:app:url:ipad"], html head meta[name="twitter:app:url:googleplay"]').attr('content', appUrl);
            angular.element('meta[property="og:url"]').attr('content', fullUrl);
        };
        /**
         * @ngdoc method
         * @name counter_location
         * @methodOf Vehicles.controller:VehicleSearchController
         * @description
         * This method will get location list by type.
         * @param {integer} type Vehicle type.
         * @returns {Object} Location list.
         **/
        $scope.counter_location = function () {
            CounterLocationFactory.get().$promise.then(function (response) {
                $scope.locations = response.data;
            });
        };
        /**
         * @ngdoc method
         * @name init
         * @methodOf Vehicles.controller:VehicleSearchController
         * @description
         * This method will initialze the page. It returns the page title.
         **/
        $scope.init = function () {
            $scope.setMetaData();
            $scope.counter_location();
        };
        $scope.init();
        /**
         * Open Picup and drop off calendar
         * @param e
         * @param date
         */
        $scope.openPickupCalendar = function (e, date) {
            $scope.open_pickup[date] = true;
        };
        $scope.openDropCalendar = function (e, date) {
            $scope.open_drop[date] = true;
        };

        $scope.open_pickup = {
            date: false
        };
        $scope.open_drop = {
            date: false
        };
        $scope.SearchSubmit = function ($valid) {
            if ($valid) {
                localStorage.setItem('searchValue', JSON.stringify($scope.vehicle));
                $state.go('vehicle_list');
            }
        };
    });
}(angular.module("BookorRent.Vehicles")));

