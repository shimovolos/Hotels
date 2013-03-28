<?$parameters = unserialize(Yii::app()->cache->get('parameters'));?>
<span id="title">
        Всего найдено:
    <? echo '<b>'.$dataProvider->totalItemCount.'</b>'?> отелей В городе:
    <? echo '<b>'.$parameters['search_city'].'</b>'?> на период: с
    <? echo '<b>'.$parameters['coming_date'].'</b>'?> по
    <? echo '<b>'.$parameters['leaving_date'].'</b>'?>
    </span>
<?php
    ViewWidgetController::renderWidget(ViewWidgetController::MAP_VIEW,array('hotels' => $test));
?>