<?php
namespace App\Decorator;

class Json
{
    function beforeRequest($controller)
    {

    }

    function afterRequest($return_value)
    {
        if ($_GET['app'] == 'json')
        {
            echo json_encode($return_value);
        }
    }
}