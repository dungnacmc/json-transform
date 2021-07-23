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
        $links = parseLink($response->header('Link'));
        $pagination_info =  getInfoPagination($links);
        preg_match('/&page=(\d+).*$/', $pagination_info['last_page_href'], $number_of_pages);
        $body = json_decode($response->body());
        session(['page_info' => getInfoPagination($links)]);
        return [
            'total_count' => $body->total_count ?? 0,
            'items' => $body->items ?? [],
            'number_of_pages' => $number_of_pages
        ];
    }
}
