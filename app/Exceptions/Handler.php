<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Throwable;
use Illuminate\Http\Response;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (Throwable $e, $request) {
            if ($request->is('api/*')) {
                return $this->handleApiExceptionResponse($e);
            }
        });
    }


    /**
     * Custom API exception response message
     * @param Throwable $e
     * @return JsonResponse
     */
    private function handleApiExceptionResponse(Throwable $e): JsonResponse
    {
        $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        if (method_exists($e, 'getStatusCode')) {
            $statusCode = $e->getStatusCode();
        }

        $response = [];
        $response['success'] = false;

        switch ($statusCode) {
            case Response::HTTP_UNAUTHORIZED:
                $response['message'] = 'Unauthorized';
                break;

            case Response::HTTP_FORBIDDEN:
                $response['message'] = 'Forbidden';
                break;

            case Response::HTTP_NOT_FOUND:
                $response['message'] = 'Not Found';
                break;

            case Response::HTTP_METHOD_NOT_ALLOWED:
                $response['message'] = 'Method Not Allowed';
                break;

            case Response::HTTP_UNPROCESSABLE_ENTITY:
                $response['message'] = $e->original['message'];
                $response['errors'] = $e->original['errors'];
                break;

            default:
                $response['message'] = 'Internal server error';
                break;
        }

        return response()->json($response, $statusCode);
    }
}
