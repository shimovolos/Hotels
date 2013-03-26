<?php
Yii::app()->getClientScript()->registerCoreScript('jquery.ui');
registerCss('/public/css/jquery.ui.min.css');
registerScript('/public/js/chosen.jquery.js');
registerCss('/public/css/chosen.css');
registerScript("/public/js/search_form.js");
registerScript('/public/js/jquery.validate.min.js');
registerCss('/public/css/table.css');
Yii::app()->getClientScript()->registerScript('priceRange', $sliderScript);
Yii::app()->getClientScript()->registerScript('starRange', $starScript);
$parameters = unserialize(Yii::app()->cache->get('parameters'));

$params = null;
if (isset(Yii::app()->session['adv_param'])) {
    $params = json_decode(Yii::app()->session['adv_param'], true);
    $price = explode('-', $params['price']);
    $star = explode('-', $params['StarRating']);
    $priceFrom = intval(str_replace('$', '', $price[0]));
    $priceTo = intval(str_replace('$', '', $price[1]));
    $starFrom = $star[0];
    $starTo = $star[1];
} else {
    $priceFrom = 5;
    $priceTo = 2000;
    $starFrom = 0;
    $starTo = 5;
}

function setCheckbox($name, $label, $params)
{
    if (isset($params)) {
        echo '<input type="checkbox" name="adv_param[' . $name . ']" value="' . $name . '" checked="true" onchange="submitAdvancedForm()"/>
                                <label>' . $label . '</label>
                                <br />';
    } else {
        echo '<input type="checkbox" name="adv_param[' . $name . ']" value="' . $name . '" onchange="submitAdvancedForm()"/>
                                <label>' . $label . '</label>
                                <br />';
    }
}
?>
<div id="advanced_search">
    <form method="get" action="<?echo Yii::app()->request->getUrl();?>">
        <table>
            <tr>
                <td>
                    <? if(isset($parameters['child_age'])): ?>
                    <input type="hidden" name="param[children_paxes]" value="<? echo $parameters['children_paxes']?>">
                    <? foreach($parameters['child_age'] as $key=>$value):?>
                        <input type="hidden" name="param[child_age][]" value="'.$value.'">
                        <? endforeach; ?>
                    <? endif; ?>
                    <input type="hidden" name="param[adult_paxes]" value="<? echo $parameters['adult_paxes']?>"/>
                    <input type="hidden" name="param[city_id]" id="city_id" value="<?=$parameters['city_id']?>"/>
                    <input type="hidden" name="param[search_city]" id="search_city" value="<?=$parameters['search_city']?>"/>
                    <?
                    $destinations = Hoteldestinations::model()->findAll(array(
                        'select' => 'Country',
                        'distinct' => true,
                        'order' => 'Country'));
                    $result = array();
                    foreach($destinations as $destination){
                        $result[$destination->Country] = $destination->Country;
                    }
                    echo CHtml::dropDownList('param[country]', '', $result, array(
                        'prompt'=> 'Выберите страну...',
                        'style' =>'width:180px',
                        'options' =>array(
                            $parameters['country'] => array(
                                'selected'=>'selected'
                            )
                        )
                    ));

                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?
                    $cities = Hoteldestinations::model()->findAll('Country=:Country ORDER BY City', array(':Country'=>$parameters['country']));
                    $result = array();
                    foreach($cities as $destination){
                        $result[$destination->DestinationId] = $destination->City;
                    }
                    echo CHtml::dropDownList('param[city]', '', $result, array(
                        'empty'=> 'Выберите город...',
                        'style' =>'width:180px',
                        'options' =>array(
                            $parameters['city_id'] => array(
                                'selected'=>'selected'
                            )
                        )
                    ));
                    unset($result);
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="text" class="date_picker advanced" name="param[coming_date]" id="coming_date" autocomplete="off" style="width: 70px;" value placeholder="прибытие"/> -
                    <input type="text" class="date_picker advanced" name="param[leaving_date]" id="leaving_date" autocomplete="off" style="width: 70px;" value placeholder="отъезд" />
                </td>
            <tr>
                <td>
                    <div id="button_wrap"><input type="submit" name="search_hotel" value="Повторить" /></div>
                </td>
            </tr>
        </table>
    </form>
    <form method="get" id='adv_search' name="adv_search"  style="font-size: 4pt;">
        <table>
            <tr>
                <td>
                    <label for="price">Стоимость за ночь:</label>
                    <input type="text" class="advanced" name="adv_param[price]" id="price"  autocomplete="off" readonly="true" />
                    <div id="price_range" style="width: 155px"></div>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="star">Количество звёзд:</label><br/>
                    <input type="text" class="advanced" name="adv_param[StarRating]" id="star"  autocomplete="off" readonly="true" />
                    <div id="star_range" style="width: 155px"></div>
                </td>
            </tr>
            <tr>
                <td>
                    <label >Дополниетельно:</label>
                </td>
            </tr>
            <tr>
                <td>
                    <?
                    setCheckbox('Internet', 'Интерент', $params['Internet']);
                    setCheckbox('Bar', 'Бар', $params['Bar']);
                    setCheckbox('Parking', 'Парковка', $params['Parking']);
                    setCheckbox('Restaurant', 'Ресторан', $params['Restaurant']);
                    setCheckbox('Swimming', 'Бассейн', $params['Swimming']);
                    ?>
                </td>
            </tr>

        </table>
    </form>
