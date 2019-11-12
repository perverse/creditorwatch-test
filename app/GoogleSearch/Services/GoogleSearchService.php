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
    protected $search_repo;

    public function __construct(SearchRepository $search_repo)
    {
        $this->search_repo = $search_repo;
    }

    /**
     * Get the number of mentions of $website for the given query strings
     *
     * @param string $query
     * @param string $website
     * @param integer $num_results_to_check
     * @return array
     */
    public function getMentionResultsForQuery(string $query, string $website, int $num_results_to_check = 100)
    {
        $results = $this->search_repo->getSearchResults($query, $num_results_to_check);

        $return = [];

        foreach ($results as $position => $result) {
            if ($this->resultMentionsWebsite($result, $website)) {
                // Indexing the return array like this will work for our HTML implementation
                // but if this were a JSON API returning this data, I'd like position to be on the object itself ideally

                $result['position'] = $position + 1;
                $return[$result['position']] = $result;
            }
        }

        return $return;
    }

    /**
     * Check if an api result mentions the provided website
     *
     * @param array $result
     * @param string $website
     * @return boolean
     */
    protected function resultMentionsWebsite(array $result, string $website)
    {
        return stripos($result['link'], $website) !== false ||
               stripos($result['displayLink'], $website) !== false;
    }

    /**
     * Abstracting this logic here instead of doing a count in the controller is being indulgent, I'll admit.
     * It goes to illustrate how the design pattern works, more than anything. No logic in controllers except knowing where to
     * get the data from services and applying them to presentation logic
     *
     * @param array $results
     * @return integer
     */
    public function countMentionsInResults(array $results)
    {
        return count($results);
    }
}