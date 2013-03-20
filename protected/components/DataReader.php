<?php

class DataReader
{
    public function getHotelDescription($hotelCode)
    {
        $hotels = HotelsList::model()->find('HotelCode=:HotelCode',array(':HotelCode'=>$hotelCode));
        return $hotels;
    }

    public function getAutocomplete($getTerm)
    {
        $result = array();
        $object = HotelDestinations::model()->findAll(array('condition'=>"City LIKE '%$getTerm%'"));

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