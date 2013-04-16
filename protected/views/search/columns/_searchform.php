    <?
    registerCss('/public/css/jquery.qtip.min.css');
    registerScript('/public/js/jquery.qtip.min.js');
    registerScript('/public/js/messages_ru.js');
    registerScript('/public/js/jquery.validate.min.js');
    registerScript("/public/js/search_form.js");
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
            $.post('". baseUrl()."/search/autocomplete','key='+$(this).val(), function(cities){
                    $.each(cities, function(){
                        $('#param_city').append($('<option value='+ this.id +'>' + this.city +'</option>'));
                    });
                    $('#param_city').trigger('liszt:updated');
                }
            );
        });
    });");
    Yii::app()->clientScript->registerScript('datepickers',
        '
       $(function() {
        $( "#coming_date" ).datepicker({
            dateFormat: "yy-mm-dd",
            changeMonth: true,
            numberOfMonths: 1,
            minDate: 1,
            onSelect: function( selectedDate ) {
                $( "#leaving_date" ).datepicker( "option", "minDate", selectedDate  );
            }
        });

        $( "#leaving_date" ).datepicker({
            dateFormat: "yy-mm-dd",
            changeMonth: true,
            numberOfMonths: 1,
            onClose: function( selectedDate ) {
                $( "#coming_date" ).datepicker( "option", "maxDate", selectedDate );
            }
        });
    });
    $(function(){
        if($("#is_child").is(":checked")){
            $("#add_child").show();
            $("#children_age").show()
        }
    })

        ')
    ?>

<form method="get" id=search_form class="adv_search" action="<?php echo baseUrl() ?>/search" style="padding-top: 10px; border-top: 1px solid rgba(173,170,140,0.63)">
    <table>
        <tr>
            <td>
                <label>Повторить поиск</label>
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
                <input type="text" class="date_picker advanced" name="param[coming_date]" id="coming_date" autocomplete="off" style="width: 75px;" value="<? echo $parameters['coming_date']?>" placeholder="прибытие"/> -
                <input type="text" class="date_picker advanced" name="param[leaving_date]" id="leaving_date" autocomplete="off" style="width: 75px;" value="<? echo $parameters['leaving_date']?>" placeholder="отъезд" />
            </td>
        <tr>
            <td>
                <label>Взрослых:<br></label>
                <input type="text" name="param[adult_paxes]" id="adult" autocomplete="off" value="<? echo $parameters['adult_paxes']?>" placeholder="взрослых" style="width: 65px"/>
                <input type="checkbox" name="param[is_child]" id="is_child" onchange="hide_block()" <?if(isset($parameters['is_child'])) echo "checked=true"?>/><label>&nbsp c детьми</label><br/>
            </td>
        </tr>
        <tr id="add_child">
            <td>
                <label>Детей:</label><br/>
                <input type="text" name="param[children_paxes]" id="children_paxes" autocomplete="off" style="width: 65px"
                <?if(isset($parameters['is_child'])) echo "value=".$parameters['children_paxes']?>><br/>
            </td>
        </tr>
        <tr id="children_age">
            <td id="container">
        <?if(isset($parameters['is_child']) && isset($parameters['child_age'])):?>
                <?foreach($parameters['child_age'] as $key=>$value):?>
                        <label class="age_label">возраст детей<br/></label>
                    <input type="text" class="child_age" autocomplete="off" name="param[child_age][]" value="<?=$value?>"  style="width:35px; margin-right:3px" />
                <?endforeach?>

        <?endif?>
            </td>
        </tr>
        <tr>
            <td>
                <div id="button_wrap"><input type="submit" name="search_hotel" value="Повторить" /></div>
            </td>
        </tr>
    </table>
</form>