(function (module) {
    /**
     * @ngdoc directive
     * @name CurrencyConversion.directive:currencyConversion
     * @module CurrencyConversions
     * @scope
     * This directive used to load converted currency details.
     * @restrict EA
     * @description
     * This directive used to load the converted currency details in element.
     */
    module.directive('currencyConversion', function () {
        return {
            restrict: 'EA',
            templateUrl: "Plugins/CurrencyConversions/currency_conversion.tpl.html",
            controller: function ($scope, $rootScope, $element, $attrs, $state, Flash, $filter) {
                var model = this;
                $scope.getCurrentCurrency = function (currency_id) {
                    angular.forEach($rootScope.currencies, function (data) {
                        if (data.id == currency_id) {
                            localStorage.setItem('convertedCurrency', JSON.stringify(data));
                            $scope.currency_code = data.code;
                        }
                    });
                };
                $scope.$watch('defaultcurrency', function (currency_id) {
                    var default_currency, currency_obj, currency;
                    if ($scope.defaultcurrency !== '') {
                        default_currency = $scope.defaultcurrency;
                        currency_obj = localStorage.getItem('convertedCurrency');
                        currency_obj = JSON.parse(currency_obj);
                        currency = (currency_obj) ? currency_obj : default_currency;
                        $scope.currency = currency.id;
                        $scope.currency_code = currency.code;

                    }
                });
            },
            scope: {
                getCurrentCurrency: '&',
                defaultcurrency: '='
            }
        };
    });

}(angular.module("BookorRent.CurrencyConversions")));
