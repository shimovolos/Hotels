$(function() {
    $( "#coming_date" ).datepicker({
        dateFormat: "yy-mm-dd",
        changeMonth: true,
        numberOfMonths: 1,
        minDate: 1,
        onSelect: function( selectedDate ) {
            $( "#leaving_date" ).datepicker( "option", "minDate", selectedDate  );
            $(this).trigger('keyup')
        }
    });

    $( "#leaving_date" ).datepicker({
        dateFormat: "yy-mm-dd",
        changeMonth: true,
        numberOfMonths: 1,
        onClose: function( selectedDate ) {
            $( "#coming_date" ).datepicker( "option", "maxDate", selectedDate );
            $(this).trigger('keyup')
        }
    });
});

function hide_block(){
    if($("#is_child").is(":checked")){
        $("#add_child").show(200);
    }
    else{
        $("#add_child").hide(200);
        $("#children_age").hide(200);
    }
}

function check_number(element, check_value){
    if(element.value > check_value){
        element.value = check_value;
    }
    if(element.value < 0){
        element.value = 1;
    }
    element.value = element.value.replace(/[^\d]/g, "");
}

$(document).ready(function(){
    $("#add_child").hide();
    $("#children_age").hide();
    $("#load").hide();
    $("#children_paxes").keyup(function(){
        $("input.child_age").remove();
        $("#children_age").show(200);
        var children = $("#children_paxes").val();
        if(children > 0 && children <=5){
            $('<label>возраст детей</label><br/>').fadeIn('slow').appendTo('#container');
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

function openRoomsForm(){
    var pax_count = $("*[name=adult_paxes]").val();
    var child_count = $("*[name=children_paxes]").val();
//    if(pax_count == 0){
//        $('#error').arcticmodal();
//    }
//    else{
    $('#overlay').arcticmodal();
//        $("#pax_count").attr('value',pax_count);
//        if(child_count == '') child_count = 0;
//        $("#child_count").attr('value',child_count);
//    }
}

function submitAdvancedForm(){
    var url = $("#adv_search").attr("action");
    var split = url.split('?');
    newUrl = split[1]+'&'+$("#adv_search").serialize();
    $.ajax({
        url: 'update',
        type: 'get',
        data: newUrl,
        success: function(response) {
            $("#view").html(response);
        },
        error:function(){
            alert('Ошибка загрузки формы');
        }
    })
}

function addRowToTable(){
    var row =
        '<tr><td>Номер:</td>'+
            '<td><input  type="text" name="adult_in_room[]" onkeyup="check_number(this, $(\'#pax_count\').val());" style="width: 35px" /></td>'+
            '<td><input  type="text" name="child_in_room[]" onkeyup="check_number(this, $(\'#child_count\').val());alert();" style="width: 35px"/></td>' +
            '</tr>';
    var a = $("input[name^=adult_in_room]:last").val();
    $("#pax_count").attr("value", $("#pax_count").val() - a);
    $("#rooms_table tr:last").after(row);
}

function removeRowFromTable(){
    $("#rooms_table tr:last").remove();
}

$(function() {
    $( "#draggable, #draggable-nonvalid" ).draggable();
    $( "#droppable" ).droppable({
        accept: "#draggable",
        activeClass: "ui-state-hover",
        hoverClass: "ui-state-active",
        drop: function( event, ui ) {
            $( this )
                .addClass( "ui-state-highlight" )
                .find( "p" )
                .html( "Dropped!" );
        }
    });
});

function load(){
    $("#load").show();
    $('.loader').css({
        position:'absolute',
        left: ($(window).width() - $('.loader').outerWidth())/2,
        top: ($(window).height() - $('.loader').outerHeight())/2
    });
}

