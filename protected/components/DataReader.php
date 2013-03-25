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
        $engDestinations = HotelDestinations::model()->findAll(array('condition'=>"City LIKE '%$getTerm%' OR Country LIKE '%$getTerm%'"));
        $rusDestinations = Hoteldestinationsrus::model()->findAll(array('condition' => "City LIKE '%$getTerm%' OR Country LIKE '%$getTerm%'"));
        foreach($engDestinations as $obj)
        {
            $result[] = array(
                'id' => $obj->DestinationId,
                'label' => $obj->City."; ".$obj->Country
            );
        }
        foreach($rusDestinations as $obj){
            $result[] = array(
                'id' => $obj->DestinationId,
                'label' => $obj->City."; ".$obj->Country
            );
        }
        return $result;
    }
}