</div>
<div id="search_result">
    <span id="title">
        Всего найдено:
        <? echo '<b>'.$dataProvider->totalItemCount.'</b>'?> отелей В городе:
        <? echo '<b>'.$parameters['search_city'].'</b>'?> на период: с
        <? echo '<b>'.$parameters['coming_date'].'</b>'?> по
        <? echo '<b>'.$parameters['leaving_date'].'</b>'?>
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
        'sortableAttributes'=>array('starRating', 'hotelName'),
        'sorterHeader'=>'Сортировать по:',
        'viewData' => array('hotels' => $hotels ),
        'template'=>"<div class='info_table'><table style='width: 100%'>{summary}{sorter}{items}</table>{pager}</div>",
        'pager' => array(
            'header' => '',
        ),
        'summaryText'=>'',
    ));
    ?>
</div>
<script type="text/javascript">
    $(function() {
        $("#price_range" ).slider({
            range: true,
            min: 5,
            max: 2000,
            values: ['<?=$priceFrom?>','<?=$priceTo?>'],
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
    });

    $(function(){
        $("#star_range").slider({
            range: true,
            min: 0,
            max: 5,
            values:['<?=$starFrom?> ','<?=$starTo?>'],
            slide: function(event, ui){
                $("#star").val(ui.values[0] + " - " + ui.values[1]);
            },
            change: function(event, ui){
                submitAdvancedForm();
            }
        });
        $("#star").val($( "#star_range" ).slider( "values", 0 ) +
                " - " + $( "#star_range" ).slider( "values", 1 ));
    })

    $(function(){
        $('#param_city').chosen().change(function(){
            var selected = $(this).find('option').eq(this.selectedIndex);
            $('#city_id').attr('value', selected.attr('value'));
            $('#search_city').attr('value', selected.text());
        });
        $("#param_country").chosen().change(function(){
            $("#param_city option").remove();
            $("#param_city").append("<option>Выберите город...</option>");
            $.ajax({
                url: '<?=Yii::app()->createUrl('site/autocomplete')?>',
                type: 'get',
                dataType: 'json',
                data: 'key='+$(this).val(),
                success: function(cities){
                    $.each(cities, function(){
                        $("#param_city").append($('<option value="'+ this.id +'">' + this.city +'</option>'));
                    });
                    $('#param_city').trigger("liszt:updated");
                }
            });
        });
    })
</script>