(function (module) {
    /**
     * @ngdoc directive
     * @name VehicleFeedbacks.directive:starRating
     * @scope
     * @restrict EA
     * @description
     * starRating directive used to load the rating template.
     * @param {string} starRating Name of the directive
     **/
    module.directive('starRating', function () {
        return {
            scope: {
                rating: '=',
                maxRating: '@',
                readOnly: '@',
                click: "&",
                mouseHover: "&",
                mouseLeave: "&"
            },
            restrict: 'EA',
            templateUrl: "Plugins/VehicleFeedbacks/rating.tpl.html",
            compile: function (element, attrs) {
                if (!attrs.maxRating || (Number(attrs.maxRating) <= 0)) {
                    attrs.maxRating = '5';
                }
                ;
            },
            controller: function ($scope, $element, $attrs) {
                $scope.maxRatings = [];

                for (var i = 1; i <= $scope.maxRating; i++) {
                    $scope.maxRatings.push({});
                }
                ;

                $scope._rating = $scope.rating;

                $scope.isolatedClick = function (param) {
                    if ($scope.readOnly == 'true') return;

                    $scope.rating = $scope._rating = param;
                    $scope.hoverValue = 0;
                    $scope.click({
                        param: param
                    });
                };

                $scope.isolatedMouseHover = function (param) {
                    if ($scope.readOnly == 'true') return;

                    $scope._rating = 0;
                    $scope.hoverValue = param;
                    $scope.mouseHover({
                        param: param
                    });
                };

                $scope.isolatedMouseLeave = function (param) {
                    if ($scope.readOnly == 'true') return;
                    $scope._rating = $scope.rating;
                    $scope.hoverValue = 0;
                    $scope.mouseLeave({
                        param: param
                    });
                };
                $scope.emptyStar = 'assets/img/star-empty-lg.png';
                $scope.fillStar = 'assets/img/star-fill-lg.png';
            }
        };
    });
    /**
     * @ngdoc directive
     * @name VehicleFeedbacks.directive:feedback
     * @scope
     * @restrict EA
     * @description
     * feedback directive used to load the feedback template.
     * @param {string} feedback Name of the directive
     **/
    module.directive('feedback', function () {
        return {
            restrict: 'EA',
            templateUrl: "Plugins/VehicleFeedbacks/feedback.tpl.html",
            controller: function ($scope, $rootScope, $element, $attrs, $state, Flash, $filter, AuthFactory, VehicleRentalFactory, BookerFeedbackFactory, HostFeedbackFactory) {
                var model = this;
                $scope.vehicle_rental_id = $state.params.vehicle_rental_id ? $state.params.vehicle_rental_id : '';
                $scope.user = {};
                $scope.starRating = 0;
                $scope.hoverRating = 0;
                $scope.isRated = false;
                $scope.init = function () {
                    VehicleRentalFactory.get({'id': $scope.vehicle_rental_id}).$promise.then(function (response) {
                        $scope.VehicleRentalDetails = response;
                        $scope.itemDetail = response.item;
                    });
                    AuthFactory.fetch().$promise.then(function (user) {
                        $rootScope.auth = user;
                    });
                };
                /**
                 * @ngdoc method
                 * @name init
                 * @methodOf VehicleFeedbacks.controller:FeedbackController
                 * @description
                 * This method will initialize the page.
                 **/
                $scope.init();
                $scope.getRating = function (param) {
                    $scope.isRated = true;
                    $scope.starRating = param;
                };
                /**
                 * @ngdoc method
                 * @name feedbackSubmit
                 * @methodOf VehicleFeedbacks.controller:FeedbackController
                 * @description
                 * This method will add the feedback details.
                 * @param {integer} Feedback Feedback form details.
                 * @returns {Array} Success or failure message.
                 **/
                $scope.feedbackSubmit = function ($valid) {
                    if ($valid && $scope.isRated) {
                        if ($rootScope.auth.id == $scope.VehicleRentalDetails.user_id) {
                            BookerFeedbackFactory.save({item_user_id: $scope.vehicle_rental_id, feedback: $scope.user.feedback, rating: $scope.starRating}, function (response) {
                                Flash.set($filter("translate")("Feedback Added Successfully!"), 'success', true);
                                $state.go('activity', {vehicle_rental_id: $scope.vehicle_rental_id, action: 'all'});
                            }, function (error) {
                                Flash.set($filter("translate")("Feedback Could not be added!"), 'error', false);
                            });
                        }
                        if ($rootScope.auth.id == $scope.vehicleDetails.user_id) {
                            HostFeedbackFactory.save({item_user_id: $scope.vehicle_rental_id, feedback: $scope.user.feedback, rating: $scope.starRating}, function (response) {
                                Flash.set($filter("translate")("Feedback Added Successfully!"), 'success', true);
                                $state.go('activity', {vehicle_rental_id: $scope.vehicle_rental_id, action: 'all'});
                            }, function (error) {
                                Flash.set($filter("translate")("Feedback Could not be added!"), 'error', false);
                            });
                        }
                    }
                };
            }
        };
    });
    /**
     * @ngdoc directive
     * @name VehicleFeedbacks.directive:editFeedback
     * @scope
     * @restrict EA
     * @description
     * editFeedback directive used to load the feedback template.
     * @param {string} editFeedback Name of the directive
     **/
    module.directive('editFeedback', function () {
        return {
            restrict: 'EA',
            templateUrl: "Plugins/VehicleFeedbacks/edit_feedback.tpl.html",
            controller: function ($scope, $rootScope, $element, $attrs, $state, Flash, $filter, AuthFactory, GetFeedbackFactory, EditFeedbackFactory) {
                var model = this;
                $scope.init = function () {
                    AuthFactory.fetch().$promise.then(function (user) {
                        $rootScope.auth = user;
                    });
                    GetFeedbackFactory.get({id: $scope.feedbackId}).$promise.then(function (response) {
                        $scope.bookingFeedback = response;
                        $scope.rating = response.rating;
                    });
                };
                /**
                 * @ngdoc method
                 * @name init
                 * @methodOf VehicleFeedbacks.controller:FeedbackController
                 * @description
                 * This method will initialize the page.
                 **/
                $scope.init();
                $scope.getRating = function (param) {
                    $scope.rating = param;
                };
                /**
                 * @ngdoc method
                 * @name feedbackSubmit
                 * @methodOf VehicleFeedbacks.controller:FeedbackController
                 * @description
                 * This method will update the feedback details.
                 * @param {integer} Feedback Feedback form details.
                 * @returns {Array} Success or failure message.
                 **/
                $scope.EditfeedbackSubmit = function ($valid) {
                    if ($valid) {
                        $scope.feedbackEdit.$setPristine();
                        $scope.feedbackEdit.$setUntouched();
                        $scope.feedback = {
                            id: $scope.bookingFeedback.id,
                            rating: $scope.rating,
                            feedback: $scope.bookingFeedback.feedback,
                            item_user_id: $scope.itemUserId,
                            dispute_closed_type_id: $scope.disputeClosedTypeId
                        };
                        EditFeedbackFactory.update($scope.feedback, function (response) {
                            Flash.set($filter("translate")("Feedback Updated Successfully!"), 'success', true);
                            $state.reload();
                        }, function (error) {
                            Flash.set($filter("translate")("Feedback Could not be Updated!"), 'error', false);
                        });
                    }
                };
            },
            scope: {
                feedbackId: '=',
                itemUserId: '=',
                disputeClosedTypeId: '='
            }
        };
    });
    /**
     * @ngdoc controller
     * @name VehicleFeedbacks.controller:FeedbackController
     * @description
     * This is FeedbackController having the methods init(), setMetaData(), and it defines the item list related funtions.
     **/
    module.controller('FeedbackController', function ($state, $scope, $http, Flash, $filter, FeedbackFactory, FeedbackCancelFactory, AuthFactory, $rootScope, $location, ConstItemUserStatus, FeedbackStatusFactory) {


    });
}(angular.module("BookorRent.VehicleFeedbacks")));
