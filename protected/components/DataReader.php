<?php

class DataReader
{
    public function getHotelDescription($hotelCode)
    {
        $hotels = Hotelslist::model()->find('HotelCode=:HotelCode',array(':HotelCode'=>$hotelCode));
        return $hotels;
    }

}