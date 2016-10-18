$(document).ready(function() {

    /** ================================================================
    *   Slug generator call in AJAX
    */
       
    var $slugField =    $("input[name$='[slug]']");
    var $slugGenerate = $("#generateSlug");
    $slugGenerate.click(function() {
     
        // Get the good field to slug
        if ($("input[name$='[name]']").length > 0){
            var toSlug = $.trim($("input[name$='[name]']").val());
        }else{
            var toSlug = $.trim($("input[name$='[title]']").val());
        }

        if(!toSlug.length > 0){
            alert("Vous devez remplir le nom de l'entité pour générer le slug.");
            return;
        }

        var data = {};
        data['toSlug'] = toSlug;
        data['id'] = $(this).attr('data-id');
        data['className'] = $(this).attr('data-entityClass');
        $(".slugerror").html("");
        $(".slugmessage").html("");
        $.ajax({
            url:  $(this).attr('data-url'),
            data : data,
            method : 'POST',
            success: function(result) {
                if(result.error){
                    $(".slugerror").html(result.error);
                }else{
                    $slugField.val(result.slug);
                    $(".slugmessage").html(result.message);
                }
            }
        });
    });
    //
    // ================================================================
});