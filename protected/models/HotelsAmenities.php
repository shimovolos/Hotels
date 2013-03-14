<?php

class HotelsAmenities extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function relations()
    {
        return array(
            'lists'=>array(self::BELONGS_TO,'hotelslist','HotelCode'),
        );
    }
}