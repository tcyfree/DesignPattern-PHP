<?php
namespace IMooc;

class Proxy implements IUserProxy
{
    function getUserName($id)
    {
        $db = Factory::getDatabase('slave');
        $db->query("select name from user where id =$id limit 1");
    }

    function setUserName($id, $name)
    {
        $db = Factory::getDatabase('master');
        $db->query("update user set name = $name where id =$id limit 1");
    }
}