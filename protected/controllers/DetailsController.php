<?php

class DetailsController extends Controller {

    public function actionIndex()
    {
        $result = array();
        $responseData = json_decode(Yii::app()->session['responseData']);
        $hotelCode = $_GET['HotelCode'];

        $hotelsCode = $this->client->removeDuplicateHotels(unserialize(Yii::app()->cache->get('response')));

        $hotel = join("','",$hotelsCode['hotelsCode']);
        $data = Hotelslist::model()->findAll(array('condition'=>"HotelCode IN ('".$hotel."')"));
        foreach($data as $key=>$val){
            $result[] = $val->HotelCode;
        }

        $allocateHotelCode = $this->client->allocateHotelCode($hotelCode, $responseData->searchID);

        $this->render('details',array(
            'hotel'=>$this->dataDB->getHotelDescription($hotelCode),
            'allocateResponse' => $allocateHotelCode,
            'hotelsCode'=>$result,
        ));
    }

}