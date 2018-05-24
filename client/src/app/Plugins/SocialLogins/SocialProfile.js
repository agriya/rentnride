(function (module) {
    /**
     * @ngdoc controller
     * @name SocialLogins.controller:SocialProfileController
     * @description
     * This is SocialProfile controller having the methods init(), setMetaData(), and getProviderUsers().
     * It maintains the functinolities of the social profile.
     **/
    module.controller('SocialProfileController', function ($state, $auth, $scope, $rootScope, ProvidersFactory, $location, $filter, Flash, ProviderUsersFactory, UpdateProfileFactory, UserAttachmentFactory, AuthFactory, ConstSocialLogin) {
        var model = this;
        model.updateProfileDetails = [];
        /**
         * @ngdoc method
         * @name setMetaData
         * @methodOf SocialLogins.controller:SocialProfileController
         * @description
         * This method will set the meta data dynamically by using the angular.element.
         * @returns {Element} New meta data element.
         **/
        model.setMetaData = function () {
            var pageTitle = $filter("translate")("Social Profile Image");
            var fullUrl = $location.absUrl();
            var appUrl = $rootScope.settings['scheme_name'] + ":/" + $location.url();
            angular.element('html head meta[property="og:title"], html head meta[name="twitter:title"]').attr("content", $rootScope.settings['site.name'] + " | " + pageTitle);
            angular.element('meta[property="al:ios:url"], meta[property="al:ipad:url"], meta[property="al:android:url"], meta[property="al:windows_phone:url"], html head meta[name="twitter:app:url:iphone"], html head meta[name="twitter:app:url:ipad"], html head meta[name="twitter:app:url:googleplay"]').attr('content', appUrl);
            angular.element('meta[property="og:url"]').attr('content', fullUrl);
        };
        model.sociallogin = {};
        /**
         * @ngdoc method
         * @name getProviderUsers
         * @methodOf SocialLogins.controller:SocialProfileController
         * @description
         * This method will get the list of service provider usres list.
         **/
        model.getProviderUsers = function () {
            model.fb_connected = false;
            ProviderUsersFactory.get().$promise.then(function (response) {
                angular.forEach(response.data, function (value, key) {
                    model.updateProfileDetails.source_id = value.user.user_avatar_source_id;
                    if (value.provider_id == ConstSocialLogin.Facebook && value.is_connected) {
                        model.fb_connected = true;
                        model.fb_img = value.profile_picture_url;
                    } else if (value.provider_id == ConstSocialLogin.Twitter && value.is_connected) {
                        model.twitter_connected = true;
                        model.twt_img = value.profile_picture_url;
                    } else if (value.provider_id == ConstSocialLogin.Google && value.is_connected) {
                        model.google_connected = true;
                        model.goo_img = value.profile_picture_url;
                    } else if (value.provider_id == ConstSocialLogin.Github && value.is_connected) {
                        model.github_connected = true;
                        model.git_img = value.profile_picture_url;
                    }
                });
            });
        };
        /**
         * @ngdoc method
         * @name init
         * @methodOf SocialLogins.controller:SocialProfileController
         * @description
         * This method will initialize the meta data and functionalities.
         **/
        model.init = function () {
            model.setMetaData();
            $rootScope.pageTitle = $rootScope.settings['site.name'] + " | " + $filter("translate")("Profile Images");
            model.user_avatar_source = ConstSocialLogin;
            UserAttachmentFactory.get({
                id: $rootScope.auth.id
            }).$promise
                .then(function (response) {
                    $scope.media = response;
                });
            //Get Active social logins
            ProvidersFactory.get({'filter': 'active', 'sortby': 'asc', 'sort': 'display_order'}).$promise
                .then(function (response) {
                    model.sociallogin = response;
                });
            //check if user connect with social
            model.getProviderUsers();
        };
        model.init();
        //Connect with providers
        /**
         * @ngdoc method
         * @name connect
         * @methodOf SocialLogins.controller:SocialProfileController
         * @description
         * This method will be used in connecting the user to the social websites.
         * @param {integer} provider Provider details.
         * @returns {Array} Success or failure message.
         **/
        model.connect = function (provider) {
            $auth.link(provider).then(function (response) {
                if (response.data.provider_id == ConstSocialLogin.Facebook && response.data.is_connected) {
                    model.fb_connected = true;
                    model.fb_img = response.data.profile_picture_url;
                } else if (response.data.provider_id == ConstSocialLogin.Twitter && response.data.is_connected) {
                    model.twitter_connected = true;
                    model.twt_img = response.data.profile_picture_url;
                } else if (response.data.provider_id == ConstSocialLogin.Google && response.data.is_connected) {
                    model.google_connected = true;
                    model.goo_img = response.data.profile_picture_url;
                } else if (response.data.provider_id == ConstSocialLogin.Github && response.data.is_connected) {
                    model.github_connected = true;
                    model.git_img = response.data.profile_picture_url;
                }
                Flash.set($filter("translate")("Connected Successfully"), 'success', true);
            }).catch(function (error) {
                Flash.set($filter("translate")(error.data.message), 'error', false);
            });
        };
        //Update profile image
        /**
         * @ngdoc method
         * @name updateProfile
         * @methodOf SocialLogins.controller:SocialProfileController
         * @description
         * This method is used to update the user profile image.
         * @param {integer} updateProfileDetails User profile details.
         * @returns {Array} Success or failure message.
         **/
        model.updateProfile = function (updateProfileDetails) {
            UpdateProfileFactory.update({'source_id': updateProfileDetails.source_id}).$promise.then(function (response) {
                AuthFactory.fetch().$promise.then(function (user) {
                    $rootScope.auth = user;
                });
                Flash.set($filter("translate")("Profile image updated successfully"), 'success', true);
            });
        };
    });
}(angular.module('BookorRent.SocialLogins')));
