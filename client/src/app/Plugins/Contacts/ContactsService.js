(function (module) {
    /**
     * @ngdoc service
     * @name Contacts.ContactsFactory
     * @description
     * ContactsFactory used to store the contact details.
     * @param {function()} function It used to save the data.
     * @param {string} url Base url accessed in GENERAL_CONFIG.
     * @returns {object} The service contains these actions:
     *
     *      {
	 *			'save':   {method:'POST'}
	 *		};
     */
    module.factory('ContactsFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(
            GENERAL_CONFIG.api_url + '/contacts', {}, {
                'save': {
                    method: 'POST'
                }
            }
        );
    });

})(angular.module("BookorRent.Contacts"));
