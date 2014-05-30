cciWidget.directive('cciWidget', function(Country, City, Institution) {
    function Node(count) {
        this.id = 'institution' + count;
        this.name = 'institution' + count;
        this.institutions = [];
        this.child = [];
        this.institution = {};
    }

    function link(scope, element, attrs) {
        scope.select_count = 0;
        scope.countries = Country.query();
        scope.select.country = {};
        scope.select.city = {};
        scope.select.tree = [new Node(scope.select_count)];
        scope.buildTree = function(node, institution, count) {
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
        scope.setInstitutionFromPreset = function(institution) {
            var count = 0;
            var top = institution;
            while (!_.isUndefined(top.parent)) {
                top = institution.parent;
                count++;
            }
            var node = new Node(count);
            scope.select.tree = scope.buildTree(node, institution, count);
            scope.select.country = !_.isUndefined(top.country) ? top.country.id : {};
            scope.select.city = !_.isUndefined(top.city) ? top.city.id : {};
            scope.countryChanged();
        };
        scope.countryChanged = function() {
            scope.cities = City.query({country_id: scope.select.country.id});
            scope.institutions = Institution.query({country_id: scope.select.country}, function(institutions) {
                _.first(scope.select.tree).institutions = institutions;
            });
        };
        scope.cityChanged = function() {
            scope.institutions = Institution.query({country_id: scope.select.country, city_id: scope.select.city}, function(institutions) {
                _.first(scope.select.tree).institutions = institutions;
            });
        };
        scope.institutionChanged = function(data) {
            Institution.parent({parent_id: data.institution}, function(institutions) {
                if (data.child.length === 0 && institutions.length > 0) {
                    var node = new Node(++scope.select_count);
                    node.institutions = institutions;
                    data.child = [node];
                } else {
                    _.first(data.child).institutions = institutions;
                }
            });
        };
        scope.$on('reset', function() {
            scope.select_count = 0;
            scope.cities = [];
            scope.select.country = {};
            scope.select.city = {};
            scope.select.tree = [new Node(scope.select_count)];
        });
        scope.$on('preset', function(event, institution) {
            scope.setInstitutionFromPreset(institution);
        });
    }

    return {
        restrict: 'E',
        templateUrl: '/ng-app/Directives/CCI/partials/cci.html',
        link: link,
        scope: true
    };
});