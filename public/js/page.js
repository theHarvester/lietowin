var flipItem;
var flipped = false;
var pageHeight;
var middlePageStart;
var middlePageEnd;
var middlePageTriggered;


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
            $('.flipMe').addClass('animated fadeInRight');
        }
        if($(window).scrollTop() > middlePageStart && $(window).scrollTop() < middlePageEnd){
            if(!middlePageTriggered){
                $('#top-menu .button').addClass('inverse');
                $('#top-menu .button').removeClass('normal');
                middlePageTriggered = true;
            }
        } else {
            if(middlePageTriggered){
                $('#top-menu .button').removeClass('inverse');
                $('#top-menu .button').addClass('normal');
                middlePageTriggered = false;
            }
        }
    });
    middlePageTriggered = false;
    pageHeight = $(window).height() + 50;
    if(pageHeight < 600){
        pageHeight = 600;
    }
    var middleHeight = $('#body-content .section').height() + 20;
    if(pageHeight > middleHeight){
        middleHeight = pageHeight;
    }
    middlePageStart = pageHeight - 100;
    middlePageEnd = (pageHeight + middleHeight) - 100;
    $('.page').css('height', pageHeight);
    $('#body-content').css('height', middleHeight);

    $(function() {

        // scroll handler
        var scrollToAnchor = function( id ) {

            // grab the element to scroll to based on the name
            var elem = $("a[name='"+ id +"']");

            // if that didn't work, look for an element with our ID
            if ( typeof( elem.offset() ) === "undefined" ) {
                elem = $("#"+id);
            }

            // if the destination element exists
            if ( typeof( elem.offset() ) !== "undefined" ) {

                // do the scroll
                $('html, body').animate({
                    scrollTop: elem.offset().top
                }, 500 );

            }
        };

        // bind to click event
        $("a").click(function( event ) {

            // only do this if it's an anchor link
            if ( $(this).attr("href").match("#") ) {

                // cancel default event propagation
                event.preventDefault();

                // scroll to the location
                var href = $(this).attr('href').replace('#', '')
                scrollToAnchor( href );
            }

        });

    });





});

function checkVisible( elm ) {
    var vpH = $(window).height(), // Viewport Height
        st = $(window).scrollTop(), // Scroll Top
        y = $(elm).offset().top;

    return !(y > (vpH + st));
}