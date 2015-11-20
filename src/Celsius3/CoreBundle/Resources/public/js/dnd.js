var firstColumn = $('.firstColumn > .draggable').length;
var secondColumn = $('.secondColumn > .draggable').length;

$('.connectedSortable').sortable({
    connectWith: '.connectedSortable',
    update: function (event, ui) {
        var actualFC = $('.firstColumn > .draggable').length;
        var actualSC = $('.secondColumn > .draggable').length;
        if (firstColumn > actualFC) {
            $('.firstColumn').append($('.secondColumn > .draggable').slice(0, firstColumn - actualFC));
        } else if (secondColumn > actualSC) {
            $('.secondColumn').prepend($('.firstColumn > .draggable').slice(secondColumn - firstColumn));
        }

        // Se habilita el boton
        $('.submit-catalog-data.disabled').removeClass('btn-default disabled').addClass('btn-primary enabled');
    }
}).disableSelection();

$('.submit-catalog-data.disabled').on('click', function (e) {
    e.preventDefault();
});

$(document).on('click', '.submit-catalog-data.enabled', function () {
    var ids = [];
    $('.draggable').each(function (i, elem) {
        ids.push($(elem).data('id'));
    });
    $.ajax({
        type: 'POST',
        format: 'json',
        data: {
            ids: ids
        },
        url: Routing.generate('admin_catalog_persist'),
        success: function (data) {
            $('.flashes').empty().append('<div class="alert alert-success">' +
                    '<button data-dismiss="alert" class="close" type="button">×</button>' +
                    'The Catalogs were successfully sorted.' +
                    '</div>');
            $('.submit-catalog-data.enabled').removeClass('btn-primary enabled').addClass('btn-default disabled');
        }
    });
});
