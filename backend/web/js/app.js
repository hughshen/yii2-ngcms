'use strict';

var app = angular.module('app', [
    'ngRoute',
    'ngAnimate',
    'services',
    'directives',
    'controllers',
]);

app.config(['$routeProvider', '$httpProvider',
    function($routeProvider, $httpProvider) {
        $routeProvider.
            when('/', {
                pageTitle: 'Dashboard',
                templateUrl: 'partials/manager/dashboard.html',
                controller: 'DashboardController'
            }).
            when('/login', {
                pageTitle: 'Login',
                templateUrl: 'partials/manager/login.html',
                controller: 'LoginController'
            }).
            when('/signup', {
                pageTitle: 'Sign Up',
                templateUrl: 'partials/manager/signup.html',
                controller: 'SignupController'
            }).
            when('/slider/:id?', {
                pageTitle: 'Slider',
                templateUrl: 'partials/dashboard/slider.html',
                controller: 'SliderController'
            }).
            when('/media', {
                pageTitle: 'Media',
                templateUrl: 'partials/dashboard/media.html',
                controller: 'MediaController'
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
            if (next.originalPath != '/signup') {
                $location.path('/login').replace();
            }
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