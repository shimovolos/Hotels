<?php

class DetailsController extends Controller {

    public function actionIndex()
    {
        $result = array();
        $responseData = Yii::app()->request->getParam('id');
        $hotelCode = Yii::app()->request->getParam('hotel');

        $hotelsCode = $this->client->removeDuplicateHotels(unserialize(Yii::app()->cache->get('response')));

        $hotel = join("','",$hotelsCode['hotelsCode']);
        $data = Hotelslist::model()->findAll(array('condition'=>"HotelCode IN ('".$hotel."')"));
        foreach($data as $key=>$val){
            $result[] = $val->HotelCode;
        }

        $allocateHotelCode = $this->client->allocateHotelCode($hotelCode, $responseData);

        $this->render('details',array(
            'hotel'=>$this->dataDB->getHotelDescription($hotelCode),
            'allocateResponse' => $allocateHotelCode,
            'hotelsCode'=>$result,
        ));
    }

}