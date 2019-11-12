<?php

namespace Tests\GoogleSearch;

use App\GoogleSearch\Contracts\Services\GoogleSearchServiceInterface;
use App\GoogleSearch\Contracts\Repositories\SearchRepository;
use App\GoogleSearch\Services\GoogleSearchService;
use App\GoogleSearch\Repositories\GoogleSearchRepositoryApi;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\HttpFoundation\Request as SfRequest;
use Symfony\Component\HttpFoundation\ParameterBag;

trait MocksTrait
{
    public function getSearchTerm()
    {
        return 'Github';
    }

    public function getWebsite()
    {
        return '';
    }

    public function getLimit()
    {
        return 100;
    }

    public function mockHttpRequest($query_parameters)
    {
        return new SfRequest($query_parameters);
    }

    public function mockGoogleSearchRepoForSearch($search_term, $limit)
    {
        $repository_mock = $this->createMock(SearchRepository::class);
        $repository_mock->expects($this->once())
            ->method('getSearchResults')
            ->with($search_term, $limit)
            ->will($this->returnValue([]));

        return $repository_mock;
    }

    public function mockGoogleSearchServiceBasic()
    {
        return $this->createMock(GoogleSearchServiceInterface::class);
    }

    public function mockGoogleSearchServiceForSearch($search_term, $website, $limit)
    {
        $service_mock = $this->createMock(GoogleSearchServiceInterface::class);
        $service_mock->expects($this->once())
            ->method('getMentionResultsForQuery')
            ->with($search_term, $website, $limit)
            ->will($this->returnValue([]));

        return $service_mock;
    }

    public function mockGuzzleForResults()
    {
        $handler = new MockHandler([
            new GuzzleResponse(200, [], json_encode([
                'items' => []
            ])),
            new GuzzleResponse(200, [], json_encode([
                'items' => []
            ])),
            new GuzzleResponse(200, [], json_encode([
                'items' => []
            ])),
            new GuzzleResponse(200, [], json_encode([
                'items' => []
            ])),
            new GuzzleResponse(200, [], json_encode([
                'items' => []
            ])),
            new GuzzleResponse(200, [], json_encode([
                'items' => []
            ])),
            new GuzzleResponse(200, [], json_encode([
                'items' => []
            ])),
            new GuzzleResponse(200, [], json_encode([
                'items' => []
            ])),
            new GuzzleResponse(200, [], json_encode([
                'items' => []
            ])),
            new GuzzleResponse(200, [], json_encode([
                'items' => []
            ])),
            new RequestException("Error Communicating with Server", new GuzzleRequest('GET', 'test'))
        ]);
        
        return new Client(['handler' => $handler]);
    }
}