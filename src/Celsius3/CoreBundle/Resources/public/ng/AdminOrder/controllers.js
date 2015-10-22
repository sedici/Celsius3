var orderControllers = angular.module('orderControllers', ['ngFileUpload']);

orderControllers.controller('OrderCtrl', function ($scope, $http, Upload, $filter, $translate, Order, Request, Catalog, Event, Contact, MailTemplate, CatalogResult) {
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

    $scope._ = _;

    $scope.contains = function (list, item) {
        return _.contains(list, item);
    };

    $scope.currentState = function (request) {
        return _.first(request.states.filter(function (item) {
            return item.is_current === true;
        }));
    };

    $scope.countSearches = function () {
        if (_.isUndefined($scope.searches)) {
            return 0;
        } else {
            return $scope.searches.reduce(function (p, c) {
                return p + (c.result !== 'non_searched' ? 1 : 0);
            }, 0);
        }
    };

    $scope.groupEvents = function (events) {
        var interesting = events.filter(function (item) {
            return item.type !== 'creation' && (item.type !== 'search' || item.result !== 'non_searched');
        });

        if (interesting.length === 0) {
            return null;
        } else {
            return _.groupBy(interesting, function (event) {
                return $filter('date')(event.created_at, 'yyyy');
            });
        }
    };

    $scope.getFileDownloadRoute = function (file) {
        return Routing.generate('admin_file_download_file', {request: $scope.request.id, file: file.id});
    };

    $scope.hasReceive = function (request) {
        return !_.isUndefined($scope.receptions) && $scope.receptions.filter(function (item) {
            if (item.type === 'sireceive') {
                return item.request_event.id === request.id;
            } else {
                if (!_.isUndefined(request.remote_instance)) {
                    return item.request.instance.id === request.remote_instance.id;
                } else if (!_.isUndefined(item.request_event)) {
                    return item.request_event.id === request.id;
                }
            }
        }).length > 0;
    };

    $scope.getReceive = function (request) {
        return _.first($scope.receptions.filter(function (item) {
            if (item.type === 'sireceive') {
                return item.request_event.id === request.id;
            } else {
                if (!_.isUndefined(request.remote_instance)) {
                    return item.request.instance.id === request.remote_instance.id;
                } else if (!_.isUndefined(item.request_event)) {
                    return item.request_event.id === request.id;
                }
            }
        }));
    };

    $scope.hasReclaim = function (event) {
        return !_.isUndefined($scope.reclaims) && $scope.reclaims.filter(function (item) {
            if (['sirequest', 'mirequest'].indexOf(event.type) !== -1) {
                return !_.isUndefined(item.request_event) && item.request_event.id === event.id;
            } else {
                return !_.isUndefined(item.receive_event) && item.receive_event.id === event.id;
            }
        }).length > 0;
    };

    $scope.hasApprove = function (receive) {
        return !_.isUndefined($scope.approvals) && $scope.approvals.filter(function (item) {
            return item.receive_event.id === receive.id;
        }).length > 0;
    };

    $scope.getTitle = function (order) {
        if (order.material_data.type === 'journal') {
            if (!_.isUndefined(order.material_data.journal)) {
                return order.material_data.journal.name;
            } else {
                return order.material_data.other;
            }
        } else {
            return order.material_data.title;
        }
    };

    $scope.hasResult = function (catalog) {
        var result = _.find($scope.catalogResults, function (result) {
            return catalog.id === result.catalog.id;
        });

        return !_.isUndefined(result) && result.matches > 0;
    };

    $scope.sortableOptions = {
        connectWith: '.catalogSortable',
        update: function (event, ui) {
            var id = ui.item.data('id');
            var result = $(ui.item.sortable.droptarget).parents('table.table').data('type');
            var catalog = _.first($scope.catalogsWithSearches.filter(function (item) {
                return !_.isUndefined(item.search) && parseInt(item.search.id) === parseInt(id);
            }));
            catalog.search.result = result;
            $scope.updateCatalog(catalog);
        },
        items: ">*:not(.sort-disabled)"
    };

    $translate('selectContact').then(function (selectContact) {
        $scope.select2Contacts = {
            placeholder: selectContact,
            allowClear: true
        };
    });

    $translate('selectTemplate').then(function (selectTemplate) {
        $scope.select2Templates = {
            placeholder: 'Select a mail template',
            allowClear: true
        };
    });

    $translate('requestTooltipTitle').then(function (requestTooltipTitle) {
        $scope.requestTooltip = {
            "title": requestTooltipTitle,
            "placement": "top",
            "trigger": "hover"
        };
    });

    $translate('cancelTooltipTitle').then(function (cancelTooltipTitle) {
        $scope.cancelTooltip = {
            "title": cancelTooltipTitle,
            "placement": "top",
            "trigger": "hover"
        };
    });

    $translate('reclaimTooltipTitle').then(function (reclaimTooltipTitle) {
        $scope.reclaimTooltip = {
            "title": reclaimTooltipTitle,
            "placement": "top",
            "trigger": "hover"
        };
    });

    $translate('approveTooltipTitle').then(function (approveTooltipTitle) {
        $scope.approveTooltip = {
            "title": approveTooltipTitle,
            "placement": "top",
            "trigger": "hover"
        };
    });

    $translate('reuploadTooltipTitle').then(function (reuploadTooltipTitle) {
        $scope.reuploadTooltip = {
            "title": reuploadTooltipTitle,
            "placement": "top",
            "trigger": "hover"
        };
    });

    $translate('viewAll').then(function (viewAll) {
        $scope.viewAllTooltip = {
            "title": viewAll,
            "placement": "top",
            "trigger": "hover"
        };
    });

    $scope.institutionTooltip = function (institution) {
        if (institution.type !== 'institution') {
            return {};
        }
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

    $translate(['found', 'partiallyFound', 'notFound', 'nonSearched']).then(function (translations) {
        $scope.search_results = [
            {value: 'found', text: translations.found},
            {value: 'partially_found', text: translations.partiallyFound},
            {value: 'not_found', text: translations.notFound},
            {value: 'non_searched', text: translations.nonSearched}
        ];
    });

    // Data for the request form
    $scope.select = {};

    // Form data
    $scope.forms = {
        request: {},
        receive: {},
        cancel: {},
        reclaim: {},
        email: {},
        reupload: {}
    };

    // Form container for validation
    $scope.formNames = {};

    // Data for the file list modal
    $scope.files = {
        list: []
    };

    /**
     * Resource load
     */

    $scope.order = Order.get({id: entity_id}, function (order) {
        $scope.request = Request.get({order_id: order.id}, function (request) {
            $scope.forms.receive.delivery_type = request.owner.pdf ? 'PDF' : 'Printed';

            Catalog.query(function (catalogs) {
                $scope.catalogs = catalogs;
                $scope.updateTables();
            });

            $scope.filesToUpload = [];

            $scope.addFilesToUpload = function (files) {
                for (var i = 0; i < files.length; i++) {
                    $scope.filesToUpload.push(files[i]);
                }
            };

            $scope.upload = function (files) {
                if (files && files.length) {
                    for (var i = 0; i < files.length; i++) {
                        var file = files[i];
                        Upload.upload({
                            url: Routing.generate('admin_rest_event') + '/' + $scope.request.id + '/receive',
                            fields: {
                                'request': $scope.forms.receive.request,
                                'observations': $scope.forms.receive.observations,
                                'delivery_type': $scope.forms.receive.delivery_type
                            },
                            file: file
                        }).progress(function (evt) {
                            $scope.progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                            $scope.atualFileName = evt.config.file.name;
                        }).success(function (data, status, headers, config) {
                            $scope.updateTables();
                            $('.modal').modal('hide');
                            var template = $scope.forms.receive.delivery_type === 'PDF' ? 'order_printed_reconfirm' : 'order_printed';
                            $http.get(Routing.generate('admin_rest_template_compiled_get', {code: template, request_id: $scope.request.id})).success(function (response) {
                                if (response) {
                                    $scope.contacts = null;
                                    $scope.templates = response;
                                    $scope.forms.email.address = $scope.request.owner.email;
                                    $scope.forms.email.subject = response[0].title;
                                    $scope.forms.email.text = response[0].text;
                                    $('#email-modal').modal('show');
                                }
                            });
                            $scope.filesToUpload = [];
                        });
                    }
                }
            };

            $scope.filesToUploadBasic = [];

            $scope.addFilesToUploadBasic = function (files) {
                for (var i = 0; i < files.length; i++) {
                    var notExist = true;
                    for (var j = 0; j < $scope.filesToUploadBasic.length; j++) {
                        if ($scope.filesToUploadBasic[j].name === files[i].name) {
                            notExist = false;
                        }
                    }
                    if (notExist) {
                        $scope.filesToUploadBasic.push(files[i]);
                    }
                }
            };

            $scope.uploadBasic = function (files) {
                if (files && files.length) {
                    for (var i = 0; i < files.length; i++) {
                        var file = files[i];
                        Upload.upload({
                            url: Routing.generate('admin_rest_event') + '/' + $scope.request.id + '/upload',
                            fields: {
                                'request': $scope.forms.reupload.request
                            },
                            file: file
                        }).progress(function (evt) {
                            $scope.progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                            $scope.atualFileName = evt.config.file.name;
                        }).success(function (data, status, headers, config) {
                            $scope.updateTables();
                            $('.modal').modal('hide');
                            $scope.filesToUploadBasic = [];
                        });
                    }
                }
            };

            $scope.filesToReupload = [];

            $scope.addFilesToReupload = function (files) {
                for (var i = 0; i < files.length; i++) {
                    $scope.filesToReupload.push(files[i]);
                }
            };

            $scope.reupload = function (files) {
                if (files && files.length) {
                    for (var i = 0; i < files.length; i++) {
                        var file = files[i];
                        Upload.upload({
                            url: Routing.generate('admin_rest_event') + '/' + $scope.request.id + '/reupload',
                            fields: {
                                'request': $scope.forms.reupload.request,
                                'receive': $scope.forms.reupload.receive,
                            },
                            file: file
                        }).progress(function (evt) {
                            $scope.progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                            $scope.atualFileName = evt.config.file.name;
                        }).success(function (data, status, headers, config) {
                            $scope.updateTables();
                            $('.modal').modal('hide');
                            $scope.filesToReupload = [];
                        });
                    }
                }
            };

        });
    });

    /**
     * Functions
     */

     $scope.previousSearches = function(catalog_id) {
         return _.filter($scope.catalogResultsOrder, function(result) {
             return result.catalog.id == catalog_id;
         });
     };

     $scope.hasPreviousSearches = function(catalog) {
         return _.filter($scope.catalogResultsOrder, function(result) {
             return result.catalog.id == catalog.id;
         }).length !== 0;
     };

    $scope.searchCatalog = function (term) {
        $scope.catalogsWithSearches = _.each(angular.copy($scope.catalogs).filter(function (catalog) {
            var regex = new RegExp(term, 'i');
            var cond = false;
            if (!_.isUndefined(catalog.institution)) {
                cond = regex.test(catalog.institution.name);
            }
            return regex.test(catalog.name) || cond;
        }), function (item) {
            item.search = _.find($scope.searches, function (search) {
                return search.catalog.id === item.id;
            });
        });
    };

    $scope.basicMode = function () {
        $http.post(Routing.generate('admin_rest_event') + '/' + $scope.request.id + '/take').success(function (response) {
            if (response) {
                $scope.updateTables();
            }
        });
    };

    $scope.advancedMode = function () {
        $scope.advanced = true;
    };

    $scope.updateTables = function () {
        Request.get({order_id: entity_id}, function (request) {
            $scope.request = request;

            CatalogResult.get({order_id: entity_id}, function(response) {
                $scope.catalogResults = response.results;
                $scope.catalogResultsOrder = response.searches;
            });

            Event.query({request_id: $scope.request.id}, function (events) {
                $scope.groupedEvents = $scope.groupEvents(events);

                $scope.take = events.filter(function (event) {
                    return event.type === 'take';
                });

                $scope.searches = _.sortBy(events.filter(function (event) {
                    return event.type === 'search';
                }), function (event) {
                    return event.date;
                });

                $scope.requests = _.sortBy(events.filter(function (event) {
                    return event.type === 'sirequest' || event.type === 'mirequest';
                }), function (event) {
                    return event.date;
                });

                $scope.receptions = _.sortBy(events.filter(function (event) {
                    return event.type === 'sireceive' || event.type === 'mireceive' || event.type === 'upload';
                }), function (event) {
                    return event.date;
                });

                $scope.reclaims = events.filter(function (event) {
                    return event.type === 'reclaim';
                });

                $scope.approvals = events.filter(function (event) {
                    return event.type === 'approve';
                });

                $scope.catalogsWithSearches = _.each(angular.copy($scope.catalogs), function (item) {
                    item.search = _.find($scope.searches, function (search) {
                        return search.catalog.id === item.id;
                    });
                });

                $scope.filterFound = $scope.catalogsWithSearches.filter(function (catalog) {
                    return !_.isUndefined(catalog.search) && catalog.search.result === 'found';
                });

                $scope.filterPartiallyFound = $scope.catalogsWithSearches.filter(function (catalog) {
                    return !_.isUndefined(catalog.search) && catalog.search.result === 'partially_found';
                });

                $scope.filterNotFound = $scope.catalogsWithSearches.filter(function (catalog) {
                    return !_.isUndefined(catalog.search) && catalog.search.result === 'not_found';
                });

                $scope.$broadcast('updated');
            });
        });
    };

    $scope.requestFromCatalog = function (catalog) {
        $('.modal').modal('hide');
        $scope.forms.request.provider = 'institution';
        $scope.$broadcast('preset', catalog.institution);
        $('#request-modal').modal('show');
    };

    $scope.updateReceive = function (id) {
        $scope.forms.receive.request = id;
    };

    $scope.deleteSearch = function (catalog) {
        catalog.search.result = 'non_searched';
        $scope.updateCatalog(catalog);
    };

    $scope.updateCatalog = function (catalog) {
        var data = {
            result: catalog.search.result,
            catalog_id: catalog.id
        };
        $http.post(Routing.generate('admin_rest_event') + '/' + $scope.request.id + '/search', data).success(function (response) {
            if (response) {
                catalog.search = response;
                $scope.updateTables();
            }
        });
    };

    $scope.validateRequest = function () {
        if ($scope.forms.request.provider === 'web' || $scope.forms.request.provider === 'author' || !_.isEmpty(findInstitution($scope.select.tree))) {
            $scope.ccierror = '';
            $scope.submitRequest();
        } else {
            $scope.ccierror = 'has-error';
        }
    };

    $scope.submitRequest = function () {
        var provider;
        if ($scope.forms.request.provider === 'institution') {
            provider = findInstitution($scope.select.tree);
        } else {
            provider = $scope.forms.request.provider;
        }
        var data = {
            observations: $scope.forms.request.observations,
            provider: provider
        };

        $http.post(Routing.generate('admin_rest_event') + '/' + $scope.request.id + '/request', data).success(function (response) {
            if (response) {
                $scope.updateTables();
                $('#requestForm').get(0).reset();
                $scope.$broadcast('reset');
                $('.modal').modal('hide');

                if (_.isUndefined(response.provider.celsius_instance) && response.provider.type !== 'web') {
                    $scope.contacts = Contact.query({institution_id: response.provider.id});
                    $scope.templates = MailTemplate.query();
                    $('#email-modal').modal('show');
                }
            }
        });
    };

    $scope.emailModal = function (email) {
        $scope.contacts = null;
        $scope.templates = MailTemplate.query();
        $scope.forms.email.address = email;
        $('#email-modal').modal('show');
    };

    $scope.formatUploadData = function (form) {
        return _.pairs(form).map(function (item) {
            return _.object([item]);
        });
    };

    $scope.submitReceive = function () {
        $scope.upload($scope.filesToUpload);
    };

    $scope.submitUpload = function () {
        $scope.uploadBasic($scope.filesToUploadBasic);
    };

    $scope.submitReupload = function () {
        $scope.reupload($scope.filesToReupload);
    };

    $scope.validateCancel = function () {
        if ($scope.formNames.cancel.$valid) {
            $scope.cancelerror = '';
            $scope.cancelRequest();
        } else {
            $scope.cancelerror = 'has-error';
        }
    };

    $scope.validateCancelOrder = function () {
        if ($scope.formNames.cancel.$valid) {
            $scope.cancelerror = '';
            $scope.cancel();
        } else {
            $scope.cancelerror = 'has-error';
        }
    };

    $scope.cancelRequest = function () {
        var data = {
            observations: $scope.forms.cancel.observations,
            request: $scope.forms.cancel.request
        };

        $http.post(Routing.generate('admin_rest_event') + '/' + $scope.request.id + '/cancel', data).success(function (response) {
            $scope.updateTables();
            $('#cancelForm').get(0).reset();
            $('.modal').modal('hide');
        });
    };

    $scope.cancel = function () {
        var data = {
            observations: $scope.forms.cancel.observations
        };

        $http.post(Routing.generate('admin_rest_event') + '/' + $scope.request.id + '/cancel', data).success(function (response) {
            $scope.updateTables();
            $('#cancelForm').get(0).reset();
            $('.modal').modal('hide');
            $http.get(Routing.generate('admin_rest_template_compiled_get', {code: 'order_cancel', request_id: $scope.request.id})).success(function (response) {
                if (response) {
                    $scope.contacts = null;
                    $scope.templates = response;
                    $scope.forms.email.address = $scope.request.owner.email;
                    $scope.forms.email.subject = response[0].title;
                    $scope.forms.email.text = response[0].text;
                    $('#email-modal').modal('show');
                }
            });
        });
    };

    $scope.validateReclaim = function () {
        if ($scope.formNames.reclaim.$valid) {
            $scope.reclaimerror = '';
            if (!_.isUndefined($scope.forms.reclaim.request)) {
                $scope.reclaimRequest();
            } else if (!_.isUndefined($scope.forms.reclaim.receive)) {
                $scope.reclaimReception();
            }
        } else {
            $scope.reclaimerror = 'has-error';
        }
    };

    $scope.reclaimRequest = function () {
        var data = {
            observations: $scope.forms.reclaim.observations,
            request: $scope.forms.reclaim.request
        };

        $http.post(Routing.generate('admin_rest_event') + '/' + $scope.request.id + '/reclaim', data).success(function (response) {
            $scope.updateTables();
            $('#reclaimForm').get(0).reset();
            $('.modal').modal('hide');
            delete $scope.forms.reclaim.request;

            if (_.isUndefined(response.request_event.provider.celsius_instance)) {
                $scope.contacts = Contact.query({institution_id: response.request_event.provider.id});
                $scope.templates = MailTemplate.query();
                $('#email-modal').modal('show');
            }
        });
    };

    $scope.reclaimReception = function () {
        var data = {
            observations: $scope.forms.reclaim.observations,
            receive: $scope.forms.reclaim.receive
        };

        $http.post(Routing.generate('admin_rest_event') + '/' + $scope.request.id + '/reclaim', data).success(function (response) {
            $scope.updateTables();
            $('#reclaimForm').get(0).reset();
            $('.modal').modal('hide');
            delete $scope.forms.reclaim.receive;

            if (_.isUndefined(response.receive_event.provider.celsius_instance)) {
                $scope.contacts = Contact.query({institution_id: response.request_event.provider.id});
                $scope.templates = MailTemplate.query();
                $('#email-modal').modal('show');
            }
        });
    };

    $scope.approve = function (receive) {
        var data = {
            receive: receive.id
        };

        $http.post(Routing.generate('admin_rest_event') + '/' + $scope.request.id + '/approve', data).success(function (response) {
            $scope.updateTables();
        });
    };

    $scope.deliver = function () {
        $http.post(Routing.generate('admin_rest_event') + '/' + $scope.request.id + '/deliver').success(function (response) {
            $scope.updateTables();
        });
    };

    $scope.annul = function () {
        $http.post(Routing.generate('admin_rest_event') + '/' + $scope.request.id + '/annul').success(function (response) {
            $scope.updateTables();
        });
    };

    $scope.pendingSearch = function () {
        $http.post(Routing.generate('admin_rest_event') + '/' + $scope.request.id + '/searchpendings').success(function (response) {
            $scope.updateTables();
        });
    };

    $scope.noPendingSearch = function () {
        $http.post(Routing.generate('admin_rest_event') + '/' + $scope.request.id + '/nosearchpendings').success(function (response) {
            $scope.updateTables();
        });
    };

    $scope.undo = function () {
        $http.post(Routing.generate('admin_rest_event') + '/' + $scope.request.id + '/undo').success(function (response) {
            $scope.updateTables();
        });
    };

    $scope.contactChanged = function () {
        var contact = _.find($scope.contacts, function (contact) {
            return contact.id === $scope.forms.email.contact;
        });
        $scope.forms.email.address = !_.isUndefined(contact) ? contact.email : '';
    };

    $scope.templateChanged = function () {
        var template = _.find($scope.templates, function (template) {
            return template.id === $scope.forms.email.template;
        });
        $scope.forms.email.subject = !_.isUndefined(template) ? template.title : '';
        $scope.forms.email.text = !_.isUndefined(template) ? template.text : '';
    };

    $scope.validateEmail = function () {
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

    $scope.sendEmail = function () {
        var data = {
            email: $scope.forms.email.address,
            subject: $scope.forms.email.subject,
            text: $scope.forms.email.text,
            order_id: ($scope.order !== undefined) ? $scope.order.id : -1, //FIXME no me gusta esto. Gonzalo.
        };

        $http.post(Routing.generate('admin_rest_email'), data).success(function (response) {
            if (response) {
                $scope.updateTables();
                $scope.forms.email = {};
                $('.modal').modal('hide');
            }
        });
    };

    $scope.changeFileState = function (file) {
        $http.post(Routing.generate('admin_rest_file_state', {file_id: file.id})).success(function (response) {
            if (response) {
                file.enabled = !file.enabled;
                $scope.updateTables();
            }
        });
    };

    $scope.getInteraction = function () {
        $http.get(Routing.generate("admin_rest_order_interaction") + '/' + entity_id)
                .success(function (response) {
                    $scope.interaction = response;
                });
    };
    $scope.getInteraction();

    $scope.printInstitutions = function (ins) {
        var txt = '';
        if (!_.isUndefined(ins)) {
            if (!_.isUndefined(ins.parent)) {
                txt += $scope.printInstitutions(ins.parent) + ' - ' + ins.name;
            } else {
                txt += ins.name;
            }
        }
        return txt;
    };

    $scope.getHtmlMaterialData = function () {
        if (!_.isUndefined($scope.order.material_data)) {
            var content = '';

            if ($scope.order.material_data.type === 'journal') {
                content += "<b>" + $translate.instant('journal') + "</b>";
                if (!_.isUndefined($scope.order.material_data.journal)) {
                    content += ': ' + $scope.order.material_data.journal.name + "<br>";
                } else {
                    content += ': ' + $scope.order.material_data.other + "<br>";
                }

                content += "<b>" + $translate.instant('volume') + "</b>";
                content += ': ' + $scope.order.material_data.volume + "<br>";

                content += "<b>" + $translate.instant('number') + "</b>";
                content += ': ' + $scope.order.material_data.number + "<br>";
            }

            content += "<b>" + $translate.instant('year') + "</b>";
            content += ': ' + $scope.order.material_data.year + "<br>";

            content += "<b>" + $translate.instant('title') + "</b>";
            content += ': ' + $scope.order.material_data.title + "<br>";

            content += "<b>" + $translate.instant('authors') + "</b>";
            content += ': ' + $scope.order.material_data.authors + "<br>";

            if ($scope.order.material_data.type === 'thesis') {
                content += "<b>" + $translate.instant('director') + "</b>";
                content += ': ' + $scope.order.material_data.director + "<br>";

                content += "<b>" + $translate.instant('degree') + "</b>";
                content += ': ' + $scope.order.material_data.degree + "<br>";
            }

            if ($scope.order.material_data.type === 'book') {
                content += "<b>" + $translate.instant('editor') + "</b>";
                content += ': ' + $scope.order.material_data.editor + "<br>";

                content += "<b>" + $translate.instant('chapter') + "</b>";
                content += ': ' + $scope.order.material_data.chapter + "<br>";


                content += "<b>" + $translate.instant('isbn') + "</b>";
                content += ': ' + $scope.order.material_data._i_s_b_n + "<br>";
            }

            content += "<b>" + $translate.instant('startPage') + "</b>";
            content += ': ' + $scope.order.material_data.start_page + "<br>";

            content += "<b>" + $translate.instant('endPage') + "</b>";
            content += ': ' + $scope.order.material_data.end_page + "<br>";

            return content;
        }
    }
});
