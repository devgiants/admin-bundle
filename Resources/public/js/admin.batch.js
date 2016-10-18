$(document).ready(function(){
    $(document).on('change','input[type=checkbox][name=checkAll]', function(){
        var checkBoxes = $("input[name=batchActionItem]");
        checkBoxes.prop("checked", !checkBoxes.prop("checked"));
    });
});