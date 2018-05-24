(function (module) {
    /**
     * @ngdoc controller
     * @name User.controller:UserProfileController
     * @description
     *
     * This is user profile controller having the methods setmMetaData, init, upload and user_profile. It controld the user profile functions.
     **/
    module.controller('UserProfileController', function ($state, $scope, Flash, UserProfilesFactory, $filter, $rootScope, $location, Upload, GENERAL_CONFIG, ConstSocialLogin, ConstThumb) {
        var model = this;
        /**
         * @ngdoc method
         * @name setMetaData
         * @methodOf User.controller:UserProfileController
         * @description
         *
         * This method will set the meta data dynamically by using the angular.element function.
         **/
        model.setMetaData = function () {
            var pageTitle = $filter("translate")("User Profile");
            var fullUrl = $location.absUrl();
            var appUrl = $rootScope.settings['scheme_name'] + ":/" + $location.url();
            angular.element('html head meta[property="og:title"], html head meta[name="twitter:title"]').attr("content", $rootScope.settings['site.name'] + " | " + pageTitle);
            angular.element('meta[property="al:ios:url"], meta[property="al:ipad:url"], meta[property="al:android:url"], meta[property="al:windows_phone:url"], html head meta[name="twitter:app:url:iphone"], html head meta[name="twitter:app:url:ipad"], html head meta[name="twitter:app:url:googleplay"]').attr('content', appUrl);
            angular.element('meta[property="og:url"]').attr('content', fullUrl);
        };
        /**
         * @ngdoc method
         * @name init
         * @methodOf User.controller:UserProfileController
         * @description
         * This method will initialze the page. It returns the page title
         *
         **/
        model.init = function () {
            model.setMetaData();
            $rootScope.pageTitle = $rootScope.settings['site.name'] + " | " + $filter("translate")("User Profile");
            model.ConstSocialLogin = ConstSocialLogin;
            model.thumb = ConstThumb.user;
            //Get user details
            UserProfilesFactory.get().$promise.then(function (response) {
                model.user_profile = response;
                $scope.user = response.User;
                $rootScope.auth.attachmentable.thumb = $scope.user.attachmentable.thumb;
            });
        };
        model.init();
        // upload on file select or drop
        /**
         * @ngdoc method
         * @name upload
         * @methodOf User.controller:UserProfileController
         * @description
         * This method will save the user profile data
         *
         * @param {!Array.<string>} profileData contains the array of user profile data
         **/
        model.upload = function (profileData, file) {
            Upload.upload({
                url: GENERAL_CONFIG.api_url + '/user_profiles',
                data: {
                    file: file,
                    'first_name': profileData.first_name,
                    'last_name': profileData.last_name,
                    'about_me': profileData.about_me,
                    'website': profileData.website,
                    'facebook_profile_link': profileData.facebook_profile_link,
                    'twitter_profile_link': profileData.twitter_profile_link,
                    'google_plus_profile_link': profileData.google_plus_profile_link,
                    'linkedin_profile_link': profileData.linkedin_profile_link,
                    'youtube_profile_link': profileData.youtube_profile_link
                }
            }).then(function (resp) {
                Flash.set($filter("translate")("UserProfile has been updated"), 'success', true);
                $state.reload('UserProfile');
            }, function (resp) {
                Flash.set($filter("translate")("UserProfile could not be updated. Please, try again."), 'error', false);
            }, function (evt) {
                var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                console.log('progress: ' + progressPercentage + '% ' + evt.config.data.file.name);
            });
        };
        //Update user details
        /**
         * @ngdoc method
         * @name userProfile
         * @methodOf User.controller:UserProfileController
         * @description
         * This method will upload the file and returns the success message.
         *
         **/
        model.userProfile = function ($valid) {
            if($valid) {
                if ($scope.file) {
                    model.upload(model.user_profile, $scope.file);
                } else {
                    UserProfilesFactory.update(model.user_profile)
                        .$promise
                        .then(function (response) {
                            Flash.set($filter("translate")(response.Success), 'success', true);
                            $state.reload('UserProfile');
                        });
                }
            }
        };
    });
}(angular.module("BookorRent.user")));
