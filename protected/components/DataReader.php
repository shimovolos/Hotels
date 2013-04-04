<?php

class DataReader
{
    public function getHotelDescription($hotelCode)
    {
        $hotels = Hotelslist::model()->find('HotelCode=:HotelCode',array(':HotelCode'=>$hotelCode));
        return $hotels;
    }

    public function filterSearchData($filter, $hotelsCode)
    {
        $criteria = new CDbCriteria;
        $result = array();

        $internet = $filter['Internet'];
        $restaurant = $filter['Restaurant'];
        $parking = $filter['Parking'];
        $bar = $filter['Bar'];
        $swimming = $filter['Swimming'];
        $viewType = $filter['radio'];
        foreach($filter as $key=>$value){
            if($key == 'price'){
                $hotelsCode = HotelsProAPI::sortByPrice($value, unserialize(Yii::app()->cache->get('response')));
            }elseif($key == 'StarRating'){
                $criteria->addInCondition($key,$this->pullStarRange($value), 'AND');
            }else{
                $hotelCode = join("','",$hotelsCode['hotelsCode']);
                $data = Hotelsamenities::model()->findAll(array('condition'=>"HotelCode IN ('".$hotelCode."')
                    AND PAmenities LIKE '%$internet%' AND PAmenities LIKE '%$restaurant%' AND PAmenities LIKE '%$parking%' AND
                    PAmenities LIKE '%$bar%' AND PAmenities LIKE '%$swimming%'"));

                foreach($data as $key=>$val){
                    $result[] = $val->HotelCode;
                }
                $hotels = join("','",$result);
                $criteria->addCondition("HotelCode IN ('".$hotels."')",'AND');
            }
        }
        return array('criteria' => $criteria, 'viewType' => $viewType, 'hotelsCode' => $hotelsCode);
    }

    private function pullStarRange($inputRange){
        $range = explode('-',$inputRange);
        return range($range[0], $range[1]);
    }
}