<?php

namespace App\GoogleSearch\Views;

use Kernel\Contracts\View;
use App\GoogleSearch\Views\Layout;

class Listing extends View
{
    public function getTemplatePath()
    {
        return __DIR__ . '/templates/list.php';
    }

    public function getLayoutClass()
    {
        return Layout::class;
    }
}