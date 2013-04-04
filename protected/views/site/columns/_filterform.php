<form method="get" class="adv_search" id='adv_search'  name="adv_search"  style="font-size: 4pt;">
    <table>
        <tr>
            <td>
                <div id="radio" style="font-size: 8px">
                    <input class="filter" type="radio" id="radio1" name="adv_param[radio]" value="_hotelview"  checked="checked"/><label for="radio1">Список</label>
                    <input  type="radio" id="radio2" name="adv_param[radio]" value="_mapview"  /><label for="radio2">Карта</label>
                    <input class="filter" type="radio" id="radio3" name="adv_param[radio]" value="_hotelgrid" /><label for="radio3">Таблица</label>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <label for="price">Стоимость за ночь:</label>
                <input class="filter" type="text" class="advanced" name="adv_param[price]" id="price"  autocomplete="off" readonly="true" />
                <div id="price_range" style="width: 155px"></div>
            </td>
        </tr>
        <tr>
            <td>
                <label for="star">Количество звёзд:</label><br/>
                <input class="filter" type="text" class="advanced" name="adv_param[StarRating]" id="star"  autocomplete="off" readonly="true" />
                <div id="star_range" style="width: 155px"></div>
            </td>
        </tr>
        <tr>
            <td>
                <label >Дополнительно:</label><br/>
                <input class="filter" type="checkbox" name="adv_param[Internet]" value="Internet" "/><label>Интернет</label><br />
                <input class="filter" type="checkbox" name="adv_param[Bar]" value="Bar" "/><label>Бар</label><br />
                <input class="filter" type="checkbox" name="adv_param[Parking]" value="Parking" "/><label>Парковка</label><br />
                <input class="filter" type="checkbox" name="adv_param[Restaurant]" value="Restaurant" "/><label>Ресторан</label><br />
                <input class="filter" type="checkbox" name="adv_param[Swimming]" value="Swimming" "/><label>Бассейн</label><br />
            </td>
        </tr>
    </table>
</form>