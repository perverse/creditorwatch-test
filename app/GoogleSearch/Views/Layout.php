<?php

namespace App\GoogleSearch\Views;

use Kernel\Contracts\View;

class Layout extends View
{
    public function getTemplatePath()
    {
        return __DIR__ . '/templates/layout.php';
    }
}