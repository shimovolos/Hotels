<table>
    <? if(isset($trackingID)): ?>
    <tr>
       <td>
            <label>Код операции бронирования:</label>
        </td>
        <td>
            $trackingID
        </td>
    </tr>
    <? endif; ?>

    <? if(isset($getHotelBookingStatus->bookingStatus)):?>
    <tr>
        <td>
            <label>Статус бронирования:</label>
        </td>
        <td>
            <?
            switch($getHotelBookingStatus->bookingStatus){
                case 1: echo 'Заказ подтверждён'; break;
                case 2: echo 'Заказ на рассмотрении'; break;
                case 3: echo 'Заказ отклонён'; break;
                case 4: echo 'Заказ отменён'; break;
                case 5: echo 'Обработка платежа'; break;
            }
            ?>
        </td>
    </tr>
    <? endif; ?>
    <? if(isset($getHotelBookingStatus->confirmationNumber)): ?>
   <tr>
        <td>
            <label>Номер подтверждения:</label>
            </td>
        <td>
            $getHotelBookingStatus->confirmationNumber
        </td>
   </tr>
    <? endif;
    if(isset($getHotelBookingStatus->hotelCode)){
        echo "<tr><td><label>Код отеля:</label></td><td><a href='".baseUrl().'/site/details?HotelCode='.$getHotelBookingStatus->hotelCode."'> $getHotelBookingStatus->hotelCode</a></td></tr>";
    }
    if(isset($getHotelBookingStatus->checkIn)){
        echo "<tr><td><label>Дата прибытия:</label></td><td>$getHotelBookingStatus->checkIn</td></tr>";
    }
    if(isset($getHotelBookingStatus->checkOut)){
        echo "<tr><td><label>Дата отъезда:</label></td><td>$getHotelBookingStatus->checkOut</td></tr>";
    }
    if(isset($getHotelBookingStatus->totalPrice)){
        echo "<tr><td><label>Полная стоимость:</label></td><td>$getHotelBookingStatus->totalPrice</td></tr>";
    }
    if(isset($getHotelBookingStatus->totalSalePrice)){
        echo "<tr><td><label>Скидка:</label></td><td>$getHotelBookingStatus->totalSalePrice</td></tr>";
    }
    if(isset($getHotelBookingStatus->currency)){
        echo "<tr><td><label>Тип валюты:</label></td><td>$getHotelBookingStatus->currency</td></tr>";
    }
    if(isset($getHotelBookingStatus->boardType)){
        echo "<tr><td><label>Тип комнаты:</label></td><td>$getHotelBookingStatus->boardType</td></tr>";
    }
    if(isset($getHotelBookingStatus->agencyReferenceNumber)){
        echo "<tr><td><label>Номер агенства:</label></td><td>$getHotelBookingStatus->agencyReferenceNumber</td></tr>";
    }
    if(isset($getHotelBookingStatus->comments)){
        echo "<tr><td><label>Комментарии:</label></td><td>$getHotelBookingStatus->comments</td></tr>";
    }
    ?>


</table>