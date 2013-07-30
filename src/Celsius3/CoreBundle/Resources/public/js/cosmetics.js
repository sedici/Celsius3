$('.autoellipsis').dotdotdot({
    watch: true
});

$('.navbar .nav-main li').each(function(index, element) {
    if (window.location.href.indexOf($(element).children('a').attr('href').split('?')[0]) !== -1) {
        $(element).attr('class', 'active');
    }
});

/**
 * Bloqueo de la interfaz al hacer una petición.
 */
$(document).ajaxStart(function() {
    $('#activity').activity({segments: 8, width:3, space: 1, length: 3});
}).ajaxStop(function() {
    $('#activity').activity(false);
});