<?php
/**
 * Created by JetBrains PhpStorm.
 * User: amd1648
 * Date: 26.03.13
 * Time: 23:21
 * To change this template use File | Settings | File Templates.
 */

class ListViewWidget extends CWidget {
    public $dataProvider = null;
    public $hotels = null;

    public function run(){
        $this->render('listView', array('dataProvider' => $this->dataProvider, 'hotels' => $this->hotels));
    }

}