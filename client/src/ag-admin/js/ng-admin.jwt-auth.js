(function e(t, n, r) {
    function s(o, u) {
        if (!n[o]) {
            if (!t[o]) {
                var a = typeof require == "function" && require;
                if (!u && a)return a(o, !0);
                if (i)return i(o, !0);
                var f = new Error("Cannot find module '" + o + "'");
                throw f.code = "MODULE_NOT_FOUND", f
            }
            var l = n[o] = {exports: {}};
            t[o][0].call(l.exports, function (e) {
                var n = t[o][1][e];
                return s(n ? n : e)
            }, l, l.exports, e, t, n, r)
        }
        return n[o].exports
    }

    var i = typeof require == "function" && require;
    for (var o = 0; o < r.length; o++)s(r[o]);
    return s
})({
    1: [function (require, module, exports) {
        var ngAdminJWTAuthService = function ($http, jwtHelper, ngAdminJWTAuthConfigurator) {
            return {
                authenticate: function (data, successCallback, errorCallback) {
                    var url = ngAdminJWTAuthConfigurator.getAuthURL();
                    return $http({
                        url: url,
                        method: 'POST',
                        data: data
                    }).then(function (response) {
                        var payload = jwtHelper.decodeToken(response.data.token);
                        if (response.data.role != 1) {
                            response.data.message = "Authentication failed";
                            errorCallback(response);
                        } else {
                            var enabled_plugins = response.data.enabled_plugins;
                            localStorage.userToken = response.data.token;
                            localStorage.userRole = response.data.role;
                            localStorage.enabled_plugins = JSON.stringify(enabled_plugins);
                            successCallback(response);
                            var customAuthHeader = ngAdminJWTAuthConfigurator.getCustomAuthHeader();
                            if (customAuthHeader) {
                                $http.defaults.headers.common[customAuthHeader.name] = customAuthHeader.template.replace('{{token}}', response.data.token);
                            } else {
                                $http.defaults.headers.common.Authorization = 'Basic ' + response.data.token;
                            }
                        }
                    }, errorCallback);
                },
                isAuthenticated: function () {
                    var token = localStorage.userToken;
                    if (!token) {
                        return false;
                    }
                    return jwtHelper.isTokenExpired(token) ? false : true;
                },
                logout: function () {
                    localStorage.removeItem('userRole');
                    localStorage.removeItem('userToken');
                    localStorage.removeItem('auth');
                    localStorage.removeItem('enabled_plugins');
                    return true;
                }
            }
        };
        ngAdminJWTAuthService.$inject = ['$http', 'jwtHelper', 'ngAdminJWTAuthConfigurator'];
        module.exports = ngAdminJWTAuthService;
    }, {}], 2: [function (require, module, exports) {
        var ngAdminJWTAuthConfiguratorProvider = function () {
            var authConfigs = {};
            this.setJWTAuthURL = function (url) {
                authConfigs._authUrl = url;
            };
            this.setCustomLoginTemplate = function (url) {
                authConfigs._customLoginTemplate = url;
            }
            this.setLoginSuccessCallback = function (callback) {
                authConfigs._loginSuccessCallback = callback;
            }
            this.setLoginErrorCallback = function (callback) {
                authConfigs._loginErrorCallback = callback;
            }
            this.setCustomAuthHeader = function (obj) {
                return authConfigs._customAuthHeader = obj;
            }
            this.$get = function () {
                return {
                    getAuthURL: function () {
                        return authConfigs._authUrl;
                    },
                    getCustomLoginTemplate: function () {
                        return authConfigs._customLoginTemplate;
                    },
                    getLoginSuccessCallback: function () {
                        return authConfigs._loginSuccessCallback;
                    },
                    getLoginErrorCallback: function () {
                        return authConfigs._loginErrorCallback;
                    },
                    getCustomAuthHeader: function () {
                        return authConfigs._customAuthHeader;
                    }
                };
            }
        };
        module.exports = ngAdminJWTAuthConfiguratorProvider;
    }, {}], 3: [function (require, module, exports) {
        var loginController = function ($scope, $rootScope, ngAdminJWTAuthService, ngAdminJWTAuthConfigurator, notification, $location) {
            this.$scope = $scope;
            this.$rootScope = $rootScope;
            this.ngAdminJWTAuthService = ngAdminJWTAuthService;
            this.ngAdminJWTAuthConfigurator = ngAdminJWTAuthConfigurator;
            this.notification = notification;
            this.$location = $location;
        };
        loginController.prototype.login = function () {
            var that = this;
            var success = this.ngAdminJWTAuthConfigurator.getLoginSuccessCallback() || function (response) {
                    that.notification.log(response.data.message, {addnCls: 'humane-flatty-success'});
                    that.$location.path('/');
                };
            var error = this.ngAdminJWTAuthConfigurator.getLoginErrorCallback() || function (response) {
                    that.notification.log(response.data.message, {addnCls: 'humane-flatty-error'});
                };
            this.ngAdminJWTAuthService.authenticate(this.data, success, error);
        };
        loginController.$inject = ['$rootScope', '$scope', 'ngAdminJWTAuthService', 'ngAdminJWTAuthConfigurator', 'notification', '$location'];
        module.exports = loginController;
    }, {}], 4: [function (require, module, exports) {
        var loginTemplate = '<div class=\"container\">\n    <form style=\"max-width: 330px; padding: 15px; margin: 0 auto;\" class=\"form-login\" name=\"loginController.form\"  ng-submit=\"loginController.login()\">\n        <h2 class=\"form-login-heading\">Please log in<\/h2>\n        <div class=\"form-group\">\n            <label for=\"inputLogin\" class=\"sr-only\">Login<\/label>\n            <input type=\"text\" id=\"inputLogin\" class=\"form-control\" placeholder=\"Login\" ng-model=\"loginController.data.login\" ng-required=\"true\" ng-minlength=\"3\" ng-enter=\"loginController.login()\">\n        <\/div>\n        <div class=\"form-group\">\n            <label for=\"inputPassword\" class=\"sr-only\">Password<\/label>\n            <input type=\"password\" id=\"inputPassword\" class=\"form-control\" placeholder=\"Password\" ng-model=\"loginController.data.password\" ng-required=\"true\" ng-minlength=\"4\" ng-enter=\"loginController.login()\">\n        <\/div>\n\n        <button class=\"btn btn-lg btn-primary btn-block\" type=\"submit\" ng-disabled=\"loginController.form.$invalid\">Login<\/button>\n    <\/form>\n<\/div>';
        module.exports = loginTemplate;
    }, {}], 5: [function (require, module, exports) {
        var logoutController = function ($scope, ngAdminJWTAuthService, $location) {
            ngAdminJWTAuthService.logout();
            $location.path('/login');
        };
        logoutController.$inject = ['$scope', 'ngAdminJWTAuthService', '$location'];
        module.exports = logoutController;
    }, {}], 6: [function (require, module, exports) {
        'use strict';
        var ngAdminJWTAuth = angular.module('ng-admin.jwt-auth', ['angular-jwt']);
        ngAdminJWTAuth.config(['$stateProvider', '$httpProvider', function ($stateProvider, $httpProvider) {
            $stateProvider.state('login', {
                parent: '',
                url: '/login',
                controller: 'loginController',
                controllerAs: 'loginController',
                templateProvider: ['ngAdminJWTAuthConfigurator', '$http', 'notification', function (configurator, $http, notification) {
                    var template = configurator.getCustomLoginTemplate();
                    if (!template) {
                        return require('./loginTemplate');
                    }
                    return $http.get(template).then(function (response) {
                        return response.data;
                    }, function (response) {
                        notification.log('Error in template loading', {addnCls: 'humane-flatty-error'});
                    });
                }],
            });
            $stateProvider.state('logout', {
                parent: '',
                url: '/logout',
                controller: 'logoutController',
                controllerAs: 'logoutController',
            });
        }]);
        ngAdminJWTAuth.run(['$q', 'Restangular', 'ngAdminJWTAuthService', '$http', '$location', '$state', '$rootScope', 'ngAdminJWTAuthConfigurator', function ($q, Restangular, ngAdminJWTAuthService, $http, $location, $state, $rootScope, ngAdminJWTAuthConfigurator) {
            $rootScope.$on('$stateChangeStart', function (event, toState, toParams, fromState, fromParams) {
                if (!ngAdminJWTAuthService.isAuthenticated()) {
                    if (toState.name != 'login') {
                        event.preventDefault();
                        var changeState = $state.go('login');
                        changeState.then(function () {
                            $rootScope.$broadcast('$stateChangeSuccess', toState.self, toParams, fromState.self, fromParams);
                        });
                    }
                    return true;
                }
                return true;
            });
            Restangular.addFullRequestInterceptor(function (response, deferred, responseHandler) {
                if (ngAdminJWTAuthService.isAuthenticated()) {
                    var customAuthHeader = ngAdminJWTAuthConfigurator.getCustomAuthHeader();
                    if (customAuthHeader) {
                        $http.defaults.headers.common[customAuthHeader.name] = customAuthHeader.template.replace('{{token}}', localStorage.userToken);
                    } else {
                        $http.defaults.headers.common.Authorization = 'Basic ' + localstorage.userToken;
                    }
                }
            });
        }]);
        ngAdminJWTAuth.controller('loginController', require('./loginController'));
        ngAdminJWTAuth.controller('logoutController', require('./logoutController'));
        ngAdminJWTAuth.provider('ngAdminJWTAuthConfigurator', require('./configuratorProvider'));
        ngAdminJWTAuth.service('ngAdminJWTAuthService', require('./authService'));
    }, {"./authService": 1, "./configuratorProvider": 2, "./loginController": 3, "./loginTemplate": 4, "./logoutController": 5}], 7: [function (require, module, exports) {
        !function () {
            angular.module("angular-jwt", ["angular-jwt.interceptor", "angular-jwt.jwt"]), angular.module("angular-jwt.interceptor", []).provider("jwtInterceptor", function () {
                this.urlParam = null, this.authHeader = "Authorization", this.authPrefix = "Bearer ", this.tokenGetter = function () {
                    return null
                };
                var e = this;
                this.$get = ["$q", "$injector", "$rootScope", function (r, t, a) {
                    return {
                        request: function (a) {
                            if (a.skipAuthorization)return a;
                            if (e.urlParam) {
                                if (a.params = a.params || {}, a.params[e.urlParam])return a
                            } else if (a.headers = a.headers || {}, a.headers[e.authHeader])return a;
                            var n = r.when(t.invoke(e.tokenGetter, this, {config: a}));
                            return n.then(function (r) {
                                return r && (e.urlParam ? a.params[e.urlParam] = r : a.headers[e.authHeader] = e.authPrefix + r), a
                            })
                        }, responseError: function (e) {
                            return 401 === e.status && a.$broadcast("unauthenticated", e), r.reject(e)
                        }
                    }
                }]
            }), angular.module("angular-jwt.jwt", []).service("jwtHelper", function () {
                this.urlBase64Decode = function (e) {
                    var r = e.replace(/-/g, "+").replace(/_/g, "/");
                    switch (r.length % 4) {
                        case 0:
                            break;
                        case 2:
                            r += "==";
                            break;
                        case 3:
                            r += "=";
                            break;
                        default:
                            throw"Illegal base64url string!"
                    }
                    return decodeURIComponent(escape(window.atob(r)))
                }, this.decodeToken = function (e) {
                    var r = e.split(".");
                    if (3 !== r.length)throw new Error("JWT must have 3 parts");
                    var t = this.urlBase64Decode(r[1]);
                    if (!t)throw new Error("Cannot decode the token");
                    return JSON.parse(t)
                }, this.getTokenExpirationDate = function (e) {
                    var r;
                    if (r = this.decodeToken(e), "undefined" == typeof r.exp)return null;
                    var t = new Date(0);
                    return t.setUTCSeconds(r.exp), t
                }, this.isTokenExpired = function (e, r) {
                    var t = this.getTokenExpirationDate(e);
                    return r = r || 0, null === t ? !1 : !(t.valueOf() > (new Date).valueOf() + 1e3 * r)
                }
            })
        }();
    }, {}]
}, {}, [7, 1, 2, 3, 4, 5, 6]);
