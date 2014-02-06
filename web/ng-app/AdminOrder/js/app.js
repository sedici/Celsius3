var orderApp = angular.module('orderApp', [
    'ngRoute',
    'ngResource',
    'orderControllers',
    'orderFilters'
]);

orderApp.config(['$routeProvider',
    function($routeProvider) {
        $routeProvider.
                when('/', {
                    templateUrl: '/ng-app/AdminOrder/partials/show.html',
                    controller: 'OrderCtrl'
                }).
                otherwise({
                    redirectTo: '/'
                });
    }]);