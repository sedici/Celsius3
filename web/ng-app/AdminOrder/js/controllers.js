var orderControllers = angular.module('orderControllers', []);

orderControllers.controller('OrderCtrl', function($scope, $http, Order, Request, Catalog, CatalogSearch) {
    function findInstitution(tree) {
        var node = _.first(tree);
        if (node.child.length === 0) {
            return _.isUndefined(node.institution) ? null : node.institution;
        } else {
            var institution = findInstitution(node.child);
            return institution === null ? (_.isUndefined(node.institution) ? null : node.institution) : institution;
        }
    }

    $scope.sortableOptions = {
        connectWith: '.catalogSortable',
        update: function(event, ui) {
            var id = ui.item.data('id');
            var result = $(ui.item.sortable.droptarget).parents('table.table').data('type');
            var catalog = _.first($scope.catalogsWithSearches.filter(function(item) {
                return !_.isUndefined(item.search) && item.search.id === id;
            }));
            catalog.search.result = result;
            $scope.updateCatalog(catalog);
        },
        items: ">*:not(.sort-disabled)"
    };

    $scope.observations = '';

    $scope.search_results = [
        {value: 'found', text: 'Found'},
        {value: 'partially_found', text: 'Partially found'},
        {value: 'not_found', text: 'Not found'},
        {value: 'non_searched', text: 'Non searched'}
    ];

    $scope.select = {};

    Order.get({id: document_id}, function(order) {
        $scope.order = order;
    });

    $scope.request = Request.get({order_id: document_id}, function(request) {
        $scope.catalogs = Catalog.query(function(catalogs) {
            CatalogSearch.query({request_id: request.id}, function(searches) {
                $scope.catalogsWithSearches = angular.copy(catalogs).map(function(item) {
                    item.search = _.first(searches.filter(function(search) {
                        return search.catalog.id === item.id;
                    }));
                    return item;
                });

                $scope.updateTables();
            });
        });
    });

    $scope.updateTables = function() {
        $scope.filterFound = $scope.catalogsWithSearches.filter(function(catalog) {
            return !_.isUndefined(catalog.search) && catalog.search.result === 'found';
        });

        $scope.filterPartiallyFound = $scope.catalogsWithSearches.filter(function(catalog) {
            return !_.isUndefined(catalog.search) && catalog.search.result === 'partially_found';
        });

        $scope.filterNotFound = $scope.catalogsWithSearches.filter(function(catalog) {
            return !_.isUndefined(catalog.search) && catalog.search.result === 'not_found';
        });
    }

    $scope.updateCatalog = function(catalog) {
        catalog.search.catalog = _.first($scope.catalogs.filter(function(c) {
            return c.id === catalog.id;
        }));
        catalog.search = CatalogSearch.save({request_id: $scope.request.id}, catalog.search);
        $scope.updateTables();
    }

    $scope.submitRequest = function() {
        var institution = findInstitution($scope.select.tree);
        var data = {
            observations: $scope.observations,
            provider: institution
        }

        $http.post(Routing.generate('admin_rest_order') + $scope.order.id + '/event/request', data).success(function(response) {
            if (response) {
                $('.modal').modal('hide');
            }
        });
    }
});