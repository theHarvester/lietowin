var flipItem;
var flipped = false;

$(document).ready(function(){
    flipItem = $('.flipMe').first();

    $('.button').click(function(){
        if(!$(this).hasClass('inactive')){
            $(this).parent('form').submit();
        }
    });

    $('.white_content .exit').click(function(){
        $(this).parent('.white_content').hide();
        $('.black_overlay').hide();
    });

    $('.causeLoginLb').click(function(){
        $('.white_content').show();
        $('.black_overlay').show();
    });

    $(window).scroll(function(){
        if(!flipped && checkVisible(flipItem)){
            flipped = true;
            $('.flipMe').addClass('animated flipInY');

        }
    });


});

function checkVisible( elm ) {
    var vpH = $(window).height(), // Viewport Height
        st = $(window).scrollTop(), // Scroll Top
        y = $(elm).offset().top;

    return !(y > (vpH + st));
}