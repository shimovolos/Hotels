<table>
    <tr><td><label>Код операции бронирования:</label></td><td><?=$getHotelBookingStatus->trackingId?></td></tr>
    <tr><td><label>Статус бронирования:</label></td>
        <td>
            <?
            switch($getHotelBookingStatus->hotelBookingInfo->bookingStatus){
                case 1: echo 'Заказ подтверждён'; break;
                case 2: echo 'Заказ на рассмотрении'; break;
                case 3: echo 'Заказ отклонён'; break;
                case 4: echo 'Заказ отменён'; break;
                case 5: echo 'Обработка платежа'; break;

            }
            ?>
        </td>
    </tr>
    <tr><td><label>Номер подтверждения:</label></td><td><?=$getHotelBookingStatus->hotelBookingInfo->confirmationNumber;?></td></tr>
    <tr><td><label>Код отеля:</label></td><td><?=$getHotelBookingStatus->hotelBookingInfo->hotelCode;?></td></tr>
    <tr><td><label>Дата прибытия:</label></td><td><?=$getHotelBookingStatus->hotelBookingInfo->checkIn;?></td></tr>
    <tr><td><label>Дата отъезда:</label></td><td><?=$getHotelBookingStatus->hotelBookingInfo->checkOut;?></td></tr>
    <tr><td><label>Полная стоимость:</label></td><td><?=$getHotelBookingStatus->hotelBookingInfo->totalPrice;?></td></tr>
    <tr><td><label>Скидка:</label></td><td><?=$getHotelBookingStatus->hotelBookingInfo->totalSalePrice;?></td></tr>
    <tr><td><label>Валюта:</label></td><td><?=$getHotelBookingStatus->hotelBookingInfo->currency;?></td></tr>
    <tr><td><label>Тип комнаты:</label></td><td><?=$getHotelBookingStatus->hotelBookingInfo->boardType;?></td></tr>
    <tr><td><label>Номер агенства:</label></td><td><?=$getHotelBookingStatus->hotelBookingInfo->agencyReferenceNumber;?></td></tr>
    <tr><td><label>Комментарии:</label></td><td><?=$getHotelBookingStatus->hotelBookingInfo->comments;?></td></tr>
</table>
<p>
    <label style="font-size: 13pt">
        Внимание: для просмотра статуса заказа и отмены заказа Вам необходимо сохранить код операции бронирования.
    </label>
</p>
