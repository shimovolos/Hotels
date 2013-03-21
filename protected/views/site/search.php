<?php
registerCss('/public/css/jquery.qtip.min.css');
registerScript("/public/js/search_form.js");
registerScript('/public/js/jquery.qtip.min.js');
registerScript('/public/js/jquery.validate.min.js');
registerScript('/public/js/messages_ru.js');
unset(Yii::app()->session['adv_param']);
unset(Yii::app()->session['responseData']);
?>
<div id="info">
</div>
<form name=search_form id=search_form   action='<?php echo baseUrl()?>/site/hotels' method='post' >
    <table>
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
                        'value placeholder' => 'Город или страна'
                    ),
                ));
                ?>
                <br/>
            </td>
        </tr>
        <tr>
            <td>
                <input type="text" class="date_picker" name="param[coming_date]" id="coming_date" autocomplete="off" value placeholder="прибытие"/> -
                <input type="text" class="date_picker" name="param[leaving_date]" id="leaving_date" autocomplete="off" value placeholder="отъезд"/>
            </td>
        </tr>
        <tr>
            <td>
                <input type="text" name="param[adult_paxes]" autocomplete="off" value placeholder="взрослых"/>
                <input type="checkbox" name="param[is_child]" id="is_child" onchange="hide_block()"/><label>&nbsp c детьми</label><br/>
            </td>
        </tr>
        <tr id="add_child">
            <td>
                <input type="text" name="param[children_paxes]" id="children_paxes" autocomplete="off" value placeholder="детей"/><br/>
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