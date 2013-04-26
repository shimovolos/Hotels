<?
registerCss('/public/css/places.css');
Yii::app()->getClientScript()->registerCoreScript('jquery.ui');
registerCss('/public/css/jquery.ui.min.css')
?>
<script>
    $(function () {
        $("#tabs").tabs();
    });
</script>

<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=<?php echo Yii::app()->params['GOOGLE_MAPS_API_KEY']; ?>&sensor=true&language=ru&libraries=places">
</script>

<?php
$key = array_search($hotel->HotelCode,$hotelsCode);

registerScript("/public/js/slides.min.jquery.js");
registerScript("/public/js/maps.js");
registerScript("/public/js/details.js");
registerCss("/public/css/global.css");
registerCss("/public/css/table.css");
$images = explode(';',$hotel->HotelImages);

if(!isset($hotelsCode[$key-1])){
    $hotelsCode[$key-1] = $hotelsCode[$key];

}elseif(!isset($hotelsCode[$key+1])){
    $hotelsCode[$key+1] = $hotelsCode[$key];
}
?>
<a href="<?=getUrlDetails($hotelsCode[$key-1],json_decode(Yii::app()->session['responseData'])->searchID)?>">
    <img src="<?echo baseUrl().'/public/images/back.png'?>" style="padding-right: 4px">
    Предыдуший отель
</a>
<a href="<?=getUrlDetails($hotelsCode[$key+1],json_decode(Yii::app()->session['responseData'])->searchID)?>" style="float: right">
    Следующий отель
    <img src="<?echo baseUrl().'/public/images/forward.png'?>"style="padding-left: 4px">
</a>

<div id="tabs" style="min-height: 400px">
<ul>
    <li><a href="#tabs-1">Общая нформация</a></li>
    <li><a href="#tabs-2">Подробная информация</a></li>
    <li><a href="#tabs-4">Забронировать</a></li>
    <a href="<? echo baseUrl().Yii::app()->session['url'];?>" style="float: right;padding-top: 7px;padding-right: 5px">Возврат к списку отелей</a>
</ul>
<div id="tabs-1">
    <? if(isset($images[1])): ?>
    <div id="container">
        <div id="example">
            <div id="slides">
                <div class="slides_container">
                    <?php
                    for ($i = 0; $i < count($images) - 1; $i++) {
                        echo '<div>
                    <img src=' . $images[$i] . ' width="370" height="260" alt="Logo">
                    </div>';
                    }
                    ?>
                </div>
                <a href="#" class="prev"><img src=<?php echo baseUrl() . "/public/img/arrow-prev.png" ?> width="24"
                    height="43" alt="Arrow Prev"></a>
                <a href="#" class="next"><img src=<?php echo baseUrl() . "/public/img/arrow-next.png" ?> width="24"
                    height="43" alt="Arrow Next"></a>
            </div>
            <img src=<?php echo baseUrl() . "/public/img/example-frame.png" ?> width="739" height="341" alt="Frame" id="frame">
        </div>
    </div>
        <? endif; ?>
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
                <b><?php for ($i = 0; $i < $hotel->StarRating; $i++) {
                    echo '<img src="' . baseUrl() . '/public/images/star_icon.png" alt="star"/>';
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
    </table>
    </p>
    <br>
    <body onload="initialize(
        <? echo "'".$hotel->HotelAddress."'";?>,
        <? echo "'".$hotel->Latitude."'";?>,
        <? echo "'".$hotel->Longitude."'";?>,
        <? echo "'".$hotel->Country."'";?>,
        <? echo "'".$hotel->Destination."'";?>)">

    <div id="map_canvas" style="width:100%; height:500px">

    </div>
    </body>
    <div class="actions">
<!--            <input hidden="" id="gmap_keyword" type="text" name="gmap_keyword" />-->
        <div class="buttons">
            <label for="gmap_type">Тип места:</label>
            <select id="gmap_type">
                <option value="airport">Аэропорт</option>
                <option value="book_store">Книжные магазины</option>
                <option value="church">Церкви,храмы</option>
                <option value="liquor_store">Винные магазины</option>
                <option value="clothing_store">Магазины одежды</option>
                <option value="convenience_store">Продуктовые магазины</option>
                <option value="hardware_store">Хозяйственные магазины</option>
                <option value="doctor">Частные клиники</option>
                <option value="library">Библиотеки</option>
                <option value="movie_theater">Кинотеатры</option>
                <option value="museum">Музеи</option>
                <option value="night_club">Ночные клубы</option>
                <option value="pharmacy">Аптеки</option>
                <option value="post_office">Почтовое отделение</option>
                <option value="shopping_mall">Торговый центр</option>
                <option value="art_gallery">Галереи</option>
                <option value="zoo">Зоопарки</option>
                <option value="atm">Банкоматы</option>
                <option value="bank">Банк</option>
                <option value="park">Парки/места отдыха</option>
                <option value="bar">Бар</option>
                <option value="restaurant">Ресторан</option>
                <option value="cafe">Кафе</option>
                <option value="food">Закусочные</option>
                <option value="hospital">Больница</option>
                <option value="police">Полиция</option>
                <option value="train_station">Вокзал ж/д</option>
                <option value="bus_station">Автобусная остановка</option>
                <option value="local_government_office">Правительственные учреждения</option>
                <option value="establishment">Государственные заведения</option>
            </select>
        </div>
        <div class="buttons">
            <label for="gmap_radius">Радиус (м):</label>
            <select id="gmap_radius">
                <option value="500">500</option>
                <option value="1000">1000</option>
                <option value="1500">1500</option>
                <option value="3000">3000</option>
                <option value="5000">5000</option>
            </select>
        </div>
        <input type="hidden" id="lat" name="lat" value="55.755786" />
        <input type="hidden" id="lng" name="lng" value="37.617633" />
        <div class="buttons" onclick="findPlaces(); return false;">Найти организации</div>
        <div class="buttons" onclick="clearOverlays(); return false;">Очистить</div>
    </div>
