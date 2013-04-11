

function hide_block(){
    if($("#is_child").is(":checked")){
        $("#add_child").show(200);
    }
    else{
        $("#add_child").hide(200);
        $("#children_age").hide(200);
    }
}


$(function(){

    $("#add_child").hide();
    $("#children_age").hide();
    $("#load").hide();
    $("#children_paxes").keyup(function(){
        $("input.child_age").remove();
        $(".age_label").remove()
        $('<label>возраст детей</label><br/>').remove();
        $("#children_age").show(200);
        var children = $("#children_paxes").val();
        if(children > 0 && children <=5){
            $('<label class="age_label">возраст детей<br/></label>').fadeIn('slow').appendTo('#container');
            for(var i = 0; i < children; i++ ){
                $('<input type="text" class="child_age" autocomplete="off" name="param[child_age][]"  style="width:35px; margin-right:3px" />').fadeIn('slow').appendTo('#container');
            }
        }
    });
    $("#search_form").validate({
        rules: {
            select:{
                required: true
            },
            'param[coming_date]':{
                required:true
            },
            'param[leaving_date]':{
                required:true
            },
            'param[adult_paxes]':{
                required: true,
                max: 6
            },
            'param[children_paxes]':{
                required: true,
                max: 5
            },
            'param[child_age][]':{
                required: true,
                max:17
            }
        },
        errorPlacement: function(error, element)
        {
            var elem = $(element);
            var corners = ['right center', 'left center'];
            if(!error.is(':empty')) {
                elem.filter(':not(.valid)').qtip({
                    overwrite: true,
                    content: error,
                    position: {
                        my: corners[0],
                        at: corners[1]
                    },
                    show: {
                        event: false,
                        ready: true
                    },
                    hide: 'click',
                    style: {
                        classes: 'qtip-red'
                    }
                })
                    .qtip('option', 'content.text', error);
            }

            else { elem.qtip('destroy'); }
        },
        success: $.noop,
        onSelect: true
    });

});

$(function(){
    $("#radio").buttonset();
});




