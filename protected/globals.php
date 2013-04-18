<?php

function baseUrl()
{
    return Yii::app()->request->baseUrl;
}

function registerScript($dirName)
{
    return Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.$dirName,CClientScript::POS_END);
}

function registerCss($dirName)
{
    return Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.$dirName,'screen');
}

function getUrlDetails($hotelCode,$searchId)
{
    return Yii::app()->createUrl('details',array(
        'hotel' => strtolower($hotelCode),
        'id' => strtolower($searchId)));
}