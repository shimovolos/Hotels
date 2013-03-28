<?php
/**
 * Created by JetBrains PhpStorm.
 * User: amd1648
 * Date: 27.03.13
 * Time: 0:23
 * To change this template use File | Settings | File Templates.
 */

class CardViewWidget extends CWidget {
    public $dataProvider = null;
    public $hotels = null;

    public function run(){
        $this->render('cardView', array('dataProvider' => $this->dataProvider, 'hotels' => $this->hotels));
    }
}