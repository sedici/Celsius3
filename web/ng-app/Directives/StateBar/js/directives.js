stateBar.directive('stateBar', function() {
    function link(scope, element, attrs) {
        scope.updateStateline = function() {
            var canvas = new fabric.Canvas('stateline');

            fabric.Image.fromURL('/bundles/celsius3core/images/stateline/circulo_gris_claro.png', function(oImg) {
                canvas.add(oImg);
            });
        };
        
        scope.updateStateline();
    }

    return {
        restrict: 'E',
        templateUrl: '/ng-app/Directives/StateBar/partials/state_bar.html',
        link: link,
        scope: true
    };
});