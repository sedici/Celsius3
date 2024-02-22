var institutionControllers = angular.module('institutionControllers', []);

institutionControllers.controller('institutionCtrl', ['$scope', '$http', '$filter', '$translate',
    function ($scope, $http, $filter, $translate) {
        'use strict';

        $scope._ = _;

        $scope.instance_id = instance_id;
        $scope.assets_version = assets_version_admin_institution;
        $scope.institution = null;

        $scope.getInstitution = function (id) {
            $http.get(Routing.generate('admin_rest_institution_get', {id: id}))
                .then(function (response) {
                    if (response.data) {
                        $scope.institution = response.data;
                        $scope.getChildrenInstitutions(id);
                        $scope.getInstitutionUsers(id);
                    }
                }, function (response) {
                    generateCelsiusAlert(response);
                });
        };

        $scope.getChildrenInstitutions = function (id) {
            $http.get(Routing.generate('admin_rest_institution_parent_get', {parent_id: id}))
                .then(function (response) {
                    if (response.data) {
                        $scope.institution.institutions = response.data;
                    }
                }, function (response) {
                    generateCelsiusAlert(response);
                });
        };

        $scope.getInstitutionUsers = function (id) {
            $http.get(Routing.generate('admin_rest_institution_users_get', {id: id}))
                .then(function (response) {
                    if (response.data) {
                        $scope.institution.users = response.data;
                    }
                }, function (response) {
                    generateCelsiusAlert(response);
                });
        };

        $scope.getInstitution(entity_id);

    }]);
