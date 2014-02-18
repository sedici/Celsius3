var orderControllers = angular.module('orderControllers', []);

orderControllers.controller('OrderCtrl', function($scope, Order, Request, Catalog, CatalogSearch) {
    $scope.search_results = [
        {value: 'found', text: 'Found'},
        {value: 'partially_found', text: 'Partially found'},
        {value: 'not_found', text: 'Not found'},
        {value: 'non_searched', text: 'Non searched'}
    ];

    Order.get({id: document_id}, function(order) {
        $scope.order = order;
    });

    Request.get({order_id: document_id}, function(request) {
        $scope.request = request;
        Catalog.query(function(catalogs) {
            $scope.catalogs = catalogs;
            CatalogSearch.query({request_id: request.id}, function(searches) {
                $scope.catalogsWithSearches = angular.copy(catalogs).map(function(item) {
                    item.search = _.first(searches.filter(function(search) {
                        return search.catalog.id === item.id;
                    }));
                    return item;
                });
            });
        });
    });

    $scope.filterFound = function(catalog) {
        return !_.isUndefined(catalog.search) && catalog.search.result === 'found';
    }

    $scope.filterPartiallyFound = function(catalog) {
        return !_.isUndefined(catalog.search) && catalog.search.result === 'partially_found';
    }

    $scope.filterNotFound = function(catalog) {
        return !_.isUndefined(catalog.search) && catalog.search.result === 'not_found';
    }
    
    $scope.updateCatalog = function(catalog) {
        catalog.search.catalog = _.first($scope.catalogs.filter(function(c){
            return c.id === catalog.id;
        }));
        catalog.search = CatalogSearch.save({request_id: $scope.request.id}, catalog.search);
    }
});