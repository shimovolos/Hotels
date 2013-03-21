<tr class="hotel">
    <td>
        <?
        $images = explode(';',$data->HotelImages);
        ?>
        <a href="<? echo baseUrl().'/site/details?HotelCode='.$data->HotelCode?>" target="_blank">
            <img src="<? echo $images[0]?>" alt="logo" width="250" height="200"/>
        </a>
    </td>
    <td>
        <a href="<? echo baseUrl().'/site/details?HotelCode='.$data->HotelCode?>">
        <input type="hidden" name="HotelCode" value="<? echo $data->HotelCode?>"/><? echo $data->HotelName?></a><br/>
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

            <img src="<? echo baseUrl().'/public/images/star_icon.png'?>" alt="star"/>

        <?endfor?>
        <br/>
    <hr>
    </td>
</tr>
