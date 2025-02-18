var institutionControllers = angular.module('institutionControllers', []);

institutionControllers.controller('institutionCtrl', ['$scope', '$http', '$filter', '$translate',
    function ($scope, $http, $filter, $translate) {
        'use strict';

        $scope._ = _;

        $scope.instance_id = instance_id;
        $scope.assets_version = assets_version_admin_institution;
        $scope.institution = null;

        $scope.getInstitution = function (id) {
            $http.get(Routing.generate('rest_admin_institution_get', {id: id}))
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
            $http.get(Routing.generate('rest_admin_institution_parent', {parent_id: id}))
                .then(function (response) {
                    if (response.data) {
                        $scope.institution.institutions = response.data;
                    }
                }, function (response) {
                    generateCelsiusAlert(response);
                });
        };

        $scope.getInstitutionUsers = function (id) {
            $http.get(Routing.generate('rest_admin_institution_users', {id: id}))
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
