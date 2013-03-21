<?php

class DataReader
{
    public function getHotelDescription($hotelCode)
    {
        $hotels = Hotelslist::model()->find('HotelCode=:HotelCode',array(':HotelCode'=>$hotelCode));
        return $hotels;
    }

    public function getAutocomplete($getTerm)
    {
        $result = array();
        $object = Hoteldestinations::model()->findAll(array('condition'=>"City LIKE '%$getTerm%' OR Country LIKE '%$getTerm%'"));

        foreach($object as $obj)
        {
            $result[] = array(
                'id' => $obj->DestinationId,
                'label' => $obj->City."; ".$obj->Country
            );
        }

        return $result;
    }
}