<?$parameters = unserialize(Yii::app()->cache->get('parameters'));?>
<!--<span id="title">-->
<!--        Всего найдено:-->
<!--    --><?// echo '<b>'.$dataProvider->totalItemCount.'</b>'?><!-- отелей В городе:-->
<!--    --><?// echo '<b>'.$parameters['search_city'].'</b>'?><!-- на период: с-->
<!--    --><?// echo '<b>'.$parameters['coming_date'].'</b>'?><!-- по-->
<!--    --><?// echo '<b>'.$parameters['leaving_date'].'</b>'?><!--<br/>-->
<!---->
<!--    </span>-->

<?php

$this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$dataProvider,
    'itemView'=> 'views/'.$viewType,
    'sortableAttributes'=>array('starRating', 'hotelName'),
    'sorterHeader'=>'Сортировать по:',
    'viewData' => array('hotels' => $hotels ),
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
Yii::app()->clientScript->registerScript('search',
    "$('.filter').change(function(){
        param = $('#adv_search').serialize();

        $.fn.yiiListView.update(
            'ajaxListView',
            {
                data: param
            }
        );
    })");
?>