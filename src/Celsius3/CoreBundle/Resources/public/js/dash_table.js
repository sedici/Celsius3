$('#orderuser-table').dataTable({
    "bPaginate": false,
    "bInfo": false,
});

/*
 * Orders per Instance
 */
$.ajax({
    type: 'post',
    format: 'json',
    url: Routing.generate('superadmin_orderusertable'),
    success: function(data) {

    }
});