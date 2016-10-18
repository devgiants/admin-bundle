$(document).ready(function() {

    /**
    *   jQuery UI Sortable
    *   https://jqueryui.com/sortable
    */
    $( "#sortable, .sortable" ).sortable({
        axis: "y",
        containment: "parent",
        handle: ":not(select):not(a)"
    });
});