/***Limitar tamaño texto***/
$(document).ready(function(){

    $('.texto-cortado').each(function(){
 
        var longitud=100;
 
        if($(this).text().length > longitud){
            var texto=$(this).text().substring(0,longitud);
            var indiceUltimoEspacio= texto.lastIndexOf(' ');
            texto=texto.substring(0,indiceUltimoEspacio) +'<span class="puntos">...</span>';
 
            var primeraParte = '<span class="texto-mostrado">' + texto + '</span>';
            var segundaParte = '<span class="texto-ocultado">' + $(this).text().substring(indiceUltimoEspacio,$(this).text().length - 1) + '</span>';
 
            $(this).html(primeraParte + segundaParte);
            $(this).after('<span class="boton_mas_info">Ver mas</span>');
        }
    });
 
    $(document).on('click', '.boton_mas_info', function(){
        if ($(this).prev().find('.texto-ocultado').css('display') == 'none') {
            $(this).prev().find('.texto-ocultado').css('display','inline');
            $(this).prev().find('.puntos').css('display','none');
            $(this).text('Ver menos');
        } else {
            $(this).prev().find('.texto-ocultado').css('display','none');
            $(this).prev().find('.puntos').css('display','inline');
            $(this).html('Ver más');
        }
    });
});