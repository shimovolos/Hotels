<?php
registerScript("/public/js/search_form.js");
unset(Yii::app()->session['adv_param']);
unset(Yii::app()->session['responseData']);
?>
<div id="info">
</div>
<form name=search_form  onsubmit="load()" action='<?php echo baseUrl()?>/site/hotels' method='post' >
    <table id="search_form">
        <tr>
            <td>
                <input type="hidden" name="param[city_id]" id="city_id" />
                <?
                $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                    'name' => 'param[search_city]',
                    'source'=> Yii::app()->createUrl('site/autocomplete'),
                    'options' => array(
                        'minLength'=>'2',
                        'select' =>'js: function(event, ui) {
                                                    this.value = ui.item.label;
                                                    $("#city_id").val(ui.item.id);
                                                    return false; }',
                    ),
                    'htmlOptions'=>array(
                        'style' => 'width:260px;',
                        'size' => '5',
                        'autocomplete' => 'off',
                    ),
                ));
                ?>
                <br/>
                <label>Куда Вы хотите отправиться?</label>
            </td>
        </tr>
        <tr>
            <td>
                <input type="text" class="date_picker" name="param[coming_date]" id="coming_date" autocomplete="off" /> -
                <input type="text" class="date_picker" name="param[leaving_date]" id="leaving_date" autocomplete="off"/>
                <label for="coming_date">Дата прибытия</label><label for="leaving_date"> Дата отъезда</label>
            </td>
        </tr>
        <tr>
            <td>
                <input type="text" name="param[adult_paxes]" onkeyup="check_number(this, 6)" autocomplete="off" />
                <input type="checkbox" name="param[is_child]" id="is_child" onchange="hide_block()"/><label>&nbsp c детьми</label><br/>
                <label>Количество взрослых</label>
            </td>
        </tr>
        <tr id="add_child">
            <td>
                <input type="text" name="param[children_paxes]" id="children_paxes" onkeyup="check_number(this, 5)" autocomplete="off" /><br/>
                <label>Количество детей</label>
            </td>
        </tr>
        <tr id="children_age">
            <td id="container">
            </td>
        </tr>
        <tr>
            <td>
                <div id="button_wrap">
                    <input type=submit name=search value="Поехали!"/>
                </div>
            </td>
        </tr>
    </table>
</form>
<div id='load'>
    <img class="loader"  src='<? echo baseUrl().'/public/images/loader.gif';?>'/>
</div>