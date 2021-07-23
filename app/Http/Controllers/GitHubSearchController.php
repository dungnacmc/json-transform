<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\PaginationRequest;
use App\Services\GitHubSearchService;

class GitHubSearchController extends Controller
{
     public function index(PaginationRequest $request, GitHubSearchService $gitHubSearchService) {
        $page = $request->page??1;
        $item_per_page = $request->item_per_page??10;
        $seek_method = $request->seek_method??'';
        $data = $gitHubSearchService->getDataSearch($page ,$item_per_page,$seek_method);
        
        $data_respone = [
            'total_count'=>$data['total_count'],
            'items'=>$data['items'],
            'number_of_pages'=>$data['number_of_pages'][1]??$page,
            'current_page'=>$page ,
            'item_per_page'=>$item_per_page,
        ];
        if($request->page || $request->item_per_page ){
            return view('pagination.search',$data_respone);
        }
		return view('pagination.index',$data_respone);
	}
}
