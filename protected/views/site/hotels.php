<?php
Yii::app()->getClientScript()->registerCoreScript('jquery.ui');
registerCss('/public/css/jquery.ui.min.css');
registerScript('/public/js/chosen.jquery.js');
registerCss('/public/css/chosen.css');
    registerScript("/public/js/search_form.js");
    registerScript('/public/js/jquery.validate.min.js');
    registerCss('/public/css/table.css');
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
    <form method="post" action="">
        <ul id="advanced_search_options" style="font-size: 8pt;margin: auto">
            <li>
                <input type="hidden" name="param[city_id]" id="city_id"/>
                <input type="hidden" name="param[search_city]" id="search_city"/>
                <table id='search'>
                </table>
                <div id="preloader" style="width: 160px"></div>

            </li>
            <li>
                <input type="text" class="date_picker advanced" name="param[coming_date]" id="coming_date" autocomplete="off" style="width: 70px;" value placeholder="прибытие"/> -
                <input type="text" class="date_picker advanced" name="param[leaving_date]" id="leaving_date" autocomplete="off" style="width: 70px;" value placeholder="отъезд" />
            </li>
            <li>
                <input type="hidden" name="param[adult_paxes]" value="<? echo $parameters['adult_paxes']?>"
            </li>
            <? if(isset($parameters['child_age'])): ?>
            <li>
                <input type="hidden" name="param[children_paxes]" value="<? echo $parameters['children_paxes']?>">
            </li>
            <? foreach($parameters['child_age'] as $key=>$value):?>
            <li>
                <input type="hidden" name="param[child_age][]" value="'.$value.'">
            </li>
            <? endforeach; ?>
            <? endif; ?>
            <li><div id="button_wrap"><input type="submit" name="search_hotel" value="Повторить" /></div></li>
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
            </li><?
            setCheckbox('Internet', 'Интерент', $params['Internet']);
            setCheckbox('Bar', 'Бар', $params['Bar']);
            setCheckbox('Parking', 'Парковка', $params['Parking']);
            setCheckbox('Restaurant', 'Ресторан', $params['Restaurant']);
            setCheckbox('Swimming', 'Бассейн', $params['Swimming']);
            ?>
            </li>

        </ul>
    </form>
</div>
<div id="search_result">
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
                'template'=>"<div class='info_table'><table style='width: 100%'>{items}</table>{pager}</div>",
                'pager' => array(
                    'header' => '',
                )
            ));
    ?>
</div>
<div id="result">
    <span id="title">
        <br>Всего найдено:
        <? echo '<br><b>'.$dataProvider->totalItemCount.'</b>'?> отелей<br> В городе:
        <? echo '<br><b>'.$parameters['search_city'].'</b>'?> <br>На период:<br> с
        <? echo '<b>'.$parameters['coming_date'].'</b>'?> - <br>по
        <? echo '<b>'.$parameters['leaving_date'].'</b>'?>
    </span>

</div>
<script type="text/javascript">
    $(document).ready(function () {
        var form = $('#search');

        function refreshSelects() {
            var selects = form.find('select');
            selects.chosen();
            selects.unbind('change').bind('change', function () {
                var selected = $(this).find('option').eq(this.selectedIndex);
                $('#city_id').attr('value', selected.attr('value'));
                $('#search_city').attr('value', selected.text());
                var connection = selected.data('connection');
                selected.closest('#search tr').nextAll().remove();
                if (connection) {
                    fetchSelect(connection);
                }
            });
        }

        var working = false;

        function fetchSelect(val) {
            if (working) {
                return false;
            }
            working = true;
            $.getJSON('<?=Yii::app()->createUrl('site/autocomplete')?>', {key: val}, function (r) {
                var connection, options = '';
                $.each(r.items, function (k, v) {
                    connection = '';
                    if (v) {
                        connection = 'data-connection="' + v + '"';
                    }
                    if (k.search(';') != -1) {
                        var data = k.split(';');
                        options += '<option value="' + data[1] + '" ' + connection + '>' + data[0] + '</option>';
                    }
                    else {
                        options += '<option value="' + k + '" ' + connection + '>' + k + '</option>';
                    }
                });
                if (r.defaultText) {
                    options = '<option></option>' + options;
                }
                $('<tr><td>\
				<p>' + r.title + '</p>\
				<select style="width: 165px" data-placeholder="' + r.defaultText + '">\
					' + options + '\
				</select>\
				<span class="divider"></span>\
			</td></tr>').appendTo(form);
                refreshSelects();
                working = false;
            });
        }

        $('#preloader').ajaxStart(function () {
            $(this).show();
        }).ajaxStop(function () {
                $(this).hide();
            });
        fetchSelect('countrySelect');
    });

</script>