$('document').ready(function(){
   $(document).on('submit','#bachtActionForm',function(e){
        e.preventDefault();
        var ids = [];
        $('input[name=batchActionItem]:checked').each(function(){
            ids.push($(this).attr('value'));
        });

        var action = $( "#bachtActionForm option:selected" ).attr('value');

        if (typeof ids !== 'undefined' && ids.length > 0) {
            var idSelected = [];
            idSelected['ids'] = ids;
            jQuery.ajax({
                url : action,
                type: 'post',
                data : {'ids':ids},
                success: function(html) {
                    $('section#content').replaceWith(
                        $(html).find('section#content')
                    );
                }
            });
        }else{
            alert("Veuillez selectionner au moins une entité")
        }
   }); 
});