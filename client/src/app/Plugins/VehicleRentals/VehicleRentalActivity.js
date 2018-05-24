(function (module) {
    /**
     * @ngdoc controller
     * @name VehicleRentals.controller:VehicleRentalActivityController
     * @description
     * This is VehicleRentalActivityController having the methods init(), setMetaData(), and it defines the item list related funtions.
     **/
    module.controller('VehicleRentalActivityController', function ($state, $scope, $http, Flash, $filter, $rootScope, $location, VehicleRentalFactory, VehicleRentalMessageFactory, SavePrivateNoteFactory, ConstItemUserStatus, AuthFactory, $uibModal, moment) {
        var model = this;
        $scope.vehicle_dispute = {};
        $scope.vehicle_rental_id = $state.params.vehicle_rental_id ? $state.params.vehicle_rental_id : '';
        $scope.ConstItemUserStatus = ConstItemUserStatus;
        /**
         * @ngdoc method
         * @name setMetaData
         * @methodOf VehicleRentals.controller:VehicleRentalActivityController
         * @description
         * This method will set the meta data dynamically by using the angular.element.
         * @returns {Element} New meta data element.
         **/
        $scope.setMetaData = function () {
            var pageTitle = $filter("translate")("VehicleRental Activity");
            var fullUrl = $location.absUrl();
            var appUrl = $rootScope.settings['scheme_name'] + ":/" + $location.url();
            angular.element('html head meta[property="og:title"], html head meta[name="twitter:title"]').attr("content", $rootScope.settings['site.name'] + " | " + pageTitle);
            angular.element('meta[property="al:ios:url"], meta[property="al:ipad:url"], meta[property="al:android:url"], meta[property="al:windows_phone:url"], html head meta[name="twitter:app:url:iphone"], html head meta[name="twitter:app:url:ipad"], html head meta[name="twitter:app:url:googleplay"]').attr('content', appUrl);
            angular.element('meta[property="og:url"]').attr('content', fullUrl);
        };
        /**
         * @ngdoc method
         * @name getVehicleRentalMessages
         * @methodOf VehicleRentals.controller:VehicleRentalActivityController
         * @description
         * This method will get the messages of booked item.
         * @param {integer} vehicle_rental_id Rental identifier.
         * @returns {Array} Success or failure message.
         */
        $scope.getVehicleRentalMessages = function () {
            VehicleRentalMessageFactory.get({'id': $scope.vehicle_rental_id}).$promise.then(function (response) {
                if (response.messages != undefined) {
                    $scope.messageDetails = response.messages.messages;
                }
            }, function (error) {
                Flash.set($filter("translate")("Invalid Request"), 'error', false);
                $state.go('home');
            });
        };
        /**
         * @ngdoc method
         * @name init
         * @methodOf VehicleRentals.controller:VehicleRentalActivityController
         * @description
         * This method will initialize the meta data and functionalities.
         **/
        $scope.init = function () {
            $scope.setMetaData();
            $scope.action = ($state.params.action !== undefined) ? $state.params.action : 'note';
            VehicleRentalFactory.get({'id': $scope.vehicle_rental_id}).$promise.then(function (response) {
                $scope.VehicleRentalDetails = response;
                $scope.vehicleDetails = response.item_userable;
                $scope.vehicleDetails.roundedRating = response.item_userable.feedback_rating;
                $rootScope.pageTitle = $rootScope.settings['site.name'] + " | " + $filter("translate")($scope.vehicleDetails.name+" : Activities");
            });
            AuthFactory.fetch().$promise.then(function (user) {
                $rootScope.auth = user;
            });
            $scope.getVehicleRentalMessages();
            //Vehicle rating
            $scope.maxRatings = [];
            $scope.maxRating = 5;
            for (var i = 0; i < $scope.maxRating; i++) {
                $scope.maxRatings.push(i);
            }
        };
        $scope.init();
        /**
         * @ngdoc method
         * @name PrivateNoteSubmit
         * @methodOf VehicleRentals.controller:VehicleRentalActivityController
         * @description
         * This method user to add private note.
         * @param {integer} vehicle_rental_id Rental identifier.
         * @returns {Array} Success or failure message.
         */
        $scope.PrivateNoteSubmit = function ($valid) {
            if ($valid) {
                SavePrivateNoteFactory.save({id: $scope.vehicle_rental_id, message: $scope.note}, function (response) {
                    Flash.set($filter("translate")("Private Note Added Successfully!"), 'success', true);
                    $state.reload();
                }, function (error) {
                    Flash.set($filter("translate")("Private Note Could not be added!"), 'error', false);
                });
            }
        };
        $scope.filterTab = function (id, action) {
            $state.go('activity', {vehicle_rental_id: id, action: action});
        };
        /**
         * @ngdoc method
         * @name modalOpen
         * @methodOf VehicleRentals.controller:VehicleRentalActivityController
         * @description
         * This method will initialze the page. It pen the modal with vehicle feedbacks.
         * @param {integer} vehicle_id Vehicle identifier.
         * @returns {html} Load new template.
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
}(angular.module("BookorRent.VehicleRentals")));
