var cciWidget = angular.module('cciWidget');

cciWidget.directive('cciWidget', ['$translate', 'Country', 'City', 'Institution',
    function ($translate, Country, City, Institution) {
        function Node(count) {
            this.id = 'institution' + count;
            this.name = 'institution' + count;
            this.institutions = [];
            this.child = [];
            this.institution = {};
            this.count = count;
        }

        function link(scope, element, attrs) {
            scope.formatInstitution = function (institution) {
                if (_.isEmpty(institution)) {
                    return;
                }

                if (_.isUndefined(institution.celsius_instance)
                    || institution.celsius_instance.invisible
                    || !institution.celsius_instance.enabled
                ) {
                    return institution.name;
                } else {
                    return institution.name + ' <span class="glyphicon glyphicon-cloud"></span>';
                }
            };

            scope.escape = function (m) {
                return m;
            };

            scope.select_count = 0;
            scope.countries = Country.query();
            scope.select.filter = '';
            scope.select.country = {};
            scope.select.city = {};
            scope.select.tree = [new Node(scope.select_count)];
            scope.buildTree = function (node, institution, count) {
                node.institution = institution;
                scope.institutionChanged(node);
                if (_.isUndefined(institution.parent)) {
                    return [node];
                } else {
                    var parentNode = new Node(count - 1);
                    parentNode.child = [node];
                    return scope.buildTree(parentNode, institution.parent, count - 1);
                }
            };
            scope.setInstitutionFromPreset = function (institution) {
                var count = 0;
                var top = institution;
                while (!_.isUndefined(top.parent)) {
                    top = top.parent;
                    count++;
                }

                scope.select.country = !_.isUndefined(top.country) ? top.country : {};
                scope.countryChanged();
                scope.select.city = !_.isUndefined(top.city) ? top.city : {};
                scope.cityChanged();

                var node = new Node(count);
                scope.select.tree = scope.buildTree(node, institution, count);
            };
            scope.updateFilter = function () {
                if (_.isEmpty(scope.select.city)) {
                    scope.countryChanged();
                } else {
                    scope.cityChanged();
                }
            };

            scope.countryChanged = function () {
                if (scope.select.country) {
                    scope.cities = City.query({
                        country_id: scope.select.country.id
                    });
                    scope.institutions = Institution.query({
                        country_id: scope.select.country.id,
                        filter: scope.select.filter
                    }, function (institutions) {
                        if (_.first(scope.select.tree).institution.country !== scope.select.country) {
                            scope.select.tree = [new Node(0)];
                        }
                        _.first(scope.select.tree).institutions = institutions;
                    });
                } else {
                    scope.cities = [];
                    scope.institutions = [];
                    scope.select.city = null;
                    scope.select.tree = [new Node(0)];
                }
            };
            scope.cityChanged = function () {
                if (scope.select.country && scope.select.city) {
                    scope.institutions = Institution.query({
                        country_id: scope.select.country.id,
                        city_id: (scope.select.city) ? scope.select.city.id : null,
                        filter: scope.select.filter
                    }, function (institutions) {
                        if (_.first(scope.select.tree).institution.city !== scope.select.city) {
                            scope.select.tree = [new Node(0)];
                        }
                        _.first(scope.select.tree).institutions = institutions;
                    });
                } else {
                    scope.institutions = [];
                    scope.select.tree = [new Node(0)];
                }
            };
            scope.institutionChanged = function (data) {
                Institution.parent({
                    parent_id: data.institution.id
                }, function (institutions) {
                    if (data.child.length === 0 && institutions.length > 0) {
                        var node = new Node(++scope.select_count);
                        node.institutions = institutions;
                        data.child = [node];
                    } else if (data.child.length > 0) {
                        _.first(data.child).institutions = institutions;
                    }
                });
            };
            scope.$on('reset', function () {
                scope.select_count = 0;
                scope.cities = [];
                scope.select.country = {};
                scope.select.city = {};
                scope.select.tree = [new Node(scope.select_count)];
            });
            scope.$on('preset', function (event, institution) {
                scope.setInstitutionFromPreset(institution);
            });

            scope.$watch('added.institution', function (a, b) {
                if (!_.isUndefined(a) && a.type === 'institution') {
                    scope.setInstitutionFromPreset(a);
                }
            });
        }

        return {
            restrict: 'E',
            templateUrl: 'cci.html',
            link: link,
            scope: true
        };
    }]);
