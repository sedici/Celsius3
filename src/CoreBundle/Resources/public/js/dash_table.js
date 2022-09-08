var $ = require('jquery');

$('#orderuser-accordion').one('click', function () {
    'use strict';

    $.ajax({
        type: 'get',
        format: 'json',
        url: Routing.generate('superadmin_orderusertable'),
        success: function success(data) {
            data = JSON.parse(data);
            if (data.length > 0) {
                $('#orderuser-table > tbody').empty();
                $(data).each(function (i, elem) {
                    var name = elem.name ? elem.name : '';
                    var provisionOrders = elem.provisionOrders ? elem.provisionOrders : '';
                    var pendingUsers = elem.pendingUsers ? elem.pendingUsers : '';
                    var searchedOrders = elem.searchedOrders ? elem.searchedOrders : '';
                    var requestedOrders = elem.requestedOrders ? elem.requestedOrders : '';
                    var satisfiedOrders = elem.satisfiedOrders ? elem.satisfiedOrders : '';
                    var totalUsers = elem.totalUsers ? elem.totalUsers : '';

                    var totalOrders = elem.totalOrders ? elem.totalOrders : '';

                    var html = '<tr><td>' + name + '</td><td>' + elem.country + '</td><td>' + totalOrders + '</td><td>' + provisionOrders + '</td><td></td><td>' + searchedOrders + '</td><td>' + requestedOrders + '</td><td>' + satisfiedOrders + '</td><td>' + pendingUsers + '</td><td>' + totalUsers + '</td></tr>';
                    $('#orderuser-table > tbody').append(html);
                });
            }
            $('#orderuser-table').dataTable({
                "bPaginate": false,
                "bInfo": false
            });
        }
    });
});
