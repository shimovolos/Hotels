<?php
require_once(dirname(__FILE__).'/protected/globals.php');
header('Content-Type: text/html; charset=UTF-8',true);
$yii=dirname(__FILE__).'/../../../framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';
defined('YII_DEBUG') or define('YII_DEBUG',true);
require_once($yii);
Yii::createWebApplication($config)->run();
