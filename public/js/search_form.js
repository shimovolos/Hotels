

$(function() {
    $( "#coming_date" ).datepicker({
        changeMonth: true,
        numberOfMonths: 1,
        onClose: function( selectedDate ) {
            $( "#leaving_date" ).datepicker( "option", "minDate", selectedDate );
        }
    });

    $( "#leaving_date" ).datepicker({
        changeMonth: true,
        numberOfMonths: 1,
        onClose: function( selectedDate ) {
            $( "#coming_date" ).datepicker( "option", "maxDate", selectedDate );
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

$(document).ready(function(){
    $("#children_paxes").keyup(function(){
        $("input.child_age").remove();
        $("#children_age").show(200);
        var children = $("#children_paxes").val();
        if(children > 0 && children <=5){
            for(var i = 0; i < children; i++ ){
                $('<input type="text" class="child_age" autocomplete="off" name="child_age_' + i + '"  style="width:35px; margin-right:3px" onkeyup="check_number(this, 17)"/>').fadeIn('slow').appendTo('#container');
            }
        }
    });
});

function check_number(element, check_value){
    if(element.value > check_value){
        element.value = check_value;
    }
    if(element.value < 0){
        element.value = 1;
    }
    element.value = element.value.replace(/[^\d]/g, "");
}

$(document).ready(init);
function init(){
    $("#add_child").hide();
    $("#children_age").hide();
    $("#load").hide();
}

function load(){
    $("#load").show();
    $('.loader').css({
        position:'absolute',
        left: ($(window).width() - $('.loader').outerWidth())/2,
        top: ($(window).height() - $('.loader').outerHeight())/2
    });
}

function submitAdvancedForm(){
    $("#adv_search").submit();
}
