(function (module) {
    /**
     * @ngdoc service
     * @name Pages.PageFactory
     * @description
     * PageFactory used to fetch the page details.
     * @param {function()} function It used to fetch the data.
     * @param {string} url Base url accessed in GENERAL_CONFIG.
     * @param {string} slug Page slug.
     * @param {string} iso2 Content iso.
     * @returns {object} The service contains these actions:
     *
     *      {
	 *			'get':   {method:'GET'}
	 *		};
     */
    module.factory('PageFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(GENERAL_CONFIG.api_url + '/page/:slug/:iso2', {
                slug: '@slug',
                iso2: '@iso2'
            }, {
                'get': {
                    method: 'GET'
                }
            }
        );
    });
})(angular.module("BookorRent.Pages"));
