<?php

class SearchController extends Controller {

    public function actionIndex()
    {
        Yii::app()->session['url'] = Yii::app()->request->getUrl();
        Yii::app()->cache->set('parameters', serialize($_GET['param']));
        $filter = null;
        if(Yii::app()->request->cookies->contains('filter')){
            $filter = unserialize(Yii::app()->request->cookies['filter']->value);
        }
        $this->render('hotels', array(
            'hotels'=>$this->hotelsResponse(),
            'filter' => $filter
        ), false);
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

    public function actionUpdate()
    {
        if(isset($_GET['search_hotel'])){
            Yii::app()->cache->delete('response');
            Yii::app()->cache->delete('parameters');
            $this->setDataToCache();
        }elseif(isset($_GET['search']) && Yii::app()->cache->get('response')===false){
            $this->setDataToCache();
        }

        $hotelsCode = $this->client->removeDuplicateHotels(unserialize(Yii::app()->cache->get('response')));
        $criteria = new CDbCriteria;
        if(isset(Yii::app()->request->cookies['filter'])){
            $filter = unserialize(Yii::app()->request->cookies['filter']->value);
            $viewType = $filter['radio'];
        }else{
            $viewType = '_hotelview';
        }
        $params = array();
        if(isset($_GET['adv_param'])) {
            Yii::app()->request->cookies['filter'] = new CHttpCookie('filter',serialize($_GET['adv_param']));
            foreach($_GET['adv_param'] as $key=>$value){
                $params['adv_param'][$key] = $value;
            }
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
                    'pageSize' => 12,
                    'route' => 'search/update',
                    'params' => $params
                ),
                'criteria' => $criteria,
                'sort' => $this->Sort($_GET["adv_param"]),
            ));

            $this->renderPartial('views/listview',array(
                'dataProvider'=>$dataProvider,
                'availableRooms' => $hotelsCode['availableRooms'],
                'hotels'=>$this->hotelsResponse(),
                'viewType' => $viewType,
                'template' => Yii::app()->params[$viewType],
                'priceRange' => $hotelsCode['priceRange']

            ), false, true);
        }

    }

    private function hotelsResponse()
    {
        $response = unserialize(Yii::app()->cache->get('response'));
        if(isset($response->availableHotels)){
            if (is_object($response->availableHotels)) {
                $hotels[] = $response->availableHotels;
            } else {
                $hotels = $response->availableHotels;
            }
            return $hotels;
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

    private function Sort($paramsFromUrl)
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
        $sort->route = 'search/update';
        $params = array();
        foreach($paramsFromUrl as $key=>$value){
            $params['adv_param'][$key] = $value;
        }
        $sort->params = $params;
        return $sort;
    }

    public function  actionMap()
    {
        $hotelsCode = $this->client->removeDuplicateHotels(unserialize(Yii::app()->cache->get('response')));
        $filterResult = $this->dataDB->filterSearchData($_GET['adv_param'], $hotelsCode);
        $criteria = $filterResult['criteria'];
        $hotelsCode = $filterResult['hotelsCode'];
        $criteria->addInCondition('HotelCode', $hotelsCode['hotelsCode'], 'AND');

        $info = Hotelslist::model()->findAll($criteria);

        $coord = array();
        foreach ($info as $hotels) {
            foreach($this->hotelsResponse() as $key=>$val){
                if($val->hotelCode == $hotels->HotelCode){
                    $images = explode(';',$hotels->HotelImages);
                    $coord[] = array(
                        'Country' => $hotels->Country,
                        'City' => $hotels->Destination,
                        'HotelName' => $hotels->HotelName,
                        'HotelCode' => $hotels->HotelCode,
                        'Long' => $hotels->Longitude,
                        'HotelAddress' =>$hotels->HotelAddress,
                        'Lat' => $hotels->Latitude,
                        'StarRating' => $hotels->StarRating,
                        'Image' => $images[0],
                        'Price' => $val->totalPrice,
                    );
                    break;
                }
            }
        }
        $this->renderPartial('views/_mapview', array(
            'coord' => $coord,
        ), false, true);
    }
}