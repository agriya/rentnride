(function (module) {
    /**
     * @ngdoc service
     * @name Vehicles.VehicleRelatedFactory
     * @description
     * VehicleRelatedFactory is a factory service which is used to brings all vehicle related informations.
     * @param {string} VehicleRelatedFactory The name of the factory
     * @param {function()} function It uses post method to fetch the data
     * @param {string} url Base url accessed in GENERAL_CONFIG.
     * @returns {object} The service contains these actions:
     *
     *      {
	 *			'get':    {method:'GET'},
	 *		};
     */
    module.factory('VehicleRelatedFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(
            GENERAL_CONFIG.api_url + '/vehicle/add', {}, {
                'get': {
                    method: 'GET'
                }
            }
        );
    });
    /**
     * @ngdoc service
     * @name Vehicles.VehicleModelFactory
     * @description
     * VehicleModelFactory used to get vehicle types.
     * @param {function()} function It uses get method for vehicle types.
     * @param {string} url Base url accessed in GENERAL_CONFIG.
     * @param {integer=} vehicle_make_id Vehicle model identifier to get the particular vehicle type.
     * @returns {object} The service contains these actions:
     *
     *      {
	 *			'get':    {method:'GET'},
	 *		};
     */
    module.factory('VehicleModelFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(GENERAL_CONFIG.api_url + '/vehicle_models', {}, {
                'get': {
                    method: 'GET'
                }
            }
        );
    });
    /**
     * @ngdoc service
     * @name Vehicles.VehicleTypeFactory
     * @description
     * VehicleTypeFactory used to get type price.
     * @param {function()} function It uses get method for type price.
     * @param {string} url Base url accessed in GENERAL_CONFIG.
     * @param {integer=} vehicle_make_id Vehicle model identifier to get the particular vehicle type price.
     * @returns {object} The service contains these actions:
     *
     *      {
	 *			'get':    {method:'GET'},
	 *		};
     */
    module.factory('VehicleTypeFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(GENERAL_CONFIG.api_url + '/vehicle_types/:id', {}, {
                'get': {
                    method: 'GET'
                },
                'getAll':{
                    method: 'GET'
                }
            }
        );
    });
    /**
     * @ngdoc service
     * @name Vehicles.VehicleFactory
     * @description
     * VehicleFactory is a factory service which is used to store and update vehicle details.
     * @param {string} VehicleFactory The name of the factory
     * @param {function()} function It used to store and update vehicle details.
     * @param {string} url Base url accessed in GENERAL_CONFIG.
     * @returns {object} The service contains these actions:
     *
     *      {
	 *			'save':    {method:'POST'},
	 *			'update':    {method:'POST'}
	 *		};
     */
    module.factory('VehicleFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(
            GENERAL_CONFIG.api_url + '/vehicles/:id', {}, {
                'save': {
                    method: 'POST'
                },
                'update': {
                    method: 'POST'
                }
            }
        );
    });
    /**
     * @ngdoc service
     * @name Vehicles.MyVehiclesFactory
     * @description
     * MyVehiclesFactory is a factory service which is used to fetch user's vechicle details.
     * @param {string} MyVehiclesFactory The name of the factory
     * @param {function()} function It used to fetch user's vechicle details.
     * @param {string} url Base url accessed in GENERAL_CONFIG.
     * @returns {object} The service contains these actions:
     *
     *      {
	 *			'get':    {method:'GET'}
	 *		};
     */
    module.factory('MyVehiclesFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(
            GENERAL_CONFIG.api_url + '/vehicles/me', {}, {
                'get': {
                    method: 'GET'
                },
            }
        );
    });
    /**
     * @ngdoc service
     * @name Vehicles.VehicleCompanyFactory
     * @description
     * VehicleCompanyFactory is a factory service which is used to fetch user's vechicle details.
     * @param {string} VehicleCompanyFactory The name of the factory
     * @param {function()} function It used to fetch user's vechicle details.
     * @param {string} url Base url accessed in GENERAL_CONFIG.
     * @returns {object} The service contains these actions:
     *
     *      {
	 *			'save':    {method:'POST'},
	 *			'get':    {method:'GET'}
	 *		};
     */
    module.factory('VehicleCompanyFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(
            GENERAL_CONFIG.api_url + '/vehicle_companies', {}, {
                'save': {
                    method: 'POST'
                },
                'get': {
                    method: 'GET'
                }
            }
        );
    });

    /**
     * @ngdoc service
     * @name Vehicles.VehicleCompanyShowFactory
     * @description
     * VehicleCompanyShowFactory is a factory service which is used to show logged user the company details.
     * @param {string} VehicleCompanyShowFactory The name of the factory
     * @param {function()} function It used to show logged user the company details.
     */
    module.factory('VehicleCompanyShowFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(
            GENERAL_CONFIG.api_url + '/vehicle_companies/show', {}, {}
        );
    });
    /**
     * @ngdoc service
     * @name Vehicles.CounterLocationFactory
     * @description
     * CounterLocationFactory used in list and list the counter locations.
     * @param {string} CounterLocationFactory The name of the factory
     * @param {function()} function It uses get method for listing, defines the url
     * @param {string} url Base url accessed in GENERAL_CONFIG.
     * @returns {object} The service contains these actions:
     *
     *      {
	 *			'get':    {method:'GET'}
	 *		};
     */
    module.factory('CounterLocationFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(GENERAL_CONFIG.api_url + '/counter_locations', {}, {
                'get': {
                    method: 'GET'
                }
            }
        );
    });
    /**
     * @ngdoc service
     * @name Vehicles.VehicleSearchFactory
     * @description
     * VehicleSearchFactory used in list and list the vehicles.
     * @param {string} VehicleSearchFactory The name of the factory
     * @param {function()} function It uses post method for listing, defines the url
     * @param {string} url Base url accessed in GENERAL_CONFIG.
     * @returns {object} The service contains these actions:
     *
     *      {
	 *			'save':    {method:'POST'}
	 *		};
     */
    module.factory('VehicleSearchFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(GENERAL_CONFIG.api_url + '/vehicles/search', {}, {
                'post': {
                    method: 'POST'
                }
            }
        );
    });
    /**
     * @ngdoc service
     * @name Vehicles.VehicleFilterFactory
     * @description
     * VehicleFilterFactory used in list and list the vehicle filters.
     * @param {string} VehicleFilterFactory The name of the factory
     * @param {function()} function It uses post method for listing, defines the url
     * @param {string} url Base url accessed in GENERAL_CONFIG.
     * @returns {object} The service contains these actions:
     *
     *      {
	 *			'get':    {method:'GET'}
	 *		};
     */
    module.factory('VehicleFilterFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(GENERAL_CONFIG.api_url + '/vehicles/filters', {}, {
                'get': {
                    method: 'GET'
                }
            }
        );
    });
    /**
     * @ngdoc service
     * @name Vehicles.VehicleBookingFactory
     * @description
     * VehicleBookingFactory used in list and list the vehicle filters.
     * @param {string} VehicleBookingFactory The name of the factory
     * @param {function()} function It uses post method for booking the vehicle
     * @param {string} url Base url accessed in GENERAL_CONFIG.
     * @returns {object} The service contains these actions:
     *
     *      {
	 *			'save':    {method:'POST'}
	 *		};
     */
    module.factory('VehicleBookingFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(GENERAL_CONFIG.api_url + '/vehicle_rentals', {}, {
                'save': {
                    method: 'POST'
                }
            }
        );
    });
    /**
     * @ngdoc service
     * @name Vehicles.VehicleDetailFactory
     * @description
     * VehicleDetailFactory used in list and list the vehicle filters.
     * @param {string} VehicleDetailFactory The name of the factory
     * @param {function()} function It uses get method for get the booking vehicle details
     * @param {string} url Base url accessed in GENERAL_CONFIG.
     * @returns {object} The service contains these actions:
     *
     *      {
	 *			'get':    {method:'GET'}
	 *		};
     */
    module.factory('VehicleDetailFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(GENERAL_CONFIG.api_url + '/vehicle_rentals/:id', {
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
     * @name Vehicles.UpdateBookingDetailFactory
     * @description
     * UpdateBookingDetailFactory used in list and list the vehicle filters.
     * @param {string} UpdateBookingDetailFactory The name of the factory
     * @param {function()} function It uses get method for put method for update the booking details
     * @param {string} url Base url accessed in GENERAL_CONFIG.
     * @returns {object} The service contains these actions:
     *
     *      {
	 *			'update':    {method:'PUT'}
	 *		};
     */
    module.factory('UpdateVehicleRentalFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(GENERAL_CONFIG.api_url + '/vehicle_rentals/:id', {
                id: '@id'
            }, {
                'update': {
                    method: 'PUT'
                }
            }
        );
    });
    /**
     * @ngdoc service
     * @name Vehicles.GetVehicleFactory
     * @description
     * GetVehicleFactory used in list and list the vehicle filters.
     * @param {string} GetVehicleFactory The name of the factory
     * @param {function()} function It uses get method for get method for get the vehicle
     * @param {string} url Base url accessed in GENERAL_CONFIG.
     * @returns {object} The service contains these actions:
     *
     *      {
	 *			'get':    {method:'GET'}
	 *		};
     */
    module.factory('GetVehicleFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(GENERAL_CONFIG.api_url + '/vehicles/:id', {
                íd: '@id'
            }, {
                'get': {
                    method: 'GET'
                }
            }
        );
    });
    /**
     * @ngdoc service
     * @name Vehicles.VehiclePaymentFactory
     * @description
     * VehiclePaymentFactory used in vehicle payment method.
     * @param {string} VehiclePaymentFactory The name of the factory
     * @param {function()} function It uses Post method for vehicle payment
     * @param {string} url Base url accessed in GENERAL_CONFIG.
     * @returns {object} The service contains these actions:
     *
     *      {
	 *			'save':    {method:'POST'}
	 *		};
     */
    module.factory('VehiclePaymentFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(GENERAL_CONFIG.api_url + '/vehicles/paynow', {}
            , {
                'save': {
                    method: 'POST'
                }
            }
        );
    });
    /**
     * @ngdoc service
     * @name Vehicles.MaintenanceVehicles
     * @description
     * MaintenanceVehicles used to get maintenance vehicles
     * @param {string} MaintenanceVehicles The name of the factory
     * @param {function()} function It uses get method for get maintenance vehicles
     * @param {string} url Base url accessed in GENERAL_CONFIG.
     * @returns {object} The service contains these actions:
     *
     *      {
	 *			'get':    {method:'GET'},
	 *			'save':    {method:'POST'},
	 *			'update':    {method:'PUT'},
	 *			'delete':    {method:'DELETE'}
	 *		};
     */
    module.factory('MaintenanceVehicles', function ($resource, GENERAL_CONFIG) {
        return $resource(GENERAL_CONFIG.api_url + '/unavailable_vehicles/:id', {
                id: '@id'
            }
            , {
                'get': {
                    method: 'GET'
                },
                'save': {
                    method: 'POST'
                },
                'update': {
                    method: 'PUT'
                },
                'delete': {
                    method: 'DELETE'
                }
            }
        );
    });
    /**
     * @ngdoc service
     * @name Vehicles.EditMaintenanceVehicles
     * @description
     * EditMaintenanceVehicles used to get maintenance vehicles
     * @param {string} EditMaintenanceVehicles The name of the factory
     * @param {function()} function It uses get method for get maintenance vehicles
     * @param {string} url Base url accessed in GENERAL_CONFIG.
     * @returns {object} The service contains these actions:
     *
     *      {
	 *			'get':    {method:'GET'}
	 *		};
     */
    module.factory('EditMaintenanceVehicles', function ($resource, GENERAL_CONFIG) {
        return $resource(GENERAL_CONFIG.api_url + '/unavailable_vehicles/:id/edit', {
                id: '@id'
            }
            , {
                'get': {
                    method: 'GET'
                },
            }
        );
    });
    /**
     * @ngdoc service
     * @name Vehicles.GetVehicleFeedbackFactory
     * @description
     * GetVehicleFeedbackFactory used to get vehicle feedbacks
     * @param {string} GetVehicleFeedbackFactory The name of the factory
     * @param {function()} function It uses get method for get vehicle feedbacks
     * @param {string} url Base url accessed in GENERAL_CONFIG.
     * @returns {object} The service contains these actions:
     *
     *      {
	 *			'get':    {method:'GET'}
	 *		};
     */
    module.factory('GetVehicleFeedbackFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(GENERAL_CONFIG.api_url + '/vehicle_feedbacks', {}
            , {
                'get': {
                    method: 'GET'
                },
            }
        );
    });

})(angular.module('BookorRent.Vehicles'));
