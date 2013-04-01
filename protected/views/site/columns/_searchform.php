    <?
    Yii::app()->clientScript->registerScript('cities', "$(function(){
        $('#param_city').chosen().change(function(){
            var selected = $(this).find('option').eq(this.selectedIndex);
            $('#city_id').attr('value', selected.attr('value'));
            $('#search_city').attr('value', selected.text());
             $('#param_city').trigger('liszt:updated');
        });
        $('#param_country').chosen().change(function(){
            $('#param_city option').remove();
            $('#param_city').append('<option>Выберите город...</option>');
            $.post('". baseUrl()."/site/autocomplete','key='+$(this).val(), function(cities){
                    $.each(cities, function(){
                        $('#param_city').append($('<option value='+ this.id +'>' + this.city +'</option>'));
                    });
                    $('#param_city').trigger('liszt:updated');
                }
            );
        });
    });");
    ?>

<form method="get" class="adv_search" action="" style="padding-top: 10px; border-top: 1px solid rgba(173,170,140,0.63)">
    <table>
        <tr>
            <td>
                <label>Повторить поиск</label>
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
                unset($result);
                ?>
            </td>
        </tr>
        <tr>
            <td >
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
                <input type="text" class="date_picker advanced" name="param[coming_date]" id="coming_date" autocomplete="off" style="width: 75px;" value placeholder="прибытие"/> -
                <input type="text" class="date_picker advanced" name="param[leaving_date]" id="leaving_date" autocomplete="off" style="width: 75px;" value placeholder="отъезд" />
            </td>
        <tr>
            <td>
                <div id="button_wrap"><input type="submit" name="search_hotel" value="Повторить" /></div>
            </td>
        </tr>
    </table>
</form>