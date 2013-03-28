<?php
/**
 * Created by JetBrains PhpStorm.
 * User: amd1648
 * Date: 26.03.13
 * Time: 23:56
 * To change this template use File | Settings | File Templates.
 */

class ViewWidgetController {
    const MAP_VIEW = 0;
    const LIST_VIEW = 1;
    const CARD_VIEW = 2;
    static function renderWidget($type, $params){
        switch($type){
            case 0:
                $map = new GMapWidget;
                $map->params = $params['hotels'];
                $map->run();
                break;
            case 1:
                $list = new ListViewWidget;
                $list->dataProvider = $params['dataProvider'];
                $list->hotels = $params['hotels'];
                $list->run();
                break;
            case 2:
                $card = new CardViewWidget;
                $card->dataProvider = $params['dataProvider'];
                $card->hotels = $params['hotels'];
                $card->run();
                break;
        }
    }
}