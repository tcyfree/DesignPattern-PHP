<?php
namespace IMooc;

class Database
{
    static private $db;

    private function __construct()
    {

    }

    static function getInstance()
    {
        if (empty(self::$db)) {
            self::$db = new self;
            return self::$db;
        } else {
            return self::$db;
        }
    }

    function where($where)
    {
        return $this;
    }

    function order($order)
    {
        return $this;
    }

    function limit($limit)
    {
        return $this;
    }

    function query($sql)
    {
        echo "SQL: $sql\n";
    }
}