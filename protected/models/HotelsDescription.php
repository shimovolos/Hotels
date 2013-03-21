<?php

class Hotelsdescription extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'hotelsdescription';
    }

    public function relations()
    {
        return array(
            'hotelCode' => array(self::BELONGS_TO, 'Hotelslist', 'HotelCode'),
        );
    }
}