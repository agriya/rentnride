(function (module) {
    /**
     * @ngdoc controller
     * @name Message.controller:MessageController
     * @description
     *
     * 1.This is MessageController having the methods setMetaData, init and inbox.
     **/
    module.controller('MessageController', function ($state, $auth, $scope, Flash, $http, $filter, $rootScope, $location, InboxFactory, GetMessageFactory, StarMailFactory, SentMailFactory) {
        var model = this;
        $scope.maxSize = 5;
        var params = {};
        $scope.sidebar_tpl = 'Message/message_sidebar.tpl.html';
        /**
         * @ngdoc method
         * @name setMetaData
         * @methodOf Message.controller:MessageController
         * @description
         * This method is used to set the meta data dynamically by using the angular.element
         *
         */
        $scope.setMetaData = function () {
            var pageTitle = $filter("translate")("Inbox");
            var fullUrl = $location.absUrl();
            var appUrl = $rootScope.settings['scheme_name'] + ":/" + $location.url();
            angular.element('html head meta[property="og:title"], html head meta[name="twitter:title"]').attr("content", $rootScope.settings['site.name'] + " | " + pageTitle);
            angular.element('meta[property="al:ios:url"], meta[property="al:ipad:url"], meta[property="al:android:url"], meta[property="al:windows_phone:url"], html head meta[name="twitter:app:url:iphone"], html head meta[name="twitter:app:url:ipad"], html head meta[name="twitter:app:url:googleplay"]').attr('content', appUrl);
            angular.element('meta[property="og:url"]').attr('content', fullUrl);
        };
        /**
         * @ngdoc method
         * @name getInboxMessages
         * @methodOf Message.controller:MessageController
         * @description
         * This method is used to get the auth user messages
         *
         */
        $scope.getInboxMessages = function () {
            $scope.messages = [];
            params.page = $scope.currentPage;
            InboxFactory.list(params).$promise.then(function (response) {
                if (response.data) {
                    $scope.messages = response.data;
                    $scope._metadata = response.meta.pagination;
                }
            });
        };

        /**
         * @ngdoc method
         * @name getSentMessages
         * @methodOf Message.controller:MessageController
         * @description
         * This method is used to get the auth user sent messages
         *
         */
        $scope.getSentMessages = function() {
            $scope.messages = [];
            params.page = $scope.currentPage;
            SentMailFactory.list(params).$promise.then(function(response) {
                if(response.data) {
                    $scope.messages = response.data;
                    $scope._metadata = response.meta.pagination;
                }
            });
        };

        /**
         * @ngdoc method
         * @name getStarMessages
         * @methodOf Message.controller:MessageController
         * @description
         * This method is used to get the auth user messages
         *
         */
        $scope.getStarMessages = function () {
            $scope.messages = [];
            params.page = $scope.currentPage;
            StarMailFactory.list(params).$promise.then(function (response) {
                if (response.data) {
                    $scope.messages = response.data;
                    $scope._metadata = response.meta.pagination;
                }
            });
        };
        /**
         * @ngdoc method
         * @name getMessages
         * @methodOf Message.controller:MessageController
         * @description
         * This method is used to get the auth user message
         *
         */
        $scope.getMessage = function () {
            GetMessageFactory.get({id: $state.params.id}).$promise.then(function (response) {
                $scope.message = {};
                if (response.item_users !== undefined) {
                    $scope.message = response;
                    $scope.message.item_users = response.item_users.booking;
                } else {
                    $scope.message = response;
                }
            });
        };
        /**
         * @ngdoc method
         * @name init
         * @methodOf Message.controller:MessageController
         * @description
         * This method is used to initialize the meta data that is already set by setmetadata() method.
         *
         */
        $scope.init = function () {
            $scope.setMetaData();
            $scope.currentPage = ($scope.currentPage !== undefined) ? parseInt($scope.currentPage) : 1;
            $rootScope.pageTitle = $rootScope.settings['site.name'] + " | " + $filter("translate")("Inbox");
            $scope.type = $state.params.type;
            if($scope.type == 'inbox') {
                $scope.getInboxMessages();
            } else if($scope.type == 'sentmail') {
                $scope.getSentMessages();
            } else if($scope.type == 'starred') {
                $scope.getStarMessages();
            }
            if ($state.params.id !== undefined && $state.params.action !== undefined) {
                $scope.getMessage();
                $scope.message_type = $state.params.action;
            }
        };
        $scope.init();
        $scope.paginate = function (pageno) {
            $scope.currentPage = parseInt($scope.currentPage);
            $scope.init();
        };
        /**
         * @ngdoc method
         * @name StarMessage
         * @methodOf Message.controller:MessageController
         * @description
         * This method is used to store the message to starred
         *
         */
         $scope.StarMessage = function(message_id, is_star) {
             GetMessageFactory.update({id:message_id,stared:is_star}, function(response){
				 if(is_star === 1){
					 Flash.set($filter("translate")("Message unstarred successfully!"), 'success', true);
				 }
				 if(is_star === 0){
					 Flash.set($filter("translate")("Message starred successfully!"), 'success', true);
				 }                 
                 if($scope.type == 'inbox') {
                     $scope.getInboxMessages();
                 } else if($scope.type == 'sentmail') {
                     $scope.getSentMessages();
                 } else if($scope.type == 'starred') {
                     $scope.getStarMessages();
                 }
             }, function(error){
                 Flash.set($filter("translate")("Message could not be updated!"), 'error', false);
             });
         };
    });
}(angular.module("BookorRent.Messages")));
