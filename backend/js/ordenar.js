$(function() 
{
    $("#sortable1, #sortable2").sortable(
    {
        connectWith: '.connectedSortable',
        update : function () 
        { 
            $.ajax(
            {
                type: "POST",
                url: "ordena_post<?PHP if($ord == 2){echo '_col';}?>.php",
                data: 
                {
                    sort1:$("#sortable1").sortable('serialize'),
                    sort2:$("#sortable2").sortable('serialize')
                },
                success: function(html)
                {
                    //$('.success').fadeIn(500);
                    //$('.success').fadeOut(500);
                }
            });
        } 
    }).disableSelection();
});