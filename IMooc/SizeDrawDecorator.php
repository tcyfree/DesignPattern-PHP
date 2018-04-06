<?php
namespace IMooc;

class SizeDrawDecorator implements DrawDecorator
{
    protected $size;
    function __construct($size = '14px')
    {
        $this->size = $size;
    }

    function beforeDraw()
    {
        echo "<div style='font-size: {$this->size};'>";
    }

    function afterDraw()
    {
        echo "</div>";
    }
}