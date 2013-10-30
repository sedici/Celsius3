$('.user-enable').on('click', function(e) {
    e.preventDefault();
    var item = $(this).parents('.user-item');
    var userId = item.find('input:hidden').val();
    $.ajax({
        url: Routing.generate('admin_user_enable', { id: userId })
    }).done(function(data) {
        $(item).empty().text('User enabled');
    });
});