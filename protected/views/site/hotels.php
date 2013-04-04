<?php
Yii::app()->getClientScript()->registerCoreScript('jquery.ui');
registerCss('/public/css/jquery.ui.min.css');
registerScript('/public/js/chosen.jquery.js');
registerCss('/public/css/chosen.css');
registerScript("/public/js/search_form.js");
registerScript('/public/js/jquery.validate.min.js');
registerCss('/public/css/table.css');
registerScript('/public/js/FancyZoom.js');
$parameters = unserialize(Yii::app()->cache->get('parameters'));

Yii::app()->clientScript->registerScript('preload','
$(function (){
            $("#search_result").css("background", "url(/public/images/301.gif) no-repeat center center");
            var url = window.location.href;
            var param = url.split("?");
            $.ajax({
                url: "'.baseUrl().'/site/update",
                data: param[1],
                success:function(response) {
                $("#search_result").css("background","#fff").html(response);

                },
                error:function(){alert()}
                })
            });');

?>
<div id="advanced_search">
    <?
        $this->renderPartial("columns/_filterform");
        $this->renderPartial("columns/_searchform", array('parameters' => $parameters));

    ?>
</div>
<div id="search_result">

</div>
<script>
    $("#radio2").click(function(){
        $.ajax({
            url: "<?=baseUrl()."/site/test"?>",
            data: $("#adv_search").serialize(),
            success:function(response){
                $("#search_result").html(response);

            },
            error:function(){
                alert('ad');
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
                    data: param
                }
            );
        }
        else{
            $("#search_result").prepend('<img src="/public/images/ajax-loader.gif" alt=""/>');

            param = $('#adv_search').serialize();
            $.ajax({
                url: "<?=baseUrl()."/site/update"?>",
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
</script>