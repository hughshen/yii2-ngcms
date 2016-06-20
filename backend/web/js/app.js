'use strict';

var app = angular.module('app', [
    'ngRoute',
    'controllers'
]);

app.config(['$routeProvider', '$httpProvider',
    function($routeProvider, $httpProvider) {
        $routeProvider.
            when('/', {
                pageTitle: 'Dashboard',
                templateUrl: 'partials/dashboard.html',
                controller: 'DashboardController'
            }).
            when('/login', {
                pageTitle: 'Login',
                templateUrl: 'partials/login.html',
                controller: 'LoginController'
            }).
            when('/slider', {
                pageTitle: 'Slider',
                templateUrl: 'partials/slider.html',
                controller: 'SliderController'
            }).
            otherwise({
                templateUrl: 'partials/404.html'
            });
        $httpProvider.interceptors.push('authInterceptor');
    }
]);

app.run(['$rootScope', '$location', '$window', function ($rootScope, $location, $window) {
    $rootScope.$on('$routeChangeStart', function (event, next, current) {
        if (Boolean(next.pageTitle)) {
            $window.document.title = next.pageTitle;
        }
        if (!$window.sessionStorage.access_token) {
            $location.path('/login').replace();
        }
    });
}]);

app.factory('authInterceptor', function ($q, $window, $location) {
    return {
        request: function (config) {
            if ($window.sessionStorage.access_token) {
                //HttpBearerAuth
                config.headers.Authorization = 'Bearer ' + $window.sessionStorage.access_token;
            }
            return config;
        },
        responseError: function (rejection) {
            if (rejection.status === 401) {
                $location.path('/login').replace();
            }
            return $q.reject(rejection);
        }
    };
});