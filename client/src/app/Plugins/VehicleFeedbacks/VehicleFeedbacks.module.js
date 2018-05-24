/**
 * BookorRent - v1.0a.01 - 2016-03-28
 *
 * Copyright (c) 2016 Agriya
 */
/**
 * @ngdoc object
 * @name VehicleFeedbacks
 * @description
 *
 * This is the module for VehicleFeedbacks. It contains the VehicleFeedbacks functionalities.
 *
 * The VehicleFeedbacks module act as a state provider, this module get the url and load the template and call the controller instantly.
 *
 * @param {string} VehicleFeedbacks name of the module
 * @param {!Array.<string>=} dependencies If specified then new module is being created. If unspecified then the module is being retrieved for further configuration.
 *
 *        [
 *            'ui.router',
 *            'ngResource',
 *            'angulartics',
 *            'angulartics.google.analytics',
 *            'angulartics.facebook.pixel' *
 *        ]
 * @returns {BookorRent.VehicleFeedbacks} new BookorRent.VehicleFeedbacks module.
 **/
(function (module) {
    module.directive('allFeedback', function () {
        return {
            restrict: 'EA',
            templateUrl: "Plugins/VehicleFeedbacks/vehicle_feedbacks.tpl.html",
            controller: function ($scope, $element, $attrs, $state, FeedbacksFactory, $rootScope) {
                $scope.getFeedbacks = function() {
                    FeedbacksFactory.get({type:'vehicle'}).$promise.then(function(response) {
                        $scope.feedbacks = response.data;
                    });
                };
                $scope.init = function() {
                    $scope.noWrapSlides = false;
                    $scope.interval = 5000;
                    $scope.getFeedbacks();
                    //Vehicle rating
                    $scope.maxRatings = [];
                    $scope.maxRating = 5;
                    for (var i = 0; i < $scope.maxRating; i++) {
                        $scope.maxRatings.push(i);
                    }
                };
                $scope.init();
            }
        };
    });
    module.directive('userFeedback', function () {
        return {
            restrict: 'EA',
            templateUrl: "Plugins/VehicleFeedbacks/user_feedbacks.tpl.html",
            controller: function ($scope, $element, $attrs, $state, UserFeedbacksFactory, $rootScope, moment) {
                var model = this;
                $scope.maxSize = 5;
                $scope.getUserFeedbacks = function() {
                    $scope.$watch('userId', function(userId) {
                        if(userId != undefined) {
                            UserFeedbacksFactory.get({
                                to_user_id: userId,
                                'page': $scope.feedback_currentPage
                            }).$promise.then(function (response) {
                                $scope.user_feedbacks = response.data;
                                $scope.feedback_metadata = response.meta.pagination;
                            });
                        }
                    });
                };
                $scope.init = function() {
                    $scope.feedback_currentPage = ($scope.feedback_currentPage != undefined)?$scope.feedback_currentPage:1;
                    $scope.getUserFeedbacks();
                    //Vehicle rating
                    $scope.FeedbackMaxRatings = [];
                    $scope.FeedbackMaxRating = 5;
                    for (var i = 0; i < $scope.FeedbackMaxRating; i++) {
                        $scope.FeedbackMaxRatings.push(i);
                    }
                };
                $scope.feedback_paginate = function() {
                    $scope.feedback_currentPage = parseInt($scope.feedback_currentPage);
                    $scope.getUserFeedbacks();
                };
                $scope.init();
            },
            scope: {
                userId: '='
            }
        };
    });

}(angular.module('BookorRent.VehicleFeedbacks', [
    'ui.router',
    'ngResource',
    'angulartics',
    'angulartics.google.analytics',
    'angulartics.facebook.pixel',
    'ui.bootstrap'
])));
