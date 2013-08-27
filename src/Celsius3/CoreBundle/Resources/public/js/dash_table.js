$('#orderuser-accordion').one('show.bs.collapse', function() {
    $.ajax({
        type: 'get',
        format: 'json',
        url: Routing.generate('superadmin_orderusertable'),
        success: function(data) {
            data = JSON.parse(data);
            if (data.length > 0) {
                $('#orderuser-table > tbody').empty();
                $(data).each(function(i, elem) {
                    $('#orderuser-table > tbody').append(ich.orderuser(elem));
                });
            }
            $('#orderuser-table').dataTable({
                "bPaginate": false,
                "bInfo": false
            });
        }
    });
});
