(function (module) {
    /**
     * @ngdoc service
     * @name VehicleRentals.VehicleRentalFactory
     * @description
     * VehicleRentalFactory used to list, store, filter the rental details.
     * @param {string} VehicleRentalFactory The name of the factory.
     * @param {function()} function It used to access the rental details.
     * @param {string} url Base url accessed in GENERAL_CONFIG.
     * @returns {object} The service contains these actions:
     *
     *      {
	 *			'save':   {method:'POST'},
	 *			'get':   {method:'GET'},
	 *			'list':   {method:'GET'},
	 *			'filter':   {method:'GET'}
	 *		};
     */
    module.factory('VehicleRentalFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(GENERAL_CONFIG.api_url + '/vehicle_rentals/:id', {}, {
                'save': {
                    method: 'POST'
                },
                'get': {
                    method: 'GET'
                },
                'list': {
                    method: 'GET'
                },
                'filter': {
                    method: 'GET'
                }

            }
        );
    });
    /**
     * @ngdoc service
     * @name VehicleRentals.VehicleRentalCancelFactory
     * @description
     * VehicleRentalCancelFactory used to cancel the rental.
     * @param {string} VehicleRentalCancelFactory The name of the factory.
     * @param {function()} function It used to cancel the rental.
     * @param {string} url Base url accessed in GENERAL_CONFIG.
     * @returns {object} The service contains these actions:
     *
     *      {
	 *			'cancel':   {method:'GET'}
	 *		};
     */
    module.factory('VehicleRentalCancelFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(GENERAL_CONFIG.api_url + '/vehicle_rentals/:id/cancel', {}, {
                'cancel': {
                    method: 'GET'
                }
            }
        );
    });
    /**
     * @ngdoc service
     * @name VehicleRentals.ApplyCouponFactory
     * @description
     * ApplyCouponFactory used to apply the coupon code.
     * @param {string} ApplyCouponFactory The name of the factory.
     * @param {function()} function It used to apply the coupon code.
     * @param {string} url Base url accessed in GENERAL_CONFIG.
     * @returns {object} The service contains these actions:
     *
     *      {
	 *			'update':   {method:'POST'}
	 *		};
     */
    module.factory('ApplyCouponFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(
            GENERAL_CONFIG.api_url + '/vehicle_coupons/:id', {
                id: '@id',
            }, {
                'update': {
                    method: 'POST'
                }
            }
        );
    });
    /**
     * @ngdoc service
     * @name VehicleRentals.PaymentFactory
     * @description
     * PaymentFactory used to apply the payment.
     * @param {string} PaymentFactory The name of the factory
     * @param {function()} function It used to apply the payment.
     * @param {string} url Base url accessed in GENERAL_CONFIG.
     * @returns {object} The service contains these actions:
     *
     *      {
	 *			'save':   {method:'POST'}
	 *		};
     */
    module.factory('PaymentFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(
            GENERAL_CONFIG.api_url + '/vehicle_rentals/:id/paynow', {
                id: '@id',
            }, {
                'save': {
                    method: 'POST'
                }
            }
        );
    });
    /**
     * @ngdoc service
     * @name VehicleRentals.VehicleRentalStatusFactory
     * @description
     * VehicleRentalStatusFactory used to get the booking status.
     * @param {string} VehicleRentalStatusFactory The name of the factory
     * @param {function()} function It used to get the booking status.
     * @param {string} url Base url accessed in GENERAL_CONFIG.
     */
    module.factory('VehicleRentalStatusFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(
            GENERAL_CONFIG.api_url + '/vehicle_rental_status', {}, {}
        );
    });
    /**
     * @ngdoc service
     * @name VehicleRentals.ItemsOrderFactory
     * @description
     * ItemsOrderFactory used to get the item orders.
     * @param {string} ItemsOrderFactory The name of the factory
     * @param {function()} function It used to get the item orders.
     * @param {string} url Base url accessed in GENERAL_CONFIG.
     * @returns {object} The service contains these actions:
     *
     *      {
	 *			'get':   {method:'GET'},
	 *			'filter':   {method:'GET'}
	 *		};
     */
    module.factory('ItemsOrderFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(
            GENERAL_CONFIG.api_url + '/item_orders', {}, {
                'get': {
                    method: 'GET'
                },
                'filter': {
                    method: 'GET'
                }
            }
        );
    });
    /**
     * @ngdoc service
     * @name VehicleRentals.VehicleRentalMessageFactory
     * @description
     * VehicleRentalMessageFactory used to get the booked item messages.
     * @param {string} VehicleRentalMessageFactory The name of the factory
     * @param {function()} function It used to get the booked item messages.
     * @param {string} url Base url accessed in GENERAL_CONFIG.
     * @returns {object} The service contains these actions:
     *
     *      {
	 *			'get':   {method:'GET'}
	 *		};
     */
    module.factory('VehicleRentalMessageFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(
            GENERAL_CONFIG.api_url + '/item_user_messages/:id', {
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
     * @name VehicleRentals.SavePrivateNoteFactory
     * @description
     * SavePrivateNoteFactory used to save private note for booked item.
     * @param {string} SavePrivateNoteFactory The name of the factory
     * @param {function()} function It used to save private note for booked item.
     * @param {string} url Base url accessed in GENERAL_CONFIG.
     * @returns {object} The service contains these actions:
     *
     *      {
	 *			'save':   {method:'POST'}
	 *		};
     */
    module.factory('SavePrivateNoteFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(
            GENERAL_CONFIG.api_url + '/private_notes', {}, {
                'save': {
                    method: 'POST'
                }
            }
        );
    });
    /**
     * @ngdoc service
     * @name VehicleRentals.GetGatewaysFactory
     * @description
     * GetGatewaysFactory used to get all payment gateways.
     * @param {string} GetGatewaysFactory The name of the factory
     * @param {function()} function It used to get all payment gateways.
     * @param {string} url Base url accessed in GENERAL_CONFIG.
     * @returns {object} The service contains these actions:
     *
     *      {
	 *			'get':   {method:'GET'}
	 *		};
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
     * @name VehicleRentals.GetCountries
     * @description
     * GetCountries used to list the countries.
     * @param {string} GetCountries The name of the factory
     * @param {function()} function It used to list the countries.
     * @param {string} url Base url accessed in GENERAL_CONFIG.
     * @returns {object} The service contains these actions:
     *
     *      {
	 *			'get':   {method:'GET'}
	 *		};
     */
    module.factory('GetCountries', function ($resource, GENERAL_CONFIG) {
        return $resource(GENERAL_CONFIG.api_url + '/countries', {}, {
                'get': {
                    method: 'GET'
                }
            }
        );
    });
    /**
     * @ngdoc service
     * @name VehicleRentals.ConfirmVehicleRental
     * @description
     * ConfirmVehicleRental used to list and change booking status id.
     * @param {string} ConfirmVehicleRental The name of the factory
     * @param {function()} function It used to list and change booking status id.
     * @param {string} url Base url accessed in GENERAL_CONFIG.
     * @returns {object} The service contains these actions:
     *
     *      {
	 *			'confirm':   {method:'GET'}
	 *		};
     */
    module.factory('ConfirmVehicleRental', function ($resource, GENERAL_CONFIG) {
        return $resource(GENERAL_CONFIG.api_url + '/vehicle_rentals/:id/confirm', {
                id: '@id',
            }, {
                'confirm': {
                    method: 'GET'
                }
            }
        );
    });
    /**
     * @ngdoc service
     * @name VehicleRentals.RejectVehicleRental
     * @description
     * RejectVehicleRental used to reject the rental.
     * @param {string} RejectVehicleRental The name of the factory
     * @param {function()} function It used to reject the rental.
     * @param {string} url Base url accessed in GENERAL_CONFIG.
     * @returns {object} The service contains these actions:
     *
     *      {
	 *			'reject':   {method:'GET'}
	 *		};
     */
    module.factory('RejectVehicleRental', function ($resource, GENERAL_CONFIG) {
        return $resource(GENERAL_CONFIG.api_url + '/vehicle_rentals/:id/reject', {
                id: '@id',
            }, {
                'reject': {
                    method: 'GET'
                }
            }
        );
    });
    /**
     * @ngdoc service
     * @name VehicleRentals.VehicleDisputesFactory
     * @description
     * VehicleDisputesFactory used to list dispute types and closed types.
     * @param {string} VehicleDisputesFactory The name of the factory
     * @param {function()} function It used to list dispute types and closed types.
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
     * @name VehicleRentals.VehicleDisputeFactory
     * @description
     * VehicleDisputeFactory used to create disputes.
     * @param {string} VehicleDisputeFactory The name of the factory
     * @param {function()} function It used to create disputes.
     * @param {string} url Base url accessed in GENERAL_CONFIG.
     * @returns {object} The service contains these actions:
     *
     *      {
	 *			'get':   {method:'GET'}
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
     * @name VehicleRentals.VehicleDisputeResolveFactory
     * @description
     * VehicleDisputeResolveFactory used to resolve disputes.
     * @param {string} VehicleDisputeResolveFactory The name of the factory
     * @param {function()} function It used to resolve disputes.
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
    /**
     * @ngdoc service
     * @name VehicleRentals.checkInFactory
     * @description
     * checkInFactory used to checkin vehicel rentals.
     * @param {string} checkInFactory The name of the factory
     * @param {function()} function It used to checkin vehicel rentals.
     * @param {string} url Base url accessed in GENERAL_CONFIG.
     * @returns {object} The service contains these actions:
     *
     *      {
	 *			'checkin':   {method:'GET'}
	 *		};
     */
    module.factory('checkInFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(GENERAL_CONFIG.api_url + '/vehicle_rentals/:id/checkin', {
                id: '@id'
            }, {
                'checkin': {
                    method: 'GET'
                }
            }
        );
    });
    /**
     * @ngdoc service
     * @name VehicleRentals.checkOutFactory
     * @description
     * checkOutFactory used to checkout vehicel rentals.
     * @param {string} checkOutFactory The name of the factory
     * @param {function()} function It used to checkout vehicel rentals.
     * @param {string} url Base url accessed in GENERAL_CONFIG.
     * @returns {object} The service contains these actions:
     *
     *      {
	 *			'checkout':   {method:'GET'}
	 *		};
     */
    module.factory('checkOutFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(GENERAL_CONFIG.api_url + '/vehicle_rentals/:id/checkout', {
                id: '@id'
            }, {
                'checkout': {
                    method: 'POST'
                }
            }
        );
    });
})(angular.module('BookorRent.VehicleRentals'));
