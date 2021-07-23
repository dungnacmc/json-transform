<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GitHubSearchService
{
    /**
     * Get data search page
     * @param int $page
     * @param int $perPage
     * @param string $seekMethod
     * @return array
     */
    function getDataSearch(int $page = 0, int $perPage = 0, string $seekMethod = ''): array
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
            $response = Http::get('https://api.github.com/search/code?q=addClass+user:mozilla' . $strPerPage . $strPage);
        }
        $links = $this->parseLink($response->header('Link'));
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
     * get url of pagination
     * @param mixed $links
     * @return array
     */
    function getInfoPagination($links): array
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

    /**
     * Separate a chain of link
     * @param mixed $header
     * @return array
     */
    function normalize($header): array
    {
        if (!is_array($header)) {
            return array_map('trim', explode(',', $header));
        }
        $result = [];
        foreach ($header as $value) {
            foreach ((array) $value as $v) {
                if (strpos($v, ',') === false) {
                    $result[] = $v;
                    continue;
                }
                foreach (preg_split('/,(?=([^"]*"[^"]*")*[^"]*$)/', $v) as $vv) {
                    $result[] = trim($vv);
                }
            }
        }
        return $result;
    }

    /**
     * Get url in link header
     * @param mixed $header
     * @return array
     */
    function parseLink($header): array
    {
        static $trimmed = "\"'  \n\t\r";
        $params = $matches = [];

        foreach ($this->normalize($header) as $val) {
            $part = [];
            foreach (preg_split('/;(?=([^"]*"[^"]*")*[^"]*$)/', $val) as $kvp) {
                if (preg_match_all('/<[^>]+>|[^=]+/', $kvp, $matches)) {
                    $m = $matches[0];
                    if (isset($m[1])) {
                        $part[trim($m[0], $trimmed)] = trim($m[1], $trimmed);
                    } else {
                        $part[] = trim($m[0], $trimmed);
                    }
                }
            }
            if ($part) {
                $params[] = $part;
            }
        }

        return $params;
    }
}
