$(document).ready(function () {
    var stickyNavTop = $('.nav').offset().top;
    var stickyNav = function () {
        var scrollTop = $(window).scrollTop();
        if (scrollTop >= stickyNavTop) {
            $('.nav').addClass('navsticky');
            $('.space_none').addClass('space');
            $('.scrollup').fadeIn();
        } else {
            $('.nav').removeClass('navsticky');
            $('.space_none').removeClass('space');
            $('.scrollup').fadeOut();
        }
    };
    stickyNav();
    $(window).scroll(function () {
        stickyNav();
    });
});

jQuery(function ($) {
    //zresetuj scrolla
    $.scrollTo(0);
    $('.scrollup').click(function () {
        $.scrollTo($('body'), 700);
    });
});


	