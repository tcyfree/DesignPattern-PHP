<?php
/**
 * Created by PhpStorm.
 * User: htf
 * Date: 14-11-20
 * Time: 上午12:12
 */

namespace IMooc;


abstract class EventGenerator {
    private $observers = array();

    function addObserver(Observer $observer)
    {
        $this->observers[] = $observer;
    }

    function notify()
    {
        foreach($this->observers as $observer)
        {
            $observer->update();
        }
    }

} 