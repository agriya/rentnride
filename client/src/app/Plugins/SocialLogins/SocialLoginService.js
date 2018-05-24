(function (module) {
    /**
     * @ngdoc service
     * @name SocialLogins.ProvidersFactory
     * @description
     * ProvidersFactory is used to listing the providers.
     * @param {string} ProvidersFactory The name of the factory service
     * @param {function()} function returns the providers list.
     * @param {string} url Base url accessed in GENERAL_CONFIG.
     */
    module.factory('ProvidersFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(GENERAL_CONFIG.api_url + '/providers', {}, {});
    });
    /**
     * @ngdoc service
     * @name SocialLogins.ProviderUsersFactory
     * @description
     * ProviderUsersFactory is used to listing provider users
     * @param {string} ProviderUsersFactory The name of the factory service
     * @param {function()} function It uses get method for get and returns the url
     * @param {string} url Base url accessed in GENERAL_CONFIG.
     * @returns {object} The service contains these actions:
     *
     *      {
	 *			'get':    {method:'GET'}
	 *		};
     */
    module.factory('ProviderUsersFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(GENERAL_CONFIG.api_url + '/provider_users', {}, {
            get: {
                method: 'GET'
            }
        });
    });
    /**
     * @ngdoc service
     * @name SocialLogins.UpdateProfileFactory
     * @description
     * UpdateProfileFactory is used to update user profile
     * @param {string} UpdateProfileFactory The name of the factory service
     * @param {function()} function It uses get method for get and returns the url
     * @param {string} url Base url accessed in GENERAL_CONFIG.
     * @returns {object} The service contains these actions:
     *
     *      {
	 *			'update':    {method:'POST'}
	 *		};
     */
    module.factory('UpdateProfileFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(GENERAL_CONFIG.api_url + '/update_profile/', {}, {
            update: {
                method: 'POST'
            },
        });
    });
    /**
     * @ngdoc service
     * @name SocialLogins.SocialLoginFactory
     * @description
     * SocialLoginFactory is used to login social user
     * @param {string} SocialLoginFactory The name of the factory service
     * @param {function()} function It uses get method for get and returns the url
     * @param {string} url Base url accessed in GENERAL_CONFIG.
     * @returns {object} The service contains these actions:
     *
     *      {
	 *			'login':    {method:'POST'}
	 *		};
     */
    module.factory('SocialLoginFactory', function ($resource) {
        return $resource('api/social_login', {}, {
            login: {
                method: 'POST'
            }
        });
    });
})(angular.module('BookorRent.SocialLogins'));
