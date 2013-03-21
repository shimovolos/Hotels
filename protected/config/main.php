<?php

return array(
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'HotelsPro App',
    'import'=>array(
        'application.models.*',
        'application.components.*',
    ),

    'defaultController'=>'site',

    'components'=>array(
        'urlManager'=>array(
            'urlFormat'=>'path',
            'showScriptName'=>false,

        ),

        'errorHandler'=>array(
            'errorAction'=>'site/error',
        ),

        'db'=>array(
            'class'=>'CDbConnection',
            'connectionString'=>'mysql:host=localhost;dbname=hotels',
            'username'=>'root',
            'password'=>'',
            'emulatePrepare'=>true,
        ),
        'cache'=>array(
            'class'=>'system.caching.CFileCache',
        ),

        'cache'=>array(
            'class'=>'system.caching.CFileCache',
        ),
    ),


);