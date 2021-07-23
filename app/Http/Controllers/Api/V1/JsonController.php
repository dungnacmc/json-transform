<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\JsonService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;


class JsonController extends Controller
{
    /**
     * Transform input JSON to expected JSON
     * @param Request $request
     * @param JsonService $jsonService
     * @return JsonResponse
     */
    public function transform(Request $request, JsonService $jsonService): JsonResponse
    {
        $data = $request->route('pattern');
        $result = $jsonService->handleJson($data);

        if (isset($result['message'])) { // Return error message
            return response()->json($result, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response()->json($result);
    }
}
