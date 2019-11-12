<?php

namespace Tests\GoogleSearch;

use PHPUnit\Framework\TestCase;
use App\GoogleSearch\Services\GoogleSearchService;

class GoogleServiceTest extends TestCase
{
    use MocksTrait;

    /**
     * Test we're getting an array back from the GoogleSearchService
     *
     * @return void
     */
    public function testMentionResults()
    {
        $search_term = $this->getSearchTerm();
        $website = $this->getWebsite();
        $limit = $this->getLimit();

        $repository_mock = $this->mockGoogleSearchRepoForSearch($search_term, $limit);
        $service = new GoogleSearchService($repository_mock);

        $result = $service->getMentionResultsForQuery($search_term, $website, $limit);

        $this->assertIsArray($result);
    }
}