<?php
namespace IMooc;

class AllUser implements \Iterator
{
    protected $ids;
    protected $data = array();
    protected $index;

    function __construct()
    {
        $db = Factory::getDatabase();
        $result = $db->query("select id from user");
        $this->ids = $result->fetch_all(MYSQLI_ASSOC);
    }

    function current()
    {
        $id = $this->ids[$this->index]['id'];
        return Factory::getUser($id);
    }

    function next()
    {
        $this->index ++;
    }

    function valid()
    {
        return $this->index < count($this->ids);
    }

    function rewind()
    {
        $this->index = 0;
    }

    function key()
    {
        return $this->index;
    }

}