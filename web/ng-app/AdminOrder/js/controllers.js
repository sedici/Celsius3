var orderControllers = angular.module('orderControllers', []);

orderControllers.controller('OrderCtrl', function($scope, $http) {
    $http.get(Routing.generate('admin_rest_order_show', {id: document_id})).success(function(data) {
        $scope.order = data.order;
        $scope.request = data.request;
        $scope.catalogs = _.values(data.catalogs).map(function(item) {
            item.search = _.first(_.values(data.searches).filter(function(search) {
                return search.catalog.id === item.id;
            }));
            return item;
        });
    });
    $scope.search_results = [
        {value: 'found', text: 'Found'},
        {value: 'partially_found', text: 'Partially found'},
        {value: 'not_found', text: 'Not found'},
        {value: 'non_searched', text: 'Non searched'}
    ];

    $scope.updateCatalog = function(catalog) {
        $http.post().success(function(data){
            
        });
    }

    $scope.filterFound = function(catalog) {
        return !_.isUndefined(catalog.search) && catalog.search.result === 'found';
    }

    $scope.filterPartiallyFound = function(catalog) {
        return !_.isUndefined(catalog.search) && catalog.search.result === 'partially_found';
    }

    $scope.filterNotFound = function(catalog) {
        return !_.isUndefined(catalog.search) && catalog.search.result === 'not_found';
    }
});