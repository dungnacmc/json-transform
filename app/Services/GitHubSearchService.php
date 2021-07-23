<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GitHubSearchService
{
    function getDataSearch($page = 0, $per_page = 0, $seek_method = '')
    {
        $str_page = '';
        $str_per_page = '';
        if ($per_page !== 0) {
            $str_per_page = '&per_page=' . $per_page;
        }
        if ($page !== 0) {
            $str_page = '&page=' . $page;
        }
        if ($seek_method &&  $seek_method != '') {
            $char_replace = [
                '<', '>'
            ];
            $url = session('page_info')[$seek_method];
            $url = str_replace($char_replace, '', $url);
            $response = Http::get($url);
        } else {
            $response = Http::get('https://api.github.com/search/code?q=addClass+user:mozilla' . $str_per_page . $str_page);
        }
        $links = $this->parseLink($response->header('Link'));
        $pagination_info =  $this->getInfoPagination($links);
        preg_match('/&page=(\d+).*$/', $pagination_info['last_page_href'], $number_of_pages);
        $body = json_decode($response->body());
        session(['page_info' => $this->getInfoPagination($links)]);
        return [
            'total_count' => $body->total_count ?? 0,
            'items' => $body->items ?? [],
            'number_of_pages' => $number_of_pages
        ];
    }
    /**
     * get url of pagination
     * @param mixed $links
     * @return array
     */
    function getInfoPagination($links)
    {
        $prev_page_href = '';
        $next_page_href = '';
        $last_page_href = '';
        $first_page_href = '';
        foreach ($links as $link) {
            if ($link['rel'] == 'prev') {
                $prev_page_href = $link[0];
            } else if ($link['rel'] == 'next') {
                $next_page_href = $link[0];
            } else if ($link['rel'] == 'last') {
                $last_page_href = $link[0];
            } else if ($link['rel'] == 'first') {
                $first_page_href = $link[0];
            }
        }
        return [
            'prev_page_href' => $prev_page_href,
            'next_page_href' => $next_page_href,
            'last_page_href' => $last_page_href,
            'first_page_href' => $first_page_href
        ];
    }
    /**
     * seprate a chain of link 
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
     * get url in link header
     * @param mixed $header
     * @return array
     */
    function parseLink($header)
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
