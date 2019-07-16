$('.user-enable').on('click', function(e) {
    e.preventDefault();
    var item = $(this).parents('.user-item');
    var userId = item.find('input:hidden').val();
    $.ajax({
        url: Routing.generate('admin_user_enable', {
            id: userId
        })
    }).done(function(data) {
        $(item).remove();
    });
});

$(document).on('click', '.user-enable-modal', function(e) {
    e.preventDefault();
    var userId = $('.modal-body').children('input:hidden').val();
    var link = $('input:hidden[value="' + userId + '"]').parents('.user-item').find('.user-enable');
    link.click();
    $('#myModal').modal('hide');
});

$(function() {
    $('[data-toggle="popover"]').popover();
});

$(document).ready(function() {
    $('input[name="disabled-catalogs-view"]').bootstrapSwitch('state', false, true);

    $('input[name="disabled-catalogs-view"]').on('switchChange.bootstrapSwitch', function() {
        if ($(this).is(':checked')) {
            $('.catalog-disabled').css('display', 'block');
        } else {
            $('.catalog-disabled').css('display', 'none');
        }
    });

    $(document).on('click', '.dc-disabled', function(e) {
        e.preventDefault();
    });

    $(document).on('click', '.disable-double-click', function() {
        $(this).addClass('dc-disabled');
    });
});

$('.view_more').on('click', function (e) {
    e.preventDefault();

    $(this).parent('.list-group').children('.hidden').removeClass('hidden');

    $(this).addClass('hidden');
});