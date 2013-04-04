<?
$images = explode(';',$data->HotelImages);
?>
<tr style="height: 100px">
    <td style="padding: 1px; width: 150px; overflow: visible">
        <div style="height: 100px; overflow-y: hidden">
            <a href="<? echo baseUrl().'/site/details?HotelCode='.$data->HotelCode?>" target="_blank">
                <img class="thumb" style="width: 150px;" src="<? echo $images[0]?>" alt="logo"/>
            </a>
        </div>
    </td>
    <td style="vertical-align: top;width: 500px">
        <a style="font-size: 22px; font-weight: 440" href="<? echo baseUrl().'/site/details?HotelCode='.$data->HotelCode?>"><?=$data->HotelName?></a><br/>
        <label>Адрес: <?=$data->HotelAddress?></label><br/>
        <?
        foreach((array)$hotels as $key=>$hotel ){
            if($hotel->hotelCode == $data->HotelCode){
                $price = $hotel->totalPrice;
                echo '<label>Тип номера: '.$hotel->boardType.'</label><br/>';
                break;
            }
        }
        ?>
        <?for($i=0;$i<$data->StarRating;$i++):?>

            <img src="<? echo baseUrl().'/public/images/star_icon.png'?>" alt="star"/>

        <?endfor?>
        <br/>
    </td>
    <td>
        <label>Полная стоимость: <b>$<?=$price?></b></label><br/>
        <label>Доступных номеров:
            <?
                foreach($availableRooms as $key=>$value){
                    if($key == $data->HotelCode){
                        echo '<b>'.$value.'</b>';break;
                    }
                }
            ?>
        </label><br/>
        <a class="button" href="<?=baseUrl().'/site/details?HotelCode='.$data->HotelCode?>">подробнее</a>
    </td>
</tr>
