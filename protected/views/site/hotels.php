<?php
Yii::app()->getClientScript()->registerCoreScript('jquery.ui');
registerCss('/public/css/jquery.ui.min.css');
registerScript('/public/js/chosen.jquery.js');
registerCss('/public/css/chosen.css');
//registerScript("/public/js/search_form.js");
registerScript('/public/js/jquery.validate.min.js');
registerScript('/public/js/filter_form.js');
registerCss('/public/css/table.css');
$parameters = unserialize(Yii::app()->cache->get('parameters'));
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