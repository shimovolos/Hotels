<?
Yii::app()->getClientScript()->registerCoreScript('jquery');
registerCss('/public/css/style.css');
registerCss('/public/css/table.css');
registerScript('/public/js/arcticmodal.js');
registerCss('/public/css/arcticmodal.css');
?>


<div class="g-hidden">
    <div class="box-modal" id='modal'>
        <table class="info_table">
            <tr>
                <td style="width:100px">
                    Количество дней до заселения, когда возможно отменить бронирование:
                </td>
                <td>
                    <?if(isset($policy->cancellationDay)) echo $policy->cancellationDay ?>
                </td>

            </tr>
            <tr>
                <td>
                    Тип комиссии:
                </td>
                <td>
                    <?
                        if(isset($policy->feeType)){
                            switch($policy->feeType){
                                case 'Percent': echo 'процент';break;
                                case 'Night': echo 'За ночь';break;
                                case 'Amount': echo 'Общая стоимость';break;
                            }
                        }
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    Размер комиссии:
                </td>
                <td><?if(isset($policy->feeAmount)) echo $policy->feeAmount ?></td>
            </tr>
            <tr>
                <td>
                    Валюта:
                </td>
                <td><?if(isset($policy->currency)) echo $policy->currency ?></td>
            </tr>
            <tr>
                <td>
                    Замечания:
                </td>
                <td><?if(isset($policy->remarks)) echo $policy->remarks ?></td>
            </tr>
        </table>
    </div>
</div>
<form method="post" action="" style="margin: auto">
    <table style="margin: auto">
        <tr>

            <td colspan="3">
                <input type="hidden" name="processId" value="<?=$_GET['processId']?>"  >
                <label style="font-size: 15px; font-style: italic; font-weight: bold">Ответственное лицо:</label><br>
            </td>
        </tr>
        <tr>
            <td>
                <label for="lead_title">Привествие:</label><br>
            </td>
            <td>
                <label for="lead_1st_name">Имя:</label><br>
            </td>
            <td>
                <label for="lead_2nd_name">Фамилия:</label><br>
            </td>

        </tr>
        <tr>
            <td>
                <select class="booking" style="width: 80px;" name="lead_title" id="lead_title">
                    <option>Mr</option>
                    <option>Ms</option>
                </select>
            </td>
            <td>
                <input class="booking" type="text" name="lead_1st_name" id="lead_1st_name"/>
            </td>
            <td>
                <input class="booking" type="text" name="lead_2nd_name" id="lead_2nd_name"/>
            </td>
        </tr>
    <tr>
    <td colspan="2">
        <?
            if(isset($data['children_paxes'])){
                $paxCount = $data['children_paxes'] + $data['adult_paxes'];
            }else{
                $paxCount = $data['adult_paxes'];
            }
            if($paxCount  > 1):
        ?>
        <label style="font-size: 15px; font-style: italic; font-weight: bold">Другие посетители:</label>
   </td>
    </tr>
        <?php
        {
            for($i = 0; $i < $paxCount - 1; $i++){
                echo '
                    <tr>
                        <td>
                            <label for="other_title_'.$i.'">Привествие:</label>
                        </td>
                        <td>
                            <label for="other_1st_name_'.$i.'">Имя:</label>
                        </td>
                         <td>
                            <label for="other_1st_name_'.$i.'">Фамилия:</label>
                        </td>

                    </tr>
                    <tr>
                        <td>
                            <select class="booking" style="width: 80px;" name="other_title_'.$i.'" id="other_title_'.$i.'">
                                <option>Mr</option>
                                <option>Ms</option>
                            </select>
                        </td>
                        <td>
                            <input class="booking" type="text" name="other_1st_name_'.$i.'" id="other_1st_name_'.$i.'"/>
                        </td>
                        <td>
                            <input class="booking" type="text" name="other_2nd_name_'.$i.'" id="other_2nd_name_'.$i.'"/>
                        </td>
                    </tr>

                    </tr>';
            }
        }
        ?>

        <?endif?>
        <tr>
            <td colspan="3">
                <label>Примечания:</label>
                <textarea class="booking" name="note" style="width: 300px"></textarea>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <div id="button_wrap">
                    <input type="submit" name="get_booking" value="Забронировать"/>
                </div>
            </td>
        </tr>
    </table>
</form>
<p style="text-align: center; font-weight: bold; font-style: italic">
    <a id="get_policy" href="#" onclick="$('#modal').arcticmodal()">
        Внимание!!! Перед бронирование настоятельно рекомендуем ознакомится с политикой отмены брони!
    </a>
</p>