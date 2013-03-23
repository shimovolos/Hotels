<?
registerCss('/public/css/style.css');
registerCss('/public/css/table.css');
?>
<form method="post" action="">
    <table class="specialty">
        <tr>
            <td colspan="2">
                <input type="hidden" name="processId" value="<?=$_GET['processId']?>"  >
                <label>Ответственное лицо:</label><br>
            </td>
        </tr>
        <tr>
            <td>
                <label for="lead_title">Привествие:</label><br>
            </td>
            <td>
                <select class="booking" style="width: 80px;" name="lead_title" id="lead_title">
                    <option>Mr</option>
                    <option>Ms</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                <label for="lead_1st_name">Имя:</label><br>
            </td>
            <td>
                <input class="booking" type="text" name="lead_1st_name" id="lead_1st_name"/>
            </td>
        </tr>
        <tr>
            <td>
                <label for="lead_2nd_name">Фамилия:</label><br>
            </td>
            <td>
                <input class="booking" type="text" name="lead_2nd_name" id="lead_2nd_name"/><span style="color: #ff8e83">*</span>
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
        <label>Другие посетители:</label>
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
                            <select class="booking" style="width: 80px;" name="other_title_'.$i.'" id="other_title_'.$i.'">
                                <option>Mr</option>
                                <option>Ms</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="other_1st_name_'.$i.'">Имя:</label>
                        </td>
                        <td>
                            <input class="booking" type="text" name="other_1st_name_'.$i.'" id="other_1st_name_'.$i.'"/>
                        </td>
                    </tr>
                        <td>
                            <label for="other_1st_name_'.$i.'">Фамилия:</label>
                        </td>
                        <td>
                            <input class="booking" type="text" name="other_2nd_name_'.$i.'" id="other_2nd_name_'.$i.'"/><span style="color: #ff8e83">*</span>
                        </td>
                    </tr>';
            }
        }
        ?>
        <?endif?>
    </table>
    <label>Примечания:</label>
    <br/>
    <textarea class="booking" name="note" style="width: 300px"></textarea>
    <div id="button_wrap">
        <input type="submit" name="get_booking" value="Забронировать"/>
    </div>
    <br>
    <label>
        <span style="color: #ff8e83">*</span> - поля обязательные для заполнения
    </label>
</form>