(function (module) {
    /**
     * @ngdoc controller
     * @name Vehicles.controller:MyVehiclesController
     * @description
     * This is MyVehiclesController having the methods init(), setMetaData(). It controls the functionality of my vehicle.
     **/
    module.controller('MyVehiclesController', function ($scope, $rootScope, $filter, Flash, $state, $location, MyVehiclesFactory, $uibModal) {
        model = this;
        model.maxSize = 5;
        /**
         * @ngdoc method
         * @name setMetaData
         * @methodOf Vehicles.controller:MyVehiclesController
         * @description
         * This method will set the meta data dynamically by using the angular.element.
         * @returns {Element} New meta data element.
         **/
        model.setMetaData = function () {
            var pageTitle = $filter("translate")("My Vehicles");
            var fullUrl = $location.absUrl();
            var appUrl = $rootScope.settings['scheme_name'] + ":/" + $location.url();
            angular.element('html head meta[property="og:title"], html head meta[name="twitter:title"]').attr("content", $rootScope.settings['site.name'] + " | " + pageTitle);
            angular.element('meta[property="al:ios:url"], meta[property="al:ipad:url"], meta[property="al:android:url"], meta[property="al:windows_phone:url"], html head meta[name="twitter:app:url:iphone"], html head meta[name="twitter:app:url:ipad"], html head meta[name="twitter:app:url:googleplay"]').attr('content', appUrl);
            angular.element('meta[property="og:url"]').attr('content', fullUrl);
        };
        /**
         * @ngdoc method
         * @name init
         * @methodOf Vehicles.controller:MyVehiclesController
         * @description
         * This method will initialze the page. It returns the page title.
         **/
        model.init = function () {
            model.setMetaData();
            model.currentPage = (model.currentPage !== undefined) ? parseInt(model.currentPage) : 1;
            $rootScope.pageTitle = $rootScope.settings['site.name'] + " | " + $filter("translate")("My Vehicles");
            model.getMyVehicleList();
            //Vehicle rating
            $scope.maxRatings = [];
            $scope.maxRating = 5;
            for (var i = 0; i < $scope.maxRating; i++) {
                $scope.maxRatings.push(i);
            }
        };
        /**
         * @ngdoc method
         * @name getMyVehicleList
         * @methodOf Vehicles.controller:MyVehiclesController
         * @description
         * This method is used to get my vehicle list details.
         * @param {Array} user_id Logged user identifier.
         * @returns {Array} Success or failure message.
         */
        model.getMyVehicleList = function () {
            MyVehiclesFactory.get({'user_id': $rootScope.auth.id, 'page': model.currentPage}).$promise.then(function (response) {
                model.vehicles = response.data;
                angular.forEach(model.vehicles, function (value, key) {
                    value.roundedRating = value.feedback_rating | 0;
                });
                model._metadata = response.meta.pagination;
            });
        };
        model.init();
        /**
         * @ngdoc method
         * @name paginate
         * @methodOf Vehicles.controller:MyVehiclesController
         * @description
         * This method will be load pagination the pages.
         **/
        model.paginate = function (pageno) {
            model.currentPage = parseInt(model.currentPage);
            model.getMyVehicleList();
        };
        model.maintenance = function (vehicle_id) {
            $state.go("maintenanceVehicles", {'vehicle_id': vehicle_id});
        };
        model.payNow = function (vehicle_id) {
            $state.go("vehiclePaynow", {'vehicle_id': vehicle_id});
        }
        /**
         * @ngdoc method
         * @name modalOpen
         * @methodOf Vehicles.controller:MyVehiclesController
         * @description
         * This method will initialze the page. It pen the modal with vehicle feedbacks.
         **/
        $scope.modalOpen = function (size, vehicle_id) {
            var modalInstance = $uibModal.open({
                templateUrl: 'Plugins/Vehicles/vehicle_feedback_modal.tpl.html',
                controller: 'VehicleModalController',
                size: size,
                resolve: {
                    vehicle_id: function () {
                        return vehicle_id;
                    }
                }
            });
        };
    });
}(angular.module("BookorRent.Vehicles")));
