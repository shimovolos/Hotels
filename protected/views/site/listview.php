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