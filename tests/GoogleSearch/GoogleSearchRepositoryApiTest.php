<?php

namespace Tests\GoogleSearch;

use PHPUnit\Framework\TestCase;
use App\GoogleSearch\Repositories\GoogleSearchRepositoryApi;

class GoogleSearchRepositoryApiTest extends TestCase
{
    use MocksTrait;

    /**
     * Make sure we're getting an array back from repository
     *
     * @return void
     */
    public function testGetSearchResults()
    {
        $search_term = $this->getSearchTerm();
        $limit = $this->getLimit();

        $client = $this->mockGuzzleForResults();
        $repository = new GoogleSearchRepositoryApi($client);

        $results = $repository->getSearchResults($search_term, $limit);

        $this->assertIsArray($results);
    }
}