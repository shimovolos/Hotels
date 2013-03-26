<?php

class Hotelslist extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function relations()
    {
        return array(
            'description'=>array(self::HAS_MANY,'Hotelsdescription','HotelCode'),
            'amenities'=>array(self::HAS_MANY,'Hotelsamenities','HotelCode'),
            'rusAmenities' => array(self::HAS_MANY, 'Hotelinforus', 'HotelCode')
        );
    }

    public function tableName()
    {
        return 'hotelslist';
    }
}