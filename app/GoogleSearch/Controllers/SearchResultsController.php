<?php

namespace App\GoogleSearch\Controllers;

use Symfony\Component\HttpFoundation\Request;

class SearchResultsController
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {

    }

    public function results()
    {
        
    }
}