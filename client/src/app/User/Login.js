(function (module) {
    /**
     * @ngdoc controller
     * @name User.controller:UserLoginController
     * @description
     *
     * This is user login controller having the methods init, setMetaData, and login. It is used for controlling the login functionalities.
     **/
    module.controller('UserLoginController', function ($auth, $state, Flash, $rootScope, $filter, AuthFactory, $location, cfpLoadingBar) {
        var model = this;
        /**
         * @ngdoc method
         * @name setMetaData
         * @methodOf User.controller:UserLoginController
         * @description
         *
         * This method will set the meta data dynamically by using the angular.element of function.
         **/
        model.setMetaData = function () {
            var pageTitle = $filter("translate")("Login");
            var fullUrl = $location.absUrl();
            var appUrl = $rootScope.settings['scheme_name'] + ":/" + $location.url();
            angular.element('html head meta[property="og:title"], html head meta[name="twitter:title"]').attr("content", $rootScope.settings['site.name'] + " | " + pageTitle);
            angular.element('meta[property="al:ios:url"], meta[property="al:ipad:url"], meta[property="al:android:url"], meta[property="al:windows_phone:url"], html head meta[name="twitter:app:url:iphone"], html head meta[name="twitter:app:url:ipad"], html head meta[name="twitter:app:url:googleplay"]').attr('content', appUrl);
            angular.element('meta[property="og:url"]').attr('content', fullUrl);
        };
        /**
         * @ngdoc method
         * @name init
         * @methodOf User.controller:UserLoginController
         * @description
         * This method will initialze the page and it uses the setmetadata() function.
         *
         **/
        model.init = function () {
            model.setMetaData();
            $rootScope.pageTitle = $rootScope.settings['site.name'] + " | " + $filter("translate")("Login");
        };
        model.init();
        /**
         * @ngdoc method
         * @name login
         * @methodOf User.controller:UserLoginController
         * @description
         * This method will validate the credentials and log in the user.
         *
         * @param {Boolean} isvalid Boolean flag to indicate whether the function call is valid or not
         **/
        model.login = function (isvalid) {
            if (isvalid) {
                cfpLoadingBar.start();
                var credentials = {
                    email: model.email,
                    password: model.password
                };
                // Use Satellizer's $auth service to login
                /**
                 * @ngdoc service
                 * @name User.login
                 * @kind function
                 * @description
                 * The auth service get the credentials from the user and validate it.
                 * @params {string=} credentials login credentials provided by the user
                 **/
                $auth.login(credentials).then(function (response) {
                    // If login is successful, redirect to the home page
                    if (response.data.userToken) {
                        localStorage.userRole = response.data.role;
                        $translate = $filter("translate")("Login successfully");
                        Flash.set($translate, 'success', true);
                        AuthFactory.fetch().$promise.then(function (user) {
                            $rootScope.auth = user;
                            cfpLoadingBar.complete();
                            var selected_vehicle = JSON.parse(localStorage.getItem('vehicle_search_value'));
                            if(selected_vehicle != null) {
                                $state.go('vehicleView', {'id':selected_vehicle.id, 'slug':selected_vehicle.slug});
                            } else {
                                $state.go('dashboard');
                            }
                        });
                    }

                }).catch(function (error) {
                    cfpLoadingBar.complete();
                    if(error.data.error) {
                        Flash.set($filter("translate")(error.data.error), 'error', false);
                    } else {
                        Flash.set($filter("translate")("Sorry, login failed. Your email or password are incorrect."), 'error', false);
                    }
                });
            }
        };
    });
}(angular.module("BookorRent.user")));


