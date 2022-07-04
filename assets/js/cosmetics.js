$('.navbar .main-navbar li').each(function (index, element) {
    var href = $(element).children('a').attr('href');
    if (!_.isUndefined(href)) {
        if (window.location.href.indexOf(href.split('?')[0]) !== -1) {
            $(element).attr('class', 'active');
        }
    }
});
