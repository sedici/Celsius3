var firstColumn = $('.firstColumn > .draggable').length;
var secondColumn = $('.secondColumn > .draggable').length;

$('.connectedSortable').sortable({
    connectWith: '.connectedSortable',
    update: function(event, ui) {
        var actualFC = $('.firstColumn > .draggable').length;
        var actualSC = $('.secondColumn > .draggable').length;
        if (firstColumn > actualFC) {
            $('.firstColumn').append($('.secondColumn > .draggable').slice(0, firstColumn - actualFC));
        } else if (secondColumn > actualSC) {
            $('.secondColumn').prepend($('.firstColumn > .draggable').slice(secondColumn - firstColumn));
        }
    }
}).disableSelection();

$('.submit-catalog-data').on('click', function() {
    var ids = new Array();
    $('.draggable').each(function(i, elem) {
        ids.push($(elem).data('id'));
    });
    console.log(ids);
});