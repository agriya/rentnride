(function (module) {
/**
 * @ngdoc service
 * @name VehicleFeedbacks.BookerFeedbackFactory
 * @description
 * BookerFeedbackFactory used in list and  booker send feedback to host
 * @param {string} BookerFeedbackFactory The name of the factory
 * @param {function()} function It uses get method for booker send feedback to host
 * @param {string} url Base url accessed in GENERAL_CONFIG.
 * @returns {object} The service contains these actions:
 *
 *      {
 *			'save':   {method:'POST'}
 *		};
 */
module.factory('BookerFeedbackFactory', function ($resource, GENERAL_CONFIG) {
    return $resource(GENERAL_CONFIG.api_url + '/booker/review', {}, {
            'save': {
                method: 'POST'
            }
        }
    );
});
/**
 * @ngdoc service
 * @name VehicleFeedbacks.HostFeedbackFactory
 * @description
 * HostFeedbackFactory used in list and host send feedback to booker.
 * @param {string} HostFeedbackFactory The name of the factory
 * @param {function()} function It uses get method for host send feedback to booker
 * @param {string} url Base url accessed in GENERAL_CONFIG.
 * @returns {object} The service contains these actions:
 *
 *      {
 *			'save':   {method:'POST'}
 *		};
 */
module.factory('HostFeedbackFactory', function ($resource, GENERAL_CONFIG) {
    return $resource(GENERAL_CONFIG.api_url + '/host/review', {}, {
            'save': {
                method: 'POST'
            }
        }
    );
});

/**
 * @ngdoc service
 * @name VehicleFeedbacks.GetFeedbackFactory
 * @description
 * GetFeedbackFactory used in list and admin get the feedback.
 * @param {string} GetFeedbackFactory The name of the factory
 * @param {function()} function It uses get method for admin get the feedback
 * @param {string} url Base url accessed in GENERAL_CONFIG.
 * @returns {object} The service contains these actions:
 *
 *      {
 *			'get':   {method:'GET'}
 *		};
 */
module.factory('GetFeedbackFactory', function ($resource, GENERAL_CONFIG) {
    return $resource(GENERAL_CONFIG.api_url + '/admin/vehicle_feedbacks/:id/edit', {
        id:'@id',
    }, {
            'get': {
                method: 'get'
            }
        }
    );
});

/**
 * @ngdoc service
 * @name VehicleFeedbacks.EditFeedbackFactory
 * @description
 * EditFeedbackFactory used in list and  admin edit the feedback.
 * @param {string} EditFeedbackFactory The name of the factory
 * @param {function()} function It uses get method for admin edit the feedback
 * @param {string} url Base url accessed in GENERAL_CONFIG.
 * @returns {object} The service contains these actions:
 *
 *      {
 *			'update':   {method:'PUT'}
 *		};
 */
module.factory('EditFeedbackFactory', function ($resource, GENERAL_CONFIG) {
    return $resource(GENERAL_CONFIG.api_url + '/admin/vehicle_feedbacks/:id', {
            id:'@id',
        }, {
            'update': {
                method: 'PUT'
            }
        }
    );
});
/**
 * @ngdoc service
 * @name VehicleFeedbacks.FeedbacksFactory
 * @description
 * FeedbacksFactory used in list  the feedback.
 * @param {string} FeedbacksFactory The name of the factory
 * @param {function()} function It uses get method for get all feedbacks
 * @param {string} url Base url accessed in GENERAL_CONFIG.
 * @returns {object} The service contains these actions:
 *
 *      {
 *			'get':   {method:'GET'}
 *		};
     */
    module.factory('FeedbacksFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(GENERAL_CONFIG.api_url + '/vehicle_feedbacks/', {

            }, {
                'get': {
                    method: 'GET'
                }
            }
        );
    });
    /**
     * @ngdoc service
     * @name User.UserFeedbacksFactory
     * @description
     * UserFeedbacksFactory is a factory service which is used to get the feedbacks.
     * @param {string} UserFeedbacksFactory The name of the factory
     * @param {function()} function It uses get method, and get the feedbacks
     */

    module.factory('UserFeedbacksFactory', ['$resource', 'GENERAL_CONFIG', function ($resource, GENERAL_CONFIG) {
        return $resource(GENERAL_CONFIG.api_url + '/vehicle_feedbacks', {}, {
                'get': {
                    method: 'GET'
                }
            }
        );
    }]);

})(angular.module('BookorRent.VehicleFeedbacks'));
