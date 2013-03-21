<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js">

</script>
<script>
    $(function() {
        $( "#tabs" ).tabs();
    });
</script>
<script type="text/javascript"
        src="http://maps.googleapis.com/maps/api/js?key=<?php echo Yii::app()->params['GOOGLE_MAPS_API_KEY']; ?>&sensor=true&language=ru">
</script>

<?php
registerScript("/public/js/slides.min.jquery.js");
registerScript("/public/js/maps.js");
registerScript("/public/js/details.js");
registerCss("/public/css/global.css");
$images = explode(';',$hotel->HotelImages);
?>
    <div id="tabs" style="min-height: 400px">
        <ul>
            <li><a href="#tabs-1">Общая информация</a></li>
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
            <a href="#" class="prev"><img src=<?php echo baseUrl()."/public/img/arrow-prev.png"?> width="24" height="43" alt="Arrow Prev"></a>
            <a href="#" class="next"><img src=<?php echo baseUrl()."/public/img/arrow-next.png"?> width="24" height="43" alt="Arrow Next"></a>
        </div>
        <img src=<?php echo baseUrl()."/public/img/example-frame.png"?> width="739" height="341" alt="Example Frame" id="frame">
    </div>
</div>
    <p align="justify"><br>
    <table class="specialty">
        <tr>
            <td>
                <i>Страна: </i>
            </td>
            <td>
                <b><?php echo $hotel->Country ?></b>
            </td>
        </tr>
        <tr>
            <td>
                <i>Город: </i>
            </td>
            <td>
                <b><?php echo $hotel->Destination ?></b>
            </td>
        </tr>
        <tr>
            <td>
                <i>Название отеля: </i>
            </td>
            <td>
                <b><?php echo $hotel->HotelName ?></b>
            </td>
        </tr>
        <tr>
            <td>
                <i>Количество звезд: </i>
            </td>
            <td>
                <b><?php for($i=0;$i<$hotel->StarRating;$i++)
                {
                    echo '<img src="'.baseUrl().'/public/images/star_icon.png" alt="star"/>';
                } ?></b>
            </td>
        </tr>
        <tr>
            <td>
                <i>Адрес: </i>
            </td>
            <td>
                <b><?php echo $hotel->HotelAddress ?></b>
            </td>
        </tr>
        <tr>
            <td>
                <i>Местонахождение: </i>
            </td>
            <td>
                <b><?php echo $hotel->description[0]->HotelLocation ?></b>
            </td>
        </tr>
        <tr>
            <td>
                <i>Почтовый код: </i>
            </td>
            <td>
                <b><?php echo $hotel->HotelPostalCode ?></b>
            </td>
        </tr>
        <tr>
            <td>
                <i>Номер телефона: </i>
            </td>
            <td>
                <b><?php echo $hotel->HotelPhoneNumber ?></b>
            </td>
        </tr>
        <tr>
            <td>
                <i>Широта: </i>
            </td>
            <td>
                <b><?php echo $hotel->Latitude ?></b>
            </td>
        </tr>
        <tr>
            <td>
                <i>Долгота: </i>
            </td>
            <td>
                <b><?php echo $hotel->Longitude ?></b>
            </td>
        </tr>
    </table>
    </p>
    <br>
    </div>

    <div id="tabs-2">
        <table class="specialty">
            <tr>
                <td>
                    <i>Информация об отеле: </i>
                </td>
                <td>
                    <b><?php echo $hotel->description[0]->HotelInfo ?></b>
                </td>
            </tr>
            <tr>
                <td>
                    <i>Территория отеля: </i>
                </td>
                <td>
                    <b><?php echo $hotel->HotelArea ?></b>
                </td>
            </tr>
            <tr>
                <td>
                    <i>Тип отеля: </i>
                </td>
                <td>
                    <b><?php echo $hotel->description[0]->HotelType ?></b>
                </td>
            </tr>
            <tr>
                <td>
                    <i>Связи: </i>
                </td>
                <td>
                    <b><?php echo $hotel->Chain ?></b>
                </td>
            </tr>
            <tr>
                <td>
                    <i>Тематика отеля: </i>
                </td>
                <td>
                    <b><?php echo $hotel->description[0]->HotelTheme ?></b>
                </td>
            </tr>
            <tr>
                <td>
                    <i>Категория: </i>
                </td>
                <td>
                    <b><?php echo $hotel->description[0]->HotelCategory ?></b>
                </td>
            </tr>
            <tr>
                <td>
                    <i>PAmenities: </i>
                </td>
                <td>
                    <b><?php echo $hotel->amenities[0]->PAmenities ?></b>
                </td>
            </tr>
            <tr>
                <td>
                    <i>RAmenities: </i>
                </td>
                <td>
                    <b><?php echo $hotel->amenities[0]->RAmenities ?></b>
                </td>
            </tr>
            </tr>
            <tr>
                <td>
                    <i>Количество комнат: </i>
                </td>
                <td>
                    <b><?php echo $hotel->amenities[0]->RoomsNumber ?></b>
                </td>
            </tr>
        </table>
    </div>


        <div id="tabs-3">
        <br>
            <body onload="initialize(<?php echo $hotel->Latitude;?>,<?php echo $hotel->Longitude;?>)">
            <div id="map_canvas" style="width:100%; height:500px">
                <script>
                    google.maps.event.trigger(map, 'resize');
                    map.setZoom( map.getZoom() );
                </script>
            </div>
            </body>
        </div>


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
            <a href="<? echo baseUrl().'/site/booking?processId='.$hotel->processId?>">Забронировать: </a><br/>
            <div style="border: #8d889e 1px solid; border-radius: 2px; margin: 10px; padding: 10px;">
                <table class="specialty">
                    <tr>
                        <td>
                            <b>Комната <?php echo($rnum + 1);?> Категории: </b>
                        </td>
                        <td>
                            <?php echo $room->roomCategory;?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>Обшая стоимость за проживание: </b>
                        </td>
                        <td>
                            <?php echo $room->totalRoomRate;?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>Размещение:</b>
                        </td>
                        <td>
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
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>Даты размешения + стоимость за ночь:</b>
                        </td>
                        <td>
                            <?php
                            foreach ((array)$ratesPerNight as $rpnum => $price) {
                                ?>
                                <?php echo $price->date;?> (<?php echo $price->amount;?>)<br/>
                                <?php
                            }
                            ?>
                        </td>
                    </tr>
            </table>
            </div>
            <?endforeach?>

        <?endforeach?>
</div>
    </div>