</div>

<div id="tabs-2">
    <div class="info_table">
        <?if (isset($hotel->rusAmenities[0])): ?>
        <ul>
            <li>
                <table>
                    <?
                    $desc = preg_split("/(?<=[.])\s+(?=[А-Я])/", $hotel->rusAmenities[0]->HotelDescription);
                    foreach ($desc as $key => $value) :
                        $split = explode(':', $value);
                        ?>
                        <tr>
                            <td>
                                <?php echo isset($split[0]) ? $split[0] : '' ?>:
                            </td>
                            <td>
                                <?php echo isset($split[1]) ? $split[1] : '' ?>
                            </td>
                        </tr>
                        <?endforeach?>
                </table>
            </li>
            <li><hr/></li>
            <li>
                <table>
                    <tr>
                        <td><b>Особенности отеля</b></td>
                        <td><b>Особенности номеров</b></td>
                    </tr>
                    <tr>
                        <td>
                            <ul>
                                <?
                                $hamen = explode(',', $hotel->rusAmenities[0]->HotelAmenities);
                                if(count($hamen)== 0){
                                    echo "<li>Нет данных</li>";
                                }
                                else{
                                    foreach ($hamen as $amenities) {
                                        echo "<li>$amenities</li>";
                                    }
                                }
                                ?>
                            </ul>
                        </td>
                        <td>
                            <ul>
                                <?
                                $ramen = explode(',', $hotel->rusAmenities[0]->RoomAmenities);
                                if(count($ramen)== 0){
                                    echo "<li>Нет данных</li>";
                                }
                                else{
                                    foreach ($ramen as $amenities) {
                                        echo "<li>$amenities</li>";
                                    }
                                }
                                ?>
                            </ul>
                        </td>
                    </tr>
                </table>
            </li>
        </ul>
        <?else:?>
        <ul>

            <li>
                <table>
                    <tr><td colspan="2"><b>Информация о данном отеле  присутствует только на английском языке. Приносим свои извинения за предоставленные неудобства!</b></b></td> </tr>
                    <tr>
                        <td>Полное описание отеля</td>
                        <td><?=$hotel->description[0]->HotelInfo?></td>
                    </tr>
                </table>

            </li>
            <li><hr/></li>
            <li>
                <table>
                    <tr>
                        <td><b>Особенности отеля</b></td>
                        <td><b>Особенности номеров</b></td>
                    </tr>
                    <tr>
                        <td>
                            <ul>
                                <?
                                $hamen = explode(';', $hotel->amenities[0]->PAmenities);
                                if(count($hamen)== 0){
                                    echo "<li>Нет данных</li>";
                                }
                                else{
                                    foreach ($hamen as $amenities) {
                                        echo "<li>$amenities</li>";
                                    }
                                }
                                ?>
                            </ul>
                        </td>
                        <td>
                            <ul>
                                <?
                                $ramen = explode(';', $hotel->amenities[0]->RAmenities);

                                if(count($ramen)== 0){
                                    echo "<li>Нет данных</li>";
                                }
                                else{
                                    foreach ($ramen as $amenities) {
                                        echo "<li>$amenities</li>";
                                    }
                                }
                                ?>
                            </ul>
                        </td>
                    </tr>
                </table>
            </li>
        </ul>
        <?endif?>

    </div>
</div>

<div id="tabs-4">
    <?
    if (is_object($allocateResponse->availableHotels)) {
        $hotels[] = $allocateResponse->availableHotels;
    } else {
        $hotels = $allocateResponse->availableHotels;
    }
    foreach ((array)$hotels as $num => $hotel):
        if (is_object($hotel->rooms)) {
            $rooms[] = $hotel->rooms;
        } else {
            $rooms = $hotel->rooms;
        }
        foreach ((array)$rooms as $rnum => $room) :
            ?>

            <div class="info_table" style="border: #8d889e 1px solid; border-radius: 2px;padding: 10px;">
                <a href="<?=Yii::app()->createUrl('booking',array('id' => strtolower($hotel->processId))) ?>" style="float: right">Забронировать</a><br/>
                <table class="specialty">
                    <tr>
                        <td style="width: 300px">
                            <b>Комната <?php echo($rnum + 1);?> Категории: </b>
                        </td>
                        <td>
                            <?
                                echo $room->roomCategory;
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>Обшая стоимость за проживание: </b>
                        </td>
                        <td>
                            $<?php echo $room->totalRoomRate;?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>Размещение + возраст:</b>
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
                                <?php
                                $paxType = array('Adult'=>'Взрослый','Child'=>'Ребенок');
                                echo $paxType[$pax->paxType];
                                ?> (<?php echo $pax->age; ?>) <br/>
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
                                <?php echo $price->date; ?> ($<?php echo $price->amount; ?>)<br/>
                                <?php
                            }
                            ?>
                        </td>
                    </tr>
                </table>
            </div>
            <br>
            <? endforeach ?>

        <? endforeach?>
</div>
</div>