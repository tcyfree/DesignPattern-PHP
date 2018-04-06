<?php
define('BASEDIR', __DIR__);
include BASEDIR.'/IMooc/Loader.php';
spl_autoload_register('\\IMooc\\Loader::autoload');
//echo '<meta http-equiv="content-type" content="text/html;charset=utf-8">';

IMooc\Application::getInstance(__DIR__)->dispatch();
