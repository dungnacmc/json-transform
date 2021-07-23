<?php
if (!function_exists('getInfoPagination')) {
    function getInfoPagination($links){
        $prev_page_href = '';
        $next_page_href = '';
        $last_page_href = '';
        $first_page_href = '';
        foreach($links as $link){
            if($link['rel'] == 'prev'){
                $prev_page_href = $link[0];
            }else if($link['rel'] == 'next'){
                $next_page_href = $link[0];
            }else if($link['rel'] == 'last'){
                $last_page_href = $link[0];
            }else if($link['rel'] == 'first'){
                $first_page_href = $link[0];
            }
        }
        return ['prev_page_href'=>$prev_page_href,
                'next_page_href'=>$next_page_href,
                'last_page_href'=>$last_page_href,
                'first_page_href'=>$first_page_href
            ];
    }
}
if (!function_exists('normalize')) {
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
}

if (!function_exists('parseLink')) {
function parseLink($header) {
  static $trimmed = "\"'  \n\t\r";
        $params = $matches = [];

        foreach (normalize($header) as $val) {
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

