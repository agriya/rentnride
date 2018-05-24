(function (module) {
    /**
     * @ngdoc service
     * @name User.AuthFactory
     * @description
     * Authfactory is a factory service which is used to authenticate the user
     * @param {string} AuthFactory The name of the factory
     * @param {function()} function It uses get method, and defines the url
     */
    module.factory('AuthFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(GENERAL_CONFIG.api_url + '/users/auth', {}, {
            fetch: {
                method: 'GET'
            }
        });
    });
    /**
     * @ngdoc service
     * @name User.UserProfilesFactory
     * @description
     * UserProfilesFactory is a factory service which updates and displays the user profile.
     * @param {string} UserProfilesFactory The name of the factory
     * @param {function()} function It uses get method for get the user profile, uses post method for update the user
     profile and defines the url
     */
    module.factory('UserProfilesFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(GENERAL_CONFIG.api_url + '/user_profiles', {}, {
            update: {
                method: 'POST'
            },
            get: {
                method: 'GET'
            }
        });
    });
    /**
     * @ngdoc service
     * @name User.UsersFactory
     * @description
     * UsersFactory is a factory servce which is used to get the users list.
     * @param {string} UsersFactory The name of the factory
     * @param {function()} function It uses get method, and defines the url
     */

    module.factory('UsersFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(GENERAL_CONFIG.api_url + '/user', {}, {
            get: {
                method: 'GET'
            }
        });
    });
    /**
     * @ngdoc service
     * @name User.UserActivateFactory
     * @description
     * UserActivateFactory is a factory service which is used for activating the user.
     * @param {string} UserActivateFactory The name of the factory
     * @param {function()} function It uses get method, and defines the url
     */

    module.factory('UserActivateFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(GENERAL_CONFIG.api_url + '/users/:id/activate/:hash', {
                id: '@id',
                hash: '@hash'
            }, {
                'activate': {
                    method: 'PUT'
                }
            }
        );
    });
    /**
     * @ngdoc service
     * @name User.ForgotPasswordFactory
     * @description
     * ForgotPasswordFactory is a factory service which will be used in resetting the password if the user forgot his password.
     * @param {string} ForgotPasswordFactory The name of the factory
     * @param {function()} function It uses get method, and defines the url
     */

    module.factory('ForgotPasswordFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(GENERAL_CONFIG.api_url + '/users/forgot_password', {}, {
            forgot_password: {
                method: 'PUT'
            }
        });
    });
    /**
     * @ngdoc service
     * @name User.UserAttachmentFactory
     * @description
     * UserAttachmentFactory is a factory service which is used for getting user upload image detail.
     * @param {string} UserAttachmentFactory The name of the factory
     * @param {function()} function It uses get method, and defines the url
     */

    module.factory('UserAttachmentFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(GENERAL_CONFIG.api_url + '/users/:id/attachment', {
                id: '@id'
            }, {
                'get': {
                    method: 'GET'
                }
            }
        );
    });
    /**
     * @ngdoc service
     * @name User.UserChangePasswordFactory
     * @description
     * UserChangePasswordFactory is a factory service which is used to change password.
     * @param {string} UserChangePasswordFactory The name of the factory
     * @param {function()} function It uses put method, and defines the url
     */

    module.factory('UserChangePasswordFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(GENERAL_CONFIG.api_url + '/users/:id/change_password', {
                id: '@id'
            }, {
                'changePassword': {
                    method: 'PUT'
                }
            }
        );
    });

    /**
     * @ngdoc service
     * @name User.MyVehiclesFactory
     * @description
     * MyVehiclesFactory is a factory service which is used to get the vehicles.
     * @param {string} MyVehiclesFactory The name of the factory
     * @param {function()} function It uses get method, and get the vehicles
     */

    module.factory('MyVehiclesFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(GENERAL_CONFIG.api_url + '/vehicles/me', {}, {
                'get': {
                    method: 'GET'
                }
            }
        );
    });
    /**
     * @ngdoc service
     * @name User.statsFactory
     * @description
     * statsFactory is a factory service which is used to get the stats of user.
     * @param {string} statsFactory The name of the factory
     * @param {function()} function It uses get method, and get the stats of user
     */

    module.factory('statsFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(GENERAL_CONFIG.api_url + '/users/stats', {}, {
                'get': {
                    method: 'GET'
                }
            }
        );
    });
})(angular.module("BookorRent.user"));
