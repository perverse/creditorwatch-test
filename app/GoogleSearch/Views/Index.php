<?php

namespace App\GoogleSearch\Views;

use Kernel\Contracts\View;
use App\GoogleSearch\Views\Layout;

class Index extends View
{
    public function getTemplatePath()
    {
        return __DIR__ . '/templates/index.php';
    }

    public function getLayoutClass()
    {
        return Layout::class;
    }
}