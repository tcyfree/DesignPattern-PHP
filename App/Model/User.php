<?php
namespace App\Model;

use IMooc\Factory;

class User extends \IMooc\Model
{
    function getInfo($id)
    {
        return Factory::getDatabase('slave')->query("select * from user where id =$id limit 1")->fetch_assoc();
    }

    function create($user)
    {
        //insert to database
        $userid = 1;
        $this->notify($user);
        return $userid;
    }
}