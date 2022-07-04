$(document).ready(function() {
    $(document).on('mouseover', 'div.main_info', function() {
        $(this).addClass('order-on-mouseover');
        $(this).css('cursor', 'pointer');
    }).on('mouseout', 'div.main_info', function() {
        $(this).removeClass('order-on-mouseover');
        $(this).css('cursor', 'auto');
    }).on('click', 'div.main_info', function() {
        var url = Routing.generate('admin_order_show', {id: $(this).attr('data-order-id')})
        $(location).attr("href", url);
    });
});