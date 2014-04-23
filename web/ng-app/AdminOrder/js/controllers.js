var orderControllers = angular.module('orderControllers', []);

orderControllers.controller('OrderCtrl', function($scope, $http, $fileUploader, $filter, Order, Request, Catalog, Event) {
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

    $scope.groupEvents = function(events) {
        return _.groupBy(events, function(event) {
            return $filter('date')(event.date, 'yyyy');
        });
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
        stop: function(event, ui) {
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
        Catalog.query(function(catalogs) {
            $scope.catalogs = catalogs;
            $scope.updateTables();
        });

        $scope.uploader = $fileUploader.create({
            scope: $scope,
            url: Routing.generate('admin_rest_event') + '/' + request.id + '/receive'
        });

        $scope.uploader.bind('beforeupload', function(event, item) {
            item.formData = $scope.formatReceiveData();
        });

        $scope.uploader.bind('completeall', function(event, items) {
            // Se recupera el ultimo response, se lo convierte a objeto y se lo agrega a las recepciones.
            var response = JSON.parse(_.last(items)._xhr.response);
            $scope.updateTables();
            $('.modal').modal('hide');
        });
    });

    /**
     * Functions
     */

    $scope.updateTables = function() {
        Event.query({request_id: $scope.request.id}, function(events) {
            $scope.groupedEvents = $scope.groupEvents(events);

            $scope.searches = _.sortBy(events.filter(function(event) {
                return event.type === 'search';
            }), function(event) {
                return event.date;
            });

            $scope.requests = _.sortBy(events.filter(function(event) {
                return event.type === 'sirequest' || event.type === 'mirequest';
            }), function(event) {
                return event.date;
            });

            $scope.receptions = _.sortBy(events.filter(function(event) {
                return event.type === 'sireceive' || event.type === 'mireceive';
            }), function(event) {
                return event.date;
            });

            $scope.catalogsWithSearches = _.each(angular.copy($scope.catalogs), function(item) {
                item.search = $scope.searches.find(function(search) {
                    return search.catalog.id === item.id;
                });
            });

            $scope.filterFound = $scope.catalogsWithSearches.filter(function(catalog) {
                return !_.isUndefined(catalog.search) && catalog.search.result === 'found';
            });

            $scope.filterPartiallyFound = $scope.catalogsWithSearches.filter(function(catalog) {
                return !_.isUndefined(catalog.search) && catalog.search.result === 'partially_found';
            });

            $scope.filterNotFound = $scope.catalogsWithSearches.filter(function(catalog) {
                return !_.isUndefined(catalog.search) && catalog.search.result === 'not_found';
            });
        });
    };

    $scope.updateReceive = function(id) {
        $scope.receive.request = id;
    };

    $scope.updateCatalog = function(catalog) {
        var data = {
            result: catalog.search.result,
            catalog_id: catalog.id
        };
        $http.post(Routing.generate('admin_rest_event') + '/' + $scope.request.id + '/search', data).success(function(response) {
            if (response) {
                catalog.search = response;
                $scope.updateTables();
            }
        });
    };

    $scope.submitRequest = function() {
        var institution = findInstitution($scope.select.tree);
        var data = {
            observations: $scope.observations,
            provider: institution
        };

        $http.post(Routing.generate('admin_rest_event') + '/' + $scope.request.id + '/request', data).success(function(response) {
            if (response) {
                $scope.updateTables();
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

    $scope.submitReceive = function() {
        $scope.uploader.uploadAll();
    };
});