<?php

namespace Tests\Unit\Services;

use App\Services\GitHubSearchService;
use Tests\TestCase;

class GitHubSearchServiceTest extends TestCase
{
    /**
     * @var GitHubSearchService
     */
    protected GitHubSearchService $gitHubSearchService;

    /**
     * @var string
     */
    protected string $hostName;

    /**
     * @var string
     */
    protected string $searchQuery;

    /**
     * @var string
     */
    protected string $perPageQuery;

    /**
     * @var string
     */
    protected string $pageQuery;


    protected function setUp():void
    {

        parent::setUp();

        $this->gitHubSearchService = new GitHubSearchService();

        $this->hostName = config('app.github.search');

        $this->searchQuery = 'repositories?q=language:php&sort=stars';

        $this->perPageQuery = '&per_page=';

        $this->pageQuery = '&page=';

    }

    /**
     * Test get response from search API.
     *
     * @return void
     */
    public function testGetSearchResponse()
    {
        $url = 'wrong_url';
        $this->assertFalse($this->gitHubSearchService->getSearchResponse($url));

        $url = $this->hostName . $this->searchQuery;
        $this->assertNotFalse($url);
    }

    /**
     * Test build URL.
     *
     * @return void
     */
    public function testBuildURL()
    {
        $url = $this->hostName . $this->searchQuery;
        $this->assertEquals($url, $this->gitHubSearchService->buildURL());

        $url = $this->hostName . $this->searchQuery . $this->pageQuery . '1' . $this->perPageQuery . '10';
        $this->assertEquals($url, $this->gitHubSearchService->buildURL(1, 10));
    }

    /**
     * Test get pagination array info.
     *
     * @return void
     */
    public function testGetInfoPagination()
    {
        $links = [
            [
                0 => '<https://api.github.com/search/repositories?q=language:php&sort=stars&page=2&per_page=10>',
                'rel' => 'next'
            ],
            [
                0 => '<https://api.github.com/search/repositories?q=language:php&sort=stars&page=100&per_page=10>',
                'rel' => 'last'
            ],
        ];

        $this->assertArrayHasKey('next_page_href', $this->gitHubSearchService->getInfoPagination($links));
        $this->assertArrayHasKey('last_page_href', $this->gitHubSearchService->getInfoPagination($links));
    }


    /**
     * Test get data search array info.
     *
     * @return void
     */
    public function testGetDataSearch()
    {
        $this->assertArrayHasKey('total_count', $this->gitHubSearchService->getDataSearch(1, 10));
        $this->assertArrayHasKey('items', $this->gitHubSearchService->getDataSearch(1, 10));
        $this->assertArrayHasKey('number_of_pages', $this->gitHubSearchService->getDataSearch(1, 10));
    }
}
