var ls = ls || {};

/**
 * JS функционал для блогов
 */
ls.offcanvar = (function ($) {


    this.openSlide=function () {
        $('body').addClass('open-navBar');
    };
    this.closeSlide=function () {
        $('body').removeClass('open-navBar');
    };

    this.toggleSlide=function () {

    };
    $(function () {
        $('[data-toggle="navbar"]').click(function () {
            if($('body').hasClass('open-navBar')){
                ls.offcanvar.closeSlide();
            }else {
                ls.offcanvar.openSlide();
            }
            return false;
        });
        $('.role-wrapper').swipe( {
            swipe:function(event, direction, distance, duration, fingerCount, fingerData) {

                if( $(window).width() > 767 ){
                    return false
                }
                if ($('body').hasClass('modal-open')) {
                    return false;
                }

                if (direction === 'right') { // открыть
                    ls.offcanvar.openSlide();
                    return false;
                } else if (direction === 'left') { // закрыть
                    ls.offcanvar.closeSlide();
                    return false;
                }


            },
            excludedElements: (".noSwipe, .modal"),
            allowPageScroll: "auto",
            threshold:100,
            fingers: 1,
            preventDefaultEvents: false,
            fallbackToMouseEvents: false,
        });
    });


    return this;
}).call(ls.offcanvar || {},jQuery);