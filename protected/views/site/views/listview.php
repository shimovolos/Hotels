<?$parameters = unserialize(Yii::app()->cache->get('parameters'));?>

<?php

$this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$dataProvider,
    'itemView'=> 'views/'.$viewType,
    'sortableAttributes'=>array('starRating', 'hotelName'),
    'sorterHeader'=>'Сортировать по:',
    'viewData' => array('hotels' => $hotels, 'availableRooms' => $availableRooms, 'priceRange' => $priceRange ),
    'template'=> $template,
    'pager' => array(
        'header' => '',
    ),
    'itemsCssClass' => 'item-list',
    'itemsTagName' => 'ul',
    'id' => 'ajaxListView',
    'ajaxUpdate' => true,
    'ajaxUrl' => 'update',
    'summaryText' =>
        "<span id='title'>{start}-{end} отелей из {count}, найденных в <b>".
            $parameters['search_city']."</b> для проживания с <b>".
            $parameters['coming_date']."</b> по <b>".
            $parameters['leaving_date']."</b>
        </span>"
));
?>