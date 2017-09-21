var stateBar = angular.module('stateBar');

stateBar.directive('stateBar', ['$translate',
    function ($translate) {
        var states_order = ['searched', 'requested', 'approval_pending', 'received', 'delivered'];
        var states = [
            {
                name: 'searched',
                back_image: 'circulo_gris_claro.png',
                max_image: 'circulo_gris_oscuro.png',
                current_image: 'circulo_celeste.png',
                final_image: 'circulo_verde.png',
                left: 50,
                top: 60,
                text_left: 42,
                text_top: 25,
            },
            {
                name: 'requested',
                back_image: 'circulo_gris_claro.png',
                max_image: 'circulo_gris_oscuro.png',
                current_image: 'circulo_celeste.png',
                final_image: 'circulo_verde.png',
                search_pending_image: 'circulo_con_exclamacion.png',
                left: 280,
                top: 60,
                text_left: 265,
                text_top: 25,
                line: {
                    back_image: 'linea_gris_claro.png',
                    max_image: 'linea_gris_oscuro.png',
                    current_image: 'linea_celeste.png',
                    final_image: 'linea_verde.png',
                    left: 75,
                    top: 70,
                    width: 210
                }
            },
            {
                name: 'received',
                back_image: 'circulo_gris_claro.png',
                max_image: 'circulo_gris_oscuro.png',
                current_image: 'circulo_celeste.png',
                final_image: 'circulo_verde.png',
                left: 510,
                top: 60,
                text_left: 498,
                text_top: 25,
                line: {
                    back_image: 'linea_gris_claro.png',
                    max_image: 'linea_gris_oscuro.png',
                    current_image: 'linea_celeste.png',
                    final_image: 'linea_verde.png',
                    left: 305,
                    top: 70,
                    width: 210
                }
            },
            {
                name: 'delivered',
                back_image: 'tilde_gris.png',
                final_image: 'tilde_verde.png',
                left: 742,
                top: 47,
                text_left: 738,
                text_top: 25,
                line: {
                    back_image: 'linea_gris_claro.png',
                    max_image: 'linea_gris_oscuro.png',
                    current_image: 'linea_celeste.png',
                    final_image: 'linea_verde.png',
                    left: 535,
                    top: 70,
                    width: 210
                }
            }
        ];

        function link(scope, element, attrs) {
            scope.drawLine = function (canvas) {
                if (scope.request.current_state === 'delivered') {
                    states.forEach(function (state) {
                        fabric.Image.fromURL('/bundles/celsius3core/images/stateline/' + state.final_image, function (oImg) {
                            oImg.setTop(state.top);
                            oImg.setLeft(state.left);
                            canvas.add(oImg);
                            if (!_.isUndefined(state.line)) {
                                fabric.Image.fromURL('/bundles/celsius3core/images/stateline/' + state.line.final_image, function (oImg) {
                                    oImg.setTop(state.line.top);
                                    oImg.setLeft(state.line.left);
                                    oImg.setWidth(state.line.width);
                                    canvas.add(oImg);
                                });
                            }
                            $translate(state.name).then(function (name) {
                                var c = new fabric.Text(name, {
                                    left: state.text_left,
                                    top: state.text_top,
                                    fontSize: 14
                                });
                                canvas.add(c);
                            });
                        });
                    });
                } else {
                    states.forEach(function (state) {
                        $translate(state.name).then(function (name) {
                            var c = new fabric.Text(name, {left: state.text_left, top: state.text_top, fontSize: 14});
                            canvas.add(c);
                        });
                        if (!_.isUndefined(_.find(scope.request.states, function (s) {
                                return s.type === state.name;
                            }))) {
                            fabric.Image.fromURL('/bundles/celsius3core/images/stateline/' + state.max_image, function (oImg) {
                                oImg.setTop(state.top);
                                oImg.setLeft(state.left);
                                canvas.add(oImg);
                                oImg.sendToBack();
                                if (!_.isUndefined(state.line)) {
                                    fabric.Image.fromURL('/bundles/celsius3core/images/stateline/' + state.line.max_image, function (oImg) {
                                        oImg.setTop(state.line.top);
                                        oImg.setLeft(state.line.left);
                                        oImg.setWidth(state.line.width);
                                        canvas.add(oImg);
                                        oImg.sendToBack();
                                    });
                                }
                            });
                        }
                        if (scope.request.current_state !== 'created') {
                            var image = state.current_image;
                            if (state.name === 'requested') {
                                var requested = _.first(scope.request.states.filter(function (item) {
                                    return item.type === 'requested';
                                }));
                                if (!_.isUndefined(requested) && requested.search_pending) {
                                    image = state.search_pending_image;
                                }
                            }
                            if (states_order.indexOf(scope.request.current_state) >= states_order.indexOf(state.name)) {
                                fabric.Image.fromURL('/bundles/celsius3core/images/stateline/' + image, function (oImg) {
                                    oImg.setTop(state.top);
                                    oImg.setLeft(state.left);
                                    canvas.add(oImg);
                                    oImg.bringToFront();
                                    if (!_.isUndefined(state.line)) {
                                        fabric.Image.fromURL('/bundles/celsius3core/images/stateline/' + state.line.current_image, function (oImg) {
                                            oImg.setTop(state.line.top);
                                            oImg.setLeft(state.line.left);
                                            oImg.setWidth(state.line.width);
                                            canvas.add(oImg);
                                            oImg.bringToFront();
                                        });
                                    }
                                });
                            }
                        }
                        fabric.Image.fromURL('/bundles/celsius3core/images/stateline/' + state.back_image, function (oImg) {
                            oImg.setTop(state.top);
                            oImg.setLeft(state.left);
                            canvas.add(oImg);
                            oImg.sendToBack();
                            if (!_.isUndefined(state.line)) {
                                fabric.Image.fromURL('/bundles/celsius3core/images/stateline/' + state.line.back_image, function (oImg) {
                                    oImg.setTop(state.line.top);
                                    oImg.setLeft(state.line.left);
                                    oImg.setWidth(state.line.width);
                                    canvas.add(oImg);
                                    oImg.sendToBack();
                                });
                            }
                        });
                    });
                }
            };

            scope.updateStateline = function () {
                var canvas = new fabric.StaticCanvas('stateline');
                scope.drawLine(canvas);
            };

            scope.$on('updated', function () {
                scope.updateStateline();
            });
        }

        return {
            restrict: 'E',
            templateUrl: 'state_bar.html',
            link: link,
            scope: true
        };
    }]);
