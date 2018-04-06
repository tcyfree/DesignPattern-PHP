<?php
class Page
{
    function index()
    {
        $user = IMooc\Factory::getUser(1);
        $user->name = 'rango';
        var_dump($user);
        $this->test();

        echo "OK";
    }

    function test()
    {
        $user = IMooc\Factory::getUser(1);
        var_dump($user);
        $user->mobile = '18933334444';
    }
}
