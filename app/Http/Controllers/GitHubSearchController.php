<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaginationRequest;
use App\Services\GitHubSearchService;
use Illuminate\View\View;

class GitHubSearchController extends Controller
{
    /**
     * Return a view of pagination
     * @param PaginationRequest $request
     * @param GitHubSearchService $gitHubSearchService
     * @return View
     */
    public function index(PaginationRequest $request, GitHubSearchService $gitHubSearchService)
    {
        $page           = $request->page ?? 1;
        $itemPerPage    = $request->item_per_page ?? 10;
        $seekMethod     = $request->seek_method ?? '';
        $data           = $gitHubSearchService->getDataSearch($page, $itemPerPage, $seekMethod);
        $dataResponse = [
            'total_count'       => $data['total_count'],
            'items'             => $data['items'],
            'number_of_pages'   => $data['number_of_pages'][1] ?? $page,
            'current_page'      => $page,
            'item_per_page'     => $itemPerPage,
        ];

        if ($request->page || $request->item_per_page) {
            return view('pagination.search', $dataResponse);
        }

        return view('pagination.index', $dataResponse);
    }
}
