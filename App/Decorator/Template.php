<?php
namespace App\Decorator;

class Template
{
    /**
     * @var \IMooc\Controller
     */
    protected $controller;

    function beforeRequest($controller)
    {
        $this->controller = $controller;
    }

    function afterRequest($return_value)
    {
        if ($_GET['app'] == 'html')
        {
            foreach($return_value as $k => $v)
            {
                $this->controller->assign($k, $v);
            }
            $this->controller->display();
        }
    }
}