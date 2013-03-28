<?
$this->widget('zii.widgets.CListView', array(
        'dataProvider'=>$dataProvider,
        'itemView'=>'_hotelgrid',
        'sortableAttributes'=>array('starRating', 'hotelName'),
        'sorterHeader'=>'Сортировать по:',
        'viewData' => array('hotels' => $hotels ),
        'template'=>"{summary}{sorter}<ul class='grid'>{items}</ul><br/><div id='pager_li'{pager}</div>",
        'pager' => array(
            'header' => '',
        ),
        'summaryText'=>'',
    ));
?>