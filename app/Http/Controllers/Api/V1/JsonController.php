<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JsonController extends Controller
{
    public function transform(Request $request) {
		$data = $request->route('pattern');
	}
}
