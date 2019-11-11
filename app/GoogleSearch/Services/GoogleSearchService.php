<?php

namespace App\GoogleSearch\Services;

use App\GoogleSearch\Contracts\Services\GoogleSearchServiceInterface;
use App\GoogleSearch\Contracts\Repositories\SearchRepository;

/**
 * The service in charge of getting our search results from google,
 * parsing them into the data/format we're after and passing them abck to the controller (or other interface)
 */
class GoogleSearchService implements GoogleSearchServiceInterface
{
    protected $search;

    public function __construct(SearchRepository $search)
    {
        $this->search = $search;
    }

    /**
     * Get the number of mentions of $website for the given query strings
     *
     * @param string $query
     * @param string $website
     * @param integer $num_results_to_check
     * @return array
     */
    public function getNumberOfMentionsForQuery(string $query, string $website, int $num_results_to_check = 100)
    {

    }
}