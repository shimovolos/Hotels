<?php
print_r(Yii::app()->session['adult_paxes']);
    registerScript("/public/js/search_form.js");
    $params = null;
    if(isset(Yii::app()->session['adv_param'])){
        $params = json_decode(Yii::app()->session['adv_param'],true);
        $price = explode('-', $params['price']);
        $from = intval(str_replace('$', '', $price[0]));
        $to = intval(str_replace('$', '', $price[1]));
    }
    else{
        $from = 5;
        $to = 2000;
    }
    $sliderScript = '
        $(function() {
        $( "#price_range" ).slider({
            range: true,
            min: 5,
            max: 2000,
            values: ['.$from.','.$to.'],
            step: 25,
            slide: function( event, ui ) {
                $( "#price" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
            },
            change: function(event, ui){
                submitAdvancedForm();
            }
        });
        $( "#price" ).val( "$" + $( "#price_range" ).slider( "values", 0 ) +
            " - $" + $( "#price_range" ).slider( "values", 1 ) );
    });';

    Yii::app()->getClientScript()->registerScript('priceRange', $sliderScript);
    $parameters = unserialize(Yii::app()->cache->get('parameters'));
?>
<div id="advanced_search">
    <form method="post" action="">
        <ul id="advanced_search_options" style="font-size: 8pt;margin: auto">
            <li>
                <input type="hidden" name="city_id" id="city_id" value="<? echo $parameters['city_id']?>" />
                <label >Город</label><br />
                <?
                $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                    'name' => 'search_city',
                    'source'=> Yii::app()->createUrl('site/autocomplete'),
                    'options' => array(
                        'minLength'=>'2',
                        'select' =>'js: function(event, ui) {
                                                    this.value = ui.item.label;
                                                    $("#city_id").val(ui.item.id);
                                                    return false; }',
                    ),
                    'htmlOptions'=>array(
                        'style' => 'width:160px; height: 15px',
                        'autocomplete' => 'off',
                        'value' => $parameters['search_city']
                    ),
                ));
                ?>
            </li>
            <li>
                <label>Дата</label><br/>
                <input type="text" class="date_picker advanced" name="coming_date" id="coming_date" autocomplete="off" style="width: 70px;" value="<? echo $parameters['coming_date']?>" /> -
                <input type="text" class="date_picker advanced" name="leaving_date" id="leaving_date" autocomplete="off" style="width: 70px;" value="<? echo $parameters['leaving_date']?>"/>
            </li>
            <li><div id="button_wrap"><input type="submit" name="search" value="Повторить"/></div></li>
        </ul>
    </form>
    <form method="get" id='adv_search' name="adv_search">
        <ul style="font-size: 8pt;margin: auto">
            <li>
                <p>
                    <label for="price">Стоимость за ночь:</label>
                    <input type="text" class="advanced" name="adv_param[price]" id="price"  autocomplete="off" readonly="true" />
                </p>

                <div id="price_range" style="width: 165px"></div>
            </li>
            <li>
                <label>Количество звёзд:</label><br/>
                <?
                for($i = 1; $i<=5;$i++){
                    if(isset($params['StarRating']) && in_array($i, $params['StarRating'])){
                        echo "<input type='checkbox' id='star' name='adv_param[StarRating][]' value='$i' checked='true' onchange='submitAdvancedForm()'/>";
                    }
                    else{
                        echo "<input type='checkbox' id='star' name='adv_param[StarRating][]' value='$i'  onchange='submitAdvancedForm()'/>";
                    }
                    for($n = $i; $n > 0; $n--){
                        echo '<img src="'.baseUrl().'/public/images/star_icon.png" alt="star"/>';
                    }
                    echo '<br/>';
                }
                ?>
            </li>
            <li>
                <label >Дополниетельно:</label>
            </li>
            <input type="checkbox" name="adv_param[OldHotelId][]" value="1/DAHTWUPPP" onchange='submitAdvancedForm()'/><label>для не курящих</label><br />
            <input type="checkbox" name="adv_param[internet]"/><label>Интернет</label><br />
            <input type="checkbox" name="adv_param[breakfast]"/><label>завтрак</label><br />
            </li>

        </ul>
    </form>
</div>
<div id="search_result">
    <span id="title">
        Всего найдено
        <? echo $dataProvider->totalItemCount?> отелей для поездки в
        <? echo $parameters['search_city']?> на период с
        <? echo $parameters['coming_date']?> по
        <? echo $parameters['leaving_date']?>
    </span>
    <?php
        $response = unserialize(Yii::app()->cache->get('response'));
        if (is_object($response->availableHotels)) {
            $hotels[] = $response->availableHotels;
        } else {
            $hotels = $response->availableHotels;
        }

        $this->widget('zii.widgets.CListView', array(
                'dataProvider'=>$dataProvider,
                'itemView'=>'_hotelview',
                'viewData' => array('hotels' => $hotels ),
                'template'=>"<table style='width: 100%'>{items}</table>{pager}",
                'pager' => array(
                    'header' => '',
                )
            ));
    ?>
</div>