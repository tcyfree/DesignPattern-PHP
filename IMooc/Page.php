<?php

class Page
{
    /**
     * @var \IMooc\UserStrategy
     */
    protected $strategy;
    function index()
    {
        echo "AD:";
        $this->strategy->showAd();
        echo "<br/>";

        echo "Category:";
        $this->strategy->showCategory();
        echo "<br/>";
    }

    function setStrategy(\IMooc\UserStrategy $strategy)
    {
        $this->strategy = $strategy;
    }
}

$page = new Page;
if (isset($_GET['female'])) {
    $strategy = new \IMooc\FemaleUserStrategy();
} else {
    $strategy = new \IMooc\MaleUserStrategy();
}
$page->setStrategy($strategy);
$page->index();