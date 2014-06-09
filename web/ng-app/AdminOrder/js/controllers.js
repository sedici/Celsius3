var orderControllers = angular.module('orderControllers', []);

orderControllers.controller('OrderCtrl', function($scope, $http, $fileUploader, $filter, $sce, Order, Request, Catalog, Event, Contact, MailTemplate) {
    'use strict';

    function findInstitution(tree) {
        var node = _.first(tree);
        if (node.child.length === 0) {
            return _.isEmpty(node.institution) ? null : node.institution;
        } else {
            var institution = findInstitution(node.child);
            return institution === null ? (_.isEmpty(node.institution) ? null : node.institution) : institution;
        }
    }

    $scope.updateStateline = function() {
        var canvas = new fabric.Canvas('stateline');

        fabric.Image.fromURL('/bundles/celsius3core/images/stateline/circulo_gris_claro.png', function(oImg) {
            canvas.add(oImg);
        });
    };

    $scope.contains = function(list, item) {
        return _.contains(list, item);
    };

    $scope.countSearches = function() {
        if (_.isUndefined($scope.searches)) {
            return 0;
        } else {
            return $scope.searches.reduce(function(p, c) {
                return p + (c.result !== 'non_searched' ? 1 : 0);
            }, 0);
        }
    };

    $scope.groupEvents = function(events) {
        var interesting = events.filter(function(item) {
            return item.type !== 'creation' && (item.type !== 'search' || item.result !== 'non_searched');
        });

        if (interesting.length === 0) {
            return null;
        } else {
            return _.groupBy(interesting, function(event) {
                return $filter('date')(event.date, 'yyyy');
            });
        }
    };

    $scope.getFileDownloadRoute = function(file) {
        return Routing.generate('admin_file_download', {request: $scope.request.id, file: file.id});
    };

    $scope.hasReceive = function(request) {
        return !_.isUndefined($scope.receptions) && $scope.receptions.filter(function(item) {
            if (item.type === 'sireceive') {
                return item.request_event.id === request.id;
            } else {
                return item.request.previous_request.id === $scope.request.id || item.request_event.id === request.id;
            }
        }).length > 0;
    };

    $scope.getReceive = function(request) {
        return _.first($scope.receptions.filter(function(item) {
            if (item.type === 'sireceive') {
                return item.request_event.id === request.id;
            } else {
                return item.request.previous_request.id === $scope.request.id || item.request_event.id === request.id;
            }
        }));
    };

    $scope.hasReclaim = function(request) {
        return !_.isUndefined($scope.reclaims) && $scope.reclaims.filter(function(item) {
            return item.request_event.id === request.id;
        }).length > 0;
    };

    $scope.hasApprove = function(receive) {
        return !_.isUndefined($scope.approvals) && $scope.approvals.filter(function(item) {
            return item.receive_event.id === receive.id;
        }).length > 0;
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

    $scope.requestTooltip = {
        "title": "You must search before making a request.",
        "placement": "top",
        "trigger": "hover"
    };

    $scope.cancelTooltip = {
        "title": "Cancel",
        "placement": "right",
        "trigger": "hover"
    };

    $scope.reclaimTooltip = {
        "title": "Reclaim",
        "placement": "right",
        "trigger": "hover"
    };

    $scope.approveTooltip = {
        "title": "Approve",
        "placement": "right",
        "trigger": "hover"
    };

    $scope.institutionTooltip = function(institution) {
        var str = '<li>' + institution.name + '</li></ul>';
        while (!_.isUndefined(institution.parent)) {
            institution = institution.parent;
            str = '<li>' + institution.name + '</li>' + str;
        }
        str = '<ul>' + str;
        return {
            title: str,
            placement: "right",
            trigger: "hover",
            html: true
        };
    };

    $scope.advanced = false;

    $scope.search_results = [
        {value: 'found', text: 'Found'},
        {value: 'partially_found', text: 'Partially found'},
        {value: 'not_found', text: 'Not found'},
        {value: 'non_searched', text: 'Non searched'}
    ];

    // Data for the request form
    $scope.select = {};

    // Form data
    $scope.forms = {
        request: {},
        receive: {},
        cancel: {},
        reclaim: {},
        email: {}
    };

    // Form container for validation
    $scope.formNames = {};

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
            url: Routing.generate('admin_rest_event') + '/' + $scope.request.id + '/receive'
        });

        $scope.uploader.bind('beforeupload', function(event, item) {
            item.formData = $scope.formatReceiveData();
        });

        $scope.uploader.bind('completeall', function(event, items) {
            // Se recupera el ultimo response, se lo convierte a objeto y se lo agrega a las recepciones.
            $scope.updateTables();
            $('.modal').modal('hide');
        });

        $scope.uploader.filters.push(function(item /*{File|HTMLInputElement}*/) {
            var type = $scope.uploader.isHTML5 ? item.type : item.value.slice(item.value.lastIndexOf('.') + 1);
            type = type.toLowerCase().slice(type.lastIndexOf('/') + 1);
            return 'pdf' === type;
        });

        $scope.uploaderBasic = $fileUploader.create({
            scope: $scope,
            url: Routing.generate('admin_rest_event') + '/' + $scope.request.id + '/upload'
        });

        $scope.uploaderBasic.bind('completeall', function(event, items) {
            $scope.updateTables();
        });

        $scope.uploaderBasic.filters.push(function pdfFilter(item /*{File|HTMLInputElement}*/) {
            var type = $scope.uploaderBasic.isHTML5 ? item.type : item.value.slice(item.value.lastIndexOf('.') + 1);
            type = type.toLowerCase().slice(type.lastIndexOf('/') + 1);
            return 'pdf' === type;
        });
    });

    /**
     * Functions
     */

    $scope.basicMode = function() {
        $http.post(Routing.generate('admin_rest_event') + '/' + $scope.request.id + '/take').success(function(response) {
            if (response) {
                $scope.updateTables();
            }
        });
    };

    $scope.advancedMode = function() {
        $scope.advanced = true;
    };

    $scope.updateTables = function() {
        Request.get({order_id: $scope.order.id}, function(request) {
            $scope.request = request;

            Event.query({request_id: $scope.request.id}, function(events) {
                $scope.groupedEvents = $scope.groupEvents(events);

                $scope.take = events.filter(function(event) {
                    return event.type === 'take';
                });

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
                    return event.type === 'sireceive' || event.type === 'mireceive' || event.type === 'upload';
                }), function(event) {
                    return event.date;
                });

                $scope.reclaims = events.filter(function(event) {
                    return event.type === 'reclaim';
                });

                $scope.approvals = events.filter(function(event) {
                    return event.type === 'approve';
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

                $scope.updateStateline();
            });
        });
    };

    $scope.requestFromCatalog = function(catalog) {
        $('.modal').modal('hide');
        $scope.$broadcast('preset', catalog.institution);
        $('#request-modal').modal('show');
    };

    $scope.updateReceive = function(id) {
        $scope.forms.receive.request = id;
    };

    $scope.deleteSearch = function(catalog) {
        catalog.search.result = 'non_searched';
        $scope.updateCatalog(catalog);
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

    $scope.validateRequest = function() {
        if (!_.isEmpty(findInstitution($scope.select.tree))) {
            $scope.ccierror = '';
            $scope.submitRequest();
        } else {
            $scope.ccierror = 'has-error';
        }
    };

    $scope.submitRequest = function() {
        var institution = findInstitution($scope.select.tree);
        var data = {
            observations: $scope.forms.request.observations,
            provider: institution
        };

        $http.post(Routing.generate('admin_rest_event') + '/' + $scope.request.id + '/request', data).success(function(response) {
            if (response) {
                $scope.updateTables();
                $('#requestForm').get(0).reset();
                $scope.$broadcast('reset');
                $('.modal').modal('hide');

                if (_.isUndefined(response.provider.celsius_instance)) {
                    $scope.contacts = Contact.query({institution_id: response.provider.id});
                    $scope.templates = MailTemplate.query();
                    $('#email-modal').modal('show');
                }
            }
        });
    };

    $scope.formatReceiveData = function() {
        return _.pairs($scope.forms.receive).map(function(item) {
            return _.object([item]);
        });
    };

    $scope.validateReceive = function() {
        $scope.delivery_type_error = '';
        $scope.files_error = '';
        if ($scope.formNames.receive.$valid) {
            $scope.submitReceive();
        } else {
            if (_.isUndefined($scope.receive.delivery_type)) {
                $scope.delivery_type_error = 'has-error';
            }
            if ($scope.uploader.queue.length === 0) {
                $scope.files_error = 'has-error';
            }
        }
    }

    $scope.submitReceive = function() {
        $scope.uploader.uploadAll();
    };

    $scope.submitUpload = function() {
        $scope.uploaderBasic.uploadAll();
    };

    $scope.validateCancel = function() {
        if ($scope.formNames.cancel.$valid) {
            $scope.cancelerror = '';
            $scope.cancelRequest();
        } else {
            $scope.cancelerror = 'has-error';
        }
    };

    $scope.validateCancelOrder = function() {
        if ($scope.formNames.cancel.$valid) {
            $scope.cancelerror = '';
            $scope.cancel();
        } else {
            $scope.cancelerror = 'has-error';
        }
    };

    $scope.cancelRequest = function() {
        var data = {
            observations: $scope.forms.cancel.observations,
            request: $scope.forms.cancel.request
        };

        $http.post(Routing.generate('admin_rest_event') + '/' + $scope.request.id + '/cancel', data).success(function(response) {
            $scope.updateTables();
            $('#cancelForm').get(0).reset();
            $('.modal').modal('hide');
        });
    };

    $scope.cancel = function() {
        var data = {
            observations: $scope.forms.cancel.observations,
        };

        $http.post(Routing.generate('admin_rest_event') + '/' + $scope.request.id + '/cancel', data).success(function(response) {
            $scope.updateTables();
            $('#cancelForm').get(0).reset();
            $('.modal').modal('hide');
        });
    };

    $scope.validateReclaim = function() {
        if ($scope.formNames.reclaim.$valid) {
            $scope.reclaimerror = '';
            $scope.reclaimRequest();
        } else {
            $scope.reclaimerror = 'has-error';
        }
    };

    $scope.reclaimRequest = function() {
        var data = {
            observations: $scope.forms.reclaim.observations,
            request: $scope.forms.reclaim.request
        };

        $http.post(Routing.generate('admin_rest_event') + '/' + $scope.request.id + '/reclaim', data).success(function(response) {
            $scope.updateTables();
            $('#reclaimForm').get(0).reset();
            $('.modal').modal('hide');

            if (_.isUndefined(response.request_event.provider.celsius_instance)) {
                $scope.contacts = Contact.query({institution_id: response.request_event.provider.id});
                $scope.templates = MailTemplate.query();
                $('#email-modal').modal('show');
            }
        });
    };

    $scope.approve = function(receive) {
        var data = {
            receive: receive.id
        };

        $http.post(Routing.generate('admin_rest_event') + '/' + $scope.request.id + '/approve', data).success(function(response) {
            $scope.updateTables();
        });
    };

    $scope.deliver = function() {
        $http.post(Routing.generate('admin_rest_event') + '/' + $scope.request.id + '/deliver').success(function(response) {
            $scope.updateTables();
        });
    };

    $scope.annul = function() {
        $http.post(Routing.generate('admin_rest_event') + '/' + $scope.request.id + '/annul').success(function(response) {
            $scope.updateTables();
        });
    };

    $scope.undo = function() {
        $http.post(Routing.generate('admin_rest_event') + '/' + $scope.request.id + '/undo').success(function(response) {
            $scope.updateTables();
        });
    };

    $scope.contactChanged = function() {
        var contact = _.find($scope.contacts, function(contact) {
            return contact.id === $scope.forms.email.contact;
        });
        $scope.forms.email.address = !_.isUndefined(contact) ? contact.email : '';
    };

    $scope.templateChanged = function() {
        var template = _.find($scope.templates, function(template) {
            return template.id === $scope.forms.email.template;
        });
        $scope.forms.email.subject = !_.isUndefined(template) ? template.title : '';
        $scope.forms.email.text = !_.isUndefined(template) ? template.text : '';
    };

    $scope.validateEmail = function() {
        if ($scope.formNames.email.$valid) {
            $scope.emailerror = '';
            $scope.subjecterror = '';
            $scope.texterror = '';
            $scope.sendEmail();
        } else {
            if (_.isUndefined($scope.forms.email.address)) {
                $scope.emailerror = 'has-error';
            }
            if (_.isUndefined($scope.forms.email.subject)) {
                $scope.subjecterror = 'has-error';
            }
            if (_.isUndefined($scope.forms.email.text)) {
                $scope.texterror = 'has-error';
            }
        }
    };

    $scope.sendEmail = function() {
        var data = {
            email: $scope.forms.email.address,
            subject: $scope.forms.email.subject,
            text: $scope.forms.email.text
        };

        $http.post(Routing.generate('admin_rest_email'), data).success(function(response) {
            if (response) {
                $scope.updateTables();
                $('#emailForm').get(0).reset();
                $('.modal').modal('hide');
            }
        });
    };
});