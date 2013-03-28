<?$parameters = unserialize(Yii::app()->cache->get('parameters'));?>
<span id="title">
        Всего найдено:
    <? echo '<b>'.$dataProvider->totalItemCount.'</b>'?> отелей В городе:
    <? echo '<b>'.$parameters['search_city'].'</b>'?> на период: с
    <? echo '<b>'.$parameters['coming_date'].'</b>'?> по
    <? echo '<b>'.$parameters['leaving_date'].'</b>'?>
    </span>
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