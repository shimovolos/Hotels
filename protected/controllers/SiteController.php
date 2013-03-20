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
            if(Yii::app()->request->isAjaxRequest){
                echo $error['message'];
            }
            else{
                $this->render('error', $error);
            }
        }
    }

    public function actionDetails()
    {
        $responseData = json_decode(Yii::app()->request->cookies['responseData']);
        $hotelCode = $_GET['HotelCode'];

        $allocateHotelCode = $this->client->allocateHotelCode($hotelCode, $responseData->searchID);

        $this->render('details',array(
            'hotel'=>$this->dataDB->getHotelDescription($hotelCode),
            'allocateResponse' => $allocateHotelCode,
        ));
    }

    public function actionAutocomplete()
    {
        if (Yii::app()->request->isAjaxRequest && isset($_GET['term'])) {
            echo CJSON::encode($this->dataDB->getAutocomplete($_GET['term']));
        }
    }

    public function actionHotels()
    {
        if (isset($_POST['search']) && Yii::app()->cache->get('response') === false) {
            $search_data = array();

            foreach ($_POST as $key => $value) {
                $search_data[$key] = $value;
            }

            $response = $this->client->getAvailableHotel($search_data);

            if (!is_soap_fault($response)) {

                Yii::app()->cache->set('response', serialize($response));
                Yii::app()->cache->set('parameters', serialize($search_data));
                Yii::app()->request->cookies['responseData'] = new CHttpCookie('responseData', json_encode(array(
                    'responseID' => $response->responseId,
                    'searchID' => $response->searchId
                )));
            } else {
                throw new CHttpException($response->getCode(), $response->getMessage());
            }
        }
        $hotelsCode = $this->client->removeDuplicateHotels(unserialize(Yii::app()->cache->get('response')));
        $criteria = new CDbCriteria(
            array(
                'order' => 'StarRating DESC',
            )
        );

        if (isset($_GET['star'])) {
            Yii::app()->request->cookies['star'] = new CHttpCookie('star', json_encode($_GET['star']));
            $criteria->addInCondition('StarRating', $_GET['star'], 'AND');
        } else {
            unset(Yii::app()->request->cookies['star']);
        }
        if (isset($_GET['price'])) {
            Yii::app()->request->cookies['price'] = new CHttpCookie('price', json_encode($_GET['price']));
            $hotelsCode = $this->client->sortByPrice($_GET['price'], unserialize(Yii::app()->cache->get('response')));
        } else {
            unset(Yii::app()->request->cookies['price']);
        }
        $criteria->addInCondition('HotelCode', $hotelsCode['hotelsCode'], 'AND');
        $dataProvider = new CActiveDataProvider('HotelsList', array(
            'pagination' => array(
                'pageSize' => 10
            ),
            'criteria' => $criteria
        ));

        $this->render('hotels', array(
            'dataProvider' => $dataProvider,
            'hotelsCode' => $hotelsCode
        ));

    }

    public function actionBooking()
    {
        $searchData = unserialize(Yii::app()->cache['parameters']);
        if (!isset($_POST['get_booking'])) {
            $this->render('booking', array('data' => $searchData));
        } else {
            $lead_traveller['paxInfo'] = array(
                'paxType' => 'Adult',
                'title' => $_POST['lead_title'],
                'firstName' => $_POST['lead_1st_name'],
                'lastName' => $_POST['lead_2nd_name'],

            );
            $lead_traveller['nationality'] = 'GB';
            $other_traveller = null;
            if ($searchData['adult_paxes'] > 1) {

                for ($i = 0; $i < $searchData['adult_paxes'] - 1; $i++) {
                    $other_traveller[] = array(
                        'title' => $_POST['other_title_' . $i],
                        'firstName' => $_POST['other_1st_name_' . $i],
                        'lastName' => $_POST['other_2nd_name_' . $i]
                    );
                }
            }
            $preferences = "";
            if (isset($_POST['preference'])) {
                $preferences = $_POST['preference'];
            }
            $note = "";
            if (isset($_POST['note']))
            {
                $note = $_POST['note'];
            }
            $travel_info = array(
                'lead_traveller' => $lead_traveller,
                'other_traveller' => $other_traveller,
                'preferences' => $preferences,
                'note' => $note,
                'process' => $_POST['processId']
            );
            $bookingResponse = $this->client->makeHotelBooking($lead_traveller, $other_traveller, $_POST['processId'], $preferences, $note);
            if (!is_soap_fault($bookingResponse)) {
                $this->render('booking_status', array(
                        'getHotelBookingStatus' => $bookingResponse->hotelBookingInfo,
                        'trakingID' => $bookingResponse->trackingId
                    )
                );
            } else {
                throw new CHttpException($bookingResponse->getCode(), $bookingResponse->getMessage());
            }
        }
    }

    public function actionBookingStatus()
    {
        if (isset($_POST['getBookingStatus'])) {

            $trackingId = str_replace(' ', '', $_POST['status_trackingId']);
            $getHotelBookingStatus = $this->client->getHotelBookingStatus($trackingId);

            if (!is_soap_fault($getHotelBookingStatus)) {
                $this->render('booking_status', array('getHotelBookingStatus' => $getHotelBookingStatus));
            } else {
                throw new CHttpException($getHotelBookingStatus->getCode(), $getHotelBookingStatus->getMessage());
            }
        } elseif (isset($_POST['cancel'])) {
            $trackingId = str_replace(' ', '', $_POST['cancel_trackingId']);
            $cancelHotelBooking = $this->client->cancelHotelBooking($trackingId);

            if (!is_soap_fault($cancelHotelBooking)) {
                $this->render('cancel_booking', array('cancelHotelBooking' => $cancelHotelBooking));
            } else {
                throw new CHttpException($cancelHotelBooking->getCode(), $cancelHotelBooking->getMessage());
            }
        } else {
            $this->render('get_booking_status');
        }

    }
}