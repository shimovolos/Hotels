<?php

class SiteController extends Controller
{

    public function actions()
    {
        return array(
            'page'=>array(
                'class'=>'CViewAction',
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
