<?php
namespace IMooc;

class Loader
{
    static function autoload($class)
    {
        require BASEDIR.'/'.str_replace('\\', '/', $class).'.php';
    }
}