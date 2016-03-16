cciWidget.directive('cciWidget', function ($translate, Country, City, Institution) {
    function Node(count) {
        this.id = 'institution' + count;
        this.name = 'institution' + count;
        this.institutions = [];
        this.child = [];
        this.institution = {};
    }

    function link(scope, element, attrs) {
        scope.formatInstitution = function (element) {
            if (_.isEmpty(element)) {
                return element;
            }

            if (element.text === '') {
                return element;
            }
            var institution = JSON.parse(element.text);
            if (_.isUndefined(institution.celsius_instance)) {
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
            node.institution = institution.id;
            scope.institutionChanged(node);
            if (_.isUndefined(institution.parent)) {
                return [node];
            } else {
                var parentNode = new Node(count - 1);
                scope.buildTree(parentNode, institution.parent, count - 1);
                parentNode.child = [node];
                return [parentNode];
            }
        };
        scope.setInstitutionFromPreset = function (institution) {
            var count = 0;
            var top = institution;
            while (!_.isUndefined(top.parent)) {
                top = top.parent;
                count++;
            }
            var node = new Node(count);
            scope.select.tree = scope.buildTree(node, institution, count);
            scope.select.country = !_.isUndefined(top.country) ? top.country.id : {};
            scope.select.city = !_.isUndefined(top.city) ? top.city.id : {};
            scope.countryChanged();
        };
        scope.updateFilter = function () {
            if (_.isEmpty(scope.select.city)) {
                scope.countryChanged();
            } else {
                scope.cityChanged();
            }
        };

        scope.filterOwnInstance = function (institutions) {
            return institutions.filter(function (obj) {
                if (!_.isUndefined(obj.celsius_instance)) {
                    return (parseInt(obj.celsius_instance.id) !== parseInt(instance_id));
                }
                return true;
            });
        };

        scope.countryChanged = function () {
            console.log(scope.mustFilterOwnInstance);
            scope.cities = City.query({country_id: scope.select.country});
            scope.institutions = Institution.query({country_id: scope.select.country, filter: scope.select.filter}, function (institutions) {
                _.first(scope.select.tree).institutions = (scope.mustFilterOwnInstance) ? scope.filterOwnInstance(institutions) : institutions;
            });
        };
        scope.cityChanged = function () {
            scope.institutions = Institution.query({country_id: scope.select.country, city_id: scope.select.city, filter: scope.select.filter}, function (institutions) {
                _.first(scope.select.tree).institutions = (scope.mustFilterOwnInstance) ? scope.filterOwnInstance(institutions) : institutions;
            });
        };
        scope.institutionChanged = function (data) {
            Institution.parent({parent_id: data.institution}, function (institutions) {
                if (data.child.length === 0 && institutions.length > 0) {
                    var node = new Node(++scope.select_count);
                    node.institutions = (scope.mustFilterOwnInstance) ? scope.filterOwnInstance(institutions) : institutions;
                    data.child = [node];
                } else if (data.child.length > 0) {
                    _.first(data.child).institutions = (scope.mustFilterOwnInstance) ? scope.filterOwnInstance(institutions) : institutions;
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
});
