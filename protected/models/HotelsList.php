<?php

class HotelsList extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function relations()
    {
        return array(
            'description'=>array(self::HAS_MANY,'hotelsdescription','HotelCode'),
            'amenities'=>array(self::HAS_MANY,'hotelsamenities','HotelCode'),
        );
    }
}