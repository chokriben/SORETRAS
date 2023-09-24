<?php

namespace App\Traits;

trait HttpResponses
{

    protected function success($data, string $message = null, int $code = 200)
    {
        return response()->json([
            'status' => 200,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    protected function error($data, string $message = null, int $code)
    {
        return response()->json([
            'status' => 'Une erreur est survenue...',
            'message' => $message,
            'data' => $data
        ], $code);
    }
}
