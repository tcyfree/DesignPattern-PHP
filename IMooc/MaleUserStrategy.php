<?php
/**
 * Created by PhpStorm.
 * User: htf
 * Date: 14-11-3
 * Time: 下午11:34
 */

namespace IMooc;


class MaleUserStrategy implements UserStrategy  {

    function showAd()
    {
        echo "IPhone6";
    }

    function showCategory()
    {
        echo "电子产品";
    }
} 