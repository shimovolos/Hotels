function mapUpdate(){
    if($('#ajaxListView').length){
        param = $('#adv_search').serialize();
        $.fn.yiiListView.update(
            'ajaxListView',
            { type:"post",data: param }
        );
    }else{
        $("#search_result").prepend('<img src="/public/images/ajax-loader.gif" alt=""/>');
        param = $('#adv_search').serialize();
        $.ajax({
            type:"post",
            url: "/site/update",
            data: param,
            success:function(response){
                $("#search_result").html(response);
            },
            error:function(){
                $("#search_result").html("<span class='error'>Что-то пошло не так! Попробуйте ещё раз!</span> ")
            }
        })
    }
}



$(function(){
    $("#radio").buttonset();
});

$("#radio2").click(function(){
    $.ajax({
        url: "/site/map",
        data: $("#adv_search").serialize(),
        success:function(response){
            $("#search_result").html(response);
        },
        error:function(){
            $("#search_result").html("<span class='error'>Не удалось загрузить карту.</span> ")
        }
    })
})

$('.filter').change(function(){
    if($("#ajaxListView").length){
        param = $('#adv_search').serialize();
        $('head script').remove();
        $.fn.yiiListView.update(
            'ajaxListView',
            {
                type: 'post',
                data: param
            }
        );
    }
    else{
        $("#search_result").prepend('<img src="/public/images/ajax-loader.gif" alt=""/>');

        param = $('#adv_search').serialize();
        $.ajax({
            type:'post',
            url: "/site/update",
            data: param,
            success:function(response){
                $("#search_result").html(response);
            },
            error:function(){
                alert('ad');
            }
        })
    }
})

$(document).ready(function(){
    $(function() {
        $( "#coming_date" ).datepicker({
            dateFormat: "yy-mm-dd",
            changeMonth: true,
            numberOfMonths: 1,
            minDate: 1,
            onSelect: function( selectedDate ) {
                $( "#leaving_date" ).datepicker( {minDate: selectedDate  });
            }
        });

        $( "#leaving_date" ).datepicker({
            dateFormat: "yy-mm-dd",
            changeMonth: true,
            numberOfMonths: 1,
            onSelect: function( selectedDate ) {
                $( "#coming_date" ).datepicker( "option", "maxDate", selectedDate );
            }
        });
    });

})

function setRanges(price, star){
    $(function () {
        $("#price_range").slider({
            range: true,
            min: 0,
            max: 2000,
            values: [price[0], price[1]],
            step: 50,
            slide: function (event, ui) {
                $("#price").val("$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ]);
            },
            change: function (event, ui) {
                mapUpdate();
            }
        });
        $("#price").val("$" + $("#price_range").slider("values", 0) +
            " - $" + $("#price_range").slider("values", 1));
    });
    $(function () {
        $("#star_range").slider({
            range: true,
            min: 0,
            max: 5,
            values: [star[0], star[1]],
            slide: function (event, ui) {
                $("#star").val(ui.values[0] + " - " + ui.values[1]);
            },
            change: function (event, ui) {
                mapUpdate();
            }
        });
        $("#star").val($("#star_range").slider("values", 0) +
            " - " + $("#star_range").slider("values", 1));
    });
}