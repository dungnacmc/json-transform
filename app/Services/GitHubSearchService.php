<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use GuzzleHttp\Psr7\Header;

class GitHubSearchService
{
    /**
     * Get data search page
     * @param int $page
     * @param int $perPage
     * @param string $seekMethod
     * @return array
     */
    public function getDataSearch(int $page = 0, int $perPage = 0, string $seekMethod = ''): array
    {
        $strPage = '';
        $strPerPage = '';

        if ($perPage !== 0) {
            $strPerPage = '&per_page=' . $perPage;
        }
        if ($page !== 0) {
            $strPage = '&page=' . $page;
        }
        if ($seekMethod &&  $seekMethod != '') {
            $charReplace = ['<', '>'];
            $url = session('page_info')[$seekMethod];
            $url = str_replace($charReplace, '', $url);
            $response = Http::get($url);
        } else {
            $url = config('app.github.search');
            $response = Http::get($url . 'repositories?q=language:php&sort=stars' . $strPerPage . $strPage);
        }

        $links = Header::parse($response->header('Link'));
        $paginationInfo =  $this->getInfoPagination($links);
        preg_match('/&page=(\d+).*$/', $paginationInfo['last_page_href'], $numberOfPages);
        $body = json_decode($response->body());
        session(['page_info' => $this->getInfoPagination($links)]);

        return [
            'total_count'       => $body->total_count ?? 0,
            'items'             => $body->items ?? [],
            'number_of_pages'   => $numberOfPages
        ];
    }

    /**
     * Get url of pagination
     * @param mixed $links
     * @return array
     */
    public function getInfoPagination($links): array
    {
        $prevPageHref = '';
        $nextPageHref = '';
        $lastPageHref = '';
        $firstPageHref = '';

        foreach ($links as $link) {
            if ($link['rel'] == 'prev') {
                $prevPageHref = $link[0];
            } else if ($link['rel'] == 'next') {
                $nextPageHref = $link[0];
            } else if ($link['rel'] == 'last') {
                $lastPageHref = $link[0];
            } else if ($link['rel'] == 'first') {
                $firstPageHref = $link[0];
            }
        }

        return [
            'prev_page_href'  => $prevPageHref,
            'next_page_href'  => $nextPageHref,
            'last_page_href'  => $lastPageHref,
            'first_page_href' => $firstPageHref
        ];
    }
}
