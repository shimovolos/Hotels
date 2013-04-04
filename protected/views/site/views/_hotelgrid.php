<?
$images = explode(';',$data->HotelImages);
?>
<li>
    <table style="width: 100%">
        <tr>
            <td>
                <div style="overflow: hidden; height: 150px; text-align: center">
                    <a href="<? echo baseUrl().'/site/details?HotelCode='.$data->HotelCode?>" target="_blank">
                        <img src="<? echo $images[0]?>" alt="logo" width="230"/>
                    </a>
                </div>
            </td>
        </tr>
        <tr>
            <td>
            <a href="<? echo baseUrl().'/site/details?HotelCode='.$data->HotelCode?>"><? echo $data->HotelName?></a>
            </td>
        </tr>
        <tr>
            <td>
            <?
            foreach((array)$hotels as $key=>$hotel ){
                if($hotel->hotelCode == $data->HotelCode){
                    echo '<label>Тип номера: '.$hotel->boardType.'</label><br/>';
                    echo '<label>Полная стоимость: <b>$'.$hotel->totalPrice.'</b></label><br/>';
                    break;
                }
            }
                foreach($availableRooms as $key=>$value){
                    if($key == $data->HotelCode){
                        echo '<label>Доступных номеров:<b>'.$value.'</b></label><br/>';break;
                    }
                }
            ?>

            </td>
        </tr>
        <tr>
            <td>
            <?for($i=0;$i<$data->StarRating;$i++):?>

                <img src="<? echo baseUrl().'/public/images/star_icon.png'?>" alt="star"/>

            <?endfor?>
                <a class="button" style="margin: 0;float: right" href="<?=baseUrl().'/site/details?HotelCode='.$data->HotelCode?>">подробнее</a>
            </td>
        </tr>
    </table>
</li>