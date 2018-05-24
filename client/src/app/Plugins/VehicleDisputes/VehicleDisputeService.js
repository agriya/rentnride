(function (module) {
    /**
     * @ngdoc service
     * @name VehicleDisputes.VehicleDisputesFactory
     * @description
     * VehicleDisputesFactory used to get the vehicle dispute details.
     * @param {function()} function It used to list the data.
     * @param {string} url Base url accessed in GENERAL_CONFIG.
     * @returns {object} The service contains these actions:
     *
     *      {
	 *			'get':   {method:'GET'}
	 *		};
     */
    module.factory('VehicleDisputesFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(GENERAL_CONFIG.api_url + '/vehicle_disputes/:id', {
                id: '@id',
            }, {
                'get': {
                    method: 'GET'
                }
            }
        );
    });
    /**
     * @ngdoc service
     * @name VehicleDisputes.VehicleDisputeFactory
     * @description
     * VehicleDisputeFactory used to save the vehicle dispute details.
     * @param {function()} function It used to store the data.
     * @param {string} url Base url accessed in GENERAL_CONFIG.
     * @returns {object} The service contains these actions:
     *
     *      {
	 *			'save':   {method:'POST'}
	 *		};
     */
    module.factory('VehicleDisputeFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(GENERAL_CONFIG.api_url + '/vehicle_disputes/add', {}, {
                'save': {
                    method: 'POST'
                }
            }
        );
    });
    /**
     * @ngdoc service
     * @name VehicleDisputes.VehicleDisputeResolveFactory
     * @description
     * VehicleDisputeResolveFactory used to save the vehicle dispute resolve details.
     * @param {function()} function It used to store the data.
     * @param {string} url Base url accessed in GENERAL_CONFIG.
     * @returns {object} The service contains these actions:
     *
     *      {
	 *			'save':   {method:'POST'}
	 *		};
     */
    module.factory('VehicleDisputeResolveFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(GENERAL_CONFIG.api_url + '/admin/vehicle_disputes/resolve', {}, {
                'save': {
                    method: 'POST'
                }
            }
        );
    });
})(angular.module('BookorRent.VehicleDisputes'));
