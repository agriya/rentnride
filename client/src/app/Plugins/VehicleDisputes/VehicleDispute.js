(function (module) {
    /**
     * @ngdoc directive
     * @name VehicleDisputes.directive:dispute
     * @module VehicleDisputes
     * @scope
     * This directive used to load the dispute page url link.
     * @restrict EA
     * @description
     * This directive used to load the dispute page template.
     */
    module.directive('dispute', function () {
        return {
            restrict: 'EA',
            templateUrl: "Plugins/VehicleDisputes/vehicle_dispute.tpl.html",
            controller: function ($scope, $rootScope, $element, $attrs, $state, Flash, $filter, VehicleDisputesFactory, VehicleDisputeFactory, VehicleDisputeResolveFactory, ConstDisputeClosedType) {
                var model = this;
                $scope.vehicle_dispute = {};
                $scope.vehicle_rental_id = $state.params.vehicle_rental_id ? $state.params.vehicle_rental_id : '';
                $scope.ConstDisputeClosedType = ConstDisputeClosedType;
                /**
                 * @ngdoc method
                 * @name init
                 * @methodOf VehicleDisputes.controller:VehicleDisputeController
                 * @description
                 * This method will initialize the meta data and functionalities.
                 **/
                $scope.init = function () {
                    //Get Dispute Types list
                    VehicleDisputesFactory.get({'id': $scope.vehicle_rental_id}).$promise.then(function (response) {
                        $scope.is_under_dispute = false;
                        $scope.all_dispute_types = false;
                        $scope.dispute_types = false;
                        $scope.dispute_array = false;
                        $scope.dispute_details = false;
                        $scope.dispute_closed_types = false;
                        if (response.is_under_dispute) {
                            $scope.is_under_dispute = true;
                        } else if (response.all_dispute_types) {
                            $scope.all_dispute_types = response.all_dispute_types;
                        } else if (response.dispute_types) {
                            $scope.dispute_types = response.dispute_types;
                        } else if (response.dispute_array) {
                            $scope.dispute_array = true;
                            $scope.dispute_details = response.dispute_array.dispute;
                            $scope.dispute_closed_types = response.dispute_array.dispute_close_types;
                            $scope.dispute_diff_days = response.dispute_array.diff_days;
                            $scope.feedback = response.dispute_array.feedback[0];
                        }
                    });
                };
                $scope.init();
                /**
                 * @ngdoc method
                 * @name disputeSubmit
                 * @methodOf VehicleDisputes.controller:VehicleDisputeController
                 * @description
                 * This method will store vehicle dispute details
                 * @param {integer} vehicle_dispute Vehicle dispute details.
                 * @returns {Array} Success or failure message.
                 **/
                $scope.disputeSubmit = function ($valid) {
                    if ($valid) {
                        $scope.vehicle_dispute.item_user_id = $scope.vehicle_rental_id;
                        $scope.vehicle_dispute.dispute_type_id = $scope.vehicle_dispute.dispute_type;
                        VehicleDisputeFactory.save($scope.vehicle_dispute, function (response) {
                            Flash.set($filter("translate")("Dispute Added Successfully!"), 'success', true);
                            $state.reload();
                        }, function (error) {
                            Flash.set($filter("translate")("Dispute Could not be added!"), 'error', false);
                        });
                    }
                };
                /**
                 * @ngdoc method
                 * @name resolveDispute
                 * @methodOf VehicleDisputes.controller:VehicleDisputeController
                 * @description
                 * This method will store vehicle dispute rewoove details
                 * @param {integer} vehicle_dispute Vehicle dispute rewoove details.
                 * @returns {Array} Success or failure message.
                 **/
                $scope.resolveDispute = function (close_type_id) {
                    $scope.vehicle_dispute.item_user_id = $scope.vehicle_rental_id;
                    $scope.vehicle_dispute.dispute_closed_type_id = close_type_id;
                    VehicleDisputeResolveFactory.save($scope.vehicle_dispute, function (response) {
                        Flash.set($filter("translate")("Dispute Resolved Successfully!"), 'success', true);
                        $state.reload();
                    }, function (error) {
                        Flash.set($filter("translate")("Dispute Could not be resolved!"), 'error', false);
                    });
                };
                $scope.checkfeedback = function (close_type_id) {
                    $('#edit_feedback_' + close_type_id).removeClass('hide');
                };
            }
        }
    });
    /**
     * @ngdoc controller
     * @name VehicleDisputes.controller:VehicleDisputeController
     * @description
     * This VehicleDisputes controller contains the VehicleDisputes functionalities.
     **/
    module.controller('VehicleDisputeController', function ($state, $scope, $http, Flash, $filter, $rootScope, $location, VehicleDisputesFactory, VehicleDisputeFactory, VehicleDisputeResolveFactory) {

    });
}(angular.module("BookorRent.VehicleDisputes")));
