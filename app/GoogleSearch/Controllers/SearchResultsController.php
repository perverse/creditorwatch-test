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
        $results_to_check = 100; // we could drive this by the front end if we wanted...
        $website = strtolower($this->request->query->get('website')); // probably best we lowercase this for comparisons sake later
        $query = $this->request->query->get('query');

        $results = $this->google_search->getMentionResultsForQuery($query, $website, $results_to_check);

        return Views\Listing::make([
            'search_results' => $results,
            'total_mentions' => $this->google_search->countMentionsInResults($results),
            'query' => $query,
            'website' => $website,
            'total_searched' => $results_to_check
        ]);
    }
}