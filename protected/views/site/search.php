<?php
Yii::app()->getClientScript()->registerCssFile(Yii::app()->assetManager->baseUrl."/css/jquery.arcticmodal-0.2.css");
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->assetManager->baseUrl."/js/search_form.js");
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->assetManager->baseUrl."/js/jquery.arcticmodal-0.2.min.js");
?>
<div id="info">
</div>
<form name=search_form  onsubmit="" action='<?php echo Yii::app()->request->baseUrl?>/site/hotels' method='post' >
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
                <a href="#" onclick="openRoomsForm()"><label>распределить по номерам</label></a>
            </td>
        </tr>
    </table>
    <div hidden="true">
        <div class="b-modal" id="overlay">
            <div id="draggable" class="pax">
            </div>
            <div id="draggable" class="pax">
            </div>
            <div id="droppable" class="room"><label>Номер</label>
            </div>
            <div class="pax"></div> <label>взрослый</label>
        </div>
    </div>
</form>
<div hidden="true">
    <div class="b-modal" id="error">
        <label style="font-size: 13pt">Для использования данной функции Вы должны указать количество посетителей!</label>
    </div>
</div>
