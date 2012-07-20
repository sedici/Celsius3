$(document).ready(function(){
    $('input.autocomplete').autocomplete({
        source: '/app_dev.php/user/ajax?target='+$('input.autocomplete').attr('target'),
        minLength: 2,
        select: function(event, ui) {
            $('input.container').val(ui.item.id);
        }
    })
});


