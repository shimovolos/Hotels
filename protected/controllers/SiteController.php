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

    public function actionAutocomplete()
    {
        if(isset($_POST['key'])){
            $destinations = Hoteldestinations::model()->findAll('Country=:Country ORDER BY City', array(':Country'=>$_POST['key']));
            $cities = array();
            foreach($destinations as $city){
                $cities[] = array('id' => $city->DestinationId, 'city' => $city->City);
            }
            header('Content-type: application/json');
            echo json_encode($cities);
        }
    }

    private function setDataToCache()
    {
        $response = $this->client->getAvailableHotel($_GET['param']);
        Yii::app()->cache->set('response', serialize($response));
        Yii::app()->cache->set('parameters', serialize($_GET['param']));
        Yii::app()->session['responseData'] = json_encode(array(
            'responseID' => $response->responseId,
            'searchID' => $response->searchId
        ));
    }

    private function Sort()
    {
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
        return $sort;
    }

    private function hotelsResponse()
    {
        $response = unserialize(Yii::app()->cache->get('response'));
        if (is_object($response->availableHotels)) {
            $hotels[] = $response->availableHotels;
        } else {
            $hotels = $response->availableHotels;
        }
        return $hotels;
    }

    public function actionUpdate()
    {
        if(isset($_GET['search_hotel'])){
            Yii::app()->cache->delete('response');
            $this->setDataToCache();
        }elseif(isset($_GET['search']) && Yii::app()->cache->get('response')===false){
            $this->setDataToCache();
        }

        $hotelsCode = $this->client->removeDuplicateHotels(unserialize(Yii::app()->cache->get('response')));
        $criteria = new CDbCriteria;
        $viewType = '_hotelview';

        if(isset($_GET['adv_param'])) {
            $filterResult = $this->dataDB->filterSearchData($_GET['adv_param'], $hotelsCode);
            $criteria = $filterResult['criteria'];
            $viewType = $filterResult['viewType'];
            $hotelsCode = $filterResult['hotelsCode'];
        }

        $criteria->addInCondition('HotelCode', $hotelsCode['hotelsCode'], 'AND');

        if($viewType == '_mapview'){
            $this->actionMap();
        }else{
            $dataProvider = new CActiveDataProvider(Hotelslist::model(), array(
                'pagination' => array(
                    'pageSize' => 12
                ),
                'criteria' => $criteria,
                'sort' => $this->Sort(),
            ));
            $this->renderPartial('views/listview',array(
                'dataProvider'=>$dataProvider,
                'availableRooms' => $hotelsCode['availableRooms'],
                'hotels'=>$this->hotelsResponse(),
                'viewType' => $viewType,
                'template' => Yii::app()->params[$viewType],
            ), false, true);
        }

    }

    public function actionHotels()
    {
        Yii::app()->cache->set('parameters', serialize($_GET['param']));
        $this->render('hotels', array(
            'hotels'=>$this->hotelsResponse(),
        ), false);
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
            if(isset($_POST['preference'])){
                $preferences = $_POST['preference'];
            }
            $note = "";

            if(isset($_POST['note'])){
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


    public function  actionMap()
    {
        $hotelsCode = $this->client->removeDuplicateHotels(unserialize(Yii::app()->cache->get('response')));
        $filterResult = $this->dataDB->filterSearchData($_GET['adv_param'], $hotelsCode);
        $criteria = $filterResult['criteria'];
        $hotelsCode = $filterResult['hotelsCode'];
        $criteria->addInCondition('HotelCode', $hotelsCode['hotelsCode'], 'AND');

        $test = Hotelslist::model()->findAll($criteria);

        $coord = array();
        $hotel = array();
        foreach ($test as $hotels) {
            $coord[] = array(
                'HotelName' => $hotels->HotelName,
                'HotelCode' => $hotels->HotelCode,
                'Long' => $hotels->Longitude,
                'Lat' => $hotels->Latitude,
            );
            $hotel[] = $hotels->HotelCode;
        }

        $hotelCode = join("','",$hotel);
        $data = Hotelslist::model()->findAll(array('condition'=>"HotelCode IN ('".$hotelCode."') AND Latitude=0.000000 AND Longitude=0.000000 "));
        foreach($data as $key=>$val){
            $result[] = array(
                'HotelCode' => $val->HotelAddress
            );
        }


        $this->renderPartial('views/_mapview', array(
            'coord' => $coord,
            'result' => $result,
        ), false, true);
        $hotelsCode = $this->client->removeDuplicateHotels(unserialize(Yii::app()->cache->get('response')));
        $filterResult = $this->dataDB->filterSearchData($_GET['adv_param'], $hotelsCode);
        $criteria = $filterResult['criteria'];
        $criteria->addInCondition('HotelCode', $hotelsCode['hotelsCode'], 'AND');

        $test = Hotelslist::model()->findAll($criteria);
        $price = array();
        $coord = array();
        $hotel = array();
        foreach ($test as $hotels) {
//            foreach($this->hotelsResponse() as $key=>$val){
//                if($val->hotelCode == $hotels->HotelCode){
                    $images = explode(';',$hotels->HotelImages);
                    $coord[] = array(
                        'HotelName' => $hotels->HotelName,
                        'HotelCode' => $hotels->HotelCode,
                        'Long' => $hotels->Longitude,
                        'Lat' => $hotels->Latitude,
                        'StarRating' => $hotels->StarRating,
                        'Image' => $images[0],
//                        'Price' => $val->totalPrice,
                    );
//                }
//            }
            $hotel[] = $hotels->HotelCode;
        }
        $hotelCode = join("','",$hotel);
        $data = Hotelslist::model()->findAll(array('condition'=>"HotelCode IN ('".$hotelCode."') AND (Latitude=0.000000 OR Latitude IS NULL)
                                                    AND (Longitude=0.000000 OR Longitude IS NULL)"));
        foreach($data as $key=>$value){
//            foreach($this->hotelsResponse() as $key=>$val){
//                if($val->hotelCode == $value->HotelCode){
                    $images = explode(';',$value->HotelImages);
                    $result[] = array(
                        'HotelName' => $value->HotelName,
                        'HotelCode' => $value->HotelCode,
                        'StarRating' => $value->StarRating,
                        'Image' => $images[0],
//                        'Price' => $val->totalPrice,
                        'HotelAddress' => $value->HotelAddress
                    );
//                }
//            }

        }

        $this->renderPartial('views/_mapview', array(
            'coord' => $coord,
            'result' => $result,
        ), false, true);
    }
}
