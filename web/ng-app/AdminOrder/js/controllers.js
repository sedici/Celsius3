var orderControllers = angular.module('orderControllers', []);

orderControllers.controller('OrderCtrl', function($scope, Order, Request, Catalog, CatalogSearch) {
//    $http.get(Routing.generate('admin_rest_order_show', {id: document_id})).success(function(data) {
//        $scope.order = data.order;
//        $scope.request = data.request;
//        $scope.catalogs = _.values(data.catalogs).map(function(item) {
//            item.search = _.first(_.values(data.searches).filter(function(search) {
//                return search.catalog.id === item.id;
//            }));
//            return item;
//        });
//    });
    $scope.search_results = [
        {value: 'found', text: 'Found'},
        {value: 'partially_found', text: 'Partially found'},
        {value: 'not_found', text: 'Not found'},
        {value: 'non_searched', text: 'Non searched'}
    ];

    Order.get({id: document_id}, function(result) {
        $scope.order = result.data;
    });

    Request.get({order_id: document_id}, function(result) {
        $scope.request = result.data;
        //$scope.searches = CatalogSearch.query({request_id: result.data.id});
    });

    //$scope.catalogs = Catalog.query();

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