<form action="" method="post">
    <table>
    <tr>
        <td><label>Для проверки сострояния заказа введите код бронирования полученный после операции оформления заказа:</label></td>
    </tr>
    <tr>
        <td>
            <input class="advanced" type="text"  id="status_trackingId" name="status_trackingId" />
            <input type="submit" name="getBookingStatus" style="height: 25px"/>
        </td>
    </tr>
    <tr>
        <td><label>Для отмены бронирования введите полученный код:</label></td>
    </tr>
    <tr>
        <td>
            <input class="advanced" type="text" id="cancel_trackingId" name="cancel_trackingId"/>
            <input type="submit" name="cancel" value="Отменить" style="height: 25px"/>
        </td>
    </tr>
    </table>
</form>