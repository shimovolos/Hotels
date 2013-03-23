<?php

class SiteController extends Controller
{

    public function actions()
    {

        return array(
            'page'=>array(
                'class'=>'CViewAction',
            ),
            'captcha'=>array(
                'class'=>'CCaptchaAction',
                'testLimit'=>'1',
                'backColor'=>0xFFFFFF,
            ),
        );
    }

    public function actionIndex()
    {
        if(Yii::app()->cache->get('response')!==false){
            Yii::app()->cache->delete('response');
        }
        $this->render('search');
    }

    public function actionAbout()
    {
        $this->render('about');
    }

    public function actionError()
    {
        if($error=Yii::app()->errorHandler->error)
        {
            if(Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    public function actionDetails()
    {
        $responseData = json_decode(Yii::app()->session['responseData']);
        $hotelCode = $_GET['HotelCode'];

        $allocateHotelCode = $this->client->allocateHotelCode($hotelCode, $responseData->searchID);

        $this->render('details',array(
            'hotel'=>$this->dataDB->getHotelDescription($hotelCode),
            'allocateResponse' => $allocateHotelCode,
        ));
    }

    public function actionAutocomplete()
    {
        $countrySelect = new SelectBox('','Выберите страну...');
        $countries = Hoteldestinations::model()->findAll(array(
            'select' => 'Country',
            'distinct' => true,
            'order' => 'Country'));

        foreach($countries as $country){
            $countrySelect->addItem($country->Country, $country->Country.'_city');
        }

        $selects = array(
            'countrySelect'=> $countrySelect,
        );

        $citySelect = new SelectBox('', 'Выберите город...');

        if(isset($_GET['key']) && strstr($_GET['key'],'_city')){

            $key = str_replace('_city','',$_GET['key']);
            $cities = Hoteldestinations::model()->findAll('Country=:Country ORDER BY City', array(':Country'=>$key));

            foreach($cities as $city){
                $citySelect->addItem($city->City.';'.$city->DestinationId);
            }
            $selects[$_GET['key']] = $citySelect;
        }
        if(array_key_exists($_GET['key'],$selects)){
            header('Content-type: application/json');
            echo $selects[$_GET['key']]->toJSON();
        }
        else{
            header('Content-type: application/json');
        }
    }

    public function setDataToCache()
    {
        $response = $this->client->getAvailableHotel($_POST['param']);
        Yii::app()->cache->set('response', serialize($response));
        Yii::app()->cache->set('parameters', serialize($_POST['param']));
        Yii::app()->session['responseData'] = json_encode(array(
            'responseID' => $response->responseId,
            'searchID' => $response->searchId
        ));
    }

    public function actionHotels()
    {
        if(isset($_POST['search_hotel'])){
            Yii::app()->cache->delete('response');
            $this->setDataToCache();
        }elseif(isset($_POST['search']) && Yii::app()->cache->get('response')===false){
            $this->setDataToCache();
        }

        $hotelsCode = $this->client->removeDuplicateHotels(unserialize(Yii::app()->cache->get('response')));

        $criteria = new CDbCriteria;
        $criteria->order = 't.StarRating DESC';

        $result = array();
        $internet = $_GET['adv_param']['Internet'];
        $restaurant = $_GET['adv_param']['Restaurant'];
        $parking = $_GET['adv_param']['Parking'];
        $bar = $_GET['adv_param']['Bar'];
        $swimming = $_GET['adv_param']['Swimming'];

        if(isset($_GET['adv_param'])){

            foreach($_GET['adv_param'] as $key=>$value){
                if($key == 'price'){
                    $hotelsCode = $this->client->sortByPrice($value, unserialize(Yii::app()->cache->get('response')));
                }elseif($key == 'StarRating'){
                    $criteria->addInCondition($key,$value, 'AND');
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
            Yii::app()->session['adv_param'] = json_encode($_GET['adv_param']);
        }
        $criteria->addInCondition('HotelCode', $hotelsCode['hotelsCode'], 'AND');
        $dataProvider = new CActiveDataProvider(Hotelslist::model(), array(
            'pagination' => array(
                'pageSize' => 10
            ),
            'criteria' => $criteria
        ));

        $this->render('hotels', array(
            'dataProvider' =>$dataProvider,
            'hotelsCode' => $hotelsCode,
        ));

    }

    public function actionBooking()
    {
        $searchData = unserialize(Yii::app()->cache['parameters']);
        if(!isset($_POST['get_booking'])){
            $this->render('booking', array('data' => $searchData));
        }
        else{
            $lead_traveller['paxInfo'] = array(
                'paxType' => 'Adult',
                'title' => $_POST['lead_title'],
                'firstName' => $_POST['lead_1st_name'],
                'lastName' =>$_POST['lead_2nd_name'],

            );
            $lead_traveller['nationality'] = 'GB';
            $other_traveller = null;
            $paxCount = $searchData['adult_paxes'];
            if(isset($searchData['children_paxes'])){
                $paxCount += $searchData['children_paxes'];
            }
            if($paxCount > 1){

                for($i = 0; $i < $paxCount-1; $i++){
                    $other_traveller[] = array(
                        'title' => $_POST['other_title_'.$i],
                        'firstName' => $_POST['other_1st_name_'.$i],
                        'lastName' => $_POST['other_2nd_name_'.$i]
                    );
                }
            }
            $preferences = "";
            if(isset($_POST['preference']))
            {
                $preferences = $_POST['preference'];
            }
            $note = "";

            if(isset($_POST['note']))
            {
                $note = $_POST['note'];
            }
            $bookingResponse = $this->client->makeHotelBooking($lead_traveller, $other_traveller, $_POST['processId'], $preferences, $note);
            $this->render('booking_status', array(
                'getHotelBookingStatus' => $bookingResponse->hotelBookingInfo,
                'trackingID' => $bookingResponse->trackingId
            ));
        }
    }

    public function actionBookingStatus()
    {
        if(isset($_POST['getBookingStatus'])){

            $trackingId = str_replace(' ', '',$_POST['status_trackingId']);
            $getHotelBookingStatus = $this->client->getHotelBookingStatus($trackingId);
            $this->render('booking_status', array(
                    'getHotelBookingStatus' => $getHotelBookingStatus->hotelBookingInfo,
                    'trackingID' => $getHotelBookingStatus->trackingId
                )
            );
        }
        elseif(isset($_POST['cancel'])){
            $trackingId =  str_replace(' ', '',$_POST['cancel_trackingId']);
            $cancelHotelBooking = $this->client->cancelHotelBooking($trackingId);
                $this->render('cancel_booking', array('cancelHotelBooking'=> $cancelHotelBooking));
        }
        else $this->render('get_booking_status');

    }
}

