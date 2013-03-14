<?php
class HotelsProAPI
{

    function __construct(){
        $this->client = new SoapClient(HP_WSDL_PATH, array('trace' => 1));
    }

    /**
     * this function makes a request to the HotelPro service and return founded Hotels
     * @param $search_parameters array that contains next data: CityCode, CheckIn date, CheckOut date, number of adult paxes, number of child paxes if they are defined
     * @return mixed - response from service, object that contains info about founded hotels
     */
    public function getAvailableHotel($search_parameters){

        for($i = 0; $i< $search_parameters['adult_paxes']; $i++){
            $adult[] = array('paxType' => 'Adult');
        }
        if(isset($search_parameters['is_child'])){
            for($i = 0; $i< $search_parameters['child']; $i++){
                $child[] = array('paxType' => 'Child', 'age' => $search_parameters['child_age_'.$i] );
            }
        }
        /**
         * @todo Продумать вопрос с размещением клиентов по комнатам .
         */
        if(isset($search_parameters['is_child'])){
            $room = array($adult, $child);
        }
        else{
            $room = array($adult);
        }
        try{
            $response = $this->client->getAvailableHotel(
                HP_API_KEY,
                $search_parameters['city_id'],
                $this->convertDate($search_parameters['coming_date']),
                $this->convertDate($search_parameters['leaving_date']),
                "USD",
                "GB",
                "false",
                $room,
                null
            );
            return $response;
        }
        catch(SoapFault $fault){
            return $fault;
        }
    }

    public function allocateHotelCode($hotelCode, $searchId){
        try{

            $response = $this->client->allocateHotelCode(HP_API_KEY,
                $searchId,
                $hotelCode);
            return $response;
        }
        catch(SoapFault $fault){
            return $fault;
        }
    }

    private function convertDate($date){
        $result =  date("Y-m-d", strtotime($date));
        return $result;
    }

    private function responseToArray($response){
        if(is_object($response->availableHotels)){
            $hotels[] = $response->availableHotels;
        }
        else{
            $hotels = $response->availableHotels;
        }
        return $hotels;
    }

    public function removeDuplicateHotels($response){
        $hotels = $this->responseToArray($response);
        foreach((array)$hotels as $hotel){
            $hotelsCode[] = $hotel->hotelCode;
        }
        $hotelsCode = array_unique($hotelsCode);
        return array(
            'responseId' => $response->responseId,
            'searchId' => $response->searchId,
            'totalFound' =>count($hotelsCode),
            'hotelsCode' => $hotelsCode
        );
    }


    public function sortByPrice($priceRange, $response){
        $price = explode('-', $priceRange);
        $price['from'] = intval(str_replace('$', '', $price[0]));
        $price['to'] = intval(str_replace('$', '', $price[1]));
        $hotels = $this->responseToArray($response);
        $hotelsCode = array();
        foreach((array)$hotels as $hotel){
            if($hotel->totalPrice >= $price['from'] && $hotel->totalPrice <= $price['to']){
                $hotelsCode[] = $hotel->hotelCode;
            }
        }
        $hc = array_unique($hotelsCode);
        return array(
            'responseId' => $response->responseId,
            'searchId' => $response->searchId,
            'totalFound' =>count($hotelsCode),
            'hotelsCode' => $hc
        );
    }

    public function makeHotelBooking($lead, $other, $processId, $preferences, $notes){
        try{
            $response = $this->client->makeHotelBooking(
                HP_API_KEY,
                $processId,
                "",
                $lead,
                $other,
                $preferences,
                $notes
            );
            return $response;
        }
        catch(SoapFault $e){
            return $e;
        }
    }

    public function getHotelBookingStatus($trackingId){
        try {
            $getHotelBookingStatus = $this->client->getHotelBookingStatus(HP_API_KEY, $trackingId);
            return $getHotelBookingStatus;
        }
        catch (SoapFault $exception) {
            return $exception;
        }
    }

    public function cancelHotelBooking($trackingId){
        try{
            $response = $this->client->cancelHotelBooking(HP_API_KEY, $trackingId);
            return $response;
        }
        catch(SoapFault $e){
            return $e;
        }
    }
}