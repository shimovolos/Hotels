<form method="post" action="">
    <ul>
        <li>
            <input type="hidden" name="processId" value="<?=$_GET['processId']?>"  >
            <label>Ответственное лицо:</label>
        </li>
        <li>
            <label for="lead_title">Привествие:</label>
            <select class="booking" style="width: 80px;" name="lead_title" id="lead_title">
                <option>Mr</option>
                <option>Ms</option>
            </select>
            <label for="lead_1st_name">Имя:</label>
            <input class="booking" type="text" name="lead_1st_name" id="lead_1st_name"/>
            <label for="lead_2nd_name">Фамилия:</label>
            <input class="booking" type="text" name="lead_2nd_name" id="lead_2nd_name"/><span style="color: #ff8e83">*</span>
        </li>
        <?if($data['adult_paxes'] > 1):?>
        <li>
            <label>Другие посетители</label>
            <?php

            {
                for($i = 0; $i < $data['adult_paxes'] - 1; $i++){
                    echo '
                    <li>
                    <label for="other_title_'.$i.'">Привествие:</label>
                    <select class="booking" style="width: 80px;" name="other_title_'.$i.'" id="other_title_'.$i.'">
                        <option>Mr</option>
                        <option>Ms</option>
                    </select>
                    <label for="other_1st_name_'.$i.'">Имя:</label>
                    <input class="booking" type="text" name="other_1st_name_'.$i.'" id="other_1st_name_'.$i.'"/>
                    <label for="other_1st_name_'.$i.'">Фамилия:</label>
                    <input class="booking" type="text" name="other_2nd_name_'.$i.'" id="other_2nd_name_'.$i.'"/><span style="color: #ff8e83">*</span>
                    </li>';
                }
            }
            ?>
        </li>
        <?endif?>
        <li>
            <label>Предпочтения:</label><br/>
            <input type="radio" name="preference" value="nonSmoking"/><label> - для не курящих</label><br/>
            <input type="radio" name="preference" value="smoking"><label> - для  курящих</label><br/>
        </li>
        <li>
            <label>Примечания:</label><br/>
            <textarea class="booking" name="note" style="width: 300px"></textarea>
        </li>
        <li ><div id="button_wrap">
            <input type="submit" name="get_booking" value="Забронировать"/>
        </div>
        </li>
        <li>
            <label>
                <span style="color: #ff8e83">*</span> - поля обязательные для заполнения
            </label>
        </li>

    </ul>
</form>