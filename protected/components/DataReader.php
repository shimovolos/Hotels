<?php

class DataReader
{
    public function getHotelDescription($hotelCode)
    {
        $hotels = HotelsList::model()->find('HotelCode=:HotelCode',array(':HotelCode'=>$hotelCode));
        return $hotels;
    }

}