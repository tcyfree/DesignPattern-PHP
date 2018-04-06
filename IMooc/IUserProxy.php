<?php
namespace IMooc;

interface IUserProxy
{
    function getUserName($id);
    function setUserName($id, $name);
}