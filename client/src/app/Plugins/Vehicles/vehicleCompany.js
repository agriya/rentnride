(function (module) {
    module.directive('vehicleCompany', function() {
       return {
           restrict : 'EA',
           templateUrl : 'Plugins/Vehicles/vehicleCompany.tpl.html',
           controller : "vehicleCompanyController"
       } ;
    });
    /**
     * @ngdoc controller
     * @name Vehicles.controller:vehicleCompanyController
     * @description
     * This is vehicleCompanyController having the methods init(), setMetaData(). It controls the functionality of vehicle company.
     **/
    module.controller('vehicleCompanyController', function ($scope, $rootScope, $filter, Flash, $state, $location, VehicleCompanyFactory, VehicleCompanyShowFactory, AuthFactory) {
        model = this;
        $scope.message = "";
        $scope.loader_is_disabled = true;
        $scope.form_is_active = false;
        $scope.is_reject_active = 0;
        /**
         * @ngdoc method
         * @name setMetaData
         * @methodOf Vehicles.controller:vehicleCompanyController
         * @description
         * This method will set the meta data dynamically by using the angular.element.
         * @returns {Element} New meta data element.
         **/
        model.setMetaData = function () {
            var pageTitle = $filter("translate")("Company");
            var fullUrl = $location.absUrl();
            var appUrl = $rootScope.settings['scheme_name'] + ":/" + $location.url();
            angular.element('html head meta[property="og:title"], html head meta[name="twitter:title"]').attr("content", $rootScope.settings['site.name'] + " | " + pageTitle);
            angular.element('meta[property="al:ios:url"], meta[property="al:ipad:url"], meta[property="al:android:url"], meta[property="al:windows_phone:url"], html head meta[name="twitter:app:url:iphone"], html head meta[name="twitter:app:url:ipad"], html head meta[name="twitter:app:url:googleplay"]').attr('content', appUrl);
            angular.element('meta[property="og:url"]').attr('content', fullUrl);
        };
        /**
         * @ngdoc method
         * @name init
         * @methodOf Vehicles.controller:vehicleCompanyController
         * @description
         * This method will initialze the page. It returns the page title.
         **/
        model.init = function () {
            model.setMetaData();
            $scope.emailErr = '';
            $rootScope.pageTitle = $rootScope.settings['site.name'] + " | " + $filter("translate")("Company");
            VehicleCompanyShowFactory.get().$promise.then(function (response) {
                $scope.loader_is_disabled = false;
                if (response) {
                    if (response.is_active == 0) {
                        $scope.message = $filter("translate")("Admin will approve your company details soon.");
                    } else if (response.is_active == 2) {
                        $scope.is_reject_active = response.is_active;
                        $scope.message = "Admin rejected your company details. Please contact ";
                    } else if (response.is_active == 1) {
                        $scope.form_is_active = true;
                        $scope.vehicleCompanies = response;
                        $scope.vehicleCompanies.full_address = response.address;
                        $scope.vehicleCompanies.address = response.address;
                    }
                } else {
                    $scope.form_is_active = true;
                }
            }, function (message) {
                $scope.form_is_active = true;
                $scope.loader_is_disabled = false;
            });
        };
        $scope.$on('g-places-autocomplete:select', function (event) {
            if (event.targetScope.model.formatted_address.indexOf(event.targetScope.model.name)) {
                $scope.vehicleCompanies.address = event.targetScope.model.name + ', ' + event.targetScope.model.formatted_address;
            } else {
                $scope.vehicleCompanies.address = event.targetScope.model.formatted_address;
            }
            $scope.vehicleCompanies.latitude = event.targetScope.model.geometry.location.lat();
            $scope.vehicleCompanies.longitude = event.targetScope.model.geometry.location.lng();
        });
        model.init();
        /**
         * @ngdoc method
         * @name VehicleCompaniesAdd
         * @methodOf Vehicles.controller:vehicleCompanyController
         * @description
         * This method is used to store vehicle company.
         * @param {Object} company Company details.
         * @returns {Array} Success or failure message.
         */
        $scope.VehicleCompaniesAdd = function ($valid) {
            if ($valid) {
                $scope.vehicleCompanies.user_id = $rootScope.auth.id;
                VehicleCompanyFactory.save($scope.vehicleCompanies, function (response) {
                    Flash.set($filter("translate")("Company details updated successfully"), 'success', true);
                    AuthFactory.fetch({}).$promise
                        .then(function (response) {
                            $rootScope.vehicle_company = response.vehicle_company;
                        });
                    $state.reload('vehicleCompany');
                }, function (error) {
                    var errorResponse = error.data.errors;
                    if (errorResponse.email) {
                        $scope.emailErr = $filter("translate")(errorResponse.email[0]);
                    }
                    if (errorResponse.address) {
                        $scope.addressErr = $filter("translate")(errorResponse.address[0]);
                    }
                    Flash.set($filter("translate")("Company details could not be updated"), 'error', false);
                });
            }
        }
    });
}(angular.module("BookorRent.Vehicles")));
