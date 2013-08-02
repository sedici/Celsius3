$.ajax({
    type: 'post',
    format: 'json',
    data: 'graphic=usersPerInstance',
    url: Routing.generate('superadmin_graphic'),
    success: function(data) {
        $.plot($('#user-graphic'), [[[0, 0], [1, 1]]], {yaxis: {max: 1}});
    }
});