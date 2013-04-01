<?php

function baseUrl()
{
    return Yii::app()->request->baseUrl;
}

function registerScript($dirName)
{
    return Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.$dirName,CClientScript::POS_HEAD);
}

function registerCss($dirName)
{
    return Yii::app()->getClientScript()->registerCssFile(Yii::app()->request->baseUrl.$dirName, CClientScript::POS_HEAD);
}