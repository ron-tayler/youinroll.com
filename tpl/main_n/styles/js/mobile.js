$(document).on('ready', function(){
    if ($(window).width() < 768 ||  $(window).width() < 640 || $(window).width() < 320) {
        $('#wrapper').removeClass('haside');
        $('#wrapper').css('margin-top', '50px !important');
        $('#confSlider').remove();
        $('#categoryBlock').remove();

        let $nomrmal = $('.sidescroll').find('.sidebar-nav.blc').first();

        $('.sidescroll').empty();

        $('.sidescroll').append($nomrmal);
    }
});