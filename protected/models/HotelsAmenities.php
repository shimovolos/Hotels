<?php

class Hotelsamenities extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'hotelsamenities';
    }

    public function relations()
    {
        return array(
            'hotelCode' => array(self::BELONGS_TO, 'Hotelslist', 'HotelCode'),
            'hotelslist' => array(self::HAS_ONE, 'Hotelslist', 'HotelCode'),
        );
    }
}