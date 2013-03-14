<?php
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->assetManager->baseUrl."/js/slides.min.jquery.js",CClientScript::POS_END);
Yii::app()->getClientScript()->registerCssFile(Yii::app()->assetManager->baseUrl."/css/global.css");
$images = explode(';',$hotel->HotelImages);
?>
<script>
    function ShowOrHide(id)
    {
        var block = document.getElementById(id).style;

        if(block.display == 'none')
        {
            block.display = 'block';
        }
        else
        {
            block.display = 'none';
        }
    }
</script>
<script>
    $(function(){
        $('#slides').slides({
            preload: true,
            play: 5000,
            pause: 2500,
            hoverPause: true,
            animationStart: function(){
                $('.caption').animate({
                    bottom:-35
                },100);
            },
            animationComplete: function(current){
                $('.caption').animate({
                    bottom:0
                },200);
                if (window.console && console.log) {
                    // example return of current slide number
                    console.log(current);
                };
            }
        });
    });
</script>
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
            <a href="#" class="prev"><img src=<?php echo Yii::app()->request->baseUrl."/assets/img/arrow-prev.png"?> width="24" height="43" alt="Arrow Prev"></a>
            <a href="#" class="next"><img src=<?php echo Yii::app()->request->baseUrl."/assets/img/arrow-next.png"?> width="24" height="43" alt="Arrow Next"></a>
        </div>
        <img src=<?php echo Yii::app()->request->baseUrl."/assets/img/example-frame.png"?> width="739" height="341" alt="Example Frame" id="frame">
    </div>

</div>
<div class="content">
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
                    echo '<img src="'.Yii::app()->request->baseUrl.'/assets/images/star_icon.png" alt="star"/>';
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
    <br>
    <br>
    <br>
    <a href="javascript:ShowOrHide('nl')" style="padding-left: 30px">Побдробная информация о отеле  [кликнуть для раскрытия]</a>
    <div id="nl" style="display:none;">
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
        <a href="<?=Yii::app()->baseUrl.'/site/booking?processId='.$hotel->processId?>">Забронировать</a><br/>
        <?endforeach?>

</div>

