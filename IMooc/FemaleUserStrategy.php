<?php
/**
 * Created by PhpStorm.
 * User: htf
 * Date: 14-11-3
 * Time: 下午11:32
 */

namespace IMooc;

class FemaleUserStrategy implements UserStrategy {
    function showAd()
    {
        echo "2014新款女装";
    }
    function showCategory()
    {
        echo "女装";
    }
} 