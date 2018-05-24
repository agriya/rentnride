(function (module) {
    /**
     * @ngdoc controller
     * @name User.controller:UserRegisterController
     * @description
     *
     * This is user register controller having the methods setmMetaData, init, and signup. It controls the register functionalities.
     **/
    module.controller('UserRegisterController', function ($auth, $state, Flash, $rootScope, $filter, AuthFactory, $location, vcRecaptchaService, $scope) {
        var model = this;
        /**
         * @ngdoc method
         * @name setMetaData
         * @methodOf User.controller:UserRegisterController
         * @description
         *
         * This method will set the meta data dynamically by using angular.element function
         **/
        model.setMetaData = function () {
            var pageTitle = $filter("translate")("Register");
            var fullUrl = $location.absUrl();
            var appUrl = $rootScope.settings['scheme_name'] + ":/" + $location.url();
            angular.element('html head meta[property="og:title"], html head meta[name="twitter:title"]').attr("content", $rootScope.settings['site.name'] + " | " + pageTitle);
            angular.element('meta[property="al:ios:url"], meta[property="al:ipad:url"], meta[property="al:android:url"], meta[property="al:windows_phone:url"], html head meta[name="twitter:app:url:iphone"], html head meta[name="twitter:app:url:ipad"], html head meta[name="twitter:app:url:googleplay"]').attr('content', appUrl);
            angular.element('meta[property="og:url"]').attr('content', fullUrl);
        };
        /**
         * @ngdoc method
         * @name init
         * @methodOf User.controller:UserRegisterController
         * @description
         * This method will initialze the page. It uses setMetaData() and captcha_site_key.
         *
         **/
        model.init = function () {
            model.setMetaData();
            model.captcha_site_key = $rootScope.settings['captcha.site_key'];
            $rootScope.pageTitle = $rootScope.settings['site.name'] + " | " + $filter("translate")("Register");
        };
        $scope.setRecaptchaId = function(widgetId) {
            $scope.widgetId = widgetId;
        };
        model.init();
        /**
         * @ngdoc method
         * @name signup
         * @methodOf User.controller:UserRegisterController
         * @description
         * This method will validate the credentials and signup the user.
         *
         * @param {Boolean} isvalid Boolean flag to indicate whether the function call is valid or not
         **/
        model.signup = function (isvalid) {
            model.captchaErr = '';
            var response = vcRecaptchaService.getResponse($scope.widgetId);
            if(response.length === 0){
                model.captchaErr = $filter("translate")("Please resolve the captcha and submit");
            }else{
                model.captchaErr = '';
            }
            if (isvalid && model.password == model.confirm_password) {
                var credentials = {
                    username: model.username,
                    email: model.email,
                    password: model.password,
                    confirm_password: model.confirm_password,
                    is_agree_terms_conditions: model.terms_conditions,
                    'g-recaptcha-response': response
                };
                /**
                 * @ngdoc service
                 * @name User.signup
                 * @kind function
                 * @description
                 * The auth service get the credentials from the user and validate it.
                 * @params {string} credentials login credentials provided by the user
                 **/
                $auth.signup(credentials).then(function (response) {
                    if (response.data.token) {
                        $auth.setToken(response.data.token);
                        localStorage.userRole = response.data.role;
                        AuthFactory.fetch().$promise.then(function (user) {
                            $rootScope.auth = user;
                            localStorage.auth = user;
                            $state.go('home');
                        });
                    } else {
                        $state.go('login');
                    }
                    Flash.set($filter("translate")(response.data.Success), 'success', true);

                }).catch(function (error) {
                    model.emailErr = '';
                    model.nameErr = '';
                    model.passwordErr = '';
                    model.confirmPasswordErr = '';
                    var errorResponse = error.data.errors;
                    if (errorResponse.email) {
                        model.emailErr = $filter("translate")(errorResponse.email[0]);
                    }
                    if (errorResponse.username) {
                        model.nameErr = $filter("translate")(errorResponse.username[0]);
                    }
                    if (errorResponse.password) {
                        model.passwordErr = $filter("translate")(errorResponse.password[0]);
                    }
                    if (errorResponse.confirm_password) {
                        model.confirmPasswordErr = $filter("translate")(errorResponse.confirm_password[0]);
                    }
                    Flash.set($filter("translate")(error.data.message), 'error', false);
                });
            }
        };
    });

}(angular.module("BookorRent.user")));