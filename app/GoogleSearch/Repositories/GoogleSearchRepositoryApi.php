<?php

namespace App\GoogleSearch\Repositories;

use App\GoogleSearch\Contracts\Repositories\SearchRepository;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Promise as GuzzlePromise;

class GoogleSearchRepositoryApi implements SearchRepository
{
    public function __construct(GuzzleClient $guzzle)
    {
        $this->guzzle = $guzzle;
    }

    public function getSearchResults(string $query, int $limit = null)
    {
        $per_page = 10; // this is a hard limit on this api implementation by google
        $total_pages = ceil($limit / $per_page);

        $promises = [];

        // since we have a hard limit of 10 results per page, we'll fire off all requests in
        // async so that we can get all the results back faster
        for ($i = 0; $i < $total_pages; $i++) {
            $promises[$i] = $this->guzzle->getAsync('/customsearch/v1', [
                'query' => $this->parseQueryParameters([
                    'q' => $query,
                    'num' => $per_page,
                    'start' => ($per_page * $i) + 1
                ])
            ]);
        }

        $results = GuzzlePromise\unwrap($promises);
        ksort($results); // make sure the pages are in correct order (we indexed the promises by page number earlier)

        $return_array = [];

        foreach($results as $result) {
            $decoded_result = json_decode($result->getBody(), true);

            if (isset($decoded_result['items'])) {
                $return_array = array_merge($return_array, array_values($decoded_result['items']));
            }
        }

        return $return_array;
    }

    protected function parseQueryParameters($opts)
    {
        return array_merge($opts, [
            'cx' => getenv('GOOGLE_SEARCH_ENGINE_ID'),
            'key' => getenv('GOOGLE_API_KEY')
        ]);
    }
}
