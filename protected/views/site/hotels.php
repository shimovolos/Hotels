<?php
    Yii::app()->getClientScript()->registerScriptFile(Yii::app()->assetManager->baseUrl."/js/search_form.js");
    if(isset(Yii::app()->request->cookies['price'])){
        $price = json_decode(Yii::app()->request->cookies['price']);
        $price = explode('-', $price);
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
    <form method="get" action="">
        <ul id="advanced_search_options" style="font-size: 8pt;margin: auto">
            <li>
                <input type="hidden" name="city_id" id="city_id" />
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
                        'style' => 'width:160px;',
                        'size' => '5',
                        'autocomplete' => 'off',
                        'value' => Yii::app()->request->cookies['city']->value
                    ),
                ));
                ?>
            </li>
            <li>
                <label>Дата</label><br/>
                <input type="text" class="date_picker advanced" name="coming_date" id="coming_date" autocomplete="off" style="width: 70px;" /> -
                <input type="text" class="date_picker advanced" name="leaving_date" id="leaving_date" autocomplete="off" style="width: 70px;"/>
            </li>
            <li><div id="button_wrap"><input type="submit" name="start_advanced_search" value="Повторить"/></div></li>
            </ul>
        </form>
    <form method="get" id='adv_search' name="adv_search">
        <ul style="font-size: 8pt;margin: auto">
            <li>
                <p>
                    <label for="price">Стоимость за ночь:</label>
                    <input type="text" class="advanced" name="price" id="price"  autocomplete="off" readonly="true" />
                </p>

                <div id="price_range" style="width: 165px"></div>
            </li>
            <li>
                <label>Количество звёзд:</label><br/>
                <?
                for($i = 1; $i<=5;$i++){
                    if(isset(Yii::app()->request->cookies['star']) && in_array($i, json_decode(Yii::app()->request->cookies['star']->value, true))){
                        echo "<input type='checkbox' id='star' name='star[]' value='$i' checked='true' onchange='submitAdvancedForm()'/>";
                    }
                    else{
                        echo "<input type='checkbox' id='star' name='star[]' value='$i'  onchange='submitAdvancedForm()'/>";
                    }
                    for($n = $i; $n > 0; $n--){
                        echo '<img src="'.Yii::app()->request->baseUrl.'/assets/images/star_icon.png" alt="star"/>';
                    }
                    echo '<br/>';
                }
                ?>
            </li>
            <li>
                <label >Дополниетельно:</label>
            </li>
            <input type="checkbox" name="no_smoking_room" /><label>для не курящих</label><br />
            <input type="checkbox" name="internet"/><label>Интернет</label><br />
            <input type="checkbox" name="breakfast"/><label>завтрак</label><br />
            </li>

        </ul>
    </form>
</div>
<div id="search_result">
    <span id="title">
        Всего найдено
        <?=$dataProvider->totalItemCount?> отелей для поездки в
        <?=$parameters['search_city']?> на период с
        <?=$parameters['coming_date']?> по
        <?=$parameters['leaving_date']?>
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