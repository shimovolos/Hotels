<table>
    <tr><td><label>Код операции бронирования:</label></td><td><?=$cancelHotelBooking->trackingId?></td></tr>
    <tr><td><label>Состояние заказа:</label></td><td><?=$cancelHotelBooking->bookingStatus;?></td></tr>
    <tr><td><label>Комментарий:</label></td><td><?=$cancelHotelBooking->note;?></td></tr>
    <tr><td><label>Номер агенства:</label></td><td><?=$cancelHotelBooking->agencyReferenceNumber;?></td></tr>
</table>
