var orderControllers = angular.module('orderControllers', []);

orderControllers.controller('OrderCtrl', function($scope, $http, $fileUploader, $filter, Order, Request, Catalog, CatalogSearch, Event) {
    'use strict';

    function findInstitution(tree) {
        var node = _.first(tree);
        if (node.child.length === 0) {
            return _.isUndefined(node.institution) ? null : node.institution;
        } else {
            var institution = findInstitution(node.child);
            return institution === null ? (_.isUndefined(node.institution) ? null : node.institution) : institution;
        }
    }

    $scope.addToEvents = function(event) {
        $scope.events[$filter('date')(event.date, 'yyyy')].push(event);
    };

    $scope.getFileDownloadRoute = function(file) {
        return Routing.generate('admin_file_download', {request: $scope.request.id, file: file.id});
    };

    $scope.hasReceive = function(request) {
        return !_.isUndefined($scope.receptions) && $scope.receptions.filter(function(item) {
            return item.request_event.id === request.id;
        }).length > 0;
    };

    $scope.getReceive = function(request) {
        return _.first($scope.receptions.filter(function(item) {
            return item.request_event.id === request.id;
        }));
    };

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

    // Data for the request form
    $scope.select = {};

    // Data for the receive form
    $scope.receive = {};

    /**
     * Resource load
     */

    Order.get({id: document_id}, function(order) {
        $scope.order = order;
    });

    $scope.request = Request.get({order_id: document_id}, function(request) {
        $scope.catalogs = Catalog.query(function(catalogs) {
            CatalogSearch.query({request_id: request.id}, function(searches) {
                $scope.searches = searches;
                $scope.catalogsWithSearches = angular.copy(catalogs).map(function(item) {
                    item.search = _.first(searches.filter(function(search) {
                        return search.catalog.id === item.id;
                    }));
                    return item;
                });

                $scope.updateTables();
            });
        });

        Event.query({request_id: request.id, event: 'request'}, function(events) {
            $scope.requests = _.sortBy(events, function(event) {
                return event.date;
            });
        });
        Event.query({request_id: request.id, event: 'receive'}, function(events) {
            $scope.receptions = _.sortBy(events, function(event) {
                return event.date;
            });
        });
        Event.query({request_id: request.id}, function(events) {
            $scope.events = _.groupBy(events, function(event) {
                return $filter('date')(event.date, 'yyyy');
            });
        });
    });

    /**
     * Functions
     */

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
    };

    $scope.updateReceive = function(id) {
        $scope.receive.request = id;
    };

    $scope.updateCatalog = function(catalog) {
        catalog.search.catalog = _.first($scope.catalogs.filter(function(c) {
            return c.id === catalog.id;
        }));
        catalog.search = CatalogSearch.save({request_id: $scope.request.id}, catalog.search);
        $scope.searches.push(catalog.search);
        $scope.updateTables();
    };

    $scope.submitRequest = function() {
        var institution = findInstitution($scope.select.tree);
        var data = {
            observations: $scope.observations,
            provider: institution
        };

        $http.post(Routing.generate('admin_rest_order') + $scope.order.id + '/event/request', data).success(function(response) {
            if (response) {
                $scope.requests.push(response);
                $scope.addToEvents(response);
                $('#requestForm').get(0).reset();
                $scope.$broadcast('reset');
                $('.modal').modal('hide');
            }
        });
    };

    $scope.formatReceiveData = function() {
        return _.pairs($scope.receive).map(function(item) {
            return _.object([item]);
        });
    };

    $scope.uploader = $fileUploader.create({
        scope: $scope,
        url: Routing.generate('admin_rest_order') + document_id + '/event/receive'
    });

    $scope.uploader.bind('beforeupload', function(event, item) {
        item.formData = $scope.formatReceiveData();
    });

    $scope.uploader.bind('completeall', function(event, items) {
        // Se recupera el ultimo response, se lo convierte a objeto y se lo agrega a las recepciones.
        var response = JSON.parse(_.last(items)._xhr.response);
        $scope.receptions.push(response);
        $scope.addToEvents(response);
        $('.modal').modal('hide');
    });

    $scope.submitReceive = function() {
        $scope.uploader.uploadAll();
    };
});