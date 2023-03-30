<?php

namespace App\Traits;

trait HttpResponses
{
    // Response::HTTP_UNAUTHORIZE TODO
    protected const ERROR_RESPONSE_CODE = 403;

    protected const SUCCESS_RESPONSE_CODE = 200;

    protected const CREATED_RESPONSE_CODE = 201;

    protected const NO_RESPONSE_CODE = 204;

    protected const SERVER_ERROR_RESPONSE_CODE = 500;

    protected const UNAUTHORIZED_RESPONSE_CODE = 401;

    protected function success($data, $message = null, $code = 200)
    {
        return response()->json([
            'status' => 'Request was successful.',
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    protected function error($data, $message = null, $code = 403)
    {
        return response()->json([
            'status' => 'Error has occurred...',
            'message' => $message,
            'data' => $data,
        ], $code);
    }
}
