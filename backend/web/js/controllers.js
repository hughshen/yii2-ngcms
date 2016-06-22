'use strict';

var controllers = angular.module('controllers', []);

controllers.controller('MainController', ['$scope', '$location', '$window',
    function($scope, $location, $window) {

        $scope.menuActive = function($matchRoute) {
            return $matchRoute == $location.path();
        };

        $scope.loggedIn = function() {
            return Boolean($window.sessionStorage.access_token);
        };

        $scope.logout = function() {
            delete $window.sessionStorage.access_token;
            $location.path('/login').replace();
        };
    }
]);

controllers.controller('ContactController', ['$scope', '$http', '$window',
    function($scope, $http, $window) {
        $scope.captchaUrl = 'index.php?r=site/captcha';
        $scope.contact = function() {
            $scope.submitted = true;
            $scope.error = {};
            $http.post('api/contact', $scope.contactModel).success(
                function(data) {
                    $scope.contactModel = {};
                    $scope.flash = data.flash;
                    $window.scrollTo(0,0);
                    $scope.submitted = false;
                    $scope.captchaUrl = 'site/captcha' + '?' + new Date().getTime();
            }).error(
                function(data) {
                    angular.forEach(data, function(error) {
                        $scope.error[error.field] = error.message;
                    });
                }
            );
        };

        $scope.refreshCaptcha = function() {
            $http.get('index.php?r=site/captcha?refresh=1').success(function(data) {
                $scope.captchaUrl = data.url;
            });
        };
    }
]);

controllers.controller('DashboardController', ['$scope', '$http',
    function($scope, $http) {
        $http.get('index.php?r=api/dashboard').success(function(data) {
           $scope.data = data;
        })
    }
]);

controllers.controller('SliderController', ['$scope', '$http', '$routeParams',
    function($scope, $http, $routeParams) {

        $scope.sliderApi = 'index.php?r=api/slider';
        $scope.layout = 'list';

        $scope.slider = {};
        $scope.slider['link_target'] = '_self';
        $scope.slider['status'] = '1';

        if (!angular.isUndefined($routeParams.id)) {
            if ($routeParams.id == 0 || parseInt($routeParams.id)) {
                $scope.layout = 'edit';
                if (parseInt($routeParams.id)) {
                    $http.get('index.php?r=api/slider', {
                        params: {
                            id: $routeParams.id,
                        }
                    }).success(function(data) {
                        angular.forEach(data, function(value, index) {
                            $scope.slider[index] = value + '';
                        });
                    });
                }
            }
        } else {
            $http.get('index.php?r=api/slider').success(function(data) {
                $scope.data = data;
            });
        }

        $scope.pushSlider = function() {
            $scope.submitted = true;
            // $scope.doSubmit = true;
            $scope.error = {};
            $http.post('index.php?r=api/slider', $scope.slider).success(function(data) {
                console.log(data);
            }).error(function(data) {
                $scope.doSubmit = false;
                console.log(data);
            });
        }
    }
]);

controllers.controller('LoginController', ['$scope', '$http', '$window', '$location',
    function($scope, $http, $window, $location) {
        $scope.login = function() {
            $scope.submitted = true;
            $scope.doSubmit = true;
            $scope.error = {};
            $http.post('index.php?r=api/login', $scope.user).success(function(data) {
                $window.sessionStorage.access_token = data.access_token;
                $location.path('/').replace();
            }).error(function(data) {
                $scope.doSubmit = false;
                angular.forEach(data, function(error) {
                    $scope.error[error.field] = error.message;
                });
            });
        };
    }
]);

controllers.controller('SignupController', ['$scope', '$http', '$window', '$location',
    function($scope, $http, $window, $location) {
        $scope.signup = function() {
            $scope.submitted = true;
            $scope.doSubmit = true;
            $scope.error = {};
            $http.post('index.php?r=api/signup', $scope.user).success(function(data) {
                $window.sessionStorage.access_token = data.access_token;
                $location.path('/').replace();
            }).error(function(data) {
                $scope.doSubmit = false;
                angular.forEach(data, function(error) {
                    $scope.error[error.field] = error.message;
                });
            });
        }
    }
]);