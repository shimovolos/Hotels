<style>
    li{display: inline-table}
</style>
<form method="post" action="">
    <ul>
            <input type="hidden" name="processId" value="<?=$_GET['processId']?>"  >
            <label>Ответственное лицо:</label><br>
        <li>
            <label for="lead_title">Привествие:</label>
            <select class="booking" style="width: 80px;" name="lead_title" id="lead_title">
                <option>Mr</option>
                <option>Ms</option>
            </select>
        </li>
        <li>
            <label for="lead_1st_name">Имя:</label>
            <input class="booking" type="text" name="lead_1st_name" id="lead_1st_name"/>
        </li>
        <li>
            <label for="lead_2nd_name">Фамилия:</label>
            <input class="booking" type="text" name="lead_2nd_name" id="lead_2nd_name"/><span style="color: #ff8e83">*</span>
        </li>
        <br>
        <br>

        <?if($data['adult_paxes'] > 1):?>
            <label>Другие посетители:</label>
        <?php

            {
                for($i = 0; $i < $data['adult_paxes'] - 1; $i++){
                    echo '
                    <br><li>
                        <label for="other_title_'.$i.'">Привествие:</label>
                        <select class="booking" style="width: 80px;" name="other_title_'.$i.'" id="other_title_'.$i.'">
                            <option>Mr</option>
                            <option>Ms</option>
                        </select>
                    </li>
                    <li>
                        <label for="other_1st_name_'.$i.'">Имя:</label>
                        <input class="booking" type="text" name="other_1st_name_'.$i.'" id="other_1st_name_'.$i.'"/>
                    </li>
                    <li>
                        <label for="other_1st_name_'.$i.'">Фамилия:</label>
                        <input class="booking" type="text" name="other_2nd_name_'.$i.'" id="other_2nd_name_'.$i.'"/><span style="color: #ff8e83">*</span>
                    </li>';
                }
            }
            ?>
        <?endif?>
<!--        <li>-->
<!--            <label>Предпочтения:</label><br/>-->
<!--            <input type="radio" name="preference" value="nonSmoking"/><label> - для не курящих</label><br/>-->
<!--            <input type="radio" name="preference" value="smoking"><label> - для  курящих</label><br/>-->
<!--        </li>-->
        <br>
        <br>
        <label>Примечания:</label>
        <br/>
            <textarea class="booking" name="note" style="width: 300px"></textarea>
        <br>
        <li >
            <div id="button_wrap">
            <input type="submit" name="get_booking" value="Забронировать"/>
            </div>
        </li>
        <br><br>
        <li>
            <label>
                <span style="color: #ff8e83">*</span> - поля обязательные для заполнения
            </label>
        </li>
    </ul>
</form>