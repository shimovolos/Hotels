<tr class="hotel">
    <td>
        <?
        $images = explode(';',$data->HotelImages);
        ?>
        <a href="<?=Yii::app()->request->baseUrl.'/site/details?HotelCode='.$data->HotelCode?>" target="_blank">
            <img src="<?=$images[0]?>" alt="logo" width="250" height="200"/>
        </a>
    </td>
    <td>
        <a href="<?=Yii::app()->request->baseUrl.'/site/details?HotelCode='.$data->HotelCode?>">
        <input type="hidden" name="HotelCode" value="<?=$data->HotelCode?>"/><?=$data->HotelName?></a><br/>
        <?
        foreach((array)$hotels as $key=>$hotel ){
            if($hotel->hotelCode == $data->HotelCode){
                echo '<label>Тип номера: '.$hotel->boardType.'</label><br/>';
                echo '<label>Полная стоимость: $'.$hotel->totalPrice.'</label><br/>';
                break;
            }
        }
        ?>
        <?for($i=0;$i<$data->StarRating;$i++):?>

            <img src="<?=Yii::app()->request->baseUrl.'/assets/images/star_icon.png'?>" alt="star"/>

        <?endfor?>
        <br/>
    </td>
</tr>