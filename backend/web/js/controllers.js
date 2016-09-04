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

controllers.controller('DashboardController', ['$scope', '$http',
    function($scope, $http) {
        $scope.api = 'index.php?r=api/manager/dashboard';

        $http.get($scope.api).success(function(data) {
            $scope.data = data;
        })
    }
]);

controllers.controller('LoginController', ['$scope', '$http', '$window', '$location',
    function($scope, $http, $window, $location) {
        $scope.api = 'index.php?r=api/manager/login';

        $scope.login = function() {
            $scope.submitted = true;
            $scope.doSubmit = true;
            $scope.error = {};
            $http.post($scope.api, $scope.user).success(function(data) {
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
        $scope.api = 'index.php?r=api/manager/signup';

        $scope.signup = function() {
            $scope.submitted = true;
            $scope.doSubmit = true;
            $scope.error = {};
            $http.post($scope.api, $scope.user).success(function(data) {
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

controllers.controller('SliderController', ['$scope', '$http', '$routeParams',
    function($scope, $http, $routeParams) {
        $scope.api = 'index.php?r=api/slider';
        $scope.layout = 'list';

        $scope.slider = {};
        $scope.slider['link_url'] = '';
        $scope.slider['link_target'] = '_self';
        $scope.slider['img_url'] = '';
        $scope.slider['status'] = '1';

        if (!angular.isUndefined($routeParams.id)) {
            if ($routeParams.id == 0 || parseInt($routeParams.id)) {
                $scope.layout = 'edit';
                if (parseInt($routeParams.id)) {
                    $http.get($scope.api, {
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
            $http.get($scope.api).success(function(data) {
                $scope.data = data;
            });
        }

        $scope.pushSlider = function() {
            $scope.submitted = true;
            // $scope.doSubmit = true;
            $scope.error = {};
            $http.post($scope.api, $scope.slider).success(function(data) {
                console.log(data);
            }).error(function(data) {
                $scope.doSubmit = false;
                console.log(data);
            });
        }
    }
]);

controllers.controller('MediaController', ['$scope', '$http',
    function($scope, $http) {
        $scope.api = 'index.php?r=api/media';

        $http.get($scope.api).success(function(data) {
            $scope.data = data;
        });
    }
]);

controllers.controller('ContactController', ['$scope', '$http', '$window',
    function($scope, $http, $window) {
        $scope.api = 'index.php?r=site/captcha';

        $scope.contact = function() {
            $scope.submitted = true;
            $scope.error = {};
            $http.post('api/contact', $scope.contactModel).success(
                function(data) {
                    $scope.contactModel = {};
                    $scope.flash = data.flash;
                    $window.scrollTo(0,0);
                    $scope.submitted = false;
                    $scope.api = 'site/captcha' + '?' + new Date().getTime();
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