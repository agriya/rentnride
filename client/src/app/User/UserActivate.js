(function (module) {
    /**
     * @ngdoc controller
     * @name User.controller:UserActivateController
     * @description
     *
     * This is user controller having the methods setmMetaData, init, upload and user_profile.
     **/
    module.controller('UserActivateController', function ($auth, $state, $rootScope, $filter, UserActivateFactory, Flash, AuthFactory, $location) {
        var model = this;
        /**
         * @ngdoc method
         * @name init
         * @methodOf User.controller:UserActivateController
         * @description
         * This method will confirm the email and return token or error message
         *
         **/
        model.init = function () {
            // activate users link
            UserActivateFactory.activate({
                id: $state.params.id,
                hash: $state.params.hash
            }).$promise.then(function (response) {
                if (response.token) {
                    $auth.setToken(response.token);					
                    localStorage.userRole = response.role;
                    AuthFactory.fetch().$promise.then(function (user) {
                        $rootScope.auth = user;
                        Flash.set($filter("translate")(response.Success), 'success', true);
                        $state.go('home');
                    });
                } else {
                    Flash.set($filter("translate")(response.Success), 'success', true);
                    $state.go('login');
                }
            }, function (error) {
                Flash.set($filter("translate")(error.data.message), 'error', true);
                $state.go('login');
            });
        };
        model.init();
    });
}(angular.module("BookorRent.user")));