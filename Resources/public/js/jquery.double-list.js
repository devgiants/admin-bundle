/**
 * Created by nicolas on 22/05/15.
 */
(function ( $ ) {

    $.fn.doubleList = function(callback) {

        if ( callback !== undefined) {
            return $.fn.doubleList[callback](this);
        }
        return this.each(function() {
            $this = $(this);
            // do some stuff.
            return $this;
        });
        return this;
    };

    // Init
    $.fn.doubleList.init = function() {
        // Only of there is master details components
        if($('.double-list').length)
        {
            $(".master a").click(function(e) {
                e.preventDefault();

                $selectedList = $(this).parents('.double-list').find('.selected-list');
                $selectedItem = $(this).parent('li').clone();
                $selectedList.append($selectedItem);

                $(this).addClass('disabled');
            });
        }
    };

}( jQuery ));