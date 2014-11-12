$('.user-enable').on('click', function (e) {
    e.preventDefault();
    var item = $(this).parents('.user-item');
    var userId = item.find('input:hidden').val();
    $.ajax({
        url: Routing.generate('admin_user_enable', {id: userId})
    }).done(function (data) {
        $(item).remove();
    });
});

$(document).on('click', '.user-enable-modal', function (e) {
    e.preventDefault();
    var userId = $('.modal-body').children('input:hidden').val();
    var link = $('input:hidden[value="' + userId + '"]').parents('.user-item').find('.user-enable');
    link.click();
    $('#myModal').modal('hide');
});