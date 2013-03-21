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

    'params' => array(
        'HP_WSDL_PATH' => 'http://api.hotelspro.com/4.1_test/hotel/b2bHotelSOAP.wsdl',
        'HP_API_KEY' => 'RzlmOXN0MDZJR0tVOCs1L2YzOVp4TzRHSWpwM0dDMkNuNXBqVkx0UEIxZmxEekZZUnhHTGZjdEl2UmRWZmdDeg==',
        'GOOGLE_MAPS_API_KEY' => 'AIzaSyB2kYXYnVhuXl1adm6xD8J_FbgIMn4A6M0',
    )
);