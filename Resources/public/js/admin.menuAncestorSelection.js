/**
 * Created by nbonniot on 04/07/16.
 */
$(function() {
    if($("nav#main_menu li.current_ancestor").length == 0) {
        $("[data-menu-root]").each(function() {
            if($('body').attr('data-route').indexOf($(this).attr('data-menu-root')) > -1) {
                if(!$(this).hasClass('active')) {
                    $(this).addClass('active');
                }
                return false;
            }
        })
    }
});