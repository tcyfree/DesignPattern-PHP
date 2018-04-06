<?php
namespace IMooc;

interface Observer
{
    function update($event_info = null);
}