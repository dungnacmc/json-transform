<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Psr7\Header;
use Illuminate\Support\Facades\Log;

class GitHubSearchService
{
    const SESSION_SEARCH = 'page_info';

    /**
     * @var string
     */
    private  string $hostName;

    /**
     * @var string
     */
    private string $perPageQuery;

    /**
     * @var string
     */
    private string $pageQuery;


    /**
     * @var string
     */
    private string $searchQuery;

    /**
     * GitHubSearchService constructor.
     */
    public function __construct()
    {
        $this->hostName = config('app.github.search');
        $this->searchQuery = 'repositories?q=language:php&sort=stars';
        $this->perPageQuery = '&per_page=';
        $this->pageQuery = '&page=';
    }

    /**
     * Get data search page
     * @param int $page
     * @param int $perPage
     * @param string $seekMethod
     * @return array
     */
    public function getDataSearch(int $page = 0, int $perPage = 0, string $seekMethod = ''): array
    {
        $url = $this->buildURL($page, $perPage, $seekMethod);
        $response = $this->getSearchResponse($url);

        $body = null;
        if ($response) {
            $links = Header::parse($response->header('Link'));
            $paginationInfo =  $this->getInfoPagination($links);
            preg_match('/&page=(\d+).*$/', $paginationInfo['last_page_href'], $numberOfPages);
            $body = json_decode($response->body());
            session([self::SESSION_SEARCH => $this->getInfoPagination($links)]);
        }

        return [
            'total_count'       => $body->total_count ?? 0,
            'items'             => $body->items ?? [],
            'number_of_pages'   => $numberOfPages ?? 0
        ];
    }

    /**
     * Build URL to search
     * @param int $page
     * @param int $perPage
     * @param string $seekMethod
     * @return string
     */
    public function buildURL(int $page = 0, int $perPage = 0, string $seekMethod = ''): string
    {
        $strPage = '';
        $strPerPage = '';

        if ($perPage > 0) {
            $strPerPage = $this->perPageQuery . $perPage;
        }
        if ($page > 0) {
            $strPage = $this->pageQuery . $page;
        }

        if (!empty($seekMethod)) {
            $charReplace = ['<', '>'];
            $url = session(self::SESSION_SEARCH)[$seekMethod];
            $url = str_replace($charReplace, '', $url);
        } else {
            $url = $this->hostName . $this->searchQuery  . $strPage . $strPerPage;
        }

        return $url;
    }

    /**
     * Get response from API search
     * @param string $url
     * @return false|Response
     */
    public function getSearchResponse(string $url)
    {
        try {
            return Http::get($url);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    /**
     * Get url of pagination
     * @param array $links
     * @return array
     */
    public function getInfoPagination(array $links): array
    {
        $pageHref = [
            'prev_page_href'  => '',
            'next_page_href'  => '',
            'last_page_href'  => '',
            'first_page_href' => ''
        ];

        foreach ($links as $link) {
            switch ($link['rel']) {
                case 'prev':
                    $pageHref['prev_page_href'] = $link[0];
                    break;

                case 'next':
                    $pageHref['next_page_href'] = $link[0];
                    break;

                case 'last':
                    $pageHref['last_page_href'] = $link[0];
                    break;

                case 'first':
                    $pageHref['first_page_href'] = $link[0];
                    break;

                default:
                    break;
            }
        }

        return $pageHref;
    }
}
