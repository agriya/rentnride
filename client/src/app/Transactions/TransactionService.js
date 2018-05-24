(function (module) {
    /**
     * @ngdoc service
     * @name Transaction.TransactionFactory
     * @description
     * TransactionFactory used in list and filter the transaction.
     * @param {string} TransactionFactory The name of the factory
     * @param {function()} function It uses get method for listing, defines the url
     */
    module.factory('TransactionFactory', function ($resource, GENERAL_CONFIG) {
        return $resource(GENERAL_CONFIG.api_url + '/transactions', {}, {
                'list': {
                    method: 'GET'
                },
                'filter': {
                    method: 'GET'
                }
            }
        );
    });
})(angular.module("BookorRent.Transactions"));
