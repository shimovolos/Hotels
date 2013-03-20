<?php
registerScript("/js/slides.min.jquery.js");
registerScript("/js/maps.js");
registerScript("/js/details.js");
registerCss("/css/global.css");
$images = explode(';',$hotel->HotelImages);
?>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
<script>
    $(function() {
        $( "#tabs" ).tabs();
    });
</script>
<script type="text/javascript"
        src="http://maps.googleapis.com/maps/api/js?key=<?php echo Yii::app()->params['GOOGLE_MAPS_API_KEY']; ?>&sensor=true&language=ru">
</script>
    <div id="tabs" style="min-height: 600px">
        <ul>
            <li><a href="#tabs-1">Фото</a></li>
            <li><a href="#tabs-2">Подробная информация</a></li>
            <li><a href="#tabs-3">Карта</a></li>
            <li><a href="#tabs-4">Забронировать</a></li>
        </ul>
<div id="tabs-1">
<div id="container">
    <div id="example">
        <div id="slides">
            <div class="slides_container">
                <?php
                for($i=0;$i<count($images)-1;$i++)
                {
                    echo '<div>
                    <img src='.$images[$i].' width="370" height="260" alt="Slide">
                    </div>';
                }
                ?>
            </div>
            <a href="#" class="prev"><img src=<?php echo baseUrl()."/assets/img/arrow-prev.png"?> width="24" height="43" alt="Arrow Prev"></a>
            <a href="#" class="next"><img src=<?php echo baseUrl()."/assets/img/arrow-next.png"?> width="24" height="43" alt="Arrow Next"></a>
        </div>
        <img src=<?php echo baseUrl()."/assets/img/example-frame.png"?> width="739" height="341" alt="Example Frame" id="frame">
    </div>
</div>
    <p align="justify"><br>
    <table style="padding-left: 2%">
        <tr>
            <td class="one">
                <i>Страна: </i>
            </td>
            <td class="one">
                <b><?php echo $hotel->Country ?></b>
            </td>
        </tr>
        <tr>
            <td class="one">
                <i>Город: </i>
            </td>
            <td class="one">
                <b><?php echo $hotel->Destination ?></b>
            </td>
        </tr>
        <tr>
            <td class="one">
                <i>Название отеля: </i>
            </td>
            <td class="one">
                <b><?php echo $hotel->HotelName ?></b>
            </td>
        </tr>
        <tr>
            <td class="one">
                <i>Количество звезд: </i>
            </td>
            <td class="one">
                <b><?php for($i=0;$i<$hotel->StarRating;$i++)
                {
                    echo '<img src="'.baseUrl().'/assets/images/star_icon.png" alt="star"/>';
                } ?></b>
            </td>
        </tr>
        <tr>
            <td class="one">
                <i>Адрес: </i>
            </td>
            <td class="one">
                <b><?php echo $hotel->HotelAddress ?></b>
            </td>
        </tr>
        <tr>
            <td class="one">
                <i>Местонахождение: </i>
            </td>
            <td class="one">
                <b><?php echo $hotel->description[0]->HotelLocation ?></b>
            </td>
        </tr>
        <tr>
            <td class="one">
                <i>Почтовый код: </i>
            </td>
            <td class="one">
                <b><?php echo $hotel->HotelPostalCode ?></b>
            </td>
        </tr>
        <tr>
            <td class="one">
                <i>Номер телефона: </i>
            </td>
            <td class="one">
                <b><?php echo $hotel->HotelPhoneNumber ?></b>
            </td>
        </tr>
        <tr>
            <td class="one">
                <i>Широта: </i>
            </td>
            <td class="one">
                <b><?php echo $hotel->Latitude ?></b>
            </td>
        </tr>
        <tr>
            <td class="one">
                <i>Долгота: </i>
            </td>
            <td class="one">
                <b><?php echo $hotel->Longitude ?></b>
            </td>
        </tr>
    </table>
    </p>
    <br>
    </div>

    <div id="tabs-2">
    <p align="left" style="padding-left: 30px">
        <i>Информация о отеле: </i><br>
            <b><?php echo $hotel->description[0]->HotelInfo ?></b><br>
        <i>Территория отеля: </i><br>
            <b><?php echo $hotel->HotelArea ?></b><br>
        <i>Тип отеля: </i><br>
            <b><?php echo $hotel->description[0]->HotelType ?></b><br>
        <i>Связи: </i><br>
            <b><?php echo $hotel->Chain ?></b><br>
        <i>Тематика отеля: </i><br>
            <b><?php echo $hotel->description[0]->HotelTheme ?></b><br>
        <i>Категория: </i><br>
            <b><?php echo $hotel->description[0]->HotelCategory ?></b><br>
        <i>PAmenities: </i><br>
            <b><?php echo $hotel->amenities[0]->PAmenities ?></b><br>
        <i>RAmenities: </i><br>
            <b><?php echo $hotel->amenities[0]->RAmenities ?></b><br>
        <i>Количество комнат: </i><br>
            <b><?php echo $hotel->amenities[0]->RoomsNumber ?></b><br>
        </p>
    </div>


        <body onload="initialize(<?php echo $hotel->Latitude;?>,<?php echo $hotel->Longitude;?>)">
        <div id="tabs-3">

    <br>
        <div id="map_canvas" style="width:100%; height:500px">
        </div>

        </div>
        </body>
        <div id="tabs-4">
    <?
    if (is_object($allocateResponse->availableHotels)) {
        $hotels[] = $allocateResponse->availableHotels;
    } else {
        $hotels = $allocateResponse->availableHotels;
    }
    foreach((array)$hotels as $num=>$hotel):
        if (is_object($hotel->rooms)) {
            $rooms[] = $hotel->rooms;
        } else {
            $rooms = $hotel->rooms;
        }
        foreach ((array)$rooms as $rnum => $room) :
            ?>
            <div style="border: #8d889e 1px solid; border-radius: 2px; margin: 10px; padding: 10px;">
                <b>Room <?php echo($rnum + 1);?> Category</b><?php echo $room->roomCategory;?><br/>
                <b>Total Room Rate</b><?php echo $room->totalRoomRate;?><br/>
                <b>Paxes</b><br/>
                <?php
                if (is_object($room->paxes)) {
                    $roomsInfo[] = $room->paxes;
                } else {
                    $roomsInfo = $room->paxes;
                }
                if (is_object($room->ratesPerNight)) {
                    $ratesPerNight[] = $room->ratesPerNight;
                } else {
                    $ratesPerNight = $room->ratesPerNight;
                }
                foreach ((array)$roomsInfo as $pnum => $pax) {
                    ?>
                    <?php echo $pax->paxType;?> (<?php echo $pax->age;?>)<br/>
                    <?php
                }
                ?>
                <b>ratesPerNight</b><br/>
                <?php
                foreach ((array)$ratesPerNight as $rpnum => $price) {
                    ?>
                    <?php echo $price->date;?> (<?php echo $price->amount;?>)<br/>
                    <?php
                }
                ?>
            </div>
            <?endforeach?>
        <a href="<? echo baseUrl().'/site/booking?processId='.$hotel->processId?>">Забронировать</a><br/>
        <?endforeach?>
</div>
    </div>
