(function (module) {
    /**
     * @ngdoc controller
     * @name User.controller:ForgotPasswordController
     * @description
     *
     * This is forgot password controller having the service forgot_password. It is used for reset the password if the users forgot their password.
     **/
    module.controller('ForgotPasswordController', function ($state, $window, $scope, $rootScope, $location, Flash, $filter, ForgotPasswordFactory) {
        var model = this;
        $scope.user = {};
        $rootScope.pageTitle = $rootScope.settings['site.name'] + " | " + $filter("translate")("Forgot Password");
        /**
         * @ngdoc method
         * @name forgot_password
         * @methodOf User.controller:ForgotPasswordController
         * @description
         * This method uses the forgot_password factory service to change the password.
         *
         **/
        $scope.forgot_password = function (forgotPassword, user) {
            $scope.user = user;
            $scope.disableButton = true;
            if (forgotPassword) {
                ForgotPasswordFactory.forgot_password($scope.user, function (response) {
                    Flash.set($filter("translate")("Password changed Successfully. New password is sent in mail."), 'success', true);
                    $state.go('login');
                }, function (error) {
                    Flash.set($filter("translate")(error.data.message), 'error', false);
                    $scope.disableButton = false;
                });
            }
        };

    });

}(angular.module("BookorRent.user")));
