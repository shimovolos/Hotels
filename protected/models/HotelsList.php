<?php

class Hotelslist extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'hotelslist';
    }

    public function relations()
    {
        return array(
            'hotelsamenities' => array(self::HAS_MANY, 'Hotelsamenities', 'HotelCode'),
            'hotelsdescriptions' => array(self::HAS_MANY, 'Hotelsdescription', 'HotelCode'),
            'hotelCode' => array(self::BELONGS_TO, 'Hotelsamenities', 'HotelCode'),
        );
    }
}