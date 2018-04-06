<?php

$users = new \IMooc\AllUser();
foreach($users as $user)
{
    echo $user->name."\n";
    $user->serial_no = rand(10000, 99999);
}

$id = 1;

$proxy = new \IMooc\Proxy();
$proxy->getUser($id);
$proxy->setUser($id, array('name' => 'wang'));