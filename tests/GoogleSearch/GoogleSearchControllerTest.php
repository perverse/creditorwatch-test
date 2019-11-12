<?php

namespace Tests\GoogleSearch;

use PHPUnit\Framework\TestCase;
use App\GoogleSearch\Controllers\SearchResultsController;
use App\GoogleSearch\Views;

class GoogleSearchControllerTest extends TestCase
{
    use MocksTrait;

    /**
     * Make sure we're getting a View object with the correct dataset back from controller
     *
     * @return void
     */
    public function testResults()
    {
        $search_term = $this->getSearchTerm();
        $website = $this->getWebsite();
        $limit = $this->getLimit();

        $request = $this->mockHttpRequest([
            'query' => $search_term,
            'website' => $website
        ]);
        $service = $this->mockGoogleSearchServiceForSearch($search_term, $website, $limit);

        $controller = new SearchResultsController($request, $service);

        $result = $controller->results();

        $this->assertInstanceOf(Views\Listing::class, $result);
        $this->assertIsArray($result->getData());
        $this->assertEquals($result->getData(), [
            'search_results' => [],
            'total_mentions' => 0,
            'query' => $search_term,
            'website' => $website,
            'total_searched' => $limit
        ]);
    }

    /**
     * Make sure we're getting a View object with the correct dataset back from controller
     *
     * @return void
     */
    public function testIndex()
    {
        $request = $this->mockHttpRequest([]);
        $service = $this->mockGoogleSearchServiceBasic();
        $controller = new SearchResultsController($request, $service);

        $result = $controller->index();

        $this->assertInstanceOf(Views\Index::class, $result);
        $this->assertIsArray($result->getData());
        $this->assertEquals($result->getData(), []);
    }
}