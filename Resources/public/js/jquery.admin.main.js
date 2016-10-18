/**
 * Created by nicolas on 22/05/15.
 */
/*
 (function ( $ ) {
 UI = {
 adminInit: function () {

 }
 };

 $(document).ready(function() {
 UI.adminInit();
 });
 }( jQuery ));
 */
$(document).ready(function(){

    $('button.close').click(function(){
        $(this).parent().slideUp();
    });

    $('select#changeSiteSeletor').change(function(){
        var dest = $(this).val();
        if (dest != "") {
            window.open(dest,'_blank');
        }
    });
    
	$(function () {
	  	$('[data-toggle="tooltip"]').tooltip()
	})
});