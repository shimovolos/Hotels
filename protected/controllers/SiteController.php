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
        if(isset($_GET['key'])){
            $destinations = Hoteldestinations::model()->findAll('Country=:Country ORDER BY City', array(':Country'=>$_GET['key']));
            $cities = array();
            foreach($destinations as $city){
                $cities[] = array('id' => $city->DestinationId, 'city' => $city->City);
            }
            header('Content-type: application/json');
            echo json_encode($cities);
        }
    }

    public function setDataToCache()
    {
        $response = $this->client->getAvailableHotel($_GET['param']);
        Yii::app()->cache->set('response', serialize($response));
        Yii::app()->cache->set('parameters', serialize($_GET['param']));
        Yii::app()->session['responseData'] = json_encode(array(
            'responseID' => $response->responseId,
            'searchID' => $response->searchId
        ));
    }

    public function actionHotels()
    {
        if(isset($_GET['search_hotel'])){
            Yii::app()->cache->delete('response');
            $this->setDataToCache();
        }elseif(isset($_GET['search']) && Yii::app()->cache->get('response')===false){
            $this->setDataToCache();
        }
        $hotelsCode = $this->client->removeDuplicateHotels(unserialize(Yii::app()->cache->get('response')));

        $criteria = new CDbCriteria;

        $result = array();
        if(isset($_GET['adv_param'])) {

            $internet = $_GET['adv_param']['Internet'];
            $restaurant = $_GET['adv_param']['Restaurant'];
            $parking = $_GET['adv_param']['Parking'];
            $bar = $_GET['adv_param']['Bar'];
            $swimming = $_GET['adv_param']['Swimming'];

            foreach($_GET['adv_param'] as $key=>$value){
                if($key == 'price'){
                    $hotelsCode = $this->client->sortByPrice($value, unserialize(Yii::app()->cache->get('response')));
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
            Yii::app()->session['adv_param'] = json_encode($_GET['adv_param']);
        }
        $criteria->addInCondition('HotelCode', $hotelsCode['hotelsCode'], 'AND');

        $sort = new CSort();
        $sort->sortVar = 'sort';
        $sort->defaultOrder = 'HotelName ASC';
        $sort->multiSort = true;
        $sort->attributes = array(
            'hotelName'=>array(
                'label'=>'названию',
                'asc'=>'HotelName ASC',
                'desc'=>'HotelName DESC',
                'default'=>'desc',
            ),
            'starRating'=>array(
                'asc'=>'StarRating ASC',
                'desc'=>'StarRating DESC',
                'default'=>'desc',
                'label'=>'звёздам',
            ),
        );

        $dataProvider = new CActiveDataProvider(Hotelslist::model(), array(
            'pagination' => array(
                'pageSize' => 10
            ),
            'criteria' => $criteria,
            'sort' => $sort,
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

    private function pullStarRange($inputRange){
        $range = explode('-',$inputRange);
        return range($range[0], $range[1]);
    }
}

/**
 * @todo 1) список результатов должен быть linkable, т.е. по сути нужно термы поиска завернуть в URL
@todo 2) нужно переключение списка (list view, card view и map view)
@todo 3) нужна возможность листать найденные отели на странице просмотра отеля, типа следующий, предыдущий
@todo 4) нужен возврат со страницы просмотра отеля на список результатов. Сейчас тыкаю back и он отваливается
@todo 6) в фильтре слева на списке результатов пусть он заполнит страну и город которые я уже выбрал на главной
 */