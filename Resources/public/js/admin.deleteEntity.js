$('document').ready(function(){
    var modal = $('#removeRecord');
    var waiting =  $("#waiting");
    var modalContent =  $("#modalContent");
    modal.on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var title = button.data('title');
        modal.find('.modal-title').text(title);
        $.get( button.data('url'), function( data ) {
            waiting.hide();
            modalContent.html( data);
        });
    });
    modal.on('hidden.bs.modal', function (event) {
        waiting.show();
        modalContent.empty();
    });
})