<?php

return array(

    'appNameIOS'     => array(
        'environment' =>'development',
        'certificate' =>app_path().'/PushNotification/tk.pem',
        'passPhrase'  =>'123456',
        'service'     =>'apns'
    ),
    'appNameAndroid' => array(
        'environment' =>'production',
        'apiKey'      =>'yourAPIKey',
        'service'     =>'gcm'
    )

);