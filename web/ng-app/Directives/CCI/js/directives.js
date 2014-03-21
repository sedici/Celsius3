cciWidget.directive('cciWidget', function(Country, City, Institution) {
    function link(scope, element, attrs) {
        scope.select_count = 0;
        scope.countries = Country.query();
        scope.select.country = {};
        scope.select.city = {};
        scope.select.tree = [{
                id: 'institution' + scope.select_count,
                name: 'institution' + scope.select_count,
                institutions: [],
                child: []
            }];
        scope.countryChanged = function() {
            scope.cities = City.query({country_id: scope.select.country});
            scope.institutions = Institution.query({country_id: scope.select.country}, function(institutions) {
                _.first(scope.select.tree).institutions = institutions;
            });
        }
        scope.cityChanged = function() {
            scope.institutions = Institution.query({country_id: scope.select.country, city_id: scope.select.city}, function(institutions) {
                _.first(scope.select.tree).institutions = institutions;
            });
        }
        scope.institutionChanged = function(data) {
            scope.select_count++;
            Institution.parent({parent_id: data.institution}, function(institutions) {
                data.child = [{
                        id: 'institution' + scope.select_count,
                        name: 'institution' + scope.select_count,
                        institutions: institutions,
                        child: []
                    }];
            });
        }
    }

    return {
        restrict: 'E',
        templateUrl: '/ng-app/Directives/CCI/partials/cci.html',
        link: link,
        scope: true
    }
});