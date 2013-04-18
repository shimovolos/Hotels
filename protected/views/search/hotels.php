<?php
Yii::app()->getClientScript()->registerCoreScript('jquery.ui');
registerCss('/public/css/jquery.ui.min.css');
registerScript('/public/js/chosen.jquery.js');
registerCss('/public/css/chosen.css');


registerScript('/public/js/filter_form.js');
registerCss('/public/css/table.css');

$parameters = unserialize(Yii::app()->cache->get('parameters'));
?>
<div id="advanced_search">
    <?
    Yii::app()->clientScript->registerScript('preload','
$(function (){
            $("#search_result").css("background", "url(/public/images/301.gif) no-repeat center center");



            var url = window.location.href;
            var param = url.split("?");
            var filter = $("#adv_search").serialize();
            $("#adv_search :input").attr("disabled", true)
            $("#price_range").slider({ disabled: true });
            $("#star_range").slider({ disabled: true });
            $( "#radio" ).buttonset({ disabled: true });
            $.ajax({
                type: "get",
                url: "'.baseUrl().'/search/update",
                data: filter+"&"+param[1],
                success:function(response) {
                $("#search_result").css("background","#fff").html(response);
                $("#adv_search :input").attr("disabled", false);
                $("#price_range").slider({ disabled: false });
                $("#star_range").slider({ disabled: false });
                $( "#radio" ).buttonset({ disabled: false });
                },
                error:function(){
                    $("#search_ewsult").html("<span>Что-то пошло не так! Попробуйте повторить поиск</span>").css("background","#fff");
                }
                })
            });');
        $this->renderPartial("columns/_filterform");
        $this->renderPartial("columns/_searchform", array('parameters' => $parameters));
    ?>
</div>
<div id="search_result">

</div>
<script>
    $(document).ready(function () {
        filter = <?=json_encode($filter)?>;
        var price, star;
        if(filter){
            price = filter.price.split("-");
            price[0] = price[0].replace('$', '');price[1] = price[1].replace('$', '')
            star = filter.StarRating.split('-');
            for(var check in filter){
                if(check != 'radio' && check!='price' && radio!='StarRating'){
                    $('#adv_search input:checkbox[name*='+check+']').attr('checked', 'checked');
                }
            }
            $("[value='"+filter.radio+"']").attr('checked','checked');
        }else{
            price = new Array(2);price[0]=5;price[1]=2000;
            star = new Array(2);star[0]=0;star[1]=5;
        }
        setRanges(price,star);

    });


</script>