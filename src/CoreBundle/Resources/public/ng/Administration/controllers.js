var administrationControllers = angular.module('administrationControllers', []);

administrationControllers.controller('AdministrationCtrl', ['$scope', '$routeParams', '$http', 'Order', 'User', '$location', '$filter',
    function ($scope, $routeParams, $http, Order, User, $location, $filter) {
        'use strict';

        $scope.assets_version = assets_version_administration;

        $scope.type = $routeParams.type;
        $scope.sortData = {
            sort: 'o.created_at',
            direction: 'asc'
        };

        $scope.user_role = user_role;

        $scope.countSearches = function (request) {
            return _.filter(request.events, function (e) {
                return e.type === 'search' && e.result !== 'non_searched';
            }).length;
        };

        $scope.searchPending = function (request) {
            return request.states.filter(function (item) {
                return item.type === 'requested' && item.search_pending;
            }).length > 0;
        };

        $scope.loadOrders = function () {
            var state;
            if ($scope.state === 'finished') {
                state = ['delivered', 'cancelled', 'annulled'];
            } else if ($scope.state === 'searched') {
                state = ['searched', 'taken'];
            } else {
                state = $scope.state;
            }

            Order.withRequests({
                type: $scope.type,
                "state[]": state,
                page: $scope.pagination.currentPage,
                sort: $scope.sortData.sort,
                direction: $scope.sortData.direction,
                orderType: $scope.orderType
            }, function (response) {
                $scope.orders = response.orders;
                if ($scope.state === 'finished') {
                    $scope.total = $scope.orderCount.delivered + $scope.orderCount.cancelled + $scope.orderCount.annulled;
                } else if ($scope.state === 'searched') {
                    $scope.total = $scope.orderCount.searched + $scope.orderCount.taken;
                } else {
                    $scope.total = $scope.orderCount[state];
                }

                $scope.numPages = Math.ceil($scope.total / 10);

                $scope.orders.forEach(function (order) {
                    order.request = response.requests[order.id];
                });
            });


        };

        $scope.pageChanged = function () {
            $scope.loadOrders();
        };

        $scope.sort = function (field) {
            if ($scope.sortData.sort === field) {
                $scope.sortData.direction = $scope.sortData.direction === 'asc' ? 'desc' : 'asc';
            } else {
                $scope.sortData.sort = field;
                $scope.sortData.direction = 'asc';
            }

            $scope.loadOrders();
        };

        $scope.enableUser = function (user_id) {
            var data = {
                id: user_id
            };
            $http.post(Routing.generate('admin_rest_user_enable'), data)
                .then(function (response) {
                    if (response.data) {
                        User.pending(function (users) {
                            $scope.users = users;
                        });
                        $('#user-modal').modal('hide');
                    }
                }, function (response) {
                    generateCelsiusAlert(response);
                });
        };

        $scope.rejectUser = function (user_id) {
            var data = {
                id: user_id
            };
            $http.post(Routing.generate('admin_rest_user_reject'), data)
                .then(function (response) {
                    if (response.data) {
                        User.pending(function (users) {
                            $scope.users = users;
                        });
                        $('#reject-user-modal').modal('hide');
                    }
                }, function (response) {
                    generateCelsiusAlert(response);
                });
        };

        $scope.showUserModal = function (user_id) {
            $scope.currentUser = _.find($scope.users, function (user) {
                return user.id === user_id;
            });
            $('#user-modal').modal('show');
        };

        $scope.rejectUserModal = function (user_id) {
            $scope.currentUser = _.find($scope.users, function (user) {
                return user.id === user_id;
            });
            $('#reject-user-modal').modal('show');
        };

        $scope.isActive = function (state) {
            return state === $scope.state ? 'active' : '';
        };

        $scope.isActiveType = function (type) {
            return type === $scope.type ? 'active' : '';
        };

        $scope.isActiveOrderType = function (orderType) {
            return orderType === $scope.orderType ? 'active' : '';
        };

        $scope.pagination = {
            currentPage: 1,
            resultsPerPage: resultsPerPage
        };

        $scope.state = _.isUndefined($routeParams.state) ? 'created' : $routeParams.state;

        $scope.orderType = _.isUndefined($routeParams.orderType) ? 'allTypes' : $routeParams.orderType;

        $http.get(Routing.generate('admin_rest_get_other_admins'))
            .then(function (response) {

                $scope.admins = response.data;

                $scope.getSelectedAdmin = function () {
                    var admin = $scope.admins.filter(function (obj) {
                        if (obj.id === $scope.type) {
                            return true;
                        }
                        return false;
                    });
                    return admin[0];
                }

                $scope.selectedAdmin = {};
                $scope.selectedAdmin.selected = $scope.getSelectedAdmin();
            }, function (response) {
                generateCelsiusAlert(response);
            });

        if (!_.isUndefined($scope.type)) {
            $http.get(Routing.generate('admin_rest_order_count_get') + '?type=' + $scope.type + '&state=' + $scope.state + '&orderType=' + $scope.orderType)
                .then(function (response) {
                    $scope.orderCount = response.data;
                    $scope.loadOrders();
                    $scope.addMouseover();
                }, function (response) {
                    generateCelsiusAlert(response);
                });

            User.pending(function (users) {
                $scope.users = users;
            });
        }

        $scope.addMouseover = function () {
            $(document)
                .on('mouseover', 'div.main_info', function () {
                    $(this).addClass('order-on-mouseover');
                    $(this).css('cursor', 'pointer');
                })
                .on('mouseout', 'div.main_info', function () {
                    $(this).removeClass('order-on-mouseover');
                    $(this).css('cursor', 'auto');
                })
                .on('click', 'div.main_info', function () {
                    var url = Routing.generate('admin_order_show', {id: $(this).attr('data-order-id')});
                    $(location).attr("href", url);
                });
        };

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

        $scope.hasRequests = function (events) {
            var requests = events.filter(function (e) {
                return (e.type === 'sirequest' || e.type === 'mirequest');
            });

            return (requests.length > 0);
        };

        $scope.adminSelectChange = function (admin) {
            $scope.type = admin.id;

            var url = '#!/';
            if (!_.isEmpty($scope.type)) {
                url += $scope.type;
            } else {
                url += 'mine';
            }

            url += '/';
            if (!_.isEmpty($scope.state)) {
                url += $scope.state;
            } else {
                url += 'created';
            }

            url += '/';
            if (!_.isEmpty($scope.orderType)) {
                url += $scope.orderType;
            } else {
                url += 'allTypes';
            }

            location.href = url;
        };

        $scope.fullAdminName = function (admin) {
            return $filter('first_upper')(admin.surname) + ', ' + $filter('first_upper')(admin.name) + ' (' + $filter('lowercase')(admin.username) + ')';
        }

        $scope.loadDataRequests = function() {
            $http.post(Routing.generate('admin_instance_data_requests_get'))
                .then(function (response) {
                    $scope.data_requests = response.data;
                }, function (response) {
                    generateCelsiusAlert(response);
                });
        }
        $scope.loadDataRequests();
    }]);
