<?$parameters = unserialize(Yii::app()->cache->get('parameters'));?>
<span id="title">
        Всего найдено:
    <? echo '<b>'.$dataProvider->totalItemCount.'</b>'?> отелей В городе:
    <? echo '<b>'.$parameters['search_city'].'</b>'?> на период: с
    <? echo '<b>'.$parameters['coming_date'].'</b>'?> по
    <? echo '<b>'.$parameters['leaving_date'].'</b>'?>
    </span>
<?php

$this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$dataProvider,
    'itemView'=>'_hotelview',
    'sortableAttributes'=>array('starRating', 'hotelName'),
    'sorterHeader'=>'Сортировать по:',
    'viewData' => array('hotels' => $hotels ),
    'template'=>"<div class='info_table'><table style='width: 100%'>{summary}{sorter}{items}</table>{pager}</div>",
    'pager' => array(
        'header' => '',
    ),
    'summaryText'=>'',
));