<?php

class BookingController extends Controller {

    public function actionIndex()
    {
        $searchData = unserialize(Yii::app()->cache['parameters']);
        if(!isset($_POST['get_booking'])){
            $response = $this->client->getHotelCancellationPolicy(Yii::app()->request->getParam('id'));
            $policy = is_array($response->cancellationPolicy) ? $response->cancellationPolicy : array($response->cancellationPolicy);
            $this->render('booking', array('data' => $searchData, 'policy' => $policy[0]));
        }
        else{
            $info = $_POST['booking']['lead'];
            $leadTraveller['paxInfo'] = array(
                'paxType' => 'Adult',
                'title' => $info['title'],
                'firstName' => $info['1st_name'],
                'lastName' => $info['2nd_name']
            );

            $leadTraveller['nationality'] = 'GB';
            $otherTraveller = null;

            if(isset($_POST['booking']['other'])){
                $otherInfo = $_POST['booking']['other'];
                for($i = 0; $i < count($otherInfo['title']); $i++){
                    $otherTraveller[] = array(
                        'title' => $otherInfo['title'][$i],
                        'firstName' => $otherInfo['1st_name'][$i],
                        'lastName' => $otherInfo['2nd_name'][$i]
                    );
                }
            }

            $note = "";
            if(isset($_POST['booking']['note'])){
                $note = $_POST['booking']['note'];
            }
            $bookingResponse = $this->client->makeHotelBooking($leadTraveller, $otherTraveller, $_POST['processId'], "", $note);

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