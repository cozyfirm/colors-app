$(document).ready(function () {
    let menu_indexes = {}; let counter = 0;

    $(".s-lm-wrapper").click(function () {
        $(".inside-links").each(function () {
            $(this).css('height', '0px').css('padding-bottom', '0px');
        });
        $(".fa-angle-right").each(function () {
            $(this).css('transform', 'rotate(0deg)');
        });

        let height = $(this).find(".inside-lm-link").length;



        if(!$(this).hasClass('active')){
            $(this).find(".inside-links").css('height', (height * 34) + 'px').css('padding-bottom', '10px');
            $(this).find(".fa-angle-right").css('transform', 'rotate(90deg)');
            $(this).addClass('active');
        }else{
            $(this).removeClass('active');
        }

        $(".s-lm-wrapper").not($(this)).removeClass('active');
    });

    // -------------------------------------------------------------------------------------------------------------- //
    let smm_open = false, smm_width = 320; // System mobile menu || System mobile menu width

    $(".system-m-i-t").click(function () {
        if(!smm_open){
            $(".s-left-menu").css('left', '0px');
        }else{
            $(".s-left-menu").css('left', '-320px');
        }
        smm_open = !smm_open;
    });

    // -------------------------------------------------------------------------------------------------------------- //

    // ** Notifications -- show hide elements ** //
    // require('./menu-includes/menu-popups');
    //
    //
    // // ** Main search -- all actions ** //
    // require('./menu-includes/main-search');
    //
    // // ** Profile image ** //
    // require('./menu-includes/profile-image');
});
