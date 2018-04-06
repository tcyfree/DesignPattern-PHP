<?php
$config = array(
    'user' => array(
        'observer' => array(
            'App\Observer\UserAdd1',
            //'App\Observer\UserAdd2',
            'App\Observer\UserAdd3',
            'App\Observer\UserAdd4',
        ),
    ),
);
return $config;