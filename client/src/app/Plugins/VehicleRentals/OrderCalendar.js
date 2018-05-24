(function (module) {
    /**
     * @ngdoc controller
     * @name orderlists.controller:OrderCalendarController
     * @description
     *
     * This is items controller having the methods init(), setMetaData().
     *
     * It controls the functionality of items.
     **/
    module.controller('OrderCalendarController', function ($scope, $rootScope, $filter, Flash, $state, $location, moment, ItemsOrderFactory, calendarConfig) {
        var model = this;

        /**
         * @ngdoc method
         * @name setMetaData
         * @methodOf orderlists.controller:OrderCalendarController
         * @description
         *
         * This method will set the meta data dynamically by using the angular.element.
         **/
        $scope.setMetaData = function () {
            var pageTitle = $filter("translate")("Orders Calendar");
            var fullUrl = $location.absUrl();
            var appUrl = $rootScope.settings['scheme_name'] + ":/" + $location.url();
            angular.element('html head meta[property="og:title"], html head meta[name="twitter:title"]').attr("content", $rootScope.settings['site.name'] + " | " + pageTitle);
            angular.element('meta[property="al:ios:url"], meta[property="al:ipad:url"], meta[property="al:android:url"], meta[property="al:windows_phone:url"], html head meta[name="twitter:app:url:iphone"], html head meta[name="twitter:app:url:ipad"], html head meta[name="twitter:app:url:googleplay"]').attr('content', appUrl);
            angular.element('meta[property="og:url"]').attr('content', fullUrl);
        };

        $scope.getEvents = function() {
            ItemsOrderFactory.get({'limit':'all'}).$promise.then(function(response) {
                $scope.vehicles = response.data;
                model.events = $scope.setEvents($scope.vehicles);
            });
        };
        /**
         * @ngdoc method
         * @name init
         * @methodOf orderlists.controller:OrderCalendarController
         * @description
         * This method will initialze the page. It returns the page title.
         *
         **/
        $scope.init = function () {
            $scope.setMetaData();
            $rootScope.pageTitle = $rootScope.settings['site.name'] + " | " + $filter("translate")("Orders Calendar");
            model.calendarView = 'month';
            model.viewDate = new Date();
            model.isCellOpen = true;
            model.title = $filter("translate")("Order Calendar");
            $scope.getEvents();
            //Translate week in  calendar
            calendarConfig.i18nStrings.weekNumber = $filter("translate")("Week") +' {week}';
        };
        /**
         * @ngdoc method
         * @name setEvents
         * @methodOf orderlists.controller:OrderCalendarController
         * @description
         * This method will set the events to calendar.
         *
         **/
        $scope.setEvents = function(vehicles) {
            var eventsLists = [];
            var types = ['info', 'warning', 'primary', 'important', 'success'];
            angular.forEach(vehicles, function(value, key) {
                var start_date =  value.item_booking_start_date.replace(/(.+) (.+)/, "$1T$2Z");
                var end_date =  value.item_booking_end_date.replace(/(.+) (.+)/, "$1T$2Z");
                start_date = $filter("date")(new Date(start_date), "MMM d, y h:mm a",'+0');
                end_date = $filter('date')(new Date(end_date), 'MMM d, y h:mm a','+0');
                var name = (value.item_userable) ? value.item_userable.name: '';
                eventsList = {
                    title: name+' '+start_date+' - '+end_date,
                    type: types[key % 5],
                    startsAt: value.item_booking_start_date,
                    endsAt: value.item_booking_end_date,
                    draggable: false,
                    resizable: false,
                    editable: false,
                    deletable: false,
                    event: value
                };
                eventsLists.push(eventsList);
            });
            return eventsLists;
        };
        model.eventClicked = function(cal_event) {
            $state.go('activity', {'vehicle_rental_id':cal_event.event.id, 'action':'note'});
        };
        $scope.init();
    });
       
}(angular.module("BookorRent.VehicleRentals")));
