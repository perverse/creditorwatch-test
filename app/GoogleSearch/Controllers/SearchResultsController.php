<?php

namespace App\GoogleSearch\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Kernel\Contracts\Controller;
use App\GoogleSearch\Views;
use App\GoogleSearch\Contracts\Services\GoogleSearchServiceInterface;

class SearchResultsController extends Controller
{
    public function __construct(Request $request, GoogleSearchServiceInterface $google_search)
    {
        $this->request = $request;
        $this->google_search = $google_search;
    }

    public function index()
    {
        return Views\Index::make();
    }

    public function results()
    {
        return Views\Listing::make([
            'search_results' => $this->google_search->getNumberOfMentionsForQuery($this->request->query->get('query'), $this->request->query->get('website'), 100)
        ]);
    }
}