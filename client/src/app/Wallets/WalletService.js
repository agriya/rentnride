(function (module) {
    /**
     * @ngdoc service
     * @name Wallets.WalletsFactory
     * @description
     * WalletsFactory is used in wallets
     * @param {string} WalletsFactory The name of the factory
     * @param {function()} function It uses post method for save and returns the url
     */
    module.factory('WalletsFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(GENERAL_CONFIG.api_url + '/wallets', {}, {
                'save': {
                    method: 'POST'
                }
            }
        );
    });

    /**
     * @ngdoc service
     * @name Wallets.GetGatewaysFactory
     * @description
     * GetGatewaysFactory is used in wallets
     * @param {string} GetGatewaysFactory The name of the factory
     * @param {function()} function It uses get method for get the gateways
     */
    module.factory('GetGatewaysFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(GENERAL_CONFIG.api_url + '/get_gateways', {}, {
                'get': {
                    method: 'GET'
                }
            }
        );
    });

    /**
     * @ngdoc service
     * @name Wallets.GetCountries
     * @description
     * GetCountries used in list and store the items.
     * @param {string} GetCountries The name of the factory
     * @param {function()} function It uses get method for listing, countries
     */
    module.factory('GetCountries', function ($resource, GENERAL_CONFIG) {
        return $resource(GENERAL_CONFIG.api_url + '/countries', {}, {
                'get': {
                    method: 'GET'
                }
            }
        );
    });
})(angular.module('BookorRent.Wallets'));
