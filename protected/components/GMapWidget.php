<?php
/**
 * Created by JetBrains PhpStorm.
 * User: amd1648
 * Date: 26.03.13
 * Time: 23:08
 * To change this template use File | Settings | File Templates.
 */

class GMapWidget extends CWidget
{

    public $params = array();

    public function run()
    {
        $this->render('gmapView', array('coord' => $this->getCoordinates()));
    }

    private function getCoordinates()
    {
        $coord = array();
        foreach ($this->params as $hotels) {
            $coord[] = array(
                'HotelName' => $hotels->HotelName,
                'HotelCode' => $hotels->HotelCode,
                'Long' => $hotels->Longitude,
                'Lat' => $hotels->Latitude
            );
        }
        return $coord;
    }
}
