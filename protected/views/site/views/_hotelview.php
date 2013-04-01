<tr>
    <td style="padding: 1px; width: 150px">
        <?
        $images = explode(';',$data->HotelImages);
        ?>
        <a href="<? echo baseUrl().'/site/details?HotelCode='.$data->HotelCode?>" target="_blank">
            <img src="<? echo $images[0]?>" alt="logo" width="150"/>
        </a>
    </td>
    <td style="vertical-align: top">
        <a href="<? echo baseUrl().'/site/details?HotelCode='.$data->HotelCode?>">
            <input type="hidden" name="HotelCode" value="<? echo $data->HotelCode?>"/><? echo $data->HotelName?></a><br/>
        <?
        foreach((array)$hotels as $key=>$hotel ){
            if($hotel->hotelCode == $data->HotelCode){
                echo '<label>Тип номера: '.$hotel->boardType.'</label><br/>';
                echo '<label>Полная стоимость: <b>$'.$hotel->totalPrice.'</b></label><br/>';
                break;
            }
        }
        ?>
        <?for($i=0;$i<$data->StarRating;$i++):?>

            <img src="<? echo baseUrl().'/public/images/star_icon.png'?>" alt="star"/>

        <?endfor?>
        <br/>
    </td>
</tr>